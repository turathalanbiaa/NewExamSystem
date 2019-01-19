@extends("ControlPanel.layout.app")

@section("title")
    <title>الامتحانات</title>
@endsection

@section("content")
    <div class="container pt-4">
        <div class="row mb-3">
            <div class="col-12">
                <a href="/control-panel/exams/create" class="btn btn-outline-secondary waves-effect waves-light">
                    <i class="fa fa-plus ml-1"></i>
                    <span>انشاء نموذج امتحاني</span>
                </a>
            </div>
        </div>

        <!-- Session Create Exam Message -->
        @if (session('CreateExamMessage'))
            <div class="row">
                <div class="col-12">
                    <div class="alert alert-info text-center">
                        {{session('CreateExamMessage')}}
                    </div>
                </div>
            </div>
        @endif

        @foreach($courses as $course)
            <div class="row mb-3">
                <div class="col-12">
                    <h4 class="bg-light p-3" data-toggle="collapse" data-target="#course-exams-{{$course->id}}" aria-expanded="false" aria-controls="collapseExams">
                        <i class="fa fa-bars text-secondary ml-1"></i>
                        {{$course->name}}
                        <span> -> </span>
                        {{\App\Enums\Level::get($course->level)}}
                    </h4>
                </div>
                <div class="col-12 collapse" id="course-exams-{{$course->id}}">
                    <div class="row">
                        @foreach($exams as $exam)
                            @if($exam->course_id == $course->id)
                                <div class="col-lg-4 col-md-6 col-sm-12 mb-3">
                                    <!-- Card -->
                                    <div class="card shadow h-100">
                                        <!-- Card view -->
                                        <div class="view shadow mdb-color px-3 py-4">
                                            <h5 class="text-center text-white">
                                                <a href="/control-panel/courses/{{$exam->id}}" class="text-white">{{$exam->title}}</a>
                                            </h5>
                                        </div>

                                        <!-- Card content -->
                                        <div class="card-body">
                                            <div class="list-group list-group-flush">
                                                <a href="#!" class="list-group-item list-group-item-action">عرض جميع الاسئلة</a>
                                                <a href="/control-panel/exams/{{$exam->id}}/edit" class="list-group-item list-group-item-action">تعديل النموذج الامتحاني</a>

                                                <a href="#!" class="list-group-item list-group-item-action">فتح الامتحان</a>
                                                <a href="#!" class="list-group-item list-group-item-action">اغلاق الامتحان</a>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Card -->
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection