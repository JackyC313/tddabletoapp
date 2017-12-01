<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function answers() {
        return $this->hasMany(Answer::class);
    }

    /**
     * Checks to see if the user has an answer for the question with question id
     *
     * @param  integer $question_id
     * @return boolean $userHasAnswer
     * 
     */
    public function checkUserHasAnswer($question_id)
    {
        $userHasAnswer = false;
        if($this->answers->where('question_id', $question_id)->count() > 0)
            $userHasAnswer = true;
        return $userHasAnswer;
    }

    // Get an array of all questions not yet answered by the user
    public function getAllUnansweredQuestions()
    {
        $user = $this;
        $questions_all = Question::get();
        $questions_unanswered = [];
        $questions_unanswered = $questions_all->reject(function($question) use($user) { 
            return $question->answers->where('user_id', $user->id)->count() > 0;
        });
        return $questions_unanswered;

    }

    public function getAllAnsweredQuestions()
    {
        $user = $this;
        $questions_answered = [];
        $questions_answered = $user->answers->map(function($answer) {
            return $answer->question;
        });
        return $questions_answered;
    }
}
