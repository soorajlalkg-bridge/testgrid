<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
    protected $table = 'session';
    protected $fillable = [
        'userid', 'start', 'end', 'session_token', 'ip'
    ];

    public $timestamps = false;

    public function userlog()
    {
        return $this->hasMany('App\UserLog', 'sessionid', 'id');
    }

    public function user()
    {
        return $this->belongsTo('App\User', 'userid');
    }
}
