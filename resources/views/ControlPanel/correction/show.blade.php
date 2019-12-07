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

        <div class="row align-items-start">
            {{-- Show Students Answers to the current question --}}
            <div class="col-lg-6 col-sm-12">
                <div class="row">
                    @foreach($studentsCollections as $studentCollections)
                        @php
                            $student = $studentCollections["student"];
                            $answers = $studentCollections["answers"];
                        @endphp
                        <div class="col-12 mb-4">
                            <div class="card">
                                {{-- Heading --}}
                                <div class="view shadow mdb-color p-3" data-toggle="collapse" data-target="#collapse-{{$student->id}}" aria-expanded="false" aria-controls="collapseStudentAnswers">
                                    <a class="h5 text-center text-white d-block m-0">
                                        <div class="d-inline-block text-truncate w-75 pl-1">
                                            <span>اجوبة الطالب </span>
                                            <span>{{$student->originalStudent->Name}}</span>
                                        </div>

                                        {{-- If Correction Is Success--}}
                                        <div class="float-left success d-none w-25">
                                            <span class="far fa-check-square text-white ml-1"></span>
                                            <span>تم التصحيح</span>
                                        </div>

                                        {{-- If Correction Is Fail--}}
                                        <div class="float-left fail d-none w-25">
                                            <span class="far fa-minus-square text-white ml-1"></span>
                                            <span>اعد المحاولة</span>
                                        </div>
                                    </a>
                                </div>

                                {{-- Body --}}
                                <div class="card-body border-bottom border-primary collapse" id="collapse-{{$student->id}}">
                                    <div class="form">
                                        {{-- Question Answers --}}
                                        @foreach($answers as $answer)
                                            @if ($loop->first)
                                                <ul class="mb-0 pr-4">
                                                    @endif
                                                    {{-- Answer --}}
                                                    <li class="mb-3">
                                                        {{-- Branch --}}
                                                        <p class="mb-1">{{$answer->branch->title}}</p>

                                                        {{-- Answer --}}
                                                        <div class="d-flex justify-content-between">
                                                            {{-- Text --}}
                                                            <p class="mb-0">
                                                                <span class="text-success">الجواب: </span>
                                                                <span>{{$answer->text}}</span>
                                                            </p>

                                                            {{-- Score --}}
                                                            <p class="d-flex mb-0 justify-content-end" style="min-width: 150px">
                                                                <label for="answer-{{$answer->id}}" class="text-success my-auto ml-1">الدرجة: </label>
                                                                <input type="number" class="form-control d-inline w-25" id="answer-{{$answer->id}}" name="{{$answer->id}}" value="{{$answer->score}}">
                                                            </p>
                                                        </div>
                                                    </li>
                                                    @if ($loop->last)
                                                </ul>
                                            @endif
                                        @endforeach

                                        <hr>
                                        <input type="hidden" name="student" value="{{$student->id}}">
                                        <input type="hidden" name="question" value="{{$question->id}}">
                                        <button class="btn btn-block btn-outline-dark-green font-weight-bold" data-action="save-scores">حفظ الدرجات</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Button Load More --}}
                        @if($loop->last)
                            <div class="col-12 text-center">
                                <button class="btn btn-link font-weight-bold" value="Refresh Page" onClick="window.location.reload()">
                                    <i class="fa fa-cloud-download-alt ml-1"></i>
                                    <span>تحميل المزيد</span>
                                </button>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>

            {{-- Typical answers to the current question --}}
            <div class="col-lg-6 col-sm-12" style="position: sticky; top: 75px;">
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
                                <ul>
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
                                </ul>
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
        </div>
    </div>
@endsection

@section("script")
    <script>
        $("button[data-action='save-scores']").click(function () {
            let student = $(this).parent().find("input[type='hidden'][name='student']").val();
            let question = $(this).parent().find("input[type='hidden'][name='question']").val();
            let answers = $(this).parent().find("input[type='number']").map(function() {
                return {
                    "id":$(this).attr("name"),
                    "score": $(this).val()
                };
            }).get();
            let currentStudentCard = $(this).parent().parent().parent();
            let currentStudentCardView = currentStudentCard.find(".view");
            let currentStudentCardBody = currentStudentCard.find(".card-body");

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: "post",
                url: "/control-panel/correction/manually",
                data: {student:student, question:question, answers:answers},
                dataType: "json",
                success: function(result){
                    console.log(result);
                    if (result["correction"] == "success")
                    {
                        currentStudentCardBody.collapse("hide");

                        setTimeout(function () {
                            currentStudentCardView.removeClass("mdb-color").addClass("success-color");
                            setTimeout(function () {
                                currentStudentCardView.find(".float-left.success").removeClass("d-none").addClass("animated bounceIn");
                            }, 750);
                        }, 500);

                    }

                    if (result["correction"] == "fail")
                    {
                        currentStudentCardBody.collapse("hide");

                        setTimeout(function () {
                            currentStudentCardView.removeClass("mdb-color").addClass("danger-color");
                            setTimeout(function () {
                                currentStudentCardView.find(".float-left.fail").removeClass("d-none").addClass("animated bounceIn");
                            }, 750);
                        }, 500);
                    }
                },
                error: function () {
                    alert("Some Error Is Occur, Please Refresh Page.");
                }
            });
        });
    </script>
@endsection
