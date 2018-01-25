@extends('layouts.master')

@section('content')

    @if(Session::has('success_message'))
        <div class="success-container">
            <p>{{Session::get('success_message')}} <span>x</span></p>
        </div>
        {{ Session::forget('success_message')  }}
    @endif
    @if(Session::has('error_message'))
        <div class="error-container">
            <p>{{Session::get('error_message')}} <span>x</span></p>
        </div>
        {{ Session::forget('error_message')  }}
    @endif

    @include('partials.index.top-nav')
    @include('partials.index.install-section')
    @include('partials.index.about-section')
    @include('partials.index.commands-section')
    @include('partials.index.contact-section')
@endsection