<?php

namespace MattDaneshvar\Survey\Tests;

use MattDaneshvar\Survey\Exceptions\GuestEntriesNotAllowedException;
use MattDaneshvar\Survey\Exceptions\MaxEntriesPerUserLimitExceeded;
use MattDaneshvar\Survey\Models\Entry;
use MattDaneshvar\Survey\Models\Survey;

class SurveyEntryTest extends TestCase
{
    /** @test */
    public function guests_may_not_create_entries_by_default()
    {
        $survey = create(Survey::class);

        $this->expectException(GuestEntriesNotAllowedException::class);

        Entry::create(['survey_id' => $survey->id]);
    }

    /** @test */
    public function guests_may_create_entries_when_survey_allows_guest_entries()
    {
        $survey = create(Survey::class, [
            'settings' => ['accept-guest-entries' => true],
        ]);

        $entry = Entry::create(['survey_id' => $survey->id]);

        $this->assertDatabaseHas($entry->getTable(), ['id' => $entry->id]);
    }

    /** @test */
    public function users_may_create_entries_when_survey_doesnt_accept_guest_entries()
    {
        $survey = create(Survey::class);

        $user = $this->signIn();

        $entry = tap(Entry::make(['survey_id' => $survey->id])->by($user))->save();

        $this->assertDatabaseHas($entry->getTable(), ['id' => $entry->id]);
    }

    /** @test */
    public function users_may_create_entries_within_the_specified_max_entries_per_user_limit()
    {
        $survey = create(Survey::class, [
            'settings' => ['limit-per-participant' => 1],
        ]);

        $user = $this->signIn();

        $entry = tap(Entry::make(['survey_id' => $survey->id])->by($user))->save();

        $this->assertDatabaseHas($entry->getTable(), ['id' => $entry->id]);

        $this->expectException(MaxEntriesPerUserLimitExceeded::class);

        Entry::make(['survey_id' => $survey->id])->by($user)->save();
    }

    /** @test */
    public function when_guest_entries_are_allowed_limit_per_participant_is_ignored()
    {
        $survey = create(Survey::class, [
            'settings' => [
                'limit-per-participant' => 0,
                'accept-guest-entries' => true,
            ],
        ]);

        $user = $this->signIn();

        $entry = tap(Entry::make(['survey_id' => $survey->id])->by($user))->save();

        $this->assertDatabaseHas($entry->getTable(), ['id' => $entry->id]);
    }
}
