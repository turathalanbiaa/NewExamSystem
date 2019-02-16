@extends("ControlPanel.layout.app")

@section("title")
    <title>{{$question->exam->title}}</title>
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

        <div class="row">
            {{-- Heading --}}
            <div class="col-12">
                <div class="view shadow mdb-color px-3 py-4 mb-3">
                    <a class="h5 text-center text-white d-block m-0" href="/control-panel/exams/{{$question->exam->id}}">{{$question->exam->title}}</a>
                </div>
            </div>

            {{-- Info --}}
            <div class="col-lg-4">
                {{-- Branch Alert Info --}}
                <div class="alert alert-info">
                    <h5 class="text-center pb-2 border-bottom border-primary">بعض المعلومات عن اضافة نقطة</h5>
                    <ul class="mb-0 pr-3">
                        <li>
                            <span>اذا كان نوع السؤال </span>
                            <span class="font-weight-bold">صح او خطأ </span>
                            <span>فهنا يمكنك اضافة عنوان (النص) النقطة واختيار الاجابة الصحيحة.</span>
                        </li>

                        <li>
                            <span>اذا كان نوع السؤال </span>
                            <span class="font-weight-bold">اخيارات </span>
                            <span>فهنا يمكنك اضافة عنوان (النص) النقطة والاختيارات واختيار الاجابة الصحيحة، </span>
                            <span class="font-weight-bold">علما ان اول ثلاث اختيارات مطلوبة.</span>
                        </li>

                        <li>
                            <span>اذا كان نوع السؤال </span>
                            <span class="font-weight-bold">فراغات او تعاريف او شرح الخ... </span>
                            <span>فهنا يمكنك اضافة عنوان (النص) النقطة فقط، </span>
                            <span class="font-weight-bold">علما انه يمكنك اضافة الاجابة الصحيحة لكي يتسنى لك الاستفادة منها عند التصحيح اليدوي.</span>
                        </li>
                    </ul>
                </div>
            </div>

            {{-- Create Branch --}}
            <div class="col-lg-8 col-sm-12">
                <div class="card">
                    {{-- Card View --}}
                    <div class="view shadow mdb-color px-3 py-4">
                        <h5 class="text-right text-white m-0">
                            <span>اضافة نقطه الى السؤال: </span>
                            <span>{{$question->title}}</span>
                            <span class="badge badge-default float-left">
                                <span rel="tooltip" title="عدد النقاط المرفوعه">{{$question->branches()->count()}}</span>
                                <span>/</span>
                                <span rel="tooltip" title="عدد النقاط المطلوبة">{{$question->no_of_branch}}</span>
                            </span>
                        </h5>
                    </div>

                    {{-- Alert Errors --}}
                    @if ($errors->any())
                        <div class="alert alert-danger mx-4 mt-4">
                            <ul class="mb-0 pr-3">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- Card Body --}}
                    <div class="card-body px-4 border-bottom border-primary">
                        @if($question->branches()->count() == $question->no_of_branch)
                            <div class="text-center py-5">
                                <i class="fa fa-lightbulb fa-4x mb-3 text-warning animated shake"></i>
                                <h4>لا يمكنك اضافة نقطة الى السؤال الحالي لان عدد النقاط المرفوعة تساوي عدد النقاط المطلوبة</h4>
                            </div>
                        @else
                            <form method="post" action="/control-panel/branches">
                                @csrf
                                <input type="hidden" name="question" value="{{$question->id}}">

                                <div class="mb-4">
                                    <label for="title">عنوان (النص)</label>
                                    <input type="text" name="title" id="title" class="form-control" value="{{old("title")}}">
                                </div>

                                @if($question->type == \App\Enums\QuestionType::TRUE_OR_FALSE)
                                    <div class="mb-5">
                                        <label for="correctOption">اختر الاجابة الصحيحة</label>
                                        <select class="browser-default custom-select" name="correctOption" id="correctOption">
                                            <option value="" disabled="" selected="">يرجى اختيار الاجابة الصحيحة</option>
                                            <option value="صح" {{(old("correctOption") == "صح" ? "selected":"")}}> صح </option>
                                            <option value="خطأ" {{(old("correctOption") == "خطأ" ? "selected":"")}}> خطأ </option>
                                        </select>
                                    </div>
                                @endif

                                @if($question->type == \App\Enums\QuestionType::SINGLE_CHOICE)
                                    <div class="mb-4">
                                        <p class="font-weight-bold">الاختيارات</p>

                                        <div class="mr-3">
                                            <div class="mb-3">
                                                <label for="option-1">الاختيار الاول</label>
                                                <input type="text" name="option-1" id="option-1" class="form-control" value="{{old("option-1")}}" data-action="change">
                                            </div>

                                            <div class="mb-3">
                                                <label for="option-2">الاختيار الثاني</label>
                                                <input type="text" name="option-2" id="option-2" class="form-control" value="{{old("option-2")}}" data-action="change">
                                            </div>

                                            <div class="mb-3">
                                                <label for="option-3">الاختيار الثالث</label>
                                                <input type="text" name="option-3" id="option-3" class="form-control" value="{{old("option-3")}}" data-action="change">
                                            </div>

                                            <div class="mb-3">
                                                <label for="option-4">الاختيار الرابع (اختياري)</label>
                                                <input type="text" name="option-4" id="option-4" class="form-control" value="{{old("option-4")}}" data-action="change">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-5">
                                        <label for="correctOption">اختر الاجابة الصحيحة</label>
                                        <select class="browser-default custom-select" name="correctOption" id="correctOption">
                                            <option value="" disabled="" selected="">يرجى اختيار الاجابة الصحيحة</option>
                                        </select>
                                    </div>
                                @endif

                                @if($question->type == \App\Enums\QuestionType::FILL_BLANK)
                                    <div class="mb-5">
                                        <label for="correctOption">الاجابة الصحيحة (اختياري)</label>
                                        <input type="text" name="correctOption" id="correctOption" class="form-control" value="{{old("correctOption")}}">
                                    </div>
                                @endif

                                @if($question->type == \App\Enums\QuestionType::EXPLAIN)
                                    <div class="mb-5">
                                        <label for="correctOption">الاجابة الصحيحة (اختياري)</label>
                                        <textarea rows="5" name="correctOption" id="correctOption" class="form-control">{{old("correctOption")}}</textarea>
                                    </div>
                                @endif

                                <button class="btn btn-outline-default btn-block mb-4 font-weight-bold" type="submit">
                                    <span>حفظ المعلومات</span>
                                </button>
                            </form>
                        @endif
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
            $('[rel="tooltip"]').tooltip();

            @if($question->type == \App\Enums\QuestionType::SINGLE_CHOICE)
                //Change Options
                $("input[data-action='change']").change(function () {
                    insertOption($(this));
                });

                //Fill Select
                for (let i=1;i<=4;i++)
                {
                    let currentInput = $("input#option-"+i);
                    if (currentInput.val() != "")
                        insertOption(currentInput);

                    //Selected Option
                    let selectOption = $("select#correctOption > option." + currentInput.attr("id"));
                    if (selectOption.val() == '{{old("correctOption")}}')
                        selectOption.attr("selected", "selected")
                }

                //Insert Or Update Option
                function insertOption(currentInput)
                {
                    let select = $("select#correctOption");
                    let selectOption = $("select#correctOption > option." + currentInput.attr("id"));

                    //Update Option
                    if (selectOption.attr("class") === currentInput.attr("id"))
                    {
                        //Delete Option
                        if (currentInput.val() == "")
                            selectOption.remove();

                        //Update Option
                        selectOption.html(currentInput.val());
                    }
                    //Create New Option
                    else
                    {
                        let newOption = '<option class="' + currentInput.attr("id") + '" value="' + currentInput.val() + '">' + currentInput.val() + '</option>';
                        select.append(newOption);
                    }
                }
            @endif
        });
    </script>
@endsection