<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StreamUrl extends Model
{
    protected $table = 'streamurl';
    protected $fillable = [
        'channelid', 'url', 
    ];

    public $timestamps = false;

    public function channel()
    {
        return $this->belongsTo('App\Channel', 'channelid');
    }
}
