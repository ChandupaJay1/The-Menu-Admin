<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class SettingsLayout extends Component
{
    public $active;
    public $title;

    public function __construct($active = 'profile', $title = 'Settings')
    {
        $this->active = $active;
        $this->title = $title;
    }

    public function render(): View|Closure|string
    {
        return view('layouts.settings');
    }
}
