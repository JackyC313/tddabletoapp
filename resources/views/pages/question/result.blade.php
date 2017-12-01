@extends('layouts.app')

@section('content-nav')
            <div class="panel panel-default">
                <div class="panel-heading">Question Results Page</div>
            </div>
@endsection

@section('content')
            <div class="panel panel-default">
                <div class="panel-heading">{{ $question->question }}</div>

                <div class="panel-body">
                    You can see question results here.
                    <ul class="list-group">
                    @foreach($question->options as $key2=>$option)
                        <li class="list-group-item">
                            @if($answer_counts[$option->id] <= 0)
                                No one  
                            @elseif($answer_counts[$option->id] == 1)
                                {{ $answer_counts[$option->id] }} Person 
                            @else
                                {{ $answer_counts[$option->id] }} People 
                            @endif
                             answered {{ $option->option}}</li>
                    @endforeach
                    </ul>
                    @if( !empty($chart))
                       {!! $chart->html() !!}
                    @endif
                </div>
            </div>
@endsection
