<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Votation extends Model
{
    protected $fillable = ['name', 'type', 'total_voters', 'quorum'];
    protected $hidden = ['created_at', 'updated_at'];
    
    public function options(){
      return $this->hasMany('App\Option');
    }
}
