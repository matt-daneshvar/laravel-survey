<?php

namespace MattDaneshvar\Survey\Tests;

use Illuminate\Foundation\Auth\User;
use MattDaneshvar\Survey\SurveyServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    /**
     * Setup test environment.
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->loadLaravelMigrations();

        $this->setUpDatabase();

        $this->setUpFactories();
    }

    /**
     * Setup test database.
     */
    protected function setUpDatabase()
    {
        //Package migrations
        include_once __DIR__.'/../database/migrations/create_surveys_table.php.stub';
        include_once __DIR__.'/../database/migrations/create_questions_table.php.stub';
        include_once __DIR__.'/../database/migrations/create_answers_table.php.stub';
        include_once __DIR__.'/../database/migrations/create_entries_table.php.stub';
        include_once __DIR__.'/../database/migrations/create_sections_table.php.stub';

        (new \CreateQuestionsTable())->up();
        (new \CreateSurveysTable())->up();
        (new \CreateEntriesTable())->up();
        (new \CreateAnswersTable())->up();
        (new \CreateSectionsTable())->up();
    }

    /**
     * Setup test factories.
     */
    protected function setUpFactories()
    {
        $this->withFactories(__DIR__.'/../database/factories');
    }

    protected function getPackageProviders($app)
    {
        return [
            SurveyServiceProvider::class,
        ];
    }

    /**
     * Sign in a dummy user.
     *
     * @param User|null $user
     * @return User
     */
    protected function signIn(User $user = null)
    {
        $user = $user ?? User::forceCreate([
            'name' => 'John',
            'email' => 'john@example.com',
            'password' => 'secret',
        ]);

        $this->actingAs($user);

        return $user;
    }
}
