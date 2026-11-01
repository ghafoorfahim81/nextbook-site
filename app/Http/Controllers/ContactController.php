<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use App\Models\DemoRequest;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function show()
    {
        return view('pages.contact');
    }

    public function demo(Request $request)
    {
        return view('pages.demo', ['selectedType' => $request->query('type')]);
    }

    public function storeMessage(Request $request)
    {
        // "website" is a honeypot: people never see it, bots fill it in.
        if ($request->filled('website')) {
            return back()->with('sent', true);
        }

        $data = $request->validate([
            'name' => 'required|string|max:120',
            'phone' => 'nullable|string|max:40',
            'email' => 'nullable|email|max:160',
            'subject' => 'nullable|string|max:160',
            'message' => 'required|string|max:4000',
        ]);

        if (empty($data['phone']) && empty($data['email'])) {
            return back()->withInput()->withErrors(['phone' => __('site.form.need_contact')]);
        }

        ContactMessage::create($data + ['ip' => $request->ip()]);

        return back()->with('sent', true);
    }

    public function storeDemo(Request $request)
    {
        if ($request->filled('website')) {
            return back()->with('sent', true);
        }

        $data = $request->validate([
            'name' => 'required|string|max:120',
            'phone' => 'required|string|max:40',
            'email' => 'nullable|email|max:160',
            'company' => 'nullable|string|max:160',
            'business_type' => 'nullable|string|in:'.implode(',', config('site.business_types')),
            'branches' => 'nullable|integer|min:1|max:1000',
            'message' => 'nullable|string|max:4000',
        ]);

        DemoRequest::create($data + [
            'locale' => app()->getLocale(),
            'ip' => $request->ip(),
        ]);

        return back()->with('sent', true);
    }
}
