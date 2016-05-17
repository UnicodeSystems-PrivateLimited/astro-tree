<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GiftCategory extends Model
{
    protected $guarded=['id'];
    
    public function gift(){
        return $this->hasOne('App\Models\Gift','cat_id');
    }
}
