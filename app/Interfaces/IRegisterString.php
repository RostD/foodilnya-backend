<?php

namespace App\Interfaces;

/**
 * Данный интерфейс использует регистр склада
 * для фиксации приходов и расходов материальных ценностей
 *
 * Interface IRegisterString
 * @package App\Interfaces
 */
interface IRegisterString
{
    public function getMaterialId();

    public function getQuantity();

    public function getUnit();
}