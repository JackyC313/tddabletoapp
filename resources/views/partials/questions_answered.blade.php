                <div class="panel-heading">Answered Questions</div>
                <div class="panel-body">
                        <div>Questions you've already answered.  See what others had to say!</div>

                        <ul class="list-group">
                        @if(count($answered_pagination->items()))
                            @foreach ($answered_pagination->items() as $question)
                            <a href="/question/{{ $question->id }}/results" class="list-group-item list-group-item-action">{{ $question->question }}</a>
                            @endforeach
                            {{ $answered_pagination->links() }}
                        @else
                            <p class="list-group-item list-group-item-action center-align">You have not answered any questions yet. Choose one from the list above.</p>
                        @endif
                        </ul>
                </div>
