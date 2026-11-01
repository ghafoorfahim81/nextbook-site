<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Answers 400 to a request whose text is not valid UTF-8. Browsers never send such a thing;
 * bots and broken clients do, and the database would reject it with a server error.
 */
class RejectMalformedInput
{
    public function handle(Request $request, Closure $next): Response
    {
        $malformed = false;

        $input = $request->all();

        array_walk_recursive($input, function ($value) use (&$malformed) {
            if (is_string($value) && ! mb_check_encoding($value, 'UTF-8')) {
                $malformed = true;
            }
        });

        abort_if($malformed, 400);

        return $next($request);
    }
}
