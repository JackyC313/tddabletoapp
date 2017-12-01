<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Charts;
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
        $user_id = auth()->user()->id;
        $user = User::find($user_id);

        if($user->checkUserHasAnswer($question->id)) {
            // If the user already has an answer for this question
            // send them to the dashboard with the proper error message        
            return redirect('/dashboard')->with('error', 'You have already answered that question');
        }

        // If the user has NOT answered this question
        // show the question and answer options   
        return view('pages.question.show')->with('question', $question);
    }

        /**
     * Display the question results.
     *
     * @param  \App\Question $question
     * @return \Illuminate\Http\Response
     */
    public function results(Question $question)
    {
        $user_id = auth()->user()->id;
        $user = User::find($user_id);

        if(!$user->checkUserHasAnswer($question->id)) {
            // If the user does not have an answer for this question
            // send them over to the question page to give them an incentive to fill it out
            return redirect('/question/'.$question->id)->with('error', 'You have not yet answered this question, why not answer it first?');
        }

        // Answers data for graphing
        $optionsArray = $question->options->reduce(function($optionsArray, $option) {
            $optionsArray["count"][$option->id] = count($option->answers); 
            $optionsArray["name"][$option->id] = $option->option; 
            return $optionsArray;
        });

        // Chart Code
        $chart = Charts::create('bar', 'google')
            // Setup the chart settings
            ->title('Question Results for \"' . $question->question . '\"')
            // A dimension of 0 means it will take 100% of the space
            ->dimensions(0, 400) // Width x Height
            // This defines a preset of colors already done:)
            // You could always set them manually
            ->colors(['#F44336', '#FFC107', '#CCFFCC', '#2196F3'])
            // Setup the datasets labels & values
            ->labels(array_values($optionsArray["name"]))
            ->values(array_values($optionsArray["count"]))
            // Setup what the values mean
            ->elementLabel("People")
            ->xAxisTitle('Answer options')
            ->yAxisTitle('Number of people answered');
            
        $blade_data = array(
            'question' => $question,
            'answer_counts' => $optionsArray["count"],
            'chart' => $chart,
        );

        return view('pages.question.result')->with($blade_data);
    }
}
