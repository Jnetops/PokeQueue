<?php

namespace App\Traits;

use Illuminate\Support\Facades\Cache;
use Log;

trait CacheReturn
{
  public function gather($name, $statement, $time)
  {
    if (Cache::has($name))
    {
      return Cache::get($name);
    } else {
        $insert = eval($statement);
        Cache::store('redis')->put($name, $insert, $time);
        \Log::info('Cached: ' . $name);
        return $insert;
    }
  }

  public function updateCache($name)
  {
    if (Cache::has($name))
    {
      Cache::forget($name);
    }
  }

}
