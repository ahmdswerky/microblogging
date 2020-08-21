@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex">{{ __('front.home') }}</div>

                <div class="card-body d-flex align-items-center justify-content-between">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    {{ __('front.welcome')  }} {{ auth()->user()->name }}


                    <button class="btn btn-primary">
                        <img src="{{ asset('assets/images/twitter-light.svg') }}" width="20" class="ml-1 mr-1" alt="twitter logo" />
                        {{ __('front.connect-twitter') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
