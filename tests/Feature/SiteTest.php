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
use Illuminate\Foundation\Testing\TestResponse;

class SiteTest extends TestCase
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

        // Macro used to follow redirects
        $thisTest = $this;
        TestResponse::macro('followRedirects', function ($testCase = null) use ($thisTest) {
            $response = $this;
            $testCase = $testCase ?: $thisTest;

            while ($response->isRedirect()) {
                $response = $testCase->get($response->headers->get('Location'));
            }

            return $response;
        });
    }

    /**
     * Test each url
     * Site has the follow different pages
     * 
     * [GET]
     * /                                - index                 [not login protected]
     * /dashboard                       - dashboard             [login protected]
     * /question/{question}             - question display      [login protected]
     * /question/{question}/results     - question results      [login protected]
     * 
     * [POST] 
     * /question/{question}/submit      - question submit       [login protected]
     * 
     */

     /**
     * /                                - index                 [not login protected]
     * 
     * While not logged in 
     * Going to Index page
     * Should show welcome msg "Welcome to the AbleTo App" in heading
     * 
     * @group FeatureSiteTest 
     * @test
     * @return void
     */
    public function testIndexNotLoggedIn()
    {
        $this->get('/')
            ->assertStatus(200)
            ->assertSeeText('Welcome to the AbleTo App');
    }
 
    /**
     * /                                - index                 [not login protected]
     * 
     * While logged in 
     * Going to Index page
     * Should send you to the dashboard
     * 
     * @group UnitSiteTest 
     * @test
     * @return void
     */
    public function testIndexLoggedIn()
    {
        $this->actingAs($this->user)
            ->get('/')
            ->assertStatus(302)
            ->assertRedirect('/dashboard')
            ->followRedirects()
            ->assertSeeText('Dashboard Home');
    }

    /**
     * /question/{question}             - question display      [login protected]
     * 
     * While logged in 
     * Going to Question page with the question_id of a question the user HAVE NOT answered
     * Should show "Question Page" in heading
     * 
     * @group UnitSiteTest 
     * @test
     * @return void
     */
    public function testQuestionPageLoggedInHaveNotAnswered()
    {
        $question_id = $this->question_unanswered->id;

        $response = $this->actingAs($this->user)
        ->get('/question/'.$question_id)
        ->assertStatus(200)
        ->assertSeeText('Question Page');
    }

    /**
     * /question/{question}             - question display      [login protected]
     * 
     * While logged in 
     * Going to Question page with the question_id of a question the user HAVE answered
     * Should redirect user back to dashboard
     * 
     * @group UnitSiteTest 
     * @test
     * @return void
     */
    public function testQuestionPageLoggedInHaveAnswered()
    {
        $question_id = $this->question->id;

        $this->actingAs($this->user)
            ->get('/question/'.$question_id)
            ->assertStatus(302)
            ->assertRedirect('/dashboard')
            ->followRedirects()
            ->assertSeeText('Dashboard Home');
    }

    /**
     * /question/{question}/results     - question results      [login protected]
     * 
     * While logged in 
     * Going to Question Results page with the question_id of a question the user HAVE NOT answered
     * Should redirect user back to dashboard
     * 
     * @group UnitSiteTest 
     * @test
     * @return void
     */
    public function testQuestionResultsLoggedInHaveNotAnswered()
    {
        $question_id = $this->question_unanswered->id;
        
        $this->actingAs($this->user)
            ->get('/question/'.$question_id.'/results')
            ->assertStatus(302)
            ->assertRedirect('/question/'.$question_id)
            ->followRedirects()
            ->assertSeeText('Question Page');
    }

   /**
     * /question/{question}/results     - question results      [login protected]
     * 
     * While logged in 
     * Going to Question Results page with the question_id of a question the user HAVE answered
     * Should show "Question Results Page" in heading
     * 
     * @group UnitSiteTest 
     * @test
     * @return void
     */
    public function testQuestionResultsLoggedInHaveAnswered()
    {
        $question_id = $this->question->id;
        
        $this->actingAs($this->user)
            ->get('/question/'.$question_id.'/results')
            ->assertStatus(200)
            ->assertSeeText('Question Results Page');
    }

}
