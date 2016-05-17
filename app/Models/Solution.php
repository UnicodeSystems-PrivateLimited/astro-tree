<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\SolutionCategory;

class Solution extends Model {

    protected $guarded = ['id'];

    public function category() {
        return $this->belongsTo('App\Models\SolutionCategory', 'cat_id');
    }

    public function client() {
        return $this->belongsTo('App\Models\Client', 'user_id');
    }

}
