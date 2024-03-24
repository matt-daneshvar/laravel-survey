<?php

namespace MattDaneshvar\Survey\Tests;

use MattDaneshvar\Survey\Models\Section;
use PHPUnit\Framework\Attributes\Test;

class SectionTest extends TestCase
{
    #[Test]
    public function it_has_a_name()
    {
        $section = Section::create(['name' => 'Basic Information']);

        $this->assertEquals('Basic Information', $section->name);
    }
}
