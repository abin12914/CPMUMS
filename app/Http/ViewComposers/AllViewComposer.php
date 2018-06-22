<?php

namespace App\Http\ViewComposers;

use Illuminate\View\View;
use Auth;

class AllViewComposer
{
    protected $loggedUser = [];

    /**
     * Create a new profile composer.
     *
     * @param  UserRepository  $users
     * @return void
     */
    public function __construct()
    {
        if(Auth::check()) {
            $this->loggedUser = Auth::user();
        }
    }

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $view->with(['loggedUser' => $this->loggedUser]);
    }
}