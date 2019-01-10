@extends("ControlPanel.layout.app")

@section("title")
    <title>اضافة مادة</title>
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

                <!-- Session Create Course Message -->
                    @if (session('CreateCourseMessage'))
                        <div class="alert alert-info text-center m-lg-4 m-3">
                            {{session('CreateCourseMessage')}}
                        </div>
                    @endif


                    <div class="card-body px-lg-5 pt-0 border-bottom border-primary">
                        <form class="md-form" method="post" action="/control-panel/courses">
                            {{ csrf_field() }}

                            <div class="md-form">
                                <label class="w-100" for="name">المادة</label>
                                <input type="text" name="name" id="name" class="form-control" value="{{old("name")}}">
                            </div>

                            <div class="md-form pt-3">
                                <select class="browser-default custom-select" name="level">
                                    <option value="" disabled="" selected="">اختر المستوى الدراسي</option>
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

                            <div class="md-form pt-3">
                                <select class="browser-default custom-select" name="lecturer">
                                    <option value="" disabled="" selected="">اختر استاذ المادة</option>
                                    @forelse($lecturers as $lecturer)
                                        <option value="{{$lecturer->id}}" {{(old("lecturer") == $lecturer->id ? "selected":"")}}>
                                            {{$lecturer->name}}
                                        </option>
                                    @empty
                                        <option value="" disabled="" selected="">لا يوجد استاذة</option>
                                    @endforelse
                                </select>
                            </div>

                            <div class="md-form pt-3">
                                <select class="browser-default custom-select" name="state">
                                    <option value="" disabled="" selected="">اختر حالة المادة</option>
                                    <option value="{{\App\Enums\CourseState::OPEN}}" {{(old("state") == \App\Enums\CourseState::OPEN ? "selected":"")}}>
                                        {{\App\Enums\CourseState::getState(\App\Enums\CourseState::OPEN)}}
                                    </option>
                                    <option value="{{\App\Enums\CourseState::CLOSE}}" {{(old("state") == \App\Enums\CourseState::CLOSE ? "selected":"")}}>
                                        {{\App\Enums\CourseState::getState(\App\Enums\CourseState::CLOSE)}}
                                    </option>
                                </select>
                            </div>

                            <div class="md-form pt-3">
                                <textarea class="form-control p-2" name="detail" rows="5" placeholder="تفاصيل حول المادة">{{old("detail")}}</textarea>
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