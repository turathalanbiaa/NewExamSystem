@extends("ControlPanel.layout.app")

@section("title")
    <title>{{$question->exam->title}}</title>
@endsection

@section("content")
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-sm-12">
                <div class="card">
                    {{-- Card View --}}
                    <div class="view shadow mdb-color px-3 py-3">
                        <h5 class="text-right text-white m-0">
                            <span>اضافة نقطه الى السؤال: </span>
                            <span>{{$question->title}}</span>
                            <span class="badge badge-default float-left" rel="tooltip" title="عدد النقاط المرفوعه">
                                <span>{{count($question->branches)}}</span>
                                <span>/</span>
                                <span>{{$question->no_of_branch}}</span>
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

                    {{-- Session Create Branch Message --}}
                    @if (session('CreateBranchMessage'))
                        <div class="alert {{(session('TypeMessage') == "Error" ? "alert-danger":"alert-success")}} text-center mx-4 mt-4">
                            {{session('CreateBranchMessage')}}
                        </div>
                    @endif

                    <div class="card-body px-4 border-bottom border-primary">
                        <form method="post" action="/control-panel/branches">
                            @csrf
                            <input type="hidden" name="question" value="{{$question->id}}">

                            <div class="mb-4">
                                <label for="title">عنوان</label>
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
                                            <label for="option-4">الاختيار الرابع</label>
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

                            <button class="btn btn-outline-default btn-block mb-4 font-weight-bold" type="submit">
                                <span>حفظ المعلومات</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section("script")
    <script>
        // Tooltips Initialization
        $('[rel="tooltip"]').tooltip();

        //Change Options
        $("input[data-action='change']").change(function () {
            let correctOptionSelect = $("select#correctOption");
            let valueOption = $(this).attr("id");
            let valueDisplay = $(this).val();
            let newOption = '<option class="' + valueOption + '" value="' + valueOption + '">' + valueDisplay + '</option>';
            if ($("select#correctOption > option."+valueOption+"").val() == valueOption)
                $("select#correctOption > option."+valueOption+"").html(valueDisplay);
            else
                correctOptionSelect.append(newOption);
        });

        //Fill Correct Option Select

    </script>
@endsection