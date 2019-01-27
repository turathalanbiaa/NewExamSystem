@extends("ControlPanel.layout.app")

@section("title")
    <title>اضافة مادة</title>
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

                    {{-- Session Create Course Message --}}
                    @if (session('CreateCourseMessage'))
                        <div class="alert {{(session('TypeMessage') == "Error" ? "alert-danger":"alert-success")}} text-center mx-4 mt-4">
                            {{session('CreateCourseMessage')}}
                        </div>
                    @endif

                    <div class="card-body px-4 border-bottom border-primary">
                        <form method="post" action="/control-panel/courses">
                            @csrf

                            <div class="mb-4">
                                <label for="name">المادة</label>
                                <input type="text" name="name" id="name" class="form-control" value="{{old("name")}}">
                            </div>

                            <div class="mb-4">
                                <label for="level">اختر المستوى الدراسي</label>
                                <select class="browser-default custom-select" name="level" id="level">
                                    <option value="" disabled="" selected="">يرجى اختيار المستوى الدراسي</option>
                                    <option value="{{\App\Enums\Level::BEGINNER}}" {{(old("level") == \App\Enums\Level::BEGINNER ? "selected":"")}}>
                                        {{\App\Enums\Level::get(\App\Enums\Level::BEGINNER)}}
                                    </option>
                                    <option value="{{\App\Enums\Level::FIRST}}" {{(old("level") == \App\Enums\Level::FIRST ? "selected":"")}}>
                                        {{\App\Enums\Level::get(\App\Enums\Level::FIRST)}}
                                    </option>
                                    <option value="{{\App\Enums\Level::SECOND}}" {{(old("level") == \App\Enums\Level::SECOND ? "selected":"")}}>
                                        {{\App\Enums\Level::get(\App\Enums\Level::SECOND)}}
                                    </option>
                                    <option value="{{\App\Enums\Level::THIRD}}" {{(old("level") == \App\Enums\Level::THIRD ? "selected":"")}}>
                                        {{\App\Enums\Level::get(\App\Enums\Level::THIRD)}}
                                    </option>
                                    <option value="{{\App\Enums\Level::FOURTH}}" {{(old("level") == \App\Enums\Level::FOURTH ? "selected":"")}}>
                                        {{\App\Enums\Level::get(\App\Enums\Level::FOURTH)}}
                                    </option>
                                    <option value="{{\App\Enums\Level::FIFTH}}" {{(old("level") == \App\Enums\Level::FIFTH ? "selected":"")}}>
                                        {{\App\Enums\Level::get(\App\Enums\Level::FIFTH)}}
                                    </option>
                                    <option value="{{\App\Enums\Level::SIXTH}}" {{(old("level") == \App\Enums\Level::SIXTH ? "selected":"")}}>
                                        {{\App\Enums\Level::get(\App\Enums\Level::SIXTH)}}
                                    </option>
                                </select>
                            </div>

                            <div class="mb-4">
                                <label for="lecturer">اختر استاذ المادة</label>
                                <select class="browser-default custom-select" name="lecturer" id="lecturer">
                                    <option value="" disabled="" selected="">يرجى اختيار استاذ المادة</option>
                                    @forelse($lecturers as $lecturer)
                                        <option value="{{$lecturer->id}}" {{(old("lecturer") == $lecturer->id ? "selected":"")}}>
                                            {{$lecturer->name}}
                                        </option>
                                    @empty
                                        <option value="" disabled="" selected="">لا يوجد استاذة</option>
                                    @endforelse
                                </select>
                            </div>

                            <div class="mb-4">
                                <label for="state">اختر حالة المادة</label>
                                <select class="browser-default custom-select" name="state" id="state">
                                    <option value="" disabled="" selected="">يرجى اختيار حالة المادة</option>
                                    <option value="{{\App\Enums\CourseState::OPEN}}" {{(old("state") == \App\Enums\CourseState::OPEN ? "selected":"")}}>
                                        {{\App\Enums\CourseState::getState(\App\Enums\CourseState::OPEN)}}
                                    </option>
                                    <option value="{{\App\Enums\CourseState::CLOSE}}" {{(old("state") == \App\Enums\CourseState::CLOSE ? "selected":"")}}>
                                        {{\App\Enums\CourseState::getState(\App\Enums\CourseState::CLOSE)}}
                                    </option>
                                </select>
                            </div>

                            <div class="mb-5">
                                <label for="detail">تفاصيل حول المادة</label>
                                <textarea class="form-control" name="detail" id="detail" rows="5" placeholder="التفاصيل ...">{{old("detail")}}</textarea>
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