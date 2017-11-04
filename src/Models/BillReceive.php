<?php

namespace SONFin\Models;


use Illuminate\Database\Eloquent\Model;

class BillReceive extends Model
{
    protected $fillable = [
        'data_launch',
        'name',
        'value',
        'user_id'
    ];
}