@extends("ControlPanel.layout.app")

@section("title")
    <title>المواد الدراسية</title>
@endsection

@section("content")
    <div class="container pt-4">
        <div class="row pb-3">
            <div class="col-12">
                <a href="/control-panel/courses/create" class="btn btn-outline-secondary">
                    <i class="fa fa-plus ml-1"></i>
                    <span>اضافة مادة</span>
                </a>
            </div>
        </div>

        <!-- Session Update Course Message -->
        @if (session('UpdateCourseMessage'))
            <div class="row">
                <div class="col-12">
                    <div class="alert alert-info text-center">
                        {{session('UpdateCourseMessage')}}
                    </div>
                </div>
            </div>
        @endif

        <!-- Session Archive Course Message -->
        @if (session('ArchiveCourseMessage'))
            <div class="row">
                <div class="col-12">
                    <div class="alert alert-info text-center">
                        {{session('ArchiveCourseMessage')}}
                    </div>
                </div>
            </div>
        @endif

        <div class="row">
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
                        <div class="card-body" style="padding-bottom: 100px;">
                            <h4>
                                <i class="fa fa-user-graduate"></i>
                                {{$course->lecturer->name}}
                            </h4>
                            <p class="card-text text-justify">
                                {{$course->detail}}
                            </p>

                            <div style="position: absolute; right: 0; bottom: 0; width: 100%; padding: 0 20px 20px 20px">
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

                                    <!-- Form-Hidden for archive course  -->
                                    <form id="form-{{$course->id}}" class="d-none" method="post" action="/control-panel/courses/{{$course->id}}">
                                        @method("DELETE")
                                        @csrf
                                    </form>
                                </div>

                                <button class="btn btn-sm btn-block btn-outline-default mt-2">
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