<?php

namespace MattDaneshvar\Survey\Http\View\Composers;

use Illuminate\Contracts\Auth\Guard;
use Illuminate\View\View;

class SurveyComposer
{
    protected $auth;

    /**
     * SurveyComposer constructor.
     *
     * @param Guard $auth
     */
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Compose the view with relevant values.
     *
     * @param View $view
     */
    public function compose(View $view)
    {
        $view->with([
            'eligible' => $view->survey->isEligible($this->auth->user()),
            'lastEntry' => $view->survey->lastEntry(auth()->user()),
        ]);
    }
}
