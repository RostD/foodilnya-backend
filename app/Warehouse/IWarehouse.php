<?php
/**
 * Created by PhpStorm.
 * User: Ростислав
 * Date: 30.12.2016
 * Time: 13:11
 */

namespace App\Warehouse;


use App\Collections\WarehouseCollection;
use App\Warehouse\AcceptPolicy\IAcceptPut;
use App\Warehouse\AcceptPolicy\IAcceptTake;
use App\Warehouse\AcceptPolicy\IAcceptValidate;

interface IWarehouse
{
    /**
     * Взаимодействие со складсм (Приход или расход материалов)
     *
     * Для обращения достаточно документа, в котором
     * будет указно что, куда, какое движение (Приход/Расход)
     *
     * @param $document
     * @return bool
     */
    public function appeal($document);

    /**
     * Если требутся поместить на склад без сопроводительных документов
     * Принудительный режим игнорирует политику AcceptPut
     *
     * @param WarehouseCollection $data
     * @param bool $forcibly
     * @return bool
     */
    public function justPut(WarehouseCollection $data, bool $forcibly = false);

    /**
     * Если требуется списать со склада без сопроводительных документов
     * Принудительный режим игнорирует политику AcceptTake
     *
     * @param WarehouseCollection $data
     * @param bool $forcibly
     * @return mixed
     */
    public function justTake(WarehouseCollection $data, bool $forcibly = false);

    /**
     * @return WarehouseCollection
     */
    public function getRequestedData();

    /**
     * Позволяет установить правила для принятия материалов на склад
     *
     * @param IAcceptPut $acceptPut
     * @return void
     */
    public function setPutPolicy(IAcceptPut $acceptPut);

    /**
     * Позволяет установить правила передачи материалов
     *
     * @param IAcceptTake $acceptTake
     * @return mixed
     */
    public function setTakePolicy(IAcceptTake $acceptTake);

    /**
     * Необходимость этого метода под вопросом
     *
     * @param IAcceptValidate $acceptValidate
     * @return void
     */
    public function setValidationPolicy(IAcceptValidate $acceptValidate);
}