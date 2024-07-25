<?php

namespace App\Traits;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\LazyCollection;
use JsonMachine\Items;

trait HandleRedisData
{
    protected static string $providerModelType;

    protected static function loadDataFromRedis()
    {
        if (!Cache::has('DataProvider' . static::$providerModelType)) {
            static::fillRedis();
        }

        static::$data = json_decode(Cache::get('DataProvider' . static::$providerModelType), true);
    }

    protected static function fillRedis()
    {
        $providerItems = Items::fromFile(storage_path('app/provider' . static::$providerModelType . '.json'));

        Cache::set('DataProvider' . static::$providerModelType, '');
        $collection = LazyCollection::make(function () use ($providerItems) {
            foreach ($providerItems as $providerItem) {
                ;
                $providerItem->provideType = static::$providerModelType;
                yield $providerItem;
            }
        });

        Cache::put('DataProvider' . static::$providerModelType, json_encode($collection->toArray()));
    }


    protected static function boot()
    {
        parent::boot();

        static::$providerModelType = substr(class_basename(static::class), -1);

        static::loadDataFromRedis();
    }


    public static function all($columns = ['*']): \Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection
    {
        return collect(static::$data)->map(function ($item) {
            return new static($item);
        });
    }

}
