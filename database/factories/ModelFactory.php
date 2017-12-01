<?php

use Faker\Generator as Faker;
use App\Question;
use App\Option;
use App\Answer;
use App\User;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(Question::class, function (Faker $faker) {
    return [
        'question' => $faker->sentence(5) . "?",
    ];
});

/* 
 * DEV NOTE: if this is called in batch, such as "factory(Question::class, 5)"
 * constraint checks may not be up to date on the next create so use a for loop to create instead
 */
$factory->define(Option::class, function (Faker $faker) {
    // Pick a question and associate option to it

    // Filter to questions that have less than (MaxOptions) options so we don't over do it
    $MaxOptions = 5;
    $questions = Question::get()->filter(function ($question) use ($MaxOptions) {
        $options_count = count($question->options);
        return $options_count < $MaxOptions;
    })->pluck('id')->toArray();

    if(!empty($questions)) {
        // Associate option to a random question
        $fake_question_id = $faker->randomElement($questions);
    } else {
        // If no questions are found with the above criteria
        // Associate option to a new question
        $fake_question_id = factory(App\Question::class)->create()->id;
    }

    return [
        'option' => $faker->word,
        'question_id' => $fake_question_id,
    ];
});

/* 
 * DEV NOTE: if this is called in batch, such as "factory(Answer::class, 5)"
 * constraint checks may not be up to date on the next create so use a for loop to create instead
 */
$factory->define(Answer::class, function (Faker $faker) {
    // Pick an option and get it's option id AND question id
    $options = Option::pluck('id')->toArray();
    $fake_option_id = $faker->randomElement($options);
    $fake_question_id = Option::find($fake_option_id)->question_id;

    // Pick a user whom hasn't already answered the question
    $users = User::whereDoesntHave('answers', function($answer) use ($fake_question_id) {
        $answer->where('question_id', '=', $fake_question_id);
    })->pluck('id')->toArray();

    if(!empty($users)) {
        // Associate answer to a random user
        $fake_user_id = $faker->randomElement($users);
    } else {
        // If no user are found with the above criteria
        // Associate answer to a new user
        $fake_user_id = factory(User::class)->create()->id;
    }
    return [
        'question_id' => $fake_question_id,
        'option_id' => $fake_option_id,
        'user_id' => $fake_user_id,
    ];
});