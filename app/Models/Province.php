<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'provinces';
    /**
     * The attributes that are mass assignable.
     * 
     * @var array
     */

    protected $fillable = ['name'];

    /**
     * Get the Contacts that belongs to this Province
     * 
     * @return Contact
     */
    public function contacts() {
        return $this->hasMany('App\Models\Contact', "province_id", "id");
    }
}
