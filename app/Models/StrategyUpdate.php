<?php

namespace App\Models;

class StrategyUpdate extends BaseModel
{
    protected $guarded = [];

    const NOT_VALID = 1;//不生效
    const NOW_VALID = 2;//立即生效
    const DATE_VALID = 3;//生效日期

    const AUTO_UPDATE = 1;
    const NOT_AUTO_UPDATE = 0;

    //转化时间
    public function getValidTimeAttribute($value)
    {
        return conversionTime($value);
    }

    /**
     * 获取更新文件
     */
    public function files()
    {
        return $this->hasMany(StrategyUpdateFiles::class, 'strategy_id', 'id');
    }
}
