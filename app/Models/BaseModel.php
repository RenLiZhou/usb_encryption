<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{

    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function getCreatedAtAttribute($value)
    {
        return conversionTime($value);
    }

    public function getUpdatedAtAttribute($value)
    {
        return conversionTime($value);
    }
}
