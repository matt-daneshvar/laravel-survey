<?php

namespace MattDaneshvar\Survey\Tests;

use MattDaneshvar\Survey\Models\Entry;
use MattDaneshvar\Survey\Models\Question;
use MattDaneshvar\Survey\Models\Survey;

class EntryTest extends TestCase
{
    /** @test */
    public function it_can_be_made_from_an_array()
    {
        $entry = (new Entry())->fromArray([
            1 => 'Five',
            2 => 'None of the above',
        ]);

        $this->assertEquals(2, $entry->answers->count());
    }

    /** @test */
    public function it_accepts_a_survey()
    {
        $survey = $this->createSurvey();

        $entry = (new Entry)->for($survey);

        $this->assertEquals($survey->id, $entry->survey->id);
    }

    /** @test */
    public function it_accepts_a_participant()
    {
        $user = $this->signIn();

        $entry = (new Entry)->by($user);

        $this->assertEquals($user->id, $entry->participant->id);
    }

    /** @test */
    public function it_can_chain_method_calls()
    {
        $survey = $this->createSurvey(2);

        $entry = new Entry();

        $entry->fromArray([
            1 => 'Five',
            2 => 'None of the above',
        ])->for($survey)->push();

        $this->assertEquals($entry->id, $survey->entries->first()->id);
    }

    protected function createSurvey($questionsCount = 2)
    {
        $survey = create(Survey::class, ['settings' => ['accept-guest-entries' => true]]);

        $questions = factory(Question::class, $questionsCount)->create();

        $survey->questions()->saveMany($questions);

        return $survey;
    }
}
