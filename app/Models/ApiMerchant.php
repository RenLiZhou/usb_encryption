<?php

namespace App\Models;

use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class ApiMerchant extends Authenticatable implements JWTSubject
{
    use Notifiable;

    public $table = 'merchants';

    public function version()
    {
        return $this->belongsToMany(MerchantVersion::class, MerchantVersionRelation::class, 'merchant_id', 'version_id');
    }

    /**
     * @return false|string
     */
    public function getExpireTimeAttribute($value)
    {
        return conversionTime($value);
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
}
