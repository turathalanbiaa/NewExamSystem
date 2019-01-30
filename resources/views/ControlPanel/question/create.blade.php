@extends("ControlPanel.layout.app")

@section("title")
    <title>اضافة سؤال</title>
@endsection

@section("content")
    <div class="container">
        <div class="row">
            {{-- Heading --}}
            <div class="col-12">
                <div class="view shadow mdb-color px-3 py-4 mb-3">
                    <h5 class="text-center text-white m-0">{{$exam->title}}</h5>
                </div>
            </div>

            {{-- Info --}}
            <div class="col-lg-4">
                {{-- Exam Alert Info --}}
                <div class="alert alert-info">
                    <h5 class="text-center pb-2 border-bottom border-primary">بعض المعلومات عن الامتحان</h5>
                    <ul class="mb-0 pr-3">
                        <li>
                            <span>درجة الامتحان الحقيقية </span>
                            <span class="badge badge-success"> {{$exam->real_mark}} </span>
                            <span>.</span>
                        </li>

                        <li>
                            <span>درجة الامتحان من </span>
                            <span class="badge badge-success"> {{$exam->fake_mark}} </span>
                            <span> والدرجة المتبقية </span>
                            <span class="badge badge-danger"> {{$exam->fake_mark - $exam->questions()->sum("score")}} </span>
                            <span>.</span>
                        </li>

                        <li>
                            <span>عدد الاسئلة الموضوعة </span>
                            <span class="badge badge-success"> {{count($exam->questions)}} </span>
                            <span>.</span>
                        </li>
                    </ul>
                </div>

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

                        <li>Empty ...</li>
                    </ul>
                </div>
            </div>

            {{-- Create Question --}}
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

                    {{-- Card View --}}
                    <div class="view shadow mdb-color px-3 py-4">
                        <h5 class="text-center text-white m-0">اضافة سؤال جديد</h5>
                    </div>

                    {{-- Card Body --}}
                    <div class="card-body px-4 border-bottom border-primary">
                        <form method="post" action="/control-panel/questions">
                            @csrf

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
                                <input type="number" name="score" id="score" class="form-control" value="{{old("score")}}">
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
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection