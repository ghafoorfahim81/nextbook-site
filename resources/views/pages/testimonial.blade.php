@extends('layouts.app', ['title' => __('site.testimonials.form_title')])

@section('content')
@include('partials.page-head', ['heading' => __('site.testimonials.form_title'), 'intro' => __('site.testimonials.form_intro')])

<section class="section">
    <div class="container-page max-w-3xl">
        <div class="card">
            @if (session('sent'))
                <div class="rounded-xl bg-green-50 px-4 py-3 text-green-800">{{ __('site.testimonials.sent') }}</div>
            @else
                <form method="POST" action="{{ route('testimonials.store') }}" enctype="multipart/form-data" class="grid gap-4">
                    @csrf
                    <div class="hidden" aria-hidden="true"><input type="text" name="website" tabindex="-1" autocomplete="off"></div>
                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label class="label" for="name">{{ __('site.testimonials.name') }}</label>
                            <input class="input" id="name" name="name" value="{{ old('name') }}" required>
                            @error('name')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="label" for="role">{{ __('site.testimonials.role') }} <span class="text-slate-400">({{ __('site.form.optional') }})</span></label>
                            <input class="input" id="role" name="role" value="{{ old('role') }}">
                        </div>
                    </div>
                    <div>
                        <label class="label" for="quote">{{ __('site.testimonials.quote') }}</label>
                        <textarea class="input" id="quote" name="quote" rows="6" required minlength="10">{{ old('quote') }}</textarea>
                        @error('quote')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="label" for="files">{{ __('site.testimonials.files') }} <span class="text-slate-400">({{ __('site.form.optional') }})</span></label>
                        <input class="input" id="files" name="files[]" type="file" multiple accept="image/*,video/mp4,video/webm,video/quicktime,audio/*">
                        <p class="mt-1 text-xs text-slate-500">{{ __('site.testimonials.files_hint') }}</p>
                        @error('files')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                        @error('files.*')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="label" for="contact">{{ __('site.testimonials.contact') }} <span class="text-slate-400">({{ __('site.form.optional') }})</span></label>
                        <input class="input" dir="ltr" id="contact" name="contact" value="{{ old('contact') }}">
                    </div>
                    <button class="btn btn-primary w-full sm:w-auto sm:justify-self-start">{{ __('site.testimonials.send') }}</button>
                </form>
            @endif
        </div>
    </div>
</section>
@endsection
