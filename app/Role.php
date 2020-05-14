<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    static $HIGHER_MANAGEMENT = 1;
    static $MID_MANAGEMENT = 2;
    static $EMPLOYEE = 3;
}
