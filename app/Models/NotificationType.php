<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotificationType extends Model
{
     /**
     * CONSTANTS
     */
    const EMAIL = 'email';
    const SMS = 'sms';    

    /**
     * The database table used by the model.
     * 
     * @var string
     */

    protected $table = 'notification_types';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $fillable = ['description', 'slug'];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;
   

    /**
     * Get all the logs for this type
     * 
     * @return EmailLog
     */
    public function logs() {
        return $this->hasMany('App\Models\NotificationLog', 'type_id');
    }    
}
