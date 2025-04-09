<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

readonly class EnsurePatientBelongsToUser
{
    public function handle(Request $request, Closure $next)
    {
        $patient = $request->route('patient');

        if ($patient->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], Response::HTTP_FORBIDDEN);
        }

        return $next($request);
    }
}
