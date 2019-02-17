@extends("ControlPanel.layout.app")

@section("title")
    <title>{{$exam->title}}</title>
@endsection

@section("content")
    <div class="container">
        {{-- Session Update Exam Message --}}
        @if (session('UpdateExamMessage'))
            <div class="row">
                <div class="col-12">
                    <div class="alert alert-danger text-center">
                        {{session('UpdateExamMessage')}}
                    </div>
                </div>
            </div>
        @endif


        <div class="row">
            {{-- Heading --}}
            <div class="col-12 mb-3">
                <div class="view shadow mdb-color px-3 py-4">
                    <h5 class="text-center text-white m-0">تعديل النموذج الامتحاني</h5>
                </div>
            </div>

            {{-- Info --}}
            <div class="col-lg-4">
                {{-- Exam Alert Info --}}
                <div class="alert alert-info">
                    <h5 class="text-center pb-2 border-bottom border-primary">طريقة تعديل النموذج الامتحاني</h5>
                    <ul class="mb-0 pr-3">
                        <li>اذا كان نوع الامتحان شهر اول اوثاني فيمكنك تعديل عنوان ودرجة وتاريخ الامتحان فقط.</li>
                        <li>واذا كان نوع الامتحان نهائي فيمكنك تعديل عنوان وتاريخ الامتحان فقط.</li>
                        <li>لا يمكنك تعديل المادة التابع لها هذا الامتحان ولا نوع الامتحان.</li>
                    </ul>
                </div>

                {{-- Exam Score Alert Info --}}
                <div class="alert alert-info">
                    <h5 class="text-center pb-2 border-bottom border-primary">طريقة توزيع الدرجة بعد تعديل النموذج الامتحاني</h5>
                    <ul class="mb-0 pr-3">
                        <li>لا يمكنك وضع (25 درجة) لامتحان الشهر الاول، اذا كانت المادة تملك امتحان شهر ثاني.</li>
                        <li>لا يمكنك وضع (25 درجة) لامتحان الشهر الثاني.</li>
                        <li>بعد تعديل درجة امتحان الشهر الاول سيتم تعديل درجة امتحان الشهر الثاني تلقائيا والعكس صحيح.</li>
                    </ul>
                </div>
            </div>

            {{-- Edit Exam --}}
            <div class="col-lg-8 col-sm-12">
                <div class="card">
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
                        <form method="post" action="/control-panel/exams/{{$exam->id}}">
                            @csrf
                            @method("PUT")
                            <input type="hidden" name="general" value="general">

                            <div class="mb-4">
                                <label for="title">عنوان الامتحان</label>
                                <input type="text" name="title" id="title" class="form-control" value="{{$exam->title}}">
                            </div>

                            <div class="mb-4">
                                <label for="score">درجة الامتحان</label>
                                <input type="number" name="score" id="score" class="form-control" value="{{$exam->real_score}}">
                            </div>

                            <div class="mb-5">
                                <label for="date">تاريخ الامتحان</label>
                                <input type="date" name="date" id="date" class="form-control" value="{{$exam->date}}">
                            </div>

                            <button class="btn btn-outline-default btn-block mb-4 font-weight-bold" type="submit">
                                <span>حفظ التغييرات على النموذج الامتحاني</span>
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
        $(document).ready(function(){
            $("select#type").change(function(){
                if (($(this).val() == '{{\App\Enums\ExamType::FINAL_FIRST_ROLE}}') || ($(this).val() == '{{\App\Enums\ExamType::FINAL_SECOND_ROLE}}'))
                    $("input#score").val("60").attr("readonly","readonly");
                else if ($(this).val() == '{{\App\Enums\ExamType::SECOND_MONTH}}')
                    $("input#score").val("0").attr("readonly","readonly");
                else
                    $("input#score").val("0").removeAttr("readonly");
            });
        });
    </script>
@endsection