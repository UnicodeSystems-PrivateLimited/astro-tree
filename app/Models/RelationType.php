<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RelationType extends Model
{
     protected $guarded=['id'];
    
    public function relation(){
        return $this->hasOne('App\Models\Relation','cat_id');
    }
    //
}
