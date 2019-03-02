@extends("ControlPanel.layout.app")

@section("title")
    <title>{{$exam->title}}</title>
@endsection

@section("content")
    <div class="container">
        {{-- Session Update Exam Curve Message --}}
        @if (session('UpdateExamCurveMessage'))
            <div class="row">
                <div class="col-12">
                    <div class="alert {{(session('TypeMessage')=="Error")?"alert-danger":"alert-success"}} text-center">
                        {{session('UpdateExamCurveMessage')}}
                    </div>
                </div>
            </div>
        @endif

        {{-- Session Delete Question Message --}}
        @if (session('DeleteQuestionMessage'))
            <div class="row">
                <div class="col-12">
                    <div class="alert alert-success text-center">
                        {{session('DeleteQuestionMessage')}}
                    </div>
                </div>
            </div>
        @endif

        {{-- Session Question Correction Message --}}
        @if (session('QuestionCorrectionMessage'))
            <div class="row">
                <div class="col-12">
                    <div class="alert {{(session('TypeMessage')=="Error")?"alert-danger":"alert-success"}} text-center">
                        {{session('QuestionCorrectionMessage')}}
                    </div>
                </div>
            </div>
        @endif

        {{-- Burron Create--}}
        <div class="row">
            <div class="col-12 mb-3">
                <a href="/control-panel/questions/create?exam={{$exam->id}}" class="btn btn-outline-default font-weight-bold">
                    <i class="fa fa-plus ml-1"></i>
                    <span>اضافة سؤال</span>
                </a>
            </div>
        </div>

        <div class="row">
            {{-- Items --}}
            <div class="col-lg-4">
                {{-- Exam Info --}}
                <div class="row">
                    {{-- Heading --}}
                    <div class="col-12 mb-3">
                        <a class="bg-light h5 p-3 m-0 d-block" data-toggle="collapse" data-target="#exam-info" aria-expanded="false" aria-controls="collapseExamInfo">
                            <i class="fa fa-caret-left text-default ml-1"></i>
                            <span>معلومات حول الامتحان الحالي</span>
                        </a>
                    </div>

                    {{-- Collapes --}}
                    <div class="col-12 mb-3 collapse" id="exam-info">
                        <div class="card">
                            <div class="card-body border-bottom border-default">
                                <ul class="list-group list-group-flush pr-0">
                                    <li class="list-group-item">
                                        <span>المادة: </span>
                                        {{$exam->course->name}}
                                    </li>

                                    <li class="list-group-item">
                                        <span>الاستاذ: </span>
                                        {{$exam->course->lecturer->name}}
                                    </li>

                                    <li class="list-group-item">
                                        <span>الدرجة: </span>
                                        {{$exam->real_score}}
                                    </li>

                                    <li class="list-group-item">
                                        <span>الكيرف: </span>
                                        {{$exam->curve}}
                                    </li>

                                    <li class="list-group-item">
                                        <span>حالة الامتحان: </span>
                                        @if($exam->state == \App\Enums\ExamState::CLOSE)
                                            <span>مغلق</span>
                                        @elseif($exam->state == \App\Enums\ExamState::OPEN)
                                            <span>مفتوح</span>
                                        @else
                                            <span>منتهي</span>
                                        @endif
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Exam Curve --}}
                <div class="row">
                    {{-- Heading --}}
                    <div class="col-12 mb-3">
                        <a class="bg-light h5 p-3 m-0 d-block" data-toggle="collapse" data-target="#exam-curve" aria-expanded="false" aria-controls="collapseExamCurve">
                            <i class="fa fa-caret-left text-default ml-1"></i>
                            <span>كيرف (اضافة درجة على الامتحان)</span>
                        </a>
                    </div>

                    {{-- Collapes --}}
                    <div class="col-12 mb-3 collapse" id="exam-curve">
                        <div class="card">
                            <div class="card-body border-bottom border-default">
                                {{-- Alert Info --}}
                                <div class="alert alert-info mb-0">
                                    <h5 class="text-center pb-2 border-bottom border-primary">اضافة الكيرف</h5>
                                    <ul class="mb-0 pr-3">
                                        <li>يعطى الكيرف على الامتحان النهائي فقط سواء كان الامتحان دور اول او ثاني.</li>
                                        <li>درجة الكيرف اقل من او تساوي (10 درجات).</li>
                                    </ul>
                                </div>

                                {{-- Form Curve --}}
                                @if(($exam->type == \App\Enums\ExamType::FINAL_FIRST_ROLE) || ($exam->type == \App\Enums\ExamType::FINAL_SECOND_ROLE))
                                    <form class="mt-3" method="post" action="/control-panel/exams/{{$exam->id}}">
                                        @method("PUT")
                                        @csrf
                                        <div class="mb-3">
                                            <label for="curve">درجة الكيرف الحالية</label>
                                            <input type="number" name="curve" id="curve" class="form-control" value="{{$exam->curve}}">
                                        </div>
                                        <button class="btn btn-sm btn-outline-default btn-block font-weight-bold" type="submit">ارسال</button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Questions --}}
                <div class="row">
                    {{-- Heading --}}
                    <div class="col-12 mb-3">
                        <a class="bg-light h5 p-3 m-0 d-block" data-toggle="collapse" data-target="#questions" aria-expanded="false" aria-controls="collapseQuestions">
                            <i class="fa fa-caret-left text-default ml-1"></i>
                            <span>جميع الاسئلة</span>
                        </a>
                    </div>

                    {{-- Collapes --}}
                    <div class="col-12 mb-3 collapse" id="questions">
                        <div class="card">
                            <div class="card-body border-bottom border-default">
                                @forelse($exam->questions as $question)
                                    @if ($loop->first)
                                        <div class="list-group list-group-flush">
                                            @endif
                                            <a href="/control-panel/questions/{{$question->id}}" class="list-group-item list-group-item-action">
                                                {{$question->title}}
                                                <span class="badge badge-default float-left">{{$question->score . " درجة"}}</span>
                                            </a>
                                            @if ($loop->last)
                                        </div>
                                    @endif
                                @empty
                                    <h5 class="text-center mb-0">لاتوجد اسئلة</h5>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Manual Correction Questions --}}
                <div class="row">
                    {{-- Heading --}}
                    <div class="col-12 mb-3">
                        <a class="bg-light h5 p-3 m-0 d-block" data-toggle="collapse" data-target="#manual-correction-questions" aria-expanded="false" aria-controls="collapseManualCorrectionQuestions">
                            <i class="fa fa-caret-left text-default ml-1"></i>
                            <span>تصحيح الاسئلة</span>
                        </a>
                    </div>

                    {{-- Collapes --}}
                    <div class="col-12 mb-3 collapse" id="manual-correction-questions">
                        <div class="card">
                            <div class="card-body border-bottom border-default">
                                @forelse($exam->questions as $question)
                                    @if ($loop->first)
                                        <div class="list-group list-group-flush">
                                            @endif
                                            <a href="/control-panel/questions-correction/{{(($question->type==\App\Enums\QuestionType::TRUE_OR_FALSE) || ($question->type==\App\Enums\QuestionType::SINGLE_CHOICE)?"automatically":"manually")}}/{{$question->id}}" class="list-group-item list-group-item-action">
                                                @if($question->correction == \App\Enums\QuestionCorrectionState::CORRECTED)
                                                    <span class="far fa-check-square text-default ml-1"></span>
                                                @else
                                                    <span class="far fa-minus-square text-default ml-1"></span>
                                                @endif
                                                <span class="font-weight-bold">تصحيح السؤال: </span>
                                                <span>{{$question->title}}</span>
                                            </a>
                                            @if ($loop->last)
                                        </div>
                                    @endif
                                @empty
                                    <h5 class="text-center mb-0">لاتوجد اسئلة</h5>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Exam --}}
            <div class="col-lg-8 col-sm-12">
                <div class="card">
                    {{-- Heading --}}
                    <div class="view shadow mdb-color p-3">
                        <h5 class="text-center text-white m-0">{{$exam->title}}</h5>
                    </div>

                    {{-- Questions --}}
                    <div class="card-body border-bottom border-primary">
                        @forelse($exam->questions as $question)
                            {{-- Question Title --}}
                            <h5>
                                {{$question->title}}
                                <span class="float-left">{{"( " . $question->score . " درجة" . " )"}}</span>
                            </h5>

                            {{-- Question Branches --}}
                            @forelse($question->branches as $branch)
                                @if ($loop->first)
                                    <ol>
                                        @endif
                                        <li>{{$branch->title}}</li>
                                        @if($question->type == \App\Enums\QuestionType::SINGLE_CHOICE)
                                            @foreach(json_decode($branch->options) as $option)
                                                @if($loop->first)
                                                    {{"( " . $option . " ، "}}
                                                    @continue
                                                @endif
                                                @if($loop->last)
                                                    {{$option . " )."}}
                                                    @break
                                                @endif
                                                {{$option . " ، "}}
                                            @endforeach
                                        @endif
                                        @if ($loop->last)
                                            @if(count($question->branches) != $question->no_of_branch)
                                                <div class="alert text-center py-4">
                                                    <h5 class="m-0">
                                                        <span>لم يتم رفع جميع النقاط التابعه لهذا السؤال.</span>
                                                        <a href="/control-panel/branches/create?question={{$question->id}}" class="btn btn-outline-primary font-weight-bold">
                                                            <i class="fa fa-plus ml-1"></i>
                                                            <span>اضافة نقطة</span>
                                                        </a>
                                                    </h5>
                                                </div>
                                            @endif
                                    </ol>
                                @endif
                            @empty
                                <div class="alert text-center py-4">
                                    <h5 class="m-0">
                                        <span>هذا السؤال لا يحتوي على بعد على اي نقطة</span>
                                        <a href="/control-panel/branches/create?question={{$question->id}}" class="btn btn-outline-primary font-weight-bold">
                                            <i class="fa fa-plus ml-1"></i>
                                            <span>اضافة نقطة</span>
                                        </a>
                                    </h5>
                                </div>
                            @endforelse

                            {{-- Line --}}
                            @if(!$loop->last)
                                <hr class="mb-4">
                            @endif
                        @empty
                            <div class="text-center py-5">
                                <i class="fa fa-lightbulb fa-4x mb-3 text-warning animated shake"></i>
                                <h4>هذا النموذج الامتحاني لايحتوي بعد على اي سؤال</h4>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection