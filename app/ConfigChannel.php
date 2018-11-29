<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ConfigChannel extends Model
{
    protected $table = 'configchannel';
    protected $fillable = [
        'configgroupid', 'groupindex', 'position', 'label', 'channelid','url','column_position',
    ];

    public $timestamps = false;

    public function configgroup()
    {
        return $this->belongsTo('App\ConfigGroup', 'userid');
    }

    public function channel()
    {
        return $this->belongsTo('App\Channel', 'channelid');
    }
}
