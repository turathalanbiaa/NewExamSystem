@extends("ControlPanel.layout.app")

@section("title")
    <title>{{$exam->title}}</title>
@endsection

@section("content")
    <div class="container">
        {{-- Session Message --}}
        @if (session('Message'))
            <div class="row">
                <div class="col-12">
                    <div class="alert {{(session('TypeMessage')=="Error")?"alert-danger":"alert-success"}} text-center">
                        {{session('Message')}}
                    </div>
                </div>
            </div>
        @endif

        {{-- Burron Create--}}
        <div class="row">
            <div class="col-12 mb-3">
                <a href="/control-panel/questions/create" class="btn btn-outline-default font-weight-bold">
                    <i class="fa fa-plus ml-1"></i>
                    <span>اضافة سؤال</span>
                </a>
            </div>
        </div>

        {{-- Questions --}}
        @foreach($exam->questions as $question)
            <div class="row">

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
                        <h2 class="text-danger">هل تريد غلق الامتحان</h2>
                        <p>بعد غلق الامتحان، لا يستطيع الطالب الدخول الى القاعة الامتحانية والاجابة على الاسئلة.</p>
                    </div>
                </div>

                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-danger" onclick="$('form#examState').submit();">غلق الامتحان</button>
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
                        <p>ستقوم باعادة فتح الامتحان بعد ان كان الامتحان مغلق لكي يستطيع الطالب الدخول الى القاعة الامتحانية والاجابة على الاسئلة.</p>
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
    <script></script>
@endsection