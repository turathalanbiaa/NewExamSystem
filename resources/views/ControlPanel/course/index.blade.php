@extends("ControlPanel.layout.app")

@section("title")
    <title>المواد الدراسية</title>
@endsection

@section("content")
    <div class="container pt-4">
        <div class="row mb-3">
            <div class="col-12">
                <a href="/control-panel/courses/create" class="btn btn-outline-secondary">
                    <i class="fa fa-plus ml-1"></i>
                    <span>اضافة مادة</span>
                </a>
            </div>
        </div>
        <div class="row">
            <!-- Session Update Course Message -->
            @if (session('UpdateCourseMessage'))
                <div class="col-12">
                    <div class="alert alert-info text-center">
                        {{session('UpdateCourseMessage')}}
                    </div>
                </div>
            @endif

            @foreach($courses as $course)
                <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
                    <!-- Card -->
                    <div class="card shadow h-100">
                        <!-- Card view -->
                        <div class="view shadow mdb-color px-3 py-4">
                            <h3 class="text-center text-white mb-3">
                                <a href="/control-panel/courses/{{$course->id}}" class="text-white">{{$course->name}}</a>
                            </h3>
                            <p class="text-center text-white font-weight-bold mb-0">
                                {{\App\Enums\Level::get($course->level)}}
                            </p>
                        </div>

                        <!-- Card content -->
                        <div class="card-body" style="padding-bottom: 75px;">
                            <h4>
                                <i class="fa fa-user-graduate"></i>
                                {{$course->lecturer->name}}
                            </h4>
                            <p class="card-text text-justify">
                                {{$course->detail}}
                            </p>

                            <div style="position:absolute; bottom: 20px; width: calc(100% - 40px); text-align: center;">
                                <hr>
                                <a href="/control-panel/courses/{{$course->id}}/edit" class="btn btn-sm btn-outline-default font-weight-bold">
                                    <i class="fa fa-edit ml-1"></i>
                                    <span>تعديل المادة</span>
                                </a>
                                <button class="btn btn-sm btn-outline-default font-weight-bold">
                                    <i class="fa fa-plus ml-1"></i>
                                    <span>انشاء نموذج امتحاني</span>
                                </button>
                            </div>
                        </div>
                    </div>
                    <!-- Card -->
                </div>
            @endforeach
        </div>
    </div>
@endsection

@section("script")
    <script>
    </script>
@endsection