<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Config extends Model
{
    protected $table = 'config';
    protected $fillable = [
        'userid', 'name', 'numgroups',
    ];

    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo('App\User', 'userid');
    }

    public function configgroup()
    {
        return $this->hasMany('App\ConfigGroup', 'configid', 'id')->orderBy('groupindex');
    }
}
