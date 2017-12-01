<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    public function options() {
        return $this->hasMany(Option::class);
    }

    public function answers() {
        return $this->hasMany(Answer::class);
    }
}
