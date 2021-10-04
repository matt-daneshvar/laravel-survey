<?php

namespace MattDaneshvar\Survey\Models;

use Illuminate\Database\Eloquent\Model;
use MattDaneshvar\Survey\Contracts\Question;
use MattDaneshvar\Survey\Contracts\Section as SectionContract;

class Section extends Model implements SectionContract
{
    /**
     * Section constructor.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        if (! isset($this->table)) {
            $this->setTable(config('survey.database.tables.sections'));
        }

        parent::__construct($attributes);
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name'];

    /**
     * The questions of the section.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function questions()
    {
        return $this->hasMany(get_class(app()->make(Question::class)));
    }
}
