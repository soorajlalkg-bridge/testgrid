<?php
/**
 * Action Model
 *
 * @category Action
 * @package  Model
 */

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Action Model
 *
 * @category Class
 * @package  Key
 */
class Action extends Model
{
    protected $table = 'action';
    protected $fillable = [
        'action_type',
    ];

    public $timestamps = false;

    public function userlog()
    {
        return $this->hasMany('App\UserLog', 'action', 'id');
    }
}
