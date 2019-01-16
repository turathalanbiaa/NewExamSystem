@extends("ControlPanel.layout.app")

@section("title")
    <title>انشاء نموذج امتحاني</title>
@endsection

@section("content")
    <div class="container pt-4">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-sm-12">
                <div class="card">
                    <!-- Errors -->
                    @if ($errors->any())
                        <div class="alert alert-info m-lg-4 m-3">
                            <ul class="mb-0 pr-3">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Session Create Exam Message -->
                    @if (session('CreateExamMessage'))
                        <div class="alert alert-info text-center m-lg-4 m-3">
                            {{session('CreateExamMessage')}}
                        </div>
                    @endif


                    <div class="card-body px-lg-5 border-bottom border-primary">
                        <form class="my-4" method="post" action="/control-panel/exams">
                            {{ csrf_field() }}

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
                                <label for="mark">درجة الامتحان</label>
                                <input type="number" name="mark" id="mark" class="form-control" value="{{old("mark")}}">
                            </div>

                            <div class="mb-5">
                                <label for="date">تاريخ الامتحان</label>
                                <input type="date" name="date" id="date" class="form-control" value="{{old("date")}}">
                            </div>

                            <button class="btn btn-outline-secondary btn-block" type="submit">
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
                {
                    $("input#mark").val("60").attr("disabled","disabled");
                }
                else
                    $("input#mark").val("").removeAttr("disabled");
            });
        });
    </script>
@endsection