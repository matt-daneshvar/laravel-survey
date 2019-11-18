<?php

namespace MattDaneshvar\Survey;

use Illuminate\Contracts\View\Factory as ViewFactory;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use MattDaneshvar\Survey\Http\View\Composers\SurveyComposer;

class SurveyServiceProvider extends ServiceProvider
{
    /**
     * Boot the package.
     *
     * @param ViewFactory $viewFactory
     */
    public function boot(ViewFactory $viewFactory)
    {
        $this->publishes([
            __DIR__.'/../config/survey.php' => config_path('survey.php'),
        ], 'config');

        $this->publishes([
            __DIR__.'/../resources/views/' => base_path('resources/views/vendor/survey'),
        ], 'views');

        $this->mergeConfigFrom(__DIR__.'/../config/survey.php', 'survey');

        $this->loadViewsFrom(__DIR__.'/../resources/views', 'survey');

        $viewFactory->composer('survey::standard', SurveyComposer::class);

        $this->publishMigrations([
            'create_surveys_table',
            'create_questions_table',
            'create_entries_table',
            'create_answers_table',
            'create_sections_table',
        ]);
    }

    /**
     * Publish package migrations.
     *
     * @param $migrations
     */
    protected function publishMigrations($migrations)
    {
        foreach ($migrations as $migration) {
            $migrationClass = Str::studly($migration);

            if (class_exists($migrationClass)) {
                return;
            }

            $this->publishes([
                __DIR__."/../database/migrations/$migration.php.stub" => database_path('migrations/'.date('Y_m_d_His',
                        time())."_$migration.php"),
            ], 'migrations');
        }
    }
}
