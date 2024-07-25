<?php

namespace App\Models;

use App\Traits\HandleRedisData;

class ProviderZ extends Provider
{
       public static  $data;

    use HandleRedisData;

    protected $fillable = [
        'balanceDummy',
        'currencyLol',
        'email',
        'statusLol',
        'created_at',
        'id',
        'provideType',
    ];

     public const filterable = [
        'authorised' => 1000,
        'decline' => 2000,
        'refunded' => 3000,
    ];

    public function getStatusStringAttribute()
    {
        return array_search($this->attributes['statusLol'], self::filterable);
    }


    public function getBalanceAttribute()
    {
        return $this->attributes['balanceDummy'];
    }

    public function getCurrencyAttribute()
    {
        return $this->attributes['currencyLol'];
    }


}
