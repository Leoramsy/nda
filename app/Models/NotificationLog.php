<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotificationLog extends Model {

    const PENDING = 'pending';
    const PROCESS = 'processing';
    const SUCCESS = 'success';
    const WARNING = 'warning';
    const ERROR = 'error';

    /**
     * The table associated with the model. 
     *
     * @var string
     */
    protected $table = 'notification_logs';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['type_id', 'contact_id', 'status', 'reason'];
  
    
    /**
     * Get the type of this log
     * 
     * @return NotificationType
     */
    public function type() {
        return $this->belongsTo('App\Models\NotificationType');
    }
    
    /**
     * Get the Contact for this log
     * 
     * @return Contact
     */
    public function contact() {
        return $this->belongsTo('App\Models\Contact');
    }

}
