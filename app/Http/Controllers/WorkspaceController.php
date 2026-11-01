<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

/**
 * "Sign in": each customer has a subdomain of their own, so the visitor says which
 * one and is sent on to that company's sign-in page.
 */
class WorkspaceController extends Controller
{
    public function show()
    {
        return view('pages.login');
    }

    public function go(Request $request)
    {
        $name = $this->normalize((string) $request->input('workspace'));

        if ($name === null) {
            return back()->withInput()->withErrors(['workspace' => __('site.login.invalid')]);
        }

        // The host is built from a validated single label plus our own domain, so this
        // can never send a visitor to another site.
        return redirect()->away(sprintf(
            '%s://%s.%s/login',
            config('site.tenant_scheme'),
            $name,
            config('site.tenant_domain'),
        ));
    }

    /**
     * Accepts "noor", "Noor", "noor.nextbook.af" or a pasted "https://noor.nextbook.af/login"
     * and returns the bare label, or null when it is not a valid subdomain name.
     */
    private function normalize(string $input): ?string
    {
        $input = Str::lower(trim($input));
        $input = preg_replace('#^[a-z]+://#', '', $input);
        $input = Str::before($input, '/');
        $input = Str::chopEnd($input, '.'.config('site.tenant_domain'));

        return preg_match('/^[a-z0-9](?:[a-z0-9-]{0,61}[a-z0-9])?$/', $input) ? $input : null;
    }
}
