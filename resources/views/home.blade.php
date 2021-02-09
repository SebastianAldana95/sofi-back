@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                        <p>You are logged in!</p>

                        <p>Your user data:</p>

                        <pre class="p-3 bg-dark text-light">
                        @php
                            var_dump(Auth::user())
                        @endphp
                        </pre>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
