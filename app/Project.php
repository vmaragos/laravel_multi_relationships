<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    // protected $guarded = [];
    protected $fillable = ['name'];

    public function members()
    {
        return $this->belongsToMany('App\User')->withTimestamps();
    }
    
    public function creator()
    {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }

}
