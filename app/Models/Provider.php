<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

abstract class Provider extends Model
{

    public static  $data;

    public $timestamps = false;

    public abstract function getBalanceAttribute();

    public abstract function getStatusStringAttribute();

    public abstract function getCurrencyAttribute();
}
