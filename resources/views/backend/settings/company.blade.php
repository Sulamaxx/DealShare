@extends('backend.layout.layout')
@php
    $title = 'CSM';
    $subTitle = 'Settings - CSM';
@endphp

@section('content')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- CKEditor 5 CDN -->
    <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>

    <div class="container">
        @foreach ([
            'about' => 'About Page',
            'guidelines' => 'Guidelines Page',
            'faq' => 'FAQ Page',
            'terms' => 'Terms of Service',
            'privacy' => 'Privacy Policy',
            'other' => 'Contact Us',
        ] as $key => $label)
            <form action="{{ route('settings.save') }}" method="POST" class="mb-5">
                @csrf
                <input type="hidden" name="type" value="{{ $key }}">
                <div class="form-group mb-2">
                    <label><strong>{{ $label }}</strong></label>
                    <textarea id="editor-{{ $key }}" name="content" class="form-control">
                        {!! old('content', $data[$key] ?? '') !!}
                    </textarea>
                </div>
                <button type="submit" class="btn btn-primary">Save {{ $label }}</button>
            </form>
        @endforeach
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const editors = document.querySelectorAll('textarea[id^="editor-"]');
            editors.forEach((textarea) => {
                ClassicEditor
                    .create(textarea)
                    .catch(error => {
                        console.error(error);
                    });
            });
        });
    </script>
@endsection
