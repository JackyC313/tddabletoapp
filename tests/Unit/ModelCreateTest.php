<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\Answer;
use App\Option;
use App\Question;
use App\User;

class ModelCreateTest extends TestCase
{
    use DatabaseMigrations;
    use DatabaseTransactions;
    /**
     * [Model Create Tests] 
     * Test creation of each modal
     * Site has the follow different models
     * 
     * User
     * Question
     * Option
     * Answer
     * 
     */

    /**
     * User
     * 
     * Use factory to create User
     * Find created User from the database
     * Should be able to match User data 
     * 
     * @group UnitModelCreateTest 
     * @test
     * @return void
     */   
    public function testUserModel()
    {
        $user = factory(User::class)->create([
            'username' => 'TestUser',
            'email' => 'test_unique12345@example.com',
            'password' => bcrypt('secret'),
            'remember_token' => str_random(10),
        ]);
        
        $new_user = User::find($user->id);
        $this->assertEquals($new_user->username, $user->username);
        $this->assertEquals($new_user->email, $user->email);
        $this->assertEquals($new_user->password, $user->password);
        $this->assertEquals($new_user->remember_token, $user->remember_token);
    }

    /**
     * Question
     * 
     * Use factory to create Question
     * Find created Question from the database
     * Should be able to match Question data 
     * 
     * @group UnitModelCreateTest 
     * @test
     * @return void
     */    
    public function testQuestionModel()
    {
        $question = factory(Question::class)->create([
            'question' => "What is my test question?",
        ]);

        $new_question = Question::find($question->id);
        $this->assertEquals($new_question->question, $question->question);
    }    

    /**
     * Option
     * 
     * Use factory to create Option
     * Find created Option from the database
     * Should be able to match Option data 
     * 
     * @group UnitModelCreateTest 
     * @test
     * @return void
     */    
    public function testOptionModel()
    {
        $question = factory(Question::class)->create([
            'question' => "Question for option test?",
        ]);

        $option = factory(Option::class)->create([
            'option' => "Test Option 1",
            'question_id' => $question->id
        ]);

        $question->options()->save($option);
        $new_option = Option::find($option->id);
        
        $this->assertEquals($new_option->option, $option->option);
        $this->assertEquals($new_option->question_id, $option->question_id);
    }    

    /**
     * Answer
     * 
     * Use factory to create Answer
     * Find created Answer from the database
     * Should be able to match Answer data 
     * 
     * @group UnitModelCreateTest 
     * @test
     * @return void
     */    
    public function testAnswerModel()
    {
        $user = factory(User::class)->create([
            'username' => 'TestUser for answer test',
            'email' => 'test_unique_answer12345@example.com',
            'password' => bcrypt('secret'),
            'remember_token' => str_random(10),
        ]);

        $question = factory(Question::class)->create([
            'question' => "Question for answer test?",
        ]);

        $option = factory(Option::class)->create([
            'option' => "Test Option 1 for answer test",
            'question_id' => $question->id
        ]);

        $question->options()->save($option);
        
        $answer = factory(Answer::class)->create([
            'question_id' => $question->id,
            'option_id' => $option->id,
            'user_id' => $user->id,
        ]);

        $new_answer = Answer::find($answer->id);
        $this->assertEquals($new_answer->question_id, $answer->question_id);
        $this->assertEquals($new_answer->option_id, $answer->option_id);
        $this->assertEquals($new_answer->user_id, $answer->user_id);
    }  
}
