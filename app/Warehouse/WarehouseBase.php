<?php
/**
 * Created by PhpStorm.
 * User: Ростислав
 * Date: 30.12.2016
 * Time: 12:57
 */

namespace App\Warehouse;


use App\Collections\WarehouseCollection;
use App\Interfaces\IMaterialDocument;
use App\Interfaces\Warehouses\IBaseDocument;
use App\Warehouse\AcceptPolicy\IAcceptPut;
use App\Warehouse\AcceptPolicy\IAcceptTake;
use App\Warehouse\AcceptPolicy\IAcceptValidate;
use App\Warehouse\AcceptPolicy\Put\FreePut;
use App\Warehouse\AcceptPolicy\Take\FreeTake;
use App\Warehouse\AcceptPolicy\Validate\NoValidate;

class WarehouseBase implements IWarehouse
{
    protected $warehouse_id;
    protected $documentNumber = null;
    protected $data;
    protected $acceptPut;
    protected $acceptTake;
    protected $acceptValidate;
    const id = 1;

    /**
     * WarehouseBase constructor.
     */
    public function __construct()
    {
        // Политики по-умолчанию
        $this->acceptPut = new FreePut($this);
        $this->acceptTake = new FreeTake($this);
        $this->acceptValidate = new NoValidate($this);

        $this->warehouse_id = WarehouseBase::id;
    }


    /**
     * @return WarehouseCollection
     */
    public function getRequestedData()
    {
        return $this->data;
    }


    /**
     * @param $isComing boolean Приход или Расход
     */
    protected function recordInRegister($documentName, $isComing)
    {
        Register::beginTransaction();

        foreach ($this->data->array() as $value) {
            Register::record(WarehouseBase::id, $documentName, $isComing, $value);
        }

        Register::commitTransaction();
    }

    /**
     * Взаимодействие со складсм (Приход или расход материалов)
     *
     * Для обращения достаточно документа, в котором
     * будет указно что, куда, какое движение (Приход/Расход)
     *
     * @param IBaseDocument $document
     * @return bool
     */
    public function appeal(IMaterialDocument $document)
    {
        if (!$document instanceof IBaseDocument)
            return;

        if ($document->isComingWHBase())
            $this->put($document->getDocumentName(), $document->getMaterialValuesData());
        else
            $this->take($document->getDocumentName(), $document->getMaterialValuesData());
        // TODO: Implement appeal() method.
        $this->clearAppeal();
    }

    public function reverseAppeal(IBaseDocument $document)
    {
        Register::beginTransaction();
        Register::refutation(WarehouseBase::id, $document->getDocumentName());
        Register::commitTransaction();
    }


    private function put(string $documentName, WarehouseCollection $data, bool $forcibly = false)
    {
        $this->data = $data;

        if (!$forcibly)
            if (!$this->acceptPut->canPut())
                return false;

        $this->recordInRegister($documentName, true);
        // обновление состояния остатков
        $this->clearAppeal();
    }

    /**
     * Если требутся поместить на склад без сопроводительных документов
     * Принудительный режим игнорирует политику AcceptPut
     *
     * @param WarehouseCollection $data
     * @param bool $forcibly
     * @return void
     */
    public function justPut(WarehouseCollection $data, bool $forcibly = false)
    {
        $this->put('none', $data, $forcibly);
    }

    private function take(string $documentName, WarehouseCollection $data, bool $forcibly = false)
    {
        $this->data = $data;

        if (!$forcibly)
            if (!$this->acceptTake->canTake())
                return false;

        $this->recordInRegister($documentName, false);
        // обновление состояния остатков
        $this->clearAppeal();
    }

    /**
     * Если требуется списать со склада без сопроводительных документов
     * Принудительный режим игнорирует политику AcceptTake
     *
     * @param WarehouseCollection $data
     * @param bool $forcibly
     * @return void
     */
    public function justTake(WarehouseCollection $data, bool $forcibly = false)
    {
        $this->take('none', $data, $forcibly);
    }

    /**
     * Позволяет установить правила для принятия материалов на склад
     *
     * @param IAcceptPut $acceptPut
     * @return void
     */
    public function setPutPolicy(IAcceptPut $acceptPut)
    {
        $this->acceptPut = $acceptPut;
    }

    /**
     * Позволяет установить правила передачи материалов
     *
     * @param IAcceptTake $acceptTake
     * @return mixed
     */
    public function setTakePolicy(IAcceptTake $acceptTake)
    {
        $this->acceptTake = $acceptTake;
    }

    /**
     * Необходимость этого метода под вопросом
     *
     * @param IAcceptValidate $acceptValidate
     * @return void
     */
    public function setValidationPolicy(IAcceptValidate $acceptValidate)
    {
        $this->acceptValidate = $acceptValidate;
    }

    /**
     *Подготавливает состояние объекта к следующим обращениям
     */
    protected function clearAppeal()
    {
        $this->data = null;
        $this->documentNumber = null;
    }

    /**
     * Текущие остатки склада
     * @return WarehouseCollection
     */
    public function getBalance()
    {
        return Register::getBalance(WarehouseBase::id);
    }
}