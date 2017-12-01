@extends('layouts.app')
@section('content-nav')
            <div class="panel panel-default">
                <div class="panel-heading">Dashboard Home</div>
            </div>
@endsection

@section('content')
            <div class="panel panel-default">
                @include('partials.questions_unanswered')
                @include('partials.questions_answered')
            </div>
@endsection
