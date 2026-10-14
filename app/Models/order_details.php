<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class order_details extends Model
{
    use HasFactory;

    protected $table = 'order_details';
    protected $guarded = ['id'];

    public function events()
    {
        return $this->belongsTo(events::class);
    }

    public function orders()
    {
        return $this->belongsTo(orders::class);
    }
}
