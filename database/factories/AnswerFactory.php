<?php

use Faker\Generator as Faker;
use MattDaneshvar\Survey\Models\Answer;
use MattDaneshvar\Survey\Models\Question;

$factory->define(Answer::class, function (Faker $faker) {
    return [
        'value' => $faker->words(3, true),
        'question_id' => factory(Question::class)->create()->id,
    ];
});
