@extends("ControlPanel.layout.app")

@section("title")
    <title>انشاء نموذج امتحاني</title>
@endsection

@section("content")
    <div class="container">
        <div class="row">
            {{-- Heading --}}
            <div class="col-12 mb-3">
                <div class="view shadow mdb-color p-3">
                    <h5 class="text-center text-white m-0">انشاء نموذج امتحاني</h5>
                </div>
            </div>

            {{-- Info --}}
            <div class="col-lg-4">
                {{-- Exam Alert Info --}}
                <div class="alert alert-info">
                    <h5 class="text-center pb-2 border-bottom border-primary">طريقة انشاء النموذج الامتحاني</h5>
                    <ul class="mb-0 pr-3">
                        <li>لا يمكنك انشاء النموذج الامتحاني لشهر الثاني الا بعد انشاء النموذج الامتحاني لشهر الاول.</li>
                        <li>لا يمكنك انشاء النموذج الامتحاني لشهر الثاني اذا كانت درجة امتحان الشهر الاول 25 درجة.</li>
                        <li>لا يمكنك انشاء النموذج الامتحاني لنهائي الدور الثاني الا بعد انشاء النموذج الامتحاني لنهائي الدور الاول.</li>
                    </ul>
                </div>

                {{-- Exam Score Alert Info --}}
                <div class="alert alert-info">
                    <h5 class="text-center pb-2 border-bottom border-primary">طريفة توزيع الدرجة حسب نوع الامتحان</h5>
                    <ul class="mb-0 pr-3">
                        <li>مجموع درجة امتحان الشهر الاول وامتحان الشهر الثاني يساوي (25) درجة.</li>
                        <li>يمكنك وضع درجة امتحان الشهر الاول.</li>
                        <li>
                            <span>توضع درجة امتحان الشهر الثاني تلقائيا وذلك حسب المعادلة التالية،</span><br>
                            <span class="font-weight-bold">درجة امتحان الشهر الثاني = 25 - درجة امتحان الشهر الاول</span>
                        </li>
                        <li>درجة الامتحان النهائي هي (60) درجة، سواء كان الامتحان النهائي دور اول او دور ثاني.</li>
                    </ul>
                </div>
            </div>

            {{-- Create Exam --}}
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

                    {{-- Session Create Exam Message --}}
                    @if (session('CreateExamMessage'))
                        <div class="alert {{(session('TypeMessage')=="Error")?"alert-danger":"alert-success"}} text-center mx-4 mt-4">
                            {{session('CreateExamMessage')}}
                        </div>
                    @endif

                    {{-- Card Body --}}
                    <div class="card-body px-4 border-bottom border-primary">
                        <form method="post" action="/control-panel/exams">
                            @csrf

                            <div class="mb-4">
                                <label for="course">اختر المادة</label>
                                <select class="browser-default custom-select" name="course" id="course">
                                    <option value="" selected="">يرجى اختيار امادة</option>
                                    @forelse($courses as $course)
                                        <option value="{{$course->id}}" {{(old("course") == $course->id ? "selected":"")}}>
                                            {{$course->name}}
                                        </option>
                                    @empty
                                        <option value="" disabled="" selected="">لا توجد مواد</option>
                                    @endforelse
                                </select>
                            </div>

                            <div class="mb-4">
                                <label for="type">اختر نوع الامتحان</label>
                                <select class="browser-default custom-select" name="type" id="type">
                                    <option value="" selected="" disabled="">يرجى اختيار نوع الامتحان</option>
                                    <option value="{{\App\Enums\ExamType::FIRST_MONTH}}" {{(old("type") == \App\Enums\ExamType::FIRST_MONTH ? "selected":"")}}>
                                        {{\App\Enums\ExamType::getType(\App\Enums\ExamType::FIRST_MONTH)}}
                                    </option>
                                    <option value="{{\App\Enums\ExamType::SECOND_MONTH}}" {{(old("type") == \App\Enums\ExamType::SECOND_MONTH ? "selected":"")}}>
                                        {{\App\Enums\ExamType::getType(\App\Enums\ExamType::SECOND_MONTH)}}
                                    </option>
                                    <option value="{{\App\Enums\ExamType::FINAL_FIRST_ROLE}}" {{(old("type") == \App\Enums\ExamType::FINAL_FIRST_ROLE ? "selected":"")}}>
                                        {{\App\Enums\ExamType::getType(\App\Enums\ExamType::FINAL_FIRST_ROLE)}}
                                    </option>
                                    <option value="{{\App\Enums\ExamType::FINAL_SECOND_ROLE}}" {{(old("type") == \App\Enums\ExamType::FINAL_SECOND_ROLE ? "selected":"")}}>
                                        {{\App\Enums\ExamType::getType(\App\Enums\ExamType::FINAL_SECOND_ROLE)}}
                                    </option>
                                </select>
                            </div>

                            <div class="mb-4">
                                <label for="title">عنوان الامتحان</label>
                                <input type="text" name="title" id="title" class="form-control" value="{{old("title")}}">
                            </div>

                            <div class="mb-4">
                                <label for="score">درجة الامتحان</label>
                                <input type="number" name="score" id="score" class="form-control" value="{{old("score", 0)}}">
                            </div>

                            <div class="mb-5">
                                <label for="date">تاريخ الامتحان</label>
                                <input type="date" name="date" id="date" class="form-control" value="{{old("date")}}">
                            </div>

                            <button class="btn btn-outline-default btn-block mb-4 font-weight-bold" type="submit">
                                <span>انشاء النموذج الامتحاني</span>
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
                    $("input#score").val("25").attr("readonly","readonly");
                else
                    $("input#score").val("0").removeAttr("readonly");
            });
        });
    </script>
@endsection