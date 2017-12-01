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

class ModelMethodTest extends TestCase
{
    use DatabaseMigrations;
    use DatabaseTransactions;

    /**
     * Set Up 1 dummy user
     * Set Up 2 dummy questions
     * Set Up 2 dummy options (one for each question)
     * Set Up 1 dummy answer for the created user
     * This is so we can have a user with an answer to one question and no answer for the other
     */
    protected function setup() {
        parent::setup();

        $this->user = factory(User::class)->create([
            'username' => 'TestUser for running test',
            'email' => 'test_unique_answer111111@example.com',
            'password' => bcrypt('secret'),
            'remember_token' => str_random(10),
        ]);

        $this->question = factory(Question::class)->create([
            'question' => "Question 1 for running test?",
        ]);

        $this->option = factory(Option::class)->create([
            'option' => "Test Option 1 for Question 1",
            'question_id' => $this->question->id
        ]);

        $this->question->options()->save($this->option);
        
        $this->answer = factory(Answer::class)->create([
            'question_id' => $this->question->id,
            'option_id' => $this->option->id,
            'user_id' => $this->user->id,
        ]);

        // Question with no answers
        $this->question_unanswered = factory(Question::class)->create([
            'question' => "Question 2 with no answers for test?",
        ]);

        $this->option_unanswered = factory(Option::class)->create([
            'option' => "Test Option for Question 2",
            'question_id' => $this->question_unanswered->id
        ]);

        $this->question_unanswered->options()->save($this->option_unanswered);
    }

    /**
     * [Model Method Tests] 
     * Test custom model methods
     * 
     * User
     *      - checkUserHasAnswer
     *      - getAllUnansweredQuestions
     *      - getAllAnsweredQuestions
     * 
     * Question
     *      - questionValidationArray
     */

    /**
     * User - checkUserHasAnswer
     * 
     * Given a user has an answer for a question
     * Calling $user->checkUserHasAnswer($question->id) with $user being the newly created user 
     *      and $question->id being the id of the question id for the answer that was just created
     * Should return true
     * 
     * @group UnitModelMethodTest 
     * @test
     * @return void
     */   
    public function testUser_checkUserHasAnswerModel()
    {
        // Calling $user->checkUserHasAnswer($question->id) with $user being the newly created user 
        //      and $question->id being the id of the question id for the answer that was just created
        $userHasAnswer = $this->user->checkUserHasAnswer($this->question->id);

        // Should return true
        $expectedAssertion = true;
        $this->assertEquals($userHasAnswer, $expectedAssertion);
    }

    /**
     * User - getAllAnsweredQuestions
     * 
     * Given a user has an answer for only 1 of 2 questions
     * Calling $user->getAllAnsweredQuestions() with $user being the newly created user 
     * Answered Question id should be in the returned collection
     * 
     * @group UnitModelMethodTest 
     * @test
     * @return void
     */   
    public function testUser_getAllAnsweredQuestions()
    {
        // Calling $user->getAllUnansweredQuestions() with $user being the newly created user 
        $questions_answered = $this->user->getAllAnsweredQuestions();
        $expectedAssertion = $this->question->id;

        // unAnswered Question id should be in the returned collection
        $this->assertContains($expectedAssertion, $questions_answered->pluck('id')->toArray());
    }

    /**
     * User - getAllUnansweredQuestions
     * 
     * Given a user has an answer for only 1 of 2 questions
     * Calling $user->getAllUnansweredQuestions() with $user being the newly created user 
     * unAnswered Question id should be in the returned collection
     * 
     * @group UnitModelMethodTest 
     * @test
     * @return void
     */   
    public function testUser_getAllUnansweredQuestions()
    {
        // Calling $user->getAllUnansweredQuestions() with $user being the newly created user 
        $questions_unanswered = $this->user->getAllUnansweredQuestions();
        $expectedAssertion = $this->question_unanswered->id;

        // unAnswered Question id should be in the returned collection
        $this->assertContains($expectedAssertion, $questions_unanswered->pluck('id')->toArray());
    }

    /**
     * User - saveUserAnswer
     * 
     * Given a user have selected and posted an answer
     * Calling $user->saveUserAnswer($question, $input_answer_id) 
     *      with $user being the user the answer belongs to
     *      with $question being the question the answer belongs to
     *      and $input_answer_id beind the option id of selected answer 
     * Answer's table should have an entry with the proper UserId, QuestionId and AnswerId
     * 
     * @group UnitModelMethodTest 
     * @test
     * @return void
     */   
    public function testUser_saveUserAnswer()
    {
        // Calling $user->getAllUnansweredQuestions() with $user being the newly created user 
        $user = $this->user;
        $question = $this->question_unanswered;
        $input_answer_id = $this->option_unanswered;

        $this->user->saveUserAnswer($question, $input_answer_id);

        $storedAnswer = Answer::where('user_id', $this->user->id)
            ->where('question_id', $this->question_unanswered->id)
            ->where('option_id', $input_answer_id)
            ->get();

        $expectedAssertion = 1;

        // unAnswered Question id should be in the returned collection
        $this->assertCount($expectedAssertion, $storedAnswer);
    }

    /**
     * Question - validationArray
     * 
     * Given a question
     * Calling $question->validationArray($keyPrefix) with $keyPrefix as a sting prefix for the indexed question id
     * An Array should be returned with all valid option responses in the format "required|in:optionId1,optionId2, optionId3,etc"
     * 
     * @group UnitModelMethodTest 
     * @test
     * @return void
     */   
    public function testQuestion_validationArray()
    {
        // Calling $question->questionValidationArray($keyPrefix) with $keyPrefix as a sting prefix for the indexed question id
        $validate = [];
        $keyPrefix = 'question_';
        $validate = $this->question->validationArray($keyPrefix);
        $expectedAssertion = "required|in:".$this->option->id;
        
        // unAnswered Question id should be in the returned collection
        $this->assertEquals($expectedAssertion, $validate['question_1']);
    }
}
