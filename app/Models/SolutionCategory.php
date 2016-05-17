<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SolutionCategory extends Model {

    protected $guarded = ['id'];

    public function solution() {
        return $this->hasOne('App\Models\Solution', 'cat_id');
    }

}
