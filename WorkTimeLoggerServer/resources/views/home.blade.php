@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    You are logged in!
                </div>
            </div>

            <passport-clients class="mt-5"></passport-clients>
            <passport-authorized-clients class="mt-5"></passport-authorized-clients>
            <passport-personal-access-tokens class="mt-5"></passport-personal-access-tokens>
            
        </div>
    </div>
</div>
@endsection
