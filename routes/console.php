<?php

use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function (): void {
    $this->comment('Build a little progress every day.');
})->purpose('Display an encouraging message');
