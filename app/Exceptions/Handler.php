<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
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
        $this->renderable(function (VendorOnlyAccessException $e, $request) {
            return response()->json([
                'message' => $e->getMessage()
            ], 403);
        });

        $this->renderable(function (UnauthorizedAccessException $e, $request) {
            return response()->json([
                'message' => $e->getMessage()
            ], 403);
        });

        $this->renderable(function (ProductAlreadyExistsException $e, $request) {
            return response()->json([
                'message' => $e->getMessage()
            ], 403);
        });

        $this->renderable(function (StoreNotFoundException $e, $request) {
            return response()->json([
                'message' => $e->getMessage()
            ], 404);
        });
    }
}
