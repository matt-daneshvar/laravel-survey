<?php

use Faker\Generator as Faker;
use MattDaneshvar\Survey\Models\Answer;
use MattDaneshvar\Survey\Models\Question;

$factory->define(Answer::class, function (Faker $faker) {
    return [
        'value' => $faker->text(10),
        'question_id' => factory(Question::class)->create()->id
    ];
});