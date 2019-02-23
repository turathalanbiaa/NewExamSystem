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


        <div class="row">
            {{-- Question --}}
            <div class="col-lg-5 col-sm-12">
                <div class="card">
                    {{-- Heading --}}
                    <div class="view shadow mdb-color p-3">
                        <h5 class="text-center text-white m-0">
                            <span>الاجوبة النموذجية للسؤال الحالي</span>
                        </h5>
                    </div>


                    <div class="card-body border-bottom border-primary">
                        {{-- Question Title --}}
                        <h5>
                            <span>{{$question->title}}</span>
                            <span class="float-left">{{"( " . $question->score . " درجة" . " )"}}</span>
                        </h5>

                        {{-- Question Branches --}}
                        @foreach($question->branches as $branch)
                            @if ($loop->first)
                                <ol>
                                    @endif
                                    {{-- Branch --}}
                                    <li class="mb-3">
                                        {{-- Title --}}
                                        <span>{{$branch->title}}</span><br>

                                        {{-- Correct Option --}}
                                        <span>
                                            <span class="text-success">الجواب: </span>
                                            <span>{{(!is_null($branch->correct_option)?$branch->correct_option:"لا يوجد جواب") . "."}}</span>
                                        </span>
                                    </li>
                                    @if ($loop->last)
                                </ol>
                            @endif
                        @endforeach

                        {{-- Question Divider --}}
                        <hr/>

                        {{-- Question Info --}}
                        <div class="h5 d-flex justify-content-between mb-0">
                            <div class="badge badge-pill badge-primary px-3 py-2 d-flex align-items-center">
                                <div class="ml-2 mt-1">عدد النقاط </div>
                                <div class="badge badge-pill badge-warning">{{$question->no_of_branch}}</div>
                            </div>

                            <div class="badge badge-pill badge-primary px-3 py-2 d-flex align-items-center">
                                <div class="ml-2 mt-1">عدد النقاط المطلوبة </div>
                                <div class="badge badge-pill badge-warning">{{$question->no_of_branch_req}}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Show Students Answers --}}
            <div class="col-lg-7 col-sm-12">
                <div class="card">
                    {{-- Heading --}}
                    <div class="view shadow mdb-color p-3">
                        <h5 class="text-center text-white m-0">
                           <span>اجوبة الطلاب</span>
                        </h5>
                    </div>

                    {{-- Questions --}}
                    <div class="card-body border-bottom border-primary">

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection