@extends("ControlPanel.layout.app")

@section("title")
    <title>{{$course->name}}</title>
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

                    <!-- Session Update Course Message -->
                    @if (session('UpdateCourseMessage'))
                        <div class="alert alert-info text-center m-lg-4 m-3">
                            {{session('UpdateCourseMessage')}}
                        </div>
                    @endif


                    <div class="card-body px-lg-5 pt-0 border-bottom border-default">
                        <form class="md-form" method="post" action="/control-panel/courses/{{$course->id}}">
                            @method('PUT')
                            {{ csrf_field() }}

                            <div class="md-form">
                                <label class="w-100" for="name">المادة</label>
                                <input type="text" name="name" id="name" class="form-control" value="{{$course->name}}">
                            </div>

                            <div class="md-form pt-3">
                                <select class="browser-default custom-select" name="level">
                                    <option value="" disabled="" selected="">اختر المستوى الدراسي</option>
                                    <option value="{{\App\Enums\Level::BEGINNER}}" {{($course->level == \App\Enums\Level::BEGINNER ? "selected":"")}}>
                                        {{\App\Enums\Level::get(\App\Enums\Level::BEGINNER)}}
                                    </option>
                                    <option value="{{\App\Enums\Level::FIRST}}" {{($course->level == \App\Enums\Level::FIRST ? "selected":"")}}>
                                        {{\App\Enums\Level::get(\App\Enums\Level::FIRST)}}
                                    </option>
                                    <option value="{{\App\Enums\Level::SECOND}}" {{($course->level == \App\Enums\Level::SECOND ? "selected":"")}}>
                                        {{\App\Enums\Level::get(\App\Enums\Level::SECOND)}}
                                    </option>
                                    <option value="{{\App\Enums\Level::THIRD}}" {{($course->level == \App\Enums\Level::THIRD ? "selected":"")}}>
                                        {{\App\Enums\Level::get(\App\Enums\Level::THIRD)}}
                                    </option>
                                    <option value="{{\App\Enums\Level::FOURTH}}" {{($course->level == \App\Enums\Level::FOURTH ? "selected":"")}}>
                                        {{\App\Enums\Level::get(\App\Enums\Level::FOURTH)}}
                                    </option>
                                    <option value="{{\App\Enums\Level::FIFTH}}" {{($course->level == \App\Enums\Level::FIFTH ? "selected":"")}}>
                                        {{\App\Enums\Level::get(\App\Enums\Level::FIFTH)}}
                                    </option>
                                    <option value="{{\App\Enums\Level::SIXTH}}" {{($course->level == \App\Enums\Level::SIXTH ? "selected":"")}}>
                                        {{\App\Enums\Level::get(\App\Enums\Level::SIXTH)}}
                                    </option>
                                </select>
                            </div>

                            <div class="md-form pt-3">
                                <select class="browser-default custom-select" name="lecturer">
                                    <option value="" disabled="" selected="">اختر استاذ المادة</option>
                                    @foreach($lecturers as $lecturer)
                                        <option value="{{$lecturer->id}}" {{($course->lecturer_id == $lecturer->id ? "selected":"")}}>
                                            {{$lecturer->name}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="md-form pt-3">
                                <select class="browser-default custom-select" name="state">
                                    <option value="" disabled="" selected="">اختر حالة المادة</option>
                                    <option value="{{\App\Enums\CourseState::OPEN}}" {{($course->state == \App\Enums\CourseState::OPEN ? "selected":"")}}>
                                        {{\App\Enums\CourseState::getState(\App\Enums\CourseState::OPEN)}}
                                    </option>
                                    <option value="{{\App\Enums\CourseState::CLOSE}}" {{($course->state == \App\Enums\CourseState::CLOSE ? "selected":"")}}>
                                        {{\App\Enums\CourseState::getState(\App\Enums\CourseState::CLOSE)}}
                                    </option>
                                </select>
                            </div>

                            <div class="md-form pt-3">
                                <textarea class="form-control p-2" name="detail" rows="5" placeholder="تفاصيل حول المادة">{{$course->detail}}</textarea>
                            </div>

                            <button class="btn btn-outline-secondary btn-block mt-5" type="submit">
                                <span>حفظ</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection