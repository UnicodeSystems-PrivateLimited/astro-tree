<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AstroProfile extends Model
{
    protected $table='astro_profile';
    protected $guarded = ["id"];
    
    public function user()
    {
        return $this->belongsTo('LaravelAcl\Authentication\Models\User', "user_id");
    }
}
