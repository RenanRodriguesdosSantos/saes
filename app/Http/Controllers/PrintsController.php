<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use App\Models\Forwarding;
use App\Models\Recipe;
use Barryvdh\DomPDF\Facade\Pdf;

class PrintsController extends Controller
{
    public function recipe(Recipe $recipe)
    {
        $patient = $recipe->appointment->service->patient;

        return Pdf::loadView('prints.recipe', compact('recipe', 'patient'))
            ->setPaper('a4')
            ->stream();
    }

    public function certificate(Certificate $certificate)
    {
        $patient = $certificate->appointment->service->patient;

        return Pdf::loadView('prints.certificate', compact('certificate', 'patient'))
            ->setPaper('a4')
            ->stream();
    }

    public function forwarding(Forwarding $forwarding)
    {
        $patient = $forwarding->appointment->service->patient;

        return Pdf::loadView('prints.forwarding', compact('forwarding', 'patient'))
            ->setPaper('a4')
            ->stream();
    }
}
