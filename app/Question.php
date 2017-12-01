<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    public function options()
    {
        return $this->hasMany(Option::class);
    }

    public function answers()
    {
        return $this->hasMany(Answer::class);
    }

    public function validationArray(string $keyPrefix = '')
    {
        // Form validation to check for valid [ids that belong to that question] option ids
        $question = $this;
        $validate = [];
        if( (count($question) > 0) && (count($question->options) > 0) ) {
            $validOptions = "";
            foreach($question->options as $option) {
                $validOptions .= $option->id . ',';
            }
            $validOptions = rtrim($validOptions,',');
            $validate[$keyPrefix.$question->id] = 'required|in:'.$validOptions;
        }
        return $validate;
    }
}
