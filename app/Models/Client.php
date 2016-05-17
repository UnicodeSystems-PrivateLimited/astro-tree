<?php

namespace App\Models;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use LaravelAcl\Authentication\Models\User;

/**
 * Description of Client
 *
 * @author Unicode
 */
class Client extends User {

    const GROUP_ID = 4;

    public function gift() {
        return $this->hasMany('App\Models\Gift', 'user_id');
    }

    public function user_profile() {
        return $this->hasMany('LaravelAcl\Authentication\Models\UserProfile', 'user_id');
    }

}
