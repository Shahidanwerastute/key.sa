<?php

namespace App\Exceptions;

use App\Helpers\Custom;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $e)
    {
        $site_settings = Custom::site_settings();
        if ($site_settings->debug_mode == 'off') {
            return response()->view('errors.custom_error_page');
        } else {
            return parent::render($request, $e);
        }
    }
}