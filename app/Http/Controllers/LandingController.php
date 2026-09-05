<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\HeroSection;
use App\Models\Project;
use Illuminate\Contracts\View\View;

class LandingController extends Controller
{
    /**
     * Show the landing page with the editable hero and the published projects.
     */
    public function __invoke(): View
    {
        return view('landing', [
            'hero' => HeroSection::current(),
            'projects' => Project::published()->get(),
        ]);
    }
}
