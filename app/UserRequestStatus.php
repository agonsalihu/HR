<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserRequestStatus extends Model
{
    static $PENDING = 1;
    static $APPROVED = 2;
    static $DENIED = 3;
}
