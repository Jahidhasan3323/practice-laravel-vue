<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    public function customerAddress()
    {
        return $this->hasOne(CustomerAddress::class);
    }

    public function customerPurchase()
    {
        return $this->hasMany(CustomerPurchase::class);
    }
}
