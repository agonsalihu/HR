<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RecruitmentStatus extends Model
{
    static $statuses = ['interviewing', 'interested', 'onboarding', 'finished'];

    public static function isStatusFinished($id) {
        if ($id == 4) {
            return true;
        }
        return false;
    }
}
