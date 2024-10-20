<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StoreInventory extends Model
{
    protected $fillable = [
        'name',
        'product_id',
        'peripheral_id'
    ];

    public function product() {
        return $this->hasOne(ProductCatalog::class, 'id', 'product_id');
    }

    public function peripheral() {
        return $this->hasOne(Peripheral::class, 'id', 'peripheral_id');
    }

    public function getDeviceAttribute() {
        if($this->product_id > 0) {
            return $this->product;
        }

        if($this->peripheral_id > 0) {
            return $this->peripheral;
        }

        return null;
    }

    public function getDeviceTypeAttribute() {
        if($this->product_id > 0) {
            return 'Unit';
        }

        if($this->peripheral_id > 0) {
            return 'Peripheral';
        }
    }
}
