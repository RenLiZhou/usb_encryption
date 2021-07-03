<?php

namespace App\Models;

class Disk extends BaseModel
{
    protected $guarded = [];

    const STATUS_USE = 0;
    const STATUS_DISABLED = 1;

    public function getFirstTimeUseAttribute($value)
    {
        return conversionTime($value);
    }
}
