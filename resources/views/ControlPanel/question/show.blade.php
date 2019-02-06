@extends("ControlPanel.layout.app")

@section("title")
    <title>{{$currentQuestion->exam->title}}</title>
@endsection

@section("content")
    <div class="container">
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

        {{-- Burron Create--}}
        <div class="row">
            <div class="col-12 mb-3">
                <a href="/control-panel/branches/create?question={{$currentQuestion->id}}" class="btn btn-outline-default font-weight-bold">
                    <i class="fa fa-plus ml-1"></i>
                    <span>اضافة نقطة جديدة الى السؤال</span>
                </a>
            </div>
        </div>

        <div class="row">
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
                    <div class="view shadow mdb-color px-3 py-3">
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
                        <h5>
                            {{$currentQuestion->title}}
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
                                        <span class="mr-2">
                                            <a href="/control-panel/branches/{{$branch->id}}/edit" class="text-decoration text-default ml-1" rel="tooltip" title="تحرير النقطة">تحرير</a>
                                            <a href="#delete-branch" class="text-decoration text-default mr-1" rel="tooltip" title="حذف النقطة">حذف</a>
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
                                            <span class="text-danger">الجواب: </span>
                                            {{(!is_null($branch->correct_option)?$branch->correct_option:"لا يوجد جواب") . "."}}
                                        </span>
                                    </li>
                                    @if ($loop->last)
                                        @if(count($currentQuestion->branches) != $currentQuestion->no_of_branch)
                                            <div class="alert alert-info text-center mt-2">
                                                <h5 class="m-0">لم يتم رفع جميع النقاط التابعه لهذا السؤال.</h5>
                                            </div>
                                        @endif
                                </ol>
                            @endif
                        @empty
                            <div class="alert alert-info text-center mt-2">
                                <h5 class="m-0">هذا السؤال لا يحتوي على اي نقطة بعد.</h5>
                            </div>
                        @endforelse

                        {{-- Buttons --}}
                        <a class="btn btn-outline-success font-weight-bold" href="/control-panel/questions/create?exam={{$currentQuestion->exam->id}}">
                            <i class="fa fa-plus ml-1"></i>
                            <span>اضافة سؤال</span>
                        </a>
                        <a class="btn btn-outline-warning font-weight-bold" href="/control-panel/questions/{{$currentQuestion->id}}/edit">
                            <i class="fa fa-edit ml-1"></i>
                            <span>تعديل السؤال</span>
                        </a>
                        <a class="btn btn-outline-danger font-weight-bold" href="#delete-question">
                            <i class="fa fa-trash ml-1"></i>
                            <span>حذف السؤال</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section("script")
    <script>
        $(document).ready(function () {
            // Tooltips Initialization
            $('[rel="tooltip"]').tooltip()
        });
    </script>
@endsection