<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ChannelStatus extends Model
{
    protected $table = 'channelstatus';
    protected $fillable = [
        'channelid', 'timestamp', 'config', 'inputsignalstatus', 'source', 'uptime', 'resyncs', 'state', 'videourl'
    ];

    public $timestamps = false;

    public function channel()
    {
        return $this->belongsTo('App\Channel', 'channelid');
    }
}
