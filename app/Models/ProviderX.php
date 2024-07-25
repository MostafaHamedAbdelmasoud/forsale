<?php

namespace App\Models;

use App\Traits\HandleRedisData;

class ProviderX extends Provider
{
      public static  $data;


    use HandleRedisData;


    const filterable = [
        'authorised' => 1,
        'decline' => 2,
        'refunded' => 3,
    ];

    protected $fillable = [
        'parentAmount',
        'Currency',
        'parentEmail',
        'statusCode',
        'registerationDate',
        'parentIdentification',
        'provideType',
    ];

    public function getBalanceAttribute()
    {
        return $this->attributes['parentAmount'];
    }

    public function getStatusStringAttribute()
    {
        return array_search($this->attributes['statusCode'], self::filterable);
    }

    public function getCurrencyAttribute()
    {
        return $this->attributes['Currency'];
    }


}
