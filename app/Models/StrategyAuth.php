<?php

namespace App\Models;

class StrategyAuth extends BaseModel
{
    protected $guarded = [];

    const EXPIRED_PERPETUAL = 0;//永久
    const EXPIRED_DAY = 1;//过期天数
    const EXPIRED_DATE = 2;//过期日期

    //转化时间
    public function getExpiredTimeAttribute($value)
    {
        return conversionTime($value);
    }

    //过期日期
    public function getExpiredDateAttribute()
    {
        if($this->expired_type == self::EXPIRED_PERPETUAL){
            return __('merchant_model.permanent');
        }elseif ($this->expired_type == self::EXPIRED_DAY){
            return __('merchant_model.n_days_after_authorization', ['day'=>$this->expired_day]);
        }elseif ($this->expired_type == self::EXPIRED_DATE){
            return conversionTime($this->expired_time);
        }
    }

}
