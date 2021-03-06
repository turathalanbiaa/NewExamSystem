@extends("ControlPanel.layout.app")

@section("title")
    <title>الامتحانات</title>
@endsection

@section("content")
    <div class="container">
        {{-- Session Update Exam Message --}}
        @if (session('UpdateExamMessage'))
            <div class="row">
                <div class="col-12">
                    <div class="alert alert-success text-center">
                        {{session('UpdateExamMessage')}}
                    </div>
                </div>
            </div>
        @endif

        {{-- Session Update Exam State Message --}}
        @if (session('UpdateExamStateMessage'))
            <div class="row">
                <div class="col-12">
                    <div class="alert {{(session('TypeMessage')=="Error")?"alert-danger":"alert-success"}} text-center">
                        {{session('UpdateExamStateMessage')}}
                    </div>
                </div>
            </div>
        @endif

        {{-- Session Delete Exam Message --}}
        @if (session('DeleteExamMessage'))
            <div class="row">
                <div class="col-12">
                    <div class="alert {{(session('TypeMessage')=="Error")?"alert-danger":"alert-success"}} text-center">
                        {{session('DeleteExamMessage')}}
                    </div>
                </div>
            </div>
        @endif

        {{-- Session Sum Message --}}
        @if (session('SumMessage'))
            <div class="row">
                <div class="col-12">
                    <div class="alert {{(session('TypeMessage')=="Error")?"alert-danger":"alert-success"}} text-center">
                        {{session('SumMessage')}}
                    </div>
                </div>
            </div>
        @endif

        {{-- Burron Create--}}
        <div class="row">
            <div class="col-12 mb-3">
                <a href="/control-panel/exams/create" class="btn btn-outline-default font-weight-bold">
                    <i class="fa fa-plus ml-1"></i>
                    <span>انشاء نموذج امتحاني</span>
                </a>
            </div>
        </div>

        {{-- Courses --}}
        @foreach($courses as $course)
            <div class="row">
                {{-- Course --}}
                <div class="col-12 mb-4">
                    <a class="h5 bg-light p-3 m-0 d-block" data-toggle="collapse" data-target="#course-{{$course->id}}-exams" aria-expanded="false" aria-controls="collapseExams">
                        <i class="fa fa-bars text-default ml-1"></i>
                        {{$course->name}}
                        <span class="text-default">&gt;&gt;&gt;</span>
                        {{\App\Enums\Level::get($course->level)}}
                    </a>
                </div>

                {{-- Exams --}}
                <div class="col-12 collapse" id="course-{{$course->id}}-exams">
                    <div class="row">
                        @if($course->state == \App\Enums\CourseState::OPEN)
                            @forelse($course->exams as $exam)
                                <div class="col-lg-4 col-md-6 col-sm-12 mb-3">
                                    {{-- Card Exam --}}
                                    <div class="card shadow h-100">
                                        {{-- Card View --}}
                                        <div class="view shadow mdb-color px-3 py-4">
                                            <a class="h5 text-center text-white d-block mb-0" href="/control-panel/exams/{{$exam->id}}">
                                                {{$exam->title}}
                                            </a>
                                            <p class="text-center text-white mb-0">{{$exam->date}}</p>
                                        </div>

                                        {{-- Card Content --}}
                                        <div class="card-body">
                                            <div class="list-group list-group-flush">
                                                <a href="/control-panel/exams/{{$exam->id}}" class="list-group-item list-group-item-action">عرض وتصحيح النموذج الامتحاني</a>
                                                <a href="/control-panel/exams/{{$exam->id}}/edit" class="list-group-item list-group-item-action">تعديل النموذج الامتحاني</a>
                                                @if($exam->state == \App\Enums\ExamState::CLOSE)
                                                    <div class="list-group-item d-flex justify-content-between">
                                                        <div class="d-flex align-items-center">الامتحان مغلق حاليا</div>
                                                        <button type="button" class="btn btn-success btn-sm m-0 mr-2" data-action="fillExamStateForm" data-exam-id="{{$exam->id}}" data-exam-title="{{$exam->title}}" data-exam-state="open" data-toggle="modal" data-target="#modelOpenExamState">فتح الامتحان</button>
                                                    </div>
                                                @elseif($exam->state == \App\Enums\ExamState::OPEN)
                                                    <div class="list-group-item d-flex justify-content-between">
                                                        <div class="d-flex align-items-center">الامتحان مفتوح حاليا</div>
                                                        <button type="button" class="btn btn-danger btn-sm m-0 mr-2" data-action="fillExamStateForm" data-exam-id="{{$exam->id}}" data-exam-title="{{$exam->title}}" data-exam-state="end" data-toggle="modal" data-target="#modelEndExamState">انهاء الامتحان</button>
                                                    </div>
                                                @else
                                                    <div class="list-group-item d-flex justify-content-between">
                                                        <div class="d-flex align-items-center">الامتحان منتهي حاليا</div>
                                                        <button type="button" class="btn btn-warning btn-sm m-0 mr-2" data-action="fillExamStateForm" data-exam-id="{{$exam->id}}" data-exam-title="{{$exam->title}}" data-exam-state="reopen" data-toggle="modal" data-target="#modelReopenExamState">اعادة فتح الامتحان</button>
                                                    </div>
                                                @endif
                                                <a href="/control-panel/sum/{{$exam->id}}" class="list-group-item list-group-item-action">جمع درجة النموذج الامتحاني</a>
                                                <a href="javascript:void(0)" class="list-group-item list-group-item-action" data-action="fillDeleteExamForm" data-exam-id="{{$exam->id}}" data-exam-title="{{$exam->title}}" data-toggle="modal" data-target="#modelDeleteExam">حذف النموذج الامتحاني</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="col-12">
                                    <div class="alert alert-info text-center">
                                        <h5 class="mb-0">هذه المادة لاتمتلك اي امتحان</h5>
                                    </div>
                                </div>
                            @endforelse
                        @else
                            <div class="col-12">
                                <div class="alert alert-info text-center">
                                    <h5 class="mb-0">هذه المادة مغلقة حاليا</h5>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection

@section("extra-content")
    {{-- Open Exam Modal --}}
    <div class="modal fade" id="modelOpenExamState" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-notify modal-success" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <p class="heading lead">اسم الامتحان</p>

                    <a href="javascript:void(0)" class="close ml-0" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" class="white-text">&times;</span>
                    </a>
                </div>

                <div class="modal-body">
                    <div class="text-center">
                        <i class="fa fa-unlock fa-4x mb-3 animated fadeIn"></i>
                        <h2 class="text-success">هل تريد فتح الامتحان</h2>
                        <p>بعد فتح الامتحان سوف يتمكن الطالب من الدخول الى القاعة الامتحانية والاجابة على الاسئلة.</p>
                    </div>
                </div>

                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-success" onclick="$('form#examState').submit();">فتح الامتحان</button>
                    <button type="button" class="btn btn-outline-success" data-dismiss="modal">لا شكرا</button>
                </div>
            </div>
        </div>
    </div>

    {{-- End Exam Modal --}}
    <div class="modal fade" id="modelEndExamState" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-notify modal-danger" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <p class="heading lead">اسم الامتحان</p>

                    <a href="javascript:void(0)" class="close ml-0" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" class="white-text">&times;</span>
                    </a>
                </div>

                <div class="modal-body">
                    <div class="text-center">
                        <i class="fa fa-lock fa-4x mb-3 animated fadeIn"></i>
                        <h2 class="text-danger">هل تريد انهاء الامتحان</h2>
                        <p>بعد انهاء الامتحان، لا يستطيع الطالب الدخول الى القاعة الامتحانية والاجابة على الاسئلة.</p>
                    </div>
                </div>

                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-danger" onclick="$('form#examState').submit();">انهاء الامتحان</button>
                    <button type="button" class="btn btn-outline-danger" data-dismiss="modal">لا شكرا</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Reopen Exam Modal --}}
    <div class="modal fade" id="modelReopenExamState" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-notify modal-warning" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <p class="heading lead">اسم الامتحان</p>

                    <a href="javascript:void(0)" class="close ml-0" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" class="white-text">&times;</span>
                    </a>
                </div>

                <div class="modal-body">
                    <div class="text-center">
                        <i class="fa fa-retweet fa-4x mb-3 animated fadeIn"></i>
                        <h2 class="text-warning">هل تريد اعادة فتح الامتحان</h2>
                        <p>بعد اعادة فتح الامتحان يستطيع الطالب الدخول الى القاعة الامتحانية والاجابة على الاسئلة.</p>
                    </div>
                </div>

                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-warning" onclick="$('form#examState').submit();">اعادة فتح الامتحان</button>
                    <button type="button" class="btn btn-outline-warning" data-dismiss="modal">لا شكرا</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Exam State Form --}}
    <form id="examState" method="post" action="">
        @csrf
        @method("PUT")
        <input type="hidden" name="state" value="">
    </form>

    {{-- Delete Exam Modal --}}
    <div class="modal fade" id="modelDeleteExam" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-notify modal-danger" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <p class="heading lead">اسم الامتحان</p>

                    <a href="javascript:void(0)" class="close ml-0" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" class="white-text">&times;</span>
                    </a>
                </div>

                <div class="modal-body">
                    <div class="text-center">
                        <i class="fa fa-trash-alt fa-4x mb-3 animated fadeIn"></i>
                        <h2 class="text-danger">هل تريد حذف الامتحان</h2>
                        <p>بعد حذف الامتحان سوف يتم مسح جميع متعلقات هذا الامتحان</p>
                    </div>
                </div>

                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-danger" onclick="$('form#deleteExam').submit();">حذف الامتحان</button>
                    <button type="button" class="btn btn-outline-danger" data-dismiss="modal">لا شكرا</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Delete Exam Form--}}
    <form id="deleteExam" method="post" action="">
        @csrf
        @method("DELETE")
    </form>
@endsection

@section("script")
    <script>
        $(document).ready(function () {
            //Fill Exam State Form And Model
            $("[data-action='fillExamStateForm']").click(function () {
                let examId = $(this).data("exam-id");
                let examState = $(this).data("exam-state");
                let examTitle = $(this).data("exam-title");

                if (examState === "open")
                    $("#modelOpenExamState .heading.lead").html(examTitle);
                else if (examState === "close")
                    $("#modelEndExamState .heading.lead").html(examTitle);
                else if (examState ==="reopen")
                    $("#modelReopenExamState .heading.lead").html(examTitle);

                $("form#examState").attr("action","/control-panel/exams/" + examId);
                $("form#examState>input[name='state']").attr("value", examState);
            });

            //Fill Delete Exam Form And Model
            $("[data-action='fillDeleteExamForm']").click(function () {
                let examId = $(this).data("exam-id");
                let examTitle = $(this).data("exam-title");

                $("form#deleteExam").attr("action","/control-panel/exams/" + examId);
                $("#modelDeleteExam .heading.lead").html(examTitle);
            });
        });
    </script>
@endsection
