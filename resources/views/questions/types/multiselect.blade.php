@component('survey::questions.base', compact('question'))
    @foreach ($question->options as $option)
        <div class="custom-control custom-checkbox">
            <input type="checkbox"
                   name="{{ $question->key }}[]"
                   id="{{ $question->key . '-' . Str::slug($option) }}"
                   value="{{ $option }}"
                   class="custom-control-input"
                    {{ ($value ?? old($question->key)) == $option ? 'checked' : '' }}
                    {{ ($disabled ?? false) ? 'disabled' : '' }}
            >
            <label class="custom-control-label"
                   for="{{ $question->key . '-' . Str::slug($option) }}">{{ $option }}
            </label>
        </div>
    @endforeach
@endcomponent
