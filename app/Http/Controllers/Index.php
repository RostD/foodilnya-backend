<?php

namespace App\Http\Controllers;

use App\Collections\Collection;
use App\Collections\WarehouseCollection;
use App\MaterialValue\CountedMaterialValue;
use App\MaterialValue\MaterialValue;
use App\Warehouse\AcceptPolicy\Put\FreePut;
use App\Warehouse\AcceptPolicy\Take\FreeTake;
use App\Warehouse\AcceptPolicy\Validate\NoValidate;
use App\Warehouse\WarehouseBase;
use App\WarehouseRegister;
use Illuminate\Http\Request;

class Index extends Controller
{
    public function index()
    {
        $f = new CountedMaterialValue(2, 'Мат. ценность3', 1, 20);
        $f2 = new CountedMaterialValue(3, 'Мат. ценность4', 1, 10);
        $f3 = new CountedMaterialValue(4, 'Мат. ценность5', 1, 5);
        
        $collection = new WarehouseCollection();
        $collection->add($f);
        $collection->add($f2);
        $collection->add($f3);

        $warehouse = new WarehouseBase(1);
        $warehouse->justPut($collection);
        

    }
}
