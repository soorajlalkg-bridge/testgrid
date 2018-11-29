<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ConfigGroup extends Model
{
    protected $table = 'configgroup';
    protected $fillable = [
        'configid', 'groupindex', 'numrows', 'numcols',
    ];

    public $timestamps = false;

    public function configchannel()
    {
        return $this->hasMany('App\ConfigChannel', 'configgroupid', 'id')->orderBy('position')->orderBy('column_position');
    }

    public function config()
    {
        return $this->belongsTo('App\Config', 'configid');
    }

}
