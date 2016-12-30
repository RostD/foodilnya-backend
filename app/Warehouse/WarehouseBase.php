<?php
/**
 * Created by PhpStorm.
 * User: Ростислав
 * Date: 30.12.2016
 * Time: 12:57
 */

namespace App\Warehouse;


use App\Collections\WarehouseCollection;
use App\Warehouse\AcceptPolicy\IAcceptPut;
use App\Warehouse\AcceptPolicy\IAcceptTake;
use App\Warehouse\AcceptPolicy\IAcceptValidate;

class WarehouseBase implements IWarehouse
{
    protected $documentNumber = null;
    protected $data;
    protected $acceptPut;
    protected $acceptTake;
    protected $acceptValidate;
    protected $warehouse_id;

    /**
     * WarehouseBase constructor.
     * @param $warehouse_id
     * @param IAcceptValidate $acceptValidate
     * @param IAcceptPut $acceptPut
     * @param IAcceptTake $acceptTake
     */
    public function __construct($warehouse_id, $acceptValidate, $acceptPut, $acceptTake)
    {
        $this->acceptPut = new $acceptPut($this);
        $this->acceptTake = new $acceptTake($this);
        $this->acceptValidate = new $acceptValidate($this);
        $this->warehouse_id = $warehouse_id;

        if (!$this->acceptValidate instanceof IAcceptValidate || !$this->acceptPut instanceof IAcceptPut || !$this->acceptTake instanceof IAcceptTake)
            exit("Не забудь сделать Exception");
    }

    /**
     * @param $data
     * @return bool
     */
    public function put(WarehouseCollection $data)
    {
        // TODO: Implement put() method.
        if (!$this->acceptValidate->isValidData())
            return false;
        if (!$this->acceptPut->canPut())
            return false;

        $this->data = $data;

        $this->recordInRegister(true);

        // запись в регистр
        // обновление состояния остатков
    }

    public function take(WarehouseCollection $data)
    {
        // TODO: Implement take() method.
        // валидация
        // запись в регистр
        // обновление состояния остатков
    }

    public function getRequestedData()
    {
        return $this->data;
    }

    /**
     * @param mixed $documentNumber
     */
    public function setDocumentNumber($documentNumber)
    {
        $this->documentNumber = $documentNumber;
    }

    protected function recordInRegister($isComing)
    {
        foreach ($this->data->array() as $value) {
            Register::record($this->warehouse_id, $isComing, $value);
        }
    }

}