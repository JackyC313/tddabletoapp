<?php

namespace Tests\Feature;

use App\User;
use App\Question;
use App\Option;
use App\Answer;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AbletoAppTest extends TestCase
{
    use DatabaseMigrations;
    use DatabaseTransactions;    
    /**
     * Test Question View Page (when user already has an answer).
     *
     * @return void
     */
    public function testViewQuestion()
    {
        // Create User
        $user = factory(User::class)->create([
            'username' => 'testuser1',
            'email' => 'testuser1@example.com',
            'password' => bcrypt('secret'),
            'remember_token' => str_random(10)
        ]);

        // Create Question
        $question = factory(Question::class)->create([
            'question' => 'What color do you like?'
        ]);

        // Create Question Options
        $option = factory(Option::class)->make([
            'option' => 'Blue',
            'question_id' => $question->id
        ]);

        $question->options()->save($option);
        
        // Create Answer From User for Question
        $answer = factory(Answer::class)->make([
            'question_id' => $question->id,
            'option_id' => $option->id,
            'user_id' => $user->id
        ]);

        $user->answers()->save($answer);
        $response = $this->actingAs($user)
        ->get('/question/'.$question->id)
        ->assertStatus(200)
        ->assertSeeText('Question Page');
    }
}
