<?php
/**
 * UserLog Model
 *
 * @category UserLog
 * @package  Model
 */

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * UserLog Model
 *
 * @category Class
 * @package  Key
 */
class UserLog extends Model
{
    protected $table = 'userlog';
    protected $fillable = [
        'sessionid', 'timestamp', 'action', 'result'
    ];

    public $timestamps = false;

    /**
     * Get the user that owns the phone.
     */
    public function session()
    {
        return $this->belongsTo('App\Session', 'sessionid');
    }

    public function actiontype()
    {
        return $this->belongsTo('App\Action', 'action');
    }
}
