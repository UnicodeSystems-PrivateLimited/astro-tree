<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\GiftCategory;

class Gift extends Model
{
   protected $guarded=['id'];
   
   public function category(){
       return $this->belongsTo('App\Models\GiftCategory','cat_id');
   }
   
   public function client(){
       return $this->belongsTo('App\Models\Client','user_id');       
   }
}
