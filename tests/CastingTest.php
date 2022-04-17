<?php

namespace MattDaneshvar\Survey\Tests;

use MattDaneshvar\Survey\Models\Entry;
use MattDaneshvar\Survey\Models\Survey;

class CastingTest extends TestCase
{
    /** @test */
    public function strings_are_stored_as_they_are()
    {
        $survey = create(Survey::class, [
            'settings' => ['accept-guest-entries' => true]
        ]);

        $q1 = $survey->questions()->create([
            'content' => 'Name of your cat?',
            'type' => 'text',
        ]);

        $entry = (new Entry)
            ->for($survey)
            ->fromArray([$q1->id => 'Jafar']);

        $entry->push();

        $this->assertEquals('Jafar', $entry->answers->first()->value);
    }

    /** @test */
    public function array_values_are_stored_as_readable_comma_separated_values()
    {
        $survey = create(Survey::class, [
            'settings' => ['accept-guest-entries' => true]
        ]);

        $q1 = $survey->questions()->create([
            'content' => 'Your favorite cat colors?',
            'type' => 'multiselect',
        ]);

        $entry = (new Entry)
            ->for($survey)
            ->fromArray([$q1->id => ['Orange', 'Black']]);

        $entry->push();

        $this->assertEquals('Orange, Black', $entry->answers->first()->value);
    }
}
