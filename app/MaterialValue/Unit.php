<?php
/**
 * Created by PhpStorm.
 * User: Ростислав
 * Date: 06.04.2017
 * Time: 13:02
 */

namespace App\MaterialValue;


use App\Models\TranslationUnit;
use App\Models\Unit as UnitM;

class Unit
{
    protected $model;

    public function __construct(UnitM $model)
    {
        $this->model = $model;
    }

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
    public function getName()
    {
        return $this->model->name;
    }

    /**
     * @return mixed
     */
    public function getFullName()
    {
        return $this->model->full_name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->model->name = trim($name);
        $this->model->save();
    }

    /**
     * @param mixed $full_name
     */
    public function setFullName($full_name)
    {
        $this->model->full_name = trim($full_name);
        $this->model->save();
    }

    public function getSimilarUnits()
    {
        $modelsSimilar = $this->model->similarUnits()->get();
        $modelMain = $this->model->mainUnits()->get();

        if ($modelsSimilar || $modelMain) {
            $similarUnits = [];

            foreach ($modelsSimilar as $model) {
                $similarUnits[] = new self($model);
            }
            foreach ($modelMain as $model) {
                $similarUnits[] = new self($model);
            }
            return $similarUnits;
        }
        return false;
    }

    public function trashed()
    {
        return $this->model->trashed();
    }

    public function destroy()
    {
        if ($this->trashed()) {
            $this->model->restore();
        } else {
            if (count($this->model->attributes) == 0 && count($this->model->materialValues) == 0)
                $this->model->forceDelete();
            else
                $this->model->delete();
        }
    }

    public static function convert($quantity, $unit_id_of, $unit_id_in)
    {
        $quantity = str_replace(",", ".", $quantity);
        if ($unit_id_of == $unit_id_in)
            return $quantity;

        $unit_of = Unit::find($unit_id_of);

        foreach ($unit_of->getSimilarUnits() as $similarUnit) {
            if ($similarUnit->id == $unit_id_in) {
                $model = TranslationUnit::where([['main_unit', '=', $unit_of->getId()], ['trans_unit', '=', $unit_id_in]])->first();
                if ($model) {
                    return $quantity * $model->value;
                } else {
                    $model = TranslationUnit::where([['trans_unit', '=', $unit_of->getId()], ['main_unit', '=', $unit_id_in]])->first();
                    return $quantity / $model->value;
                }

            }
        }
        return false;
    }

    public static function find($id)
    {
        $unit = UnitM::withTrashed()->where('id', $id)->first();

        if ($unit)
            return new self($unit);
        else
            return false;
    }

    public static function create(string $name, string $full_name)
    {
        $unit = new UnitM();
        $unit->name = trim($name);
        $unit->full_name = trim($full_name);
        $unit->save();
        if ($unit)
            return new self($unit);
        return false;
    }

    public static function all($withTrashed = true)
    {
        $units = UnitM::orderBy('full_name');

        if ($withTrashed)
            $units = $units->withTrashed();

        $units = $units->get();

        if ($units) {
            $objs = [];
            foreach ($units as $unit) {
                $objs[] = new self($unit);
            }
            return $objs;
        }
        return false;

    }
}