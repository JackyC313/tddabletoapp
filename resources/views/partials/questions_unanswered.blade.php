                <div class="panel-heading">Available Questions</div>
                <div class="panel-body">
                        <div>Questions for you to answer</div>
                        <ul class="list-group">
                        @if(count($unanswered_pagination->items()))
                            @foreach ($unanswered_pagination->items() as $question)
                            <a href="/question/{{ $question->id }}/index" class="list-group-item list-group-item-action">{{ $question->question }}</a>
                            @endforeach
                            {{ $unanswered_pagination->links() }}
                        @else
                            <p class="flow-text center-align">There are no more questions for you to answer</p>
                        @endif
                        </ul>
                </div>