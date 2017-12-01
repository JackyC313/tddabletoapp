@extends('layouts.app')

@section('content-nav')
            <div class="panel panel-default">
                <div class="panel-heading">Question Page</div>
            </div>
@endsection

@section('content')
            {!! Form::open(['action'=> ['AnswerController@store', $question->id], 'method' => 'POST', 'class'=>'form-group']) !!}
            <div class="panel panel-default">
                <div class="panel-heading">
                    {!! Form::label('question_'.$question->id, $question->question, ['class'=>'form-group']) !!}
                </div>

                <div class="panel-body">
                    <div class="form-check form-check-inline">
                    @foreach($question->options as $key2=>$option)
                        {!! Form::radio("question_".$question->id, $option->id, false,['id' => "option_" . $question->id . "_" . $option->id]) !!}
                        {!! Form::label("option_".$question->id."_".$option->id, $option->option) !!}
                    @endforeach
                    </div>
                    {!! Form::submit('Submit Answer', array('class'=>'btn btn-primary')) !!}
                </div>
            </div>
            {!! Form::close() !!}
@endsection
