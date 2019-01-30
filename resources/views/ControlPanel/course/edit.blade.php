@extends("ControlPanel.layout.app")

@section("title")
    <title>{{$course->name}}</title>
@endsection

@section("content")
    <div class="container">
        <div class="row justify-content-center">
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

                    {{-- Session Update Course Message --}}
                    @if (session('UpdateCourseMessage'))
                        <div class="alert alert-danger text-center mx-4 mt-4">
                            {{session('UpdateCourseMessage')}}
                        </div>
                    @endif

                    <div class="card-body px-4 border-bottom border-primary">
                        <form method="post" action="/control-panel/courses/{{$course->id}}">
                            @method('PUT')
                            @csrf

                            <div class="mb-4">
                                <label for="name">المادة</label>
                                <input type="text" name="name" id="name" class="form-control" value="{{$course->name}}">
                            </div>

                            <div class="mb-4">
                                <label for="level">اختر المستوى الدراسي</label>
                                <select class="browser-default custom-select" name="level" id="level">
                                    <option value="" disabled="" selected="">يرجى اختيار المستوى الدراسي</option>
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

                            <div class="mb-4">
                                <label for="lecturer">اختر استاذ المادة</label>
                                <select class="browser-default custom-select" name="lecturer" id="lecturer">
                                    <option value="" disabled="" selected="">يرجى اختيار استاذ المادة</option>
                                    @foreach($lecturers as $lecturer)
                                        <option value="{{$lecturer->id}}" {{($course->lecturer_id == $lecturer->id ? "selected":"")}}>
                                            {{$lecturer->name}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-4">
                                <label for="state">اختر حالة المادة</label>
                                <select class="browser-default custom-select" name="state" id="state">
                                    <option value="" disabled="" selected="">يرجى اختيار حالة المادة</option>
                                    <option value="{{\App\Enums\CourseState::OPEN}}" {{($course->state == \App\Enums\CourseState::OPEN ? "selected":"")}}>
                                        {{\App\Enums\CourseState::getState(\App\Enums\CourseState::OPEN)}}
                                    </option>
                                    <option value="{{\App\Enums\CourseState::CLOSE}}" {{($course->state == \App\Enums\CourseState::CLOSE ? "selected":"")}}>
                                        {{\App\Enums\CourseState::getState(\App\Enums\CourseState::CLOSE)}}
                                    </option>
                                </select>
                            </div>

                            <div class="mb-5">
                                <label for="detail">تفاصيل حول المادة</label>
                                <textarea class="form-control" name="detail" id="detail" rows="5" placeholder="التفاصيل ...">{{$course->detail}}</textarea>
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