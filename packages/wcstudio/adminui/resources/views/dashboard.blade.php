@extends('adminui::layouts.app')

@section('content')

<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h2>
                        DashBoard Index
                    </h2>
                </div>
                <div class="card-body min-card">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    Welcome !
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
