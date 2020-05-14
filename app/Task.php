<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $table = "task";

    protected $fillable = array('name', 'description', 'department_id', 'user_id');

    public $timestamps = false;
}
