<?php

namespace MattDaneshvar\Survey\Tests;

use MattDaneshvar\Survey\Models\Question;
use MattDaneshvar\Survey\Models\Survey;

class QuestionTest extends TestCase
{
    /** @test */
    public function it_has_content()
    {
        $question = create(Question::class, ['content' => 'How many cats do you have?']);

        $this->assertEquals('How many cats do you have?', $question->content);
    }

    /** @test */
    public function it_has_a_type()
    {
        $question = create(Question::class, ['type' => 'radio']);

        $this->assertEquals('radio', $question->type);
    }

    /** @test */
    public function it_has_a_key()
    {
        $question = create(Question::class);

        $this->assertNotNull($question->key);
    }

    /** @test */
    public function it_may_have_rules()
    {
        $question = new Question([
            'content' => 'How many cats do you have?',
            'rules' => ['numeric', 'min:1'],
        ]);

        $this->assertCount(2, $question->rules);
    }

    /** @test */
    public function it_may_have_options()
    {
        $question = new Question([
            'content' => 'How many cats do you have?',
            'options' => ['One', 'Two', 'Three'],
        ]);

        $this->assertCount(3, $question->options);
    }

    /** @test */
    public function it_automatically_persist_the_same_survey_id_as_the_parent_section()
    {
        $survey = create(Survey::class);

        $section = $survey->sections()->create(['name' => 'Basic Information']);

        $question = $section->questions()->create([
            'content' => 'How many cats do you have?',
        ]);

        $this->assertEquals($survey->id, $question->survey->id);
    }
}
