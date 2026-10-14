<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class events extends Model
{
    use HasFactory;

    protected $table = 'events';
    protected $guarded = ['id'];

    public function getPriceAttribute()
    {
        return $this->attributes['ticket_price'] ?? null;
    }

    public function setPriceAttribute($value)
    {
        $this->attributes['ticket_price'] = $value;
    }
}
