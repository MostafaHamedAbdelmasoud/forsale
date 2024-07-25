<?php

namespace App\Models;

use App\Traits\HandleRedisData;

class ProviderY extends Provider
{
   public static  $data;

    use HandleRedisData;

    protected $fillable = [
        'balance',
        'currency',
        'email',
        'status',
        'created_at',
        'id',
        'provideType',
    ];


    public const filterable = [
        'authorised' => 100,
        'decline' => 200,
        'refunded' => 300,
    ];


    public function getStatusStringAttribute()
    {
        return array_search($this->attributes['status'], self::filterable);
    }

    public function getBalanceAttribute()
    {
        return $this->attributes['balance'];
    }

    public function getCurrencyAttribute()
    {
        return $this->attributes['currency'];
    }



}
