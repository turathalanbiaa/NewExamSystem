@extends("ControlPanel.layout.app")

@section("title")
    <title>{{$currentQuestion->exam->title}}</title>
@endsection

@section("content")
    <div class="container">
        {{-- Session Create  َQuestion Message --}}
        @if (session('CreateQuestionMessage'))
            <div class="alert alert-success text-center mx-4 mt-4">
                {{session('CreateQuestionMessage')}}
            </div>
        @endif

        {{-- Session Update Question Message --}}
        @if (session('UpdateQuestionMessage'))
            <div class="row">
                <div class="col-12">
                    <div class="alert alert-success text-center">
                        {{session('UpdateQuestionMessage')}}
                    </div>
                </div>
            </div>
        @endif

        {{-- Session Delete Question Message --}}
        @if (session('DeleteQuestionMessage'))
            <div class="row">
                <div class="col-12">
                    <div class="alert alert-danger text-center">
                        {{session('DeleteQuestionMessage')}}
                    </div>
                </div>
            </div>
        @endif

        {{-- Session Create Branch Message --}}
        @if (session('CreateBranchMessage'))
            <div class="row">
                <div class="col-12">
                    <div class="alert alert-success text-center">
                        {{session('CreateBranchMessage')}}
                    </div>
                </div>
            </div>
        @endif

        {{-- Session Update Branch Message --}}
        @if (session('UpdateBranchMessage'))
            <div class="row">
                <div class="col-12">
                    <div class="alert alert-success text-center">
                        {{session('UpdateBranchMessage')}}
                    </div>
                </div>
            </div>
        @endif

        {{-- Session Delete Branch Message --}}
        @if (session('DeleteBranchMessage'))
            <div class="row">
                <div class="col-12">
                    <div class="alert {{(session('TypeMessage')=="Error")?"alert-danger":"alert-success"}} text-center">
                        {{session('DeleteBranchMessage')}}
                    </div>
                </div>
            </div>
        @endif

        <div class="row">
            {{-- Heading --}}
            <div class="col-12">
                <div class="view shadow mdb-color p-3 mb-3">
                    <a class="h5 text-center text-white d-block m-0" href="/control-panel/exams/{{$currentQuestion->exam->id}}">{{$currentQuestion->exam->title}}</a>
                </div>
            </div>

            {{-- Questions --}}
            <div class="col-lg-4">
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
                                @forelse($currentQuestion->exam->questions as $question)
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
            </div>

            {{-- Current Question --}}
            <div class="col-lg-8 col-sm-12">
                <div class="card">
                    {{-- Card View --}}
                    <div class="view shadow mdb-color p-3">
                        <h5 class="text-center text-white m-0">
                            <span>السؤال الحالي</span>
                            <span class="badge badge-default float-left">
                                <span rel="tooltip" title="عدد النقاط المطلوبة">{{$currentQuestion->no_of_branch_req}}</span>
                                <span>/</span>
                                <span rel="tooltip" title="عدد النقاط">{{$currentQuestion->no_of_branch}}</span>
                            </span>
                        </h5>
                    </div>

                    {{-- Card Body --}}
                    <div class="card-body px-4 border-bottom border-primary">
                        {{-- Title --}}
                        <h5 class="mb-3">
                            <span>{{$currentQuestion->title}}</span>
                            <span class="font-small mr-2">
                                <a href="/control-panel/questions/{{$currentQuestion->id}}/edit" class="text-primary text-decoration ml-1" rel="tooltip" title="تحرير السؤال">
                                    <i class="fa fa-edit ml-1"></i>
                                    <span>تحرير</span>
                                </a>
                                <a href="#modelDeleteQuestion" data-toggle="modal" class="text-danger text-decoration mr-1" rel="tooltip" title="حذف السؤال">
                                    <i class="fa fa-trash-alt ml-1"></i>
                                    <span>حذف</span>
                                </a>
                            </span>
                            <span class="float-left">{{"( " . $currentQuestion->score . " درجة" . " )"}}</span>
                        </h5>

                        {{-- Branches --}}
                        @forelse($currentQuestion->branches as $branch)
                            @if ($loop->first)
                                <ol>
                                    @endif
                                    {{-- Branch --}}
                                    <li class="mb-3">
                                        {{-- Title --}}
                                        <span>{{$branch->title}}</span>
                                        <span class="font-small mr-2">
                                            <a href="/control-panel/branches/{{$branch->id}}/edit" class="text-primary text-decoration ml-1" rel="tooltip" title="تحرير النقطة">
                                                <i class="fa fa-edit ml-1"></i>
                                                <span>تحرير</span>
                                            </a>
                                            @if($currentQuestion->exam->state == \App\Enums\ExamState::CLOSE)
                                                <a href="#modelDeleteBranch" data-toggle="modal" class="text-danger text-decoration mr-1" rel="tooltip" title="حذف النقطة" data-action="fillDeleteBranchForm" data-branch-id="{{$branch->id}}" data-branch-title="{{$branch->title}}">
                                                    <i class="fa fa-trash-alt ml-1"></i>
                                                    <span>حذف</span>
                                                </a>
                                            @endif
                                        </span><br>

                                        {{-- Options --}}
                                        @if($currentQuestion->type == \App\Enums\QuestionType::SINGLE_CHOICE)
                                            <span>
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
                                            </span><br>
                                        @endif

                                        {{-- Correct Option --}}
                                        <span>
                                            <span class="text-success">الجواب: </span>
                                            {{(!is_null($branch->correct_option)?$branch->correct_option:"لا يوجد جواب") . "."}}
                                        </span>
                                    </li>
                                    @if ($loop->last)
                                        @if(count($currentQuestion->branches) < $currentQuestion->no_of_branch)
                                            <div class="alert text-center py-4">
                                                <h5 class="m-0">لم يتم رفع جميع النقاط التابعه لهذا السؤال</h5>
                                            </div>
                                        @endif

                                        @if(count($currentQuestion->branches) > $currentQuestion->no_of_branch)
                                            <div class="alert text-center text-danger py-4 animated infinite flash">
                                                <h3 class="m-0">يجب حذف بعض النقاط التابعه لهذا السؤال.</h3>
                                            </div>
                                        @endif
                                </ol>
                            @endif
                        @empty
                            <div class="alert text-center py-4">
                                <h5 class="m-0">هذا السؤال لا يحتوي على اي نقطة بعد</h5>
                            </div>
                        @endforelse

                        {{-- Buttons --}}
                        <hr>
                        <a class="btn btn-dark font-weight-bold" href="/control-panel/branches/create?question={{$currentQuestion->id}}">
                            <i class="fa fa-plus ml-1"></i>
                            <span>اضافة نقطة جديدة الى السؤال الحالي</span>
                        </a>

                        <a class="btn btn-outline-dark font-weight-bold" href="/control-panel/questions/create?exam={{$currentQuestion->exam->id}}">
                            <i class="fa fa-plus ml-1"></i>
                            <span>اضافة سؤال الى الامتحان الحالي</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section("extra-content")
    {{-- Delete Question Modal --}}
    <div class="modal fade" id="modelDeleteQuestion" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-notify modal-danger" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <p class="heading lead">حذف السؤال الحالي</p>

                    <a href="javascript:void(0)" class="close ml-0" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" class="white-text">&times;</span>
                    </a>
                </div>

                <div class="modal-body">
                    <div class="text-center">
                        <i class="fa fa-trash-alt fa-4x mb-3 animated fadeIn"></i>
                        <h2 class="text-danger">هل تريد حذف السؤال</h2>
                        @if($currentQuestion->exam->state == \App\Enums\ExamState::CLOSE)
                            <p>بعد حذف السؤال سوف يتم مسح جميع النقاط التابعه لهذا السؤال.</p>
                        @elseif($currentQuestion->exam->state == \App\Enums\ExamState::OPEN)
                            <p>لا يمكنك حذف السؤال الحالي لان الامتحان التابع له هذا السؤال مفتوح.</p>
                        @else
                            <p>بعد حذف السؤال سوف يتم مسح جميع النقاط التابعه لهذا السؤال وجميع اجابات الطلبة على هذا السؤال.</p>
                        @endif
                    </div>
                </div>

                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-danger" onclick="$('form#deleteQuestion').submit();">حذف السؤال</button>
                    <button type="button" class="btn btn-outline-danger" data-dismiss="modal">لا شكرا</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Delete Question Form--}}
    <form id="deleteQuestion" method="post" action="/control-panel/questions/{{$currentQuestion->id}}">
        @csrf
        @method("DELETE")
    </form>

    {{-- Delete Branch Modal --}}
    <div class="modal fade" id="modelDeleteBranch" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-notify modal-danger" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <p class="heading lead">حذف النقطة</p>

                    <a href="javascript:void(0)" class="close ml-0" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" class="white-text">&times;</span>
                    </a>
                </div>

                <div class="modal-body">
                    <div class="text-center">
                        <i class="fa fa-trash-alt fa-4x mb-3 animated fadeIn"></i>
                        <h2 class="text-danger">هل تريد حذف النقطة </h2>
                        <p id="branch-title"></p>
                        @if($currentQuestion->exam->state == \App\Enums\ExamState::CLOSE)
                            <p>سيتم حذف النقطة فقط.</p>
                        @elseif($currentQuestion->exam->state == \App\Enums\ExamState::OPEN)
                            <p>لا يمكنك حذف النقطة الحالي لان الامتحان مفتوح.</p>
                        @else
                            <p>بعد حذف النقطة سوف يتم مسح جميع اجابات الطلبة على النقطة الحالية.</p>
                        @endif
                    </div>
                </div>

                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-danger" onclick="$('form#deleteBranch').submit();">حذف النقطة</button>
                    <button type="button" class="btn btn-outline-danger" data-dismiss="modal">لا شكرا</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Delete Branch Form--}}
    <form id="deleteBranch" method="post" action="/control-panel/branches/">
        @csrf
        @method("DELETE")
    </form>
@endsection

@section("script")
    <script>
        $(document).ready(function () {
            // Tooltips Initialization
            $('[rel="tooltip"]').tooltip();

            //Fill Delete Exam Form And Model
            $("[data-action='fillDeleteBranchForm']").click(function () {
                let branchId = $(this).data("branch-id");
                let branchTitle = $(this).data("branch-title");

                $("form#deleteBranch").attr("action","/control-panel/branches/" + branchId);
                $("#branch-title").html(branchTitle);
            });
        });
    </script>
@endsection