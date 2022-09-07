<?php

namespace MattDaneshvar\Survey\Models;

use Illuminate\Database\Eloquent\Model;
use MattDaneshvar\Survey\Contracts\Answer as AnswerContract;
use MattDaneshvar\Survey\Contracts\Entry;
use MattDaneshvar\Survey\Contracts\Question;
use Spatie\Translatable\HasTranslations;

class Answer extends Model implements AnswerContract
{
    use HasTranslations;

    public $translatable = [
        'value',
    ];

    /**
     * Answer constructor.
     *
     * @param  array  $attributes
     */
    public function __construct(array $attributes = [])
    {
        if (! isset($this->table)) {
            $this->setTable(config('survey.database.tables.answers'));
        }

        parent::__construct($attributes);
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['value', 'question_id', 'entry_id'];

    /**
     * The entry the answer belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function entry()
    {
        return $this->belongsTo(get_class(app()->make(Entry::class)));
    }

    /**
     * The question the answer belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function question()
    {
        return $this->belongsTo(get_class(app()->make(Question::class)));
    }
}
