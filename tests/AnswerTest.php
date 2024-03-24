<?php

namespace MattDaneshvar\Survey\Tests;

use MattDaneshvar\Survey\Models\Answer;
use PHPUnit\Framework\Attributes\Test;

class AnswerTest extends TestCase
{
    #[Test]
    public function it_has_a_value()
    {
        $answer = create(Answer::class, ['value' => 'Five']);

        $this->assertEquals('Five', $answer->value);
    }
}
