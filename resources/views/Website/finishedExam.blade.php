@extends("Website.layout.app")
@section("title")
    <title>{{$exam->title}}</title>
@endsection
@section("content")
    <div class="container">
        <div class="row">
            <div class="col-12 order-0 py-4">
                <div class="row text-center">
                    <div class="col-sm-4">
                        <h5>
                            <span>المادة: </span>
                            {{$exam->course->name}}
                        </h5>
                    </div>
                    <div class="col-sm-4">
                        <h5>
                            <span>الامتحان: </span>
                            {{$exam->title}}
                        </h5>
                    </div>
                    <div class="col-sm-4">
                        <h5>
                            <span>التاريخ: </span>
                            {{$exam->date}}
                        </h5>
                    </div>
                </div>
            </div>

            @php $total = 0; @endphp
            @foreach($exam->questions as $question)
                <div class="col-12 order-{{$loop->iteration + 3}} pt-4">
                    <div class="card">
                        <div class="card-body">
                            <h3 class="card-title">
                                {{$question->title}}
                                <span class="badge badge-pill badge-info float-left">
                                    <span>الدرجة : </span>
                                    {{$question->score}}
                                </span>
                            </h3>
                            <hr/>
                            @php
                                $no_of_answer = 0;
                                $no_of_correct_answer = 0;
                                $result = 0;
                            @endphp
                            {{--true false--}}
                            @if ($question->type== \App\Enums\QuestionType::TRUE_OR_FALSE)
                                @foreach($question->branches as $branch)
                                    <h5 class="card-title">
                                        {{$loop->iteration}} - {{$branch->title}}
                                    </h5>
                                    @foreach(json_decode($branch->options) as $option)
                                        <div class="custom-control custom-radio">
                                            <input type="radio" class="custom-control-input"
                                                   @if($branch->getStudentAnswer && $branch->getStudentAnswer->text === $option)
                                                       checked
                                                   @endif
                                                   disabled>
                                            <label class="custom-control-label">{{$option}}</label>
                                        </div>
                                    @endforeach
                                    <div class="py-2">
                                        <span>الاختيار الصحيح : </span>
                                        {{ $branch->correct_option}}
                                    </div>
                                    @if($branch->getStudentAnswer)
                                        @php $no_of_answer +=1; @endphp
                                        @if($branch->getStudentAnswer->text === $branch->correct_option)
                                            @php $no_of_correct_answer +=1; @endphp
                                            <div class="alert alert-success text-center mt-2">
                                                النتيجة مطابقة
                                            </div>
                                        @else
                                            <div class="alert alert-danger text-center mt-2">
                                                النتيجة  غير مطابقة
                                            </div>
                                        @endif
                                    @else
                                        <div class="alert alert-warning text-center mt-2">لم تتم الاجابة</div>
                                    @endif
                                    <hr/>
                                @endforeach
                            @endif
                            {{--singal choice--}}
                            @if ($question->type==\App\Enums\QuestionType::SINGLE_CHOICE)
                                @foreach($question->branches as $branch)
                                    <h5 class="card-title">
                                        {{$loop->iteration}} - {{$branch->title}}
                                    </h5>
                                    @foreach(json_decode($branch->options) as $option)
                                        <div class="custom-control custom-radio">
                                            <input type="radio" class="custom-control-input"
                                                   @if($branch->getStudentAnswer && $branch->getStudentAnswer->text === $option)
                                                       checked
                                                   @endif
                                                   disabled>
                                            <label class="custom-control-label">{{$option}}</label>
                                        </div>
                                    @endforeach
                                    <div class="py-2">
                                        <span>الاختيار الصحيح : </span>
                                        {{ $branch->correct_option}}
                                    </div>
                                    @if($branch->getStudentAnswer)
                                        @php $no_of_answer +=1; @endphp
                                        @if($branch->getStudentAnswer->text === $branch->correct_option)
                                            @php $no_of_correct_answer +=1; @endphp
                                            <div class="alert alert-success text-center mt-2">
                                                النتيجة مطابقة
                                            </div>
                                        @else
                                            <div class="alert alert-danger text-center mt-2">
                                                النتيجة  غير مطابقة
                                            </div>
                                        @endif
                                    @else
                                        <div class="alert alert-warning text-center mt-2">لم تتم الاجابة</div>
                                    @endif
                                    <hr/>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-sm-6 order-{{$loop->iteration}}">
                    <div class="card">
                        <div class="card-header bg-info text-white">{{$question->title}}</div>
                        <div class="card-body">
                            <div class="list-group list-group-flush">
                                <div class="list-group-item">
                                    <span>عدد الكلي للفروع</span>
                                    <span class="badge badge-pill badge-default float-left" style="font-size: 15px">{{$question->no_of_branch}}</span>
                                </div>
                                <div class="list-group-item">
                                    <span>عدد الفروع المطلوب الاجابة عنها</span>
                                    <span class="badge badge-pill badge-default float-left" style="font-size: 15px">{{$question->no_of_branch_req}}</span>
                                </div>
                                <div class="list-group-item">
                                    <span>عدد الفروع التي اجبت عنها</span>
                                    <span class="badge badge-pill badge-default float-left" style="font-size: 15px">{{$no_of_answer}}</span>
                                </div>
                                <div class="list-group-item">
                                    <span>عدد اجوبتك الصحيحة</span>
                                    <span class="badge badge-pill badge-default float-left" style="font-size: 15px">{{$no_of_correct_answer}}</span>
                                </div>
                                <div class="list-group-item">
                                    <span>درجتك النهائية في هذا السؤال</span>
                                    <span class="badge badge-pill badge-info float-left" style="font-size: 15px">
                                        @php
                                            $no_of_correct_answer = ($no_of_correct_answer > $question->no_of_branch_req)
                                                ? $question->no_of_branch_req
                                                : $no_of_correct_answer;
                                            $result = ($question->score/$question->no_of_branch_req) * $no_of_correct_answer;
                                            $total += $result;
                                        @endphp
                                        {{$result}} / {{$question->score}}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach

            <div class="col-12 order-3 pt-4">
                <div class="card text-center">
                    <h4 class="card-body m-0">
                        درجتكم في الامتحان
                        <span class="badge badge-pill badge-default align-text-bottom">{{$total}}/100</span>
                        <div class="mt-2">
                            <p class="lead">سيتم اعتبار درجتكم في الامتحان من 75 وحسب المعادلة الاتية</p>
                            <p class="lead">درجة الامتحان = درجتكم * 0.75</p>
                        </div>
                    </h4>
                </div>
            </div>
        </div>
    </div>
@endsection
