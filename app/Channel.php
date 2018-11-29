<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Channel extends Model
{
    protected $table = 'channel';
    protected $fillable = [
        'nodeid', 'index',
    ];

    public $timestamps = false;

    public function channelstatus()
    {
        return $this->hasMany('App\ChannelStatus', 'channelid', 'id');
    }

    public function node()
    {
        return $this->belongsTo('App\Node', 'nodeid');
    }

    public function configchannel()
    {
        return $this->hasMany('App\ConfigChannel', 'channelid', 'id');
    }

    public function streamurl()
    {
        return $this->hasOne('App\StreamUrl', 'channelid');
    }
}
