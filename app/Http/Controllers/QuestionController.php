<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Answer;
use App\Question;
use App\User;

class QuestionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a question and their multiple choice options.
     *
     * @param  \App\Question $question
     * @return \Illuminate\Http\Response
     */
    public function show(Question $question)
    {
/*
        $user_id = auth()->user()->id;
        $user = User::find($user_id);

        // Check if user has an answer already for this question
        $questions_id_answered = [];
        $questions_id_answered = $user->answers->map(function($answer) {
            return $answer->question->id;
        })->toArray();

        // If the user already has an answer for this question
        // send them to the dashboard with the proper error message        
        if(in_array($question->id, $questions_id_answered)) {
            return redirect('/dashboard')->with('error', 'You have already answered that question');
        }
*/
        return view('pages.question.show')->with('question', $question);
    }
}
