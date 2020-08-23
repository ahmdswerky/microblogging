@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex">{{ __('front.home') }}</div>

                <div class="card-body d-flex align-items-center justify-content-between">
                    <span>
                        {{ __('front.welcome')  }} {{ auth()->user()->name }}
                    </span>

                    @connected('twitter')
                        <p class="bg-success-opacity text-success rounded px-4 py-2 m-0 font-weight-bold">
                            <img src="{{ asset('assets/images/alert-success.svg') }}" width="18" class="smx-2" alt="check mark">
                            <span class="px-1"></span>
                            {{ __('front.twitter-connected') }}
                            <a href="{{ route('auth.social', 'twitter') }}" class="text-primary font-weight-light">
                                {{ __('front.change') }}
                            </a>
                        </p>
                    @else
                        <a href="{{ route('auth.social', 'twitter') }}" class="btn btn-primary">
                            <img src="{{ asset('assets/images/twitter-light.svg') }}" width="20" class="ml-1 mr-1" alt="twitter logo" />
                            {{ __('front.connect-twitter') }}
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
