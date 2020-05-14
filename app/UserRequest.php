<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserRequest extends Model
{
    //user can request for vacation, sick day, 
    //
    protected $table = "user_request";

    protected $fillable = [
        'user_id' ,
        'type_id',
        'details' ,
        'date_from',
        'date_to',
        'status_id'
    ];
}
