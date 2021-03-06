<?php
/**
 * Created by PhpStorm.
 * User: лол
 * Date: 23.04.2017
 * Time: 8:37
 */

namespace App\Order;


use App\Collections\WarehouseCollection;
use App\Interfaces\IRegisterString;
use App\Interfaces\Warehouses\IBaseDocument;
use App\MaterialValue\Adaptation;
use App\MaterialValue\AdaptationCounted;
use App\MaterialValue\Dish;
use App\MaterialValue\Ingredient;
use App\MaterialValue\IngredientCounted;
use App\MaterialValue\Unit;
use App\Models\MaterialValue;
use App\Models\OrderModel;
use App\Warehouse\WarehouseBase;
use Illuminate\Support\Facades\Gate;

class Order implements IBaseDocument
{
    /**
     * Документ прихода или расхода на складе
     */
    const isComingWHBase = false;

    protected $model;
    protected $client = false;

    protected $material_strings;
    protected $material_strings_loaded = false;


    public function __set($name, $value)
    {
        $name = "set" . $name;
        return $this->$name($value);
    }

    public function __get($name)
    {
        $name = "get" . $name;
        return $this->$name();
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->model->id;
    }

    /**
     * @return mixed
     */
    public function getConfirmed()
    {
        return $this->model->confirmed;
    }

    /**
     * @param mixed $confirmed
     */
    public function setConfirmed(bool $confirmed)
    {
        if (Gate::denies('order-confirm'))
            return;
        if ($confirmed == $this->getConfirmed())
            return;

        $this->model->confirmed = $confirmed;
        $this->model->save();
    }

    /**
     * @return mixed
     */
    public function getAddress()
    {
        return $this->model->address;
    }

    /**
     * @return mixed
     */
    public function getDone()
    {
        return $this->model->done;
    }

    /**
     * @param mixed $done
     */
    public function setDone(bool $done)
    {
        if (Gate::denies('order-close'))
            return;

        if ($done == $this->getDone())
            return;

        $this->model->done = $done;
        $this->model->save();
    }

    public function getEquipped()
    {
        return $this->model->equipped;
    }

    /**
     * @param mixed $equipped
     */
    public function setEquipped(bool $equipped)
    {
        if (Gate::denies('order-equip'))
            return;

        if ($equipped == $this->getEquipped())
            return;

        if ($equipped)
            (new WarehouseBase())->appeal($this);
        else
            (new WarehouseBase())->reverseAppeal($this);

        $this->model->equipped = $equipped;
        $this->model->save();
    }

    /**
     * @return mixed
     */
    public function getDate()
    {
        return $this->model->date;
    }

    /**
     * @param mixed $date
     */
    public function setDate($date)
    {
        $this->model->date = $date;
        $this->model->save();
    }

    /**
     * @param mixed $address
     */
    public function setAddress($address)
    {
        $this->model->address = trim($address);
        $this->model->save();
    }

    /**
     * @return mixed
     */
    public function getClient()
    {
        if (!$this->client)
            $this->client = Client::find($this->model->user_id);

        return $this->client;
    }

    public function removeMaterialString($materialId)
    {
        if ($this->getConfirmed() || $this->getEquipped() || $this->getDone())
            return;
         
        $string = OrderMaterialString::find($this->id, $materialId);
        if ($string)
            $string->destroy();
    }

    /**
     * @param int $client_id
     */
    public function setClient(int $client_id)
    {
        $client = Client::find($client_id);

        if ($client) {
            $this->model->user_id = $client->id;
            $this->model->save();
        }

    }

    public function __construct(OrderModel $model)
    {
        $this->model = $model;
    }

    private function loadMaterialStrings()
    {
        if (!$this->material_strings_loaded) {
            $this->material_strings = OrderMaterialString::all($this->model->id);
            $this->material_strings_loaded = true;
        }
    }

    public function getMaterialStrings()
    {
        $this->loadMaterialStrings();
        return $this->material_strings;
    }

    public function issetMaterialString($materialId)
    {
        $this->loadMaterialStrings();

        foreach ($this->material_strings as $material_string)
            if ($material_string->material->id == $materialId)
                return true;

        return false;
    }

    public function addMaterialString($material_id, $quantity, $unit)
    {
        if ($this->getConfirmed() || $this->getEquipped() || $this->getDone())
            return false;
        
        $model = MaterialValue::find($material_id);
        if ($model) {
            $material = false;

            if ($model->type_id == Dish::type_id)
                $material = new Dish($model);
            elseif ($model->type_id == Adaptation::type_id)
                $material = new Adaptation($model);
            elseif ($model->type_id == Ingredient::type_id)
                $material = new Ingredient($model);

            if ($material) {
                $quantity = Unit::convert($quantity, $unit, $material->unit);

                if (!$this->issetMaterialString($material->id)) {
                    OrderMaterialString::create($material->id, $quantity, $this->id);
                } else {
                    $materialString = OrderMaterialString::find($this->id, $material->id);
                    if ($materialString)
                        $materialString->quantity = $quantity;
                }
            }
            $this->material_strings_loaded = false;
        }

    }

    public function destroy()
    {
        if ($this->trashed()) {
            $this->model->restore();
        } else {
            if (!$this->getConfirmed() && !$this->getDone() && !$this->getEquipped()) {
                if (count($this->getMaterialStrings()) == 0)
                    $this->model->forceDelete();
                else
                    $this->model->delete();
            }

        }

    }

    public static function all($withTrashed = true)
    {
        $models = OrderModel::orderBy('date');

        if ($withTrashed)
            $models = $models->withTrashed();

        $models = $models->get();

        if ($models) {
            $orders = [];
            foreach ($models as $model)
                $orders[] = new self($model);

            return $orders;
        }
        return false;
    }

    public static function create($client_id, $date, $address)
    {
        $client = Client::find($client_id);

        if ($client) {
            $order = new OrderModel();
            $order->date = $date;
            $order->address = $address;
            $order->user_id = $client->id;

            $order->save();

            return new self($order);
        }

        return false;
    }

    public static function find($order_id)
    {
        $model = OrderModel::withTrashed()->find($order_id);

        if ($model)
            return new self($model);

        return false;
    }

    public function trashed()
    {
        return $this->model->trashed();
    }


    public static function allNotUsedDishes($orderId)
    {
        return self::allNotUsedMaterial($orderId, Dish::class);
    }

    public static function allNotUsedIngredients($orderId)
    {
        return self::allNotUsedMaterial($orderId, Ingredient::class);
    }

    public static function allNotUsedAdaptations($orderId)
    {
        return self::allNotUsedMaterial($orderId, Adaptation::class);
    }

    private static function allNotUsedMaterial($orderId, $class)
    {
        $order = Order::find($orderId);
        $materials = [];

        if ($order) {
            $materials = $class::all(false);

            foreach ($materials as $k => $material)
                if ($order->issetMaterialString($material->id))
                    unset($materials[$k]);

        }

        return $materials;

    }

    public static function allNotUsedMaterials($orderId)
    {
        $materials = array_merge(self::allNotUsedDishes($orderId),
            self::allNotUsedIngredients($orderId),
            self::allNotUsedAdaptations($orderId));

        return $materials;

    }


    /**
     * @return bool
     */
    public function isComingWHBase()
    {
        return self::isComingWHBase;
    }

    /**
     * @return WarehouseCollection
     */
    public function getMaterialValuesData()
    {
        $collection = new WarehouseCollection();
        foreach ($this->getMaterialStrings() as $materialString) {
            // Тип "товар" мы пока не учитываем в заказах
            if ($materialString->material->type == Adaptation::type_id || $materialString->material->type == Ingredient::type_id)
                $collection->add($materialString);
            elseif ($materialString->material->type == Dish::type_id) {
                foreach ($materialString->material->getIngredients($materialString->quantity) as $ingredient) {

                    $collection->add(new class ($ingredient) implements IRegisterString
                    {

                        protected $ingredient;

                        /**
                         * __anonymous$object$\App\Interfaces\IRegisterString@8571 constructor.
                         * @param $ingredient
                         */
                        public function __construct(IngredientCounted $ingredient)
                        {
                            $this->ingredient = $ingredient;
                        }

                        public function getMaterialId()
                        {
                            return $this->ingredient->id;
                        }

                        public function getQuantity()
                        {
                            return $this->ingredient->quantity;
                        }

                        public function getUnit()
                        {
                            return $this->ingredient->unit;
                        }
                    });
                }

                foreach ($materialString->material->getAdaptations($materialString->quantity) as $ingredient) {

                    $collection->add(new class ($ingredient) implements IRegisterString
                    {

                        protected $ingredient;

                        /**
                         * __anonymous$object$\App\Interfaces\IRegisterString@8571 constructor.
                         * @param $ingredient
                         */
                        public function __construct(AdaptationCounted $ingredient)
                        {
                            $this->ingredient = $ingredient;
                        }

                        public function getMaterialId()
                        {
                            return $this->ingredient->id;
                        }

                        public function getQuantity()
                        {
                            return $this->ingredient->quantity;
                        }

                        public function getUnit()
                        {
                            return $this->ingredient->unit;
                        }
                    });
                }

            }
        }

        return $collection;
    }

    /**
     * @return string
     */
    public function getDocumentName()
    {
        return 'Заказ_№' . $this->id;
    }
}