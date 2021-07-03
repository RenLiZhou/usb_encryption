<?php

namespace App\Models;

class DiskEncryptRecord extends BaseModel
{
    protected $guarded = [];

    /**
     * 获得拥有此电话的用户
     */
    public function disk()
    {
        return $this->belongsTo(Disk::class, 'disk_id', 'id');
    }

}
