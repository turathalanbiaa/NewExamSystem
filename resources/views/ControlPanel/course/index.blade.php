@extends("ControlPanel.layout.app")

@section("title")
    <title>المواد الدراسية</title>
@endsection

@section("content")
    <div class="container">
        {{-- Session Update Course Message --}}
        @if (session('UpdateCourseMessage'))
            <div class="row">
                <div class="col-12">
                    <div class="alert alert-success text-center">
                        {{session('UpdateCourseMessage')}}
                    </div>
                </div>
            </div>
        @endif

        {{-- Session Archive Course Message --}}
        @if (session('ArchiveCourseMessage'))
            <div class="row">
                <div class="col-12">
                    <div class="alert {{(session('TypeMessage')=="Error")?"alert-danger":"alert-success"}} text-center">
                        {{session('ArchiveCourseMessage')}}
                    </div>
                </div>
            </div>
        @endif

        {{-- Button Create --}}
        @if(session()->get("EXAM_SYSTEM_ACCOUNT_TYPE") == \App\Enums\AccountType::MANAGER)
            <div class="row">
                <div class="col-12">
                    <a href="/control-panel/courses/create" class="btn btn-outline-default font-weight-bold">
                        <i class="fa fa-plus ml-1"></i>
                        <span>اضافة</span>
                    </a>
                </div>
            </div>
        @endif

        {{-- Divider --}}
        <div class="row">
            <div class="col-12">
                <div class="divider-new my-3">
                    <span class="px-3">المواد الدراسية</span>
                </div>
            </div>
        </div>

        <div class="row">
            @foreach($courses as $course)
                <div class="col-lg-4 col-md-6 col-sm-12 mb-5">
                    {{-- Card Course --}}
                    <div class="card shadow h-100">
                        {{-- Card View --}}
                        <div class="view shadow mdb-color px-3 py-4">
                            <h5 class="text-center text-white mb-3">
                                <a href="javascript:void(0)" class="text-white">{{$course->name}}</a>
                            </h5>
                            <p class="text-center text-white font-weight-bold mb-0">
                                {{\App\Enums\Level::get($course->level)}}
                            </p>
                        </div>

                        {{-- Card Content --}}
                        <div class="card-body" style="padding-bottom: {{(session()->get("EXAM_SYSTEM_ACCOUNT_TYPE") == \App\Enums\AccountType::MANAGER)?115:68}}px;">
                            <h5>
                                <i class="fa fa-user-graduate"></i>
                                {{$course->lecturer->name}}
                            </h5>
                            <p class="card-text text-justify">
                                {{$course->detail}}
                            </p>

                            <div class="extra-content fixed">
                                <hr>
                                <div class="row">
                                    @if(session()->get("EXAM_SYSTEM_ACCOUNT_TYPE") == \App\Enums\AccountType::MANAGER)
                                        <div class="col-6">
                                            <a class="btn btn-sm btn-outline-default btn-block font-weight-bold mb-2" href="/control-panel/courses/{{$course->id}}/edit">
                                                <i class="fa fa-edit ml-1"></i>
                                                <span>تحرير المادة</span>
                                            </a>
                                        </div>

                                        <div class="col-6">
                                            <a class="btn btn-sm btn-outline-default btn-block font-weight-bold mb-2" href="#modelArchiveCourse" data-toggle="modal" data-action="fillArchiveCourseForm" data-course-id="{{$course->id}}" data-course-name="{{$course->name}}">
                                                <i class="fa fa-file-archive ml-1"></i>
                                                <span>ارشفة المادة</span>
                                            </a>
                                        </div>

                                        <div class="col-6">
                                            <a class="btn btn-sm btn-outline-default btn-block font-weight-bold mt-2" href="/control-panel/assessments/{{$course->id}}">
                                                <i class="fa fa-magic ml-1"></i>
                                                <span> تقييم الطلاب </span>
                                            </a>
                                        </div>

                                        <div class="col-6">
                                            <a class="btn btn-sm btn-outline-default btn-block font-weight-bold mt-2" href="#">
                                                <i class="fas fa-chart-pie ml-1"></i>
                                                <span> التقارير </span>
                                            </a>
                                        </div>
                                    @endif

                                    @if(session()->get("EXAM_SYSTEM_ACCOUNT_TYPE") == \App\Enums\AccountType::LECTURER)
                                        <div class="col-6">
                                            <a class="btn btn-sm btn-outline-default btn-block font-weight-bold mt-2" href="/control-panel/assessments/{{$course->id}}">
                                                <i class="fa fa-magic ml-1"></i>
                                                <span> تقييم الطلاب </span>
                                            </a>
                                        </div>

                                        <div class="col-6">
                                            <a class="btn btn-sm btn-outline-default btn-block font-weight-bold mt-2" href="#">
                                                <i class="fas fa-chart-pie ml-1"></i>
                                                <span> التقارير </span>
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection

@section("extra-content")
    {{-- Archive Course Modal --}}
    <div class="modal fade" id="modelArchiveCourse" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-notify modal-danger" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <p class="heading lead">اسم المادة</p>

                    <a href="javascript:void(0)" class="close ml-0" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" class="white-text">&times;</span>
                    </a>
                </div>

                <div class="modal-body">
                    <div class="text-center">
                        <i class="fa fa-lock fa-4x mb-3 animated fadeIn"></i>
                        <h2 class="text-danger">هل تريد ارشفة المادة</h2>
                        <p>بعد ارشفة المادة، سوف لن تظهر هذه المادة للاستاذ.</p>
                    </div>
                </div>

                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-danger font-weight-bold" onclick="$('form#archiveCourse').submit();">ارشفة المادة</button>
                    <button type="button" class="btn btn-outline-danger font-weight-bold" data-dismiss="modal">لا شكرا</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Archive Course Form --}}
    <form id="archiveCourse" method="post" action="">
        @csrf
        @method("DELETE")
    </form>
@endsection

@section("script")
    <script>
        // Fill Modal & Form
        $("[data-action='fillArchiveCourseForm']").click(function () {
            //For Fill Modal
            let courseName = $(this).data("course-name");
            $("#modelArchiveCourse .heading.lead").html(courseName);

            //For Fill Form
            let courseId = $(this).data("course-id");
            $("form#archiveCourse").attr("action","/control-panel/courses/" + courseId);
        });
    </script>
@endsection