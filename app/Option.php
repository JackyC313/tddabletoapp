<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    public function survey() {
        return $this->belongsTo(Survey::class);
    }

    public function question() {
        return $this->belongsTo(Question::class);
    }

    public function answers() {
        return $this->hasMany(Answer::class);
    }
}
