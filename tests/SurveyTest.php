<?php

namespace MattDaneshvar\Survey\Tests;

use MattDaneshvar\Survey\Models\Question;
use MattDaneshvar\Survey\Models\Survey;

class SurveyTest extends TestCase
{
    /** @test */
    public function it_has_a_name()
    {
        $survey = create(Survey::class, ['name' => 'Cat Survey']);

        $this->assertEquals('Cat Survey', $survey->name);
    }

    /** @test */
    public function it_may_have_settings()
    {
        $survey = new Survey([
            'name' => 'Cat Survey',
            'settings' => ['accept-guest-entries' => true],
        ]);

        $this->assertCount(1, $survey->settings);
    }

    /** @test */
    public function it_can_add_questions()
    {
        $survey = create(Survey::class);

        $survey->questions()->create(['content' => 'How many cats do you have?']);

        $this->assertEquals(1, $survey->questions->count());
    }

    /** @test */
    public function it_can_add_multiple_questions_at_once()
    {
        $survey = create(Survey::class);

        $questions = factory(Question::class, 2)->create();

        $survey->questions()->saveMany($questions);

        $this->assertEquals(2, $survey->questions->count());
    }

    /** @test */
    public function it_combines_the_rules_of_its_questions()
    {
        $q1 = create(Question::class, ['rules' => ['numeric', 'min:0']]);
        $q2 = create(Question::class, ['rules' => ['date']]);

        $survey = create(Survey::class);

        $survey->questions()->saveMany([$q1, $q2]);

        $this->assertArrayHasKey($q1->key, $survey->rules);
    }

    /** @test */
    public function it_has_a_limit_per_participant()
    {
        $survey = new Survey([]);

        $this->assertEquals(1, $survey->limitPerParticipant());

        $anotherSurvey = new Survey([
            'settings' => ['limit-per-participant' => 5],
        ]);

        $this->assertEquals(5, $anotherSurvey->limitPerParticipant());
    }

    /** @test */
    public function it_may_have_no_limits_per_participant()
    {
        $survey = new Survey([
            'settings' => ['limit-per-participant' => -1],
        ]);

        $this->assertNull($survey->limitPerParticipant());
    }

    /** @test */
    public function it_does_not_accept_guest_entries_by_default()
    {
        $survey = new Survey([]);

        $this->assertFalse($survey->acceptsGuestEntries());
    }

    /** @test */
    public function it_may_accept_guest_entries()
    {
        $survey = new Survey([
            'settings' => ['accept-guest-entries' => true],
        ]);

        $this->assertTrue($survey->acceptsGuestEntries());
    }
}
