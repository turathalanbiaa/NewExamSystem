@extends("ControlPanel.layout.app")

@section("title")
    <title>اضافة سؤال</title>
@endsection

@section("content")
    <div class="container">
        <div class="row">
            {{-- Exam --}}
            <div class="col-12">
                <div class="view shadow mdb-color px-3 py-4 mb-3">
                    <a class="h5 d-block text-center text-white m-0" href="/control-panel/exams/{{$exam->id}}">{{$exam->title}}</a>
                </div>
            </div>

            {{-- Info --}}
            <div class="col-lg-4">
                {{-- Question Alert Info --}}
                <div class="alert alert-info">
                    <h5 class="text-center pb-2 border-bottom border-primary">التعليمات حول اضافة السؤال</h5>
                    <ul class="mb-0 pr-3">
                        <li>
                            <span>تقسم درجة السؤال </span>
                            <span class="font-weight-bold"> بالتساوي </span>
                            <span>على جميع النقاط.</span>
                        </li>

                        <li>
                            <span>اذا كنت </span>
                            <span class="font-weight-bold">ترغب </span>
                            <span>بالترك الضمني، فيمكنك ذلك عن طريق وضع عدد النقاط المطلوبة </span>
                            <span class="font-weight-bold">اقل من </span>
                            <span>عدد النقاط.</span>
                        </li>

                        <li>
                            <span>اذا كنت </span>
                            <span class="font-weight-bold">لا ترغب </span>
                            <span>بالترك الضمني، فيمكنك ذلك عن طريق وضع عدد النقاط المطلوبة </span>
                            <span class="font-weight-bold">يساوي </span>
                            <span>عدد النقاط.</span>
                        </li>
                    </ul>
                </div>

                {{-- Question With One Branch Alert Info --}}
                <div class="alert alert-info">
                    <h5 class="text-center pb-2 border-bottom border-primary">اضافة سؤال يحتوي على نقطة واحدة</h5>
                    <ul class="mb-0 pr-3">
                        <li>
                            <span>مثلاً السؤال: </span>
                            <span class="font-weight-bold">ما الفرق بين اصول الدين وفروع الدين؟</span>
                        </li>
                        <li>فيمكنك ذلك عن طريق اتباع الخطوات التالية.</li>
                        <ul class="pr-2">
                            <li>
                                <span>اضافة </span>
                                <span class="font-weight-bold">ما الفرق بين </span>
                                <span>في عنوان السؤال.</span>
                            </li>

                            <li>
                                <span>اضافة </span>
                                <span class="font-weight-bold">اصول الدين وفروع الدين؟ </span>
                                <span>في عنوان النقطة.</span>
                            </li>
                        </ul>
                    </ul>
                </div>

                {{-- Exam Alert Info --}}
                <div class="alert alert-info">
                    <h5 class="text-center pb-2 border-bottom border-primary">بعض المعلومات عن الامتحان</h5>
                    <ul class="mb-0 pr-3">
                        <li>
                            <span>درجة الامتحان الحقيقية </span>
                            <span class="badge badge-success"> {{$exam->real_score}} </span>
                            <span>.</span>
                        </li>

                        <li>
                            <span>درجة الامتحان من </span>
                            <span class="badge badge-success"> {{$exam->fake_score}} </span>
                            <span> والدرجة المتبقية </span>
                            <span class="badge badge-danger"> {{$exam->fake_score - $exam->questions()->sum("score")}} </span>
                            <span>.</span>
                        </li>

                        <li>
                            <span>عدد الاسئلة الموضوعة </span>
                            <span class="badge badge-success"> {{count($exam->questions)}} </span>
                            <span>.</span>
                        </li>
                    </ul>
                </div>

                {{-- Question Type Alert Info --}}
                <div class="alert alert-info">
                    <h5 class="text-center pb-2 border-bottom border-primary">التصحيح اليدوي والالكتروني</h5>
                    <ul class="mb-0 pr-3">
                        <li>يكون التصحيح الكتروني اذا كان نوع السؤال صح او خطأ او اختيارات.</li>
                        <li>يكون التصحيح يدوي اذا كان نوع السؤال فراغات او تعاريف او شرح.</li>
                    </ul>
                </div>
            </div>

            {{-- Create Question --}}
            <div class="col-lg-8 col-sm-12">
                <div class="card">
                    {{-- Card View --}}
                    <div class="view shadow mdb-color px-3 py-4">
                        <h5 class="text-center text-white m-0">اضافة سؤال جديد</h5>
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
                        @if(($exam->fake_score - $exam->questions()->sum("score"))== 0)
                            <div class="text-center py-5">
                                <i class="fa fa-lightbulb fa-4x mb-3 text-warning animated shake"></i>
                                <h4>لا يمكنك اضافة سؤال جديد لان درجة الامتحان المتبقية تساوي صفر</h4>
                            </div>
                        @else
                            <form method="post" action="/control-panel/questions">
                                @csrf
                                <input type="hidden" name="exam" value="{{$exam->id}}">

                                <div class="mb-4">
                                    <label for="title">عنوان</label>
                                    <input type="text" name="title" id="title" class="form-control" value="{{old("title")}}">
                                </div>

                                <div class="mb-4">
                                    <label for="type">اختر نوع السؤال</label>
                                    <select class="browser-default custom-select" name="type" id="type">
                                        <option value="" disabled="" selected="">يرجى اختيار نوع السؤال</option>
                                        <option value="{{\App\Enums\QuestionType::TRUE_OR_FALSE}}" {{(old("type") == \App\Enums\QuestionType::TRUE_OR_FALSE ? "selected":"")}}>
                                            {{\App\Enums\QuestionType::getType(\App\Enums\QuestionType::TRUE_OR_FALSE)}}
                                        </option>
                                        <option value="{{\App\Enums\QuestionType::SINGLE_CHOICE}}" {{(old("type") == \App\Enums\QuestionType::SINGLE_CHOICE ? "selected":"")}}>
                                            {{\App\Enums\QuestionType::getType(\App\Enums\QuestionType::SINGLE_CHOICE)}}
                                        </option>
                                        <option value="{{\App\Enums\QuestionType::FILL_BLANK}}" {{(old("type") == \App\Enums\QuestionType::FILL_BLANK ? "selected":"")}}>
                                            {{\App\Enums\QuestionType::getType(\App\Enums\QuestionType::FILL_BLANK)}}
                                        </option>
                                        <option value="{{\App\Enums\QuestionType::EXPLAIN}}" {{(old("type") == \App\Enums\QuestionType::EXPLAIN ? "selected":"")}}>
                                            {{\App\Enums\QuestionType::getType(\App\Enums\QuestionType::EXPLAIN)}}
                                        </option>
                                    </select>
                                </div>

                                <div class="mb-4">
                                    <label for="score">الدرجة</label>
                                    <input type="number" name="score" id="score" class="form-control" value="{{old("score", 0)}}">
                                </div>

                                <div class="mb-4">
                                    <label for="noOfBranch">عدد النقاط</label>
                                    <input type="number" name="noOfBranch" id="noOfBranch" class="form-control" value="{{old("noOfBranch", 1)}}">
                                </div>

                                <div class="mb-5">
                                    <label for="noOfBranchRequired">عدد النقاط المطلوبة</label>
                                    <input type="number" name="noOfBranchRequired" id="noOfBranchRequired" class="form-control" value="{{old("noOfBranchRequired", 1)}}">
                                </div>

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