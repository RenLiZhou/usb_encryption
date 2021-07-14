<?php

namespace App\Models;

class DiskEncryptRecord extends BaseModel
{
    protected $guarded = [];

    /**
     * 对应u盘
     */
    public function disk()
    {
        return $this->belongsTo(Disk::class, 'disk_id', 'id');
    }

}
