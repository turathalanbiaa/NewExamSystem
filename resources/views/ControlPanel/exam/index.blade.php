@extends("ControlPanel.layout.app")

@section("title")
    <title>الامتحانات</title>
@endsection

@section("content")
    <div class="container pt-4">
        @foreach($courses as $course)
            <div class="row mb-5">
                <div class="col-12">
                    <h4 class="bg-light p-3" data-toggle="collapse" data-target="#course-exams-{{$course->id}}" data-expande="false">
                        <i class="fa fa-bars text-secondary"></i>
                        {{$course->name}}
                        <span> - </span>
                        {{\App\Enums\Level::get($course->level)}}
                    </h4>
                </div>
                <div class="col-12" id="course-exams-{{$course->id}}">
                    <div class="row">
                        @foreach($exams as $exam)
                            @if($exam->course_id == $course->id)
                                <div class="col-lg-6 col-md-6 col-sm-12 mb-4">
                                    <!-- Card -->
                                    <div class="card shadow h-100">
                                        <!-- Card view -->
                                        <div class="view shadow mdb-color px-3 py-4">
                                            <h5 class="text-center text-white mb-3">
                                                <a href="/control-panel/courses/{{$exam->id}}" class="text-white">{{$exam->title}}</a>
                                            </h5>
                                            <p class="text-center text-white font-weight-bold mb-0">
                                                {{\App\Enums\Level::get($course->level)}}
                                            </p>
                                        </div>

                                        <!-- Card content -->
                                        <div class="card-body" style="padding-bottom: 100px;">
                                            <div class="card-body-content-fixed">
                                                <hr>

                                                <div class="btn-group w-100">
                                                    <a class="btn btn-sm btn-outline-default w-50 ml-1 mr-0" href="/control-panel/courses/{{$course->id}}/edit">
                                                        <i class="fa fa-edit ml-1"></i>
                                                        <span>تعديل المادة</span>
                                                    </a>
                                                    <button class="btn btn-sm btn-outline-default w-50 ml-0 mr-1" type="button" onclick="$('#form-{{$course->id}}').submit();">
                                                        <i class="fa fa-file-archive ml-1"></i>
                                                        <span>ارشفة المادة</span>
                                                    </button>

                                                    <!-- Form-Hidden for archive course -->
                                                    <form id="form-{{$course->id}}" class="d-none" method="post" action="/control-panel/courses/{{$course->id}}">
                                                        @method("DELETE")
                                                        @csrf
                                                    </form>
                                                </div>

                                                <a class="btn btn-sm btn-block btn-outline-default mt-2" href="javascript:void(0)" onclick="$('#form-{{$course->id}}-generate-exams').submit();">
                                                    <i class="fa fa-plus ml-1"></i>
                                                    <span>انشاء النماذج الامتحانية</span>
                                                </a>

                                                <!-- Form-Hidden for generate exams -->
                                                <form class="d-none" id="form-{{$course->id}}-generate-exams" method="post" action="/control-panel/courses/generate-exams">
                                                    {{csrf_field()}}
                                                    <input type="hidden" name="id" value="{{$course->id}}">
                                                </form>
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