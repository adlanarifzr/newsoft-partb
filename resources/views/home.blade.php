@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="alert alert-warning" role="alert">
                <strong>INFO:</strong> User can only see listing, but no access to listing CRUD like admin.
            </div>

            <div class="alert alert-warning" role="alert">
                <strong>INFO:</strong> Only Admin can access user CRUD page.
            </div>

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
        </div>
    </div>
</div>
@endsection
