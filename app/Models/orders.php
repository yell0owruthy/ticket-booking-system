<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class orders extends Model
{
    protected $guarded = ['id'];

    public function orders_details()
    {
        return $this->hasMany(order_details::class, 'order_id');
    }
}
