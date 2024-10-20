<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $fillable = ['number', 'street', 'city', 'customer_id'];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
