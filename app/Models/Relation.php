<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\RelationType;


class Relation extends Model
{
     protected $guarded=['id'];
   
   public function category(){
       return $this->belongsTo('App\Models\RelationType','cat_id');
   }
   
   public function client(){
       return $this->belongsTo('App\Models\Client','user_id');       
   }
//
}
