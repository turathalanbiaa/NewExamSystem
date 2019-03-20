@extends("Website.layout.app")
@section("title")
    <title>{{$exam->title}}</title>
@endsection
@section("content")
    <div class="container">
        <div class="row">
            <h4 class="alert-heading font-weight-bold mb-4 text-center">{{$exam->title}}</h4>
            @foreach($exam->questions as $question)
                <div class="col-12 pb-5">
                    <div class="card">
                        <div class="card-body">
                            <h3 class="card-title"><a>{{$question->title}}</a></h3>
                            <hr/>
                            {{--true false--}}
                            @if ($question->type== \App\Enums\QuestionType::TRUE_OR_FALSE)
                                @foreach($question->branches as $branch)
                                    <h5 class="card-title"><a>{{$branch->title}}</a></h5>
                                    @foreach(json_decode($branch->options) as $option)
                                        <div class="custom-control custom-radio">
                                            <input type="radio" class="custom-control-input"
                                                   id="{{$branch->id."-".$loop->index}}" name="{{$branch->id}}"
                                                   @if(!empty($branch->getStudentAnswer))
                                                   @if ($branch->getStudentAnswer->text==$option) checked @endif
                                                    @endif disabled>
                                            <label class="custom-control-label"
                                                   for="{{$branch->id."-".$loop->index}}">{{$option}}</label>
                                        </div>
                                    @endforeach
                                    <hr/>
                                @endforeach
                            @endif
                            {{--singal choice--}}
                            @if ($question->type==\App\Enums\QuestionType::SINGLE_CHOICE)
                                @foreach($question->branches as $branch)
                                    <h5 class="card-title"><a>{{$branch->title}}</a></h5>
                                    @foreach(json_decode($branch->options) as $option)
                                        <div class="custom-control custom-radio">
                                            <input type="radio" class="custom-control-input"
                                                   id="{{$branch->id."-".$loop->index}}" name="{{$branch->id}}"
                                            @if(!empty($branch->getStudentAnswer))
                                                   @if ($branch->getStudentAnswer->text==$option) checked @endif
                                             @endif disabled>
                                            <label class="custom-control-label"
                                                   for="{{$branch->id."-".$loop->index}}">{{$option}}</label>
                                        </div>
                                    @endforeach
                                    <hr/>
                                @endforeach
                            @endif
                            {{--fill in the blanck--}}
                            @if ($question->type==\App\Enums\QuestionType::FILL_BLANK)
                                @foreach($question->branches as $branch)
                                    <div class="md-form">
                                        <input type="text" id="{{$branch->id}}" class="form-control" value="@if(!empty($branch->getStudentAnswer)){{$branch->getStudentAnswer->text}}@endif" disabled>
                                        <label class="w-100" for="{{$branch->id}}">{{$branch->title}}</label>
                                    </div>
                                @endforeach
                            @endif
                            {{--expalin--}}
                            @if ($question->type==\App\Enums\QuestionType::EXPLAIN)
                                @foreach($question->branches as $branch)
                                    <div class="md-form">
                                        <input type="text" id="{{$branch->id}}" class="form-control" value="@if(!empty($branch->getStudentAnswer)){{$branch->getStudentAnswer->text}}@endif" disabled>
                                        <label class="w-100" for="{{$branch->id}}">{{$branch->title}}</label>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
