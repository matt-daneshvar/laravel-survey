<div class="p-4 border-bottom">
    @include(view()->exists("survey::questions.types.{$question->type}") 
        ? "survey::questions.types.{$question->type}" 
        : "survey::questions.types.text",[
            'disabled' => !($eligible ?? true), 
            'value' => (($lastEntry ?? null) !== null) ? $lastEntry->answerFor($question) : null,
            'includeResults' => ($lastEntry ?? null) !== null
        ]
    )
</div>
