<?php

namespace MattDaneshvar\Survey\Tests;

use MattDaneshvar\Survey\Models\Answer;

class AnswerTest extends TestCase
{
    /** @test */
    public function it_has_a_value()
    {
        $answer = create(Answer::class, ['value' => 'Five']);

        $this->assertEquals('Five', $answer->value);
    }
}
