<?php

namespace SONFin\Models;


use Illuminate\Database\Eloquent\Model;

class BillPay extends Model
{
    protected $fillable = [
        'date_launch',
        'name',
        'value',
        'user_id',
        'category_cost_id'
    ];

    public function categoryCost()
    {
        return $this->belongsTo(CategoryCost::class);
    }
}
