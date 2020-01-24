<?php

namespace MattDaneshvar\Survey\Models;

use Illuminate\Database\Eloquent\Model;

class Survey extends Model
{
    /**
     * Survey constructor.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        if (! isset($this->table)) {
            $this->setTable(config('survey.database.tables.surveys'));
        }

        parent::__construct($attributes);
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'settings'];

    /**
     * The attributes that should be casted.
     *
     * @var array
     */
    protected $casts = [
        'settings' => 'array',
    ];

    /**
     * The survey sections.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function sections()
    {
        return $this->hasMany(Section::class);
    }

    /**
     * The survey questions.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    /**
     * The survey entries.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function entries()
    {
        return $this->hasMany(Entry::class);
    }

    /**
     * Check if survey accepts guest entries.
     *
     * @return bool
     */
    public function acceptsGuestEntries()
    {
        return $this->settings['accept-guest-entries'] ?? false;
    }

    /**
     * The maximum number of entries a participant may submit.
     *
     * @return int|null
     */
    public function limitPerParticipant()
    {
        if ($this->acceptsGuestEntries()) {
            return;
        }

        $limit = $this->settings['limit-per-participant'] ?? 1;

        return $limit !== -1 ? $limit : null;
    }

    /**
     * Survey entries by a participant.
     *
     * @param Model $participant
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function entriesFrom(Model $participant)
    {
        return $this->entries()->where('participant_id', $participant->id);
    }

    /**
     * Last survey entry by a participant.
     *
     * @param Model $participant
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function lastEntry(Model $participant = null)
    {
        return $participant === null ? null : $this->entriesFrom($participant)->first();
    }

    /**
     * Check if a participant is eligible to submit the survey.
     *
     * @param Model|null $model
     * @return bool
     */
    public function isEligible(Model $participant = null)
    {
        if ($participant === null) {
            return $this->acceptsGuestEntries();
        }

        if ($this->limitPerParticipant() === null) {
            return true;
        }

        return $this->limitPerParticipant() > $this->entriesFrom($participant)->count();
    }

    /**
     * Combined validation rules of the survey.
     *
     * @return mixed
     */
    public function getRulesAttribute()
    {
        return $this->questions->mapWithKeys(function ($question) {
            return [$question->key => $question->rules];
        })->all();
    }
}
