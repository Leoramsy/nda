<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model {

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'contacts';

    /**
     * The attributes that are mass assignable.
     * 
     * @var array
     */
    protected $fillable = ['name', 'surname', 'mobile_number', 'email', 'province_id'];
    protected $date = ['opt_in_date'];

    /**
     * Gets the Province that this Contact belongs to
     * 
     * @return Province
     */
    public function province() {
        return $this->belongsTo('App\Models\Province');
    }
    
    // TODO: rules, validation and 

}
