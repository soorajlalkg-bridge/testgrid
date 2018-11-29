<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Node extends Model
{
    protected $table = 'node';
    protected $fillable = [
        'ip', 
    ];

    public $timestamps = false;

    public function channel()
    {
        return $this->hasMany('App\Channel', 'nodeid', 'id');
    }
}
