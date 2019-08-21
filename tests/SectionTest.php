<?php

namespace MattDaneshvar\Survey\Tests;

use MattDaneshvar\Survey\Models\Section;

class SectionTest extends TestCase
{
    /** @test */
    public function it_has_a_name()
    {
        $section = Section::create(['name' => 'Basic Information']);

        $this->assertEquals('Basic Information', $section->name);
    }
}
