@extends("ControlPanel.layout.app")

@section("title")
    <title>تحرير سؤال</title>
@endsection

@section("content")
    <div class="container">
        {{-- Session Update Question Message --}}
        @if (session('UpdateQuestionMessage'))
            <div class="row">
                <div class="col-12">
                    <div class="alert alert-danger text-center">
                        {{session('UpdateQuestionMessage')}}
                    </div>
                </div>
            </div>
        @endif

        <div class="row">
            {{-- Heading --}}
            <div class="col-12">
                <div class="view shadow mdb-color p-3 mb-3">
                    <a class="h5 text-center text-white d-block m-0" href="/control-panel/exams/{{$question->exam->id}}">
                        <span>{{$question->exam->title}}</span>
                        <span class="text-warning"> - - - - </span>
                        <span>الامتحان حاليا </span>
                        <span>{{\App\Enums\ExamState::getState($question->exam->state)}}</span>
                    </a>
                </div>
            </div>

            {{-- Info --}}
            <div class="col-lg-4">
                {{-- Question Alert Info --}}
                <div class="alert alert-info">
                    <h5 class="text-center pb-2 border-bottom border-primary">التعليمات حول تعديل السؤال</h5>
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

                        <li>لا يمكنك تعديل السؤال اذا كان الامتحان التابع له هذا السؤال مفتوح.</li>

                        <li>اذا كان الامتحان منتهي فلا يمكنك تغيير عدد النقاط.</li>
                    </ul>
                </div>

                {{-- Question With One Branch Alert Info --}}
                <div class="alert alert-info">
                    <h5 class="text-center pb-2 border-bottom border-primary">اضافة سؤال لا يحتوي على نقاط</h5>
                    <ul class="mb-0 pr-3">
                        <li>
                            <span>مثلا السؤال هو</span>
                            <span class="font-weight-bold">مالفرق بين اصول الدين وفروع الدين؟</span>
                        </li>
                        <li>فيمكنك ذلك عن طريق اتباع الخطوات التالية.</li>
                        <ul class="pr-2">
                            <li>
                                <span>اضافة سؤال جديد عنوانه </span>
                                <span class="font-weight-bold">مالفرق بين </span>
                                <span>ووضع عدد لنقاط وعدد النقاط المطلوبة تساوي واحد.</span>
                            </li>

                            <li>
                                <span>ثم اضافة نقطه الى السؤال الحالي عنوانها </span>
                                <span class="font-weight-bold">اصول الدين وفروع الدين؟ </span>
                            </li>

                            <li>وهذا ممكن وينطبق على جميع انواع الاسئلة.</li>
                        </ul>
                    </ul>
                </div>

                {{-- Exam Alert Info --}}
                <div class="alert alert-info">
                    <h5 class="text-center pb-2 border-bottom border-primary">بعض المعلومات عن الامتحان</h5>
                    <ul class="mb-0 pr-3">
                        <li>
                            <span>درجة الامتحان الحقيقية </span>
                            <span class="badge badge-success"> {{$question->exam->real_score}} </span>
                            <span>.</span>
                        </li>

                        <li>
                            <span>درجة الامتحان من </span>
                            <span class="badge badge-success"> {{$question->exam->fake_score}} </span>
                            <span> والدرجة المتبقية </span>
                            <span class="badge badge-danger"> {{$question->exam->fake_score - $question->exam->questions()->sum("score")}} </span>
                            <span>.</span>
                        </li>

                        <li>
                            <span>عدد الاسئلة الموضوعة </span>
                            <span class="badge badge-success"> {{$question->exam->questions()->count()}} </span>
                            <span>.</span>
                        </li>
                    </ul>
                </div>
            </div>

            {{-- Edit Question --}}
            <div class="col-lg-8 col-sm-12">
                <div class="card">
                    {{-- Card View --}}
                    <div class="view shadow mdb-color p-3">
                        <h5 class="text-center text-white m-0">تحرير السؤال الحالي</h5>
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
                        @if($question->exam->state == \App\Enums\ExamState::OPEN)
                            <div class="text-center py-5">
                                <i class="fa fa-lightbulb fa-4x mb-3 text-warning animated shake"></i>
                                <h4>لا يمكنك تعديل السؤال الحالي لان الامتحان مفتوح حالياً</h4>
                            </div>
                        @else
                            <form method="post" action="/control-panel/questions/{{$question->id}}">
                                @csrf
                                @method("PUT")

                                <div class="mb-4">
                                    <label for="title">عنوان</label>
                                    <input type="text" name="title" id="title" class="form-control" value="{{$question->title}}">
                                </div>

                                <div class="mb-4">
                                    <label for="score">الدرجة</label>
                                    <input type="number" name="score" id="score" class="form-control" value="{{$question->score}}">
                                </div>

                                <div class="mb-4">
                                    <label for="noOfBranch">عدد النقاط</label>
                                    <input type="number" name="noOfBranch" id="noOfBranch" class="form-control" value="{{$question->no_of_branch}}" {{($question->exam->state == \App\Enums\ExamState::END)?"readonly":""}}>
                                </div>

                                <div class="mb-4">
                                    <label for="noOfBranchRequired">عدد النقاط المطلوبة</label>
                                    <input type="number" name="noOfBranchRequired" id="noOfBranchRequired" class="form-control" value="{{$question->no_of_branch_req}}">
                                </div>

                                @if($question->exam->state == \App\Enums\ExamState::END)
                                    <div class="mb-5">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" name="reCorrectQuestion" id="reCorrectQuestion" class="custom-control-input" value="true">
                                            <label class="custom-control-label" for="reCorrectQuestion">اذا كنت تريد اعادة تصحيح السؤال فعل هذا الاختيار.</label>
                                        </div>
                                    </div>
                                @else
                                    <div class="pb-4"></div>
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