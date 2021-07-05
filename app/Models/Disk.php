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

    public function strategy_auth()
    {
        return $this->hasOne(StrategyAuth::class, 'id', 'strategy_auth_id');
    }

    public function strategy_update()
    {
        return $this->hasOne(StrategyUpdate::class, 'id', 'strategy_update_id');
    }
}
