<?php

namespace App\Providers;

use App\Enums\Classification;
use Filament\Support\Colors\Color;
use Filament\Support\Facades\FilamentColor;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Vite::macro('image', fn (string $asset) => $this->asset("resources/images/{$asset}"));

        FilamentColor::register([
            'danger' => Color::Red,
            'gray' => Color::Zinc,
            'info' => Color::Blue,
            'primary' => Color::Amber,
            'success' => Color::Green,
            'warning' => Color::Amber,
            'classification_' . Classification::EMERGENCY => Color::Red,
            'classification_' . Classification::VERY_URGENT => Color::Orange,
            'classification_' . Classification::URGENT => Color::Yellow,
            'classification_' . Classification::LITTLE_URGENT => Color::Green,
            'classification_' . Classification::NOT_URGENT => Color::Blue
        ]);
    }
}
