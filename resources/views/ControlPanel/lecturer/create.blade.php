@extends("ControlPanel.layout.app")

@section("title")
    <title>اضافة استاذ</title>
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

                <!-- Session Create Lecturer Message -->
                    @if (session('CreateLecturerMessage'))
                        <div class="alert alert-info text-center m-lg-4 m-3">
                            {{session('CreateLecturerMessage')}}
                        </div>
                    @endif


                    <div class="card-body px-lg-5 pt-0 border-bottom border-default">
                        <form class="md-form" method="post" action="/control-panel/lecturers">
                            {{ csrf_field() }}

                            <div class="md-form">
                                <label class="w-100" for="name">الاسم الحقيقي</label>
                                <input type="text" name="name" id="name" class="form-control" value="{{old("name")}}">
                            </div>

                            <div class="md-form">
                                <label class="w-100" for="username">اسم المستخدم</label>
                                <input type="text" name="username" id="username" class="form-control" value="{{old("username")}}">
                            </div>

                            <div class="md-form">
                                <label class="w-100" for="password">كلمة المرور</label>
                                <input type="password" name="password" id="password" class="form-control">
                            </div>

                            <div class="md-form">
                                <label class="w-100" for="password_confirmation">اعد كتابة كلمة المرور</label>
                                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control">
                            </div>

                            <div class="md-form pt-3">
                                <select class="browser-default custom-select" name="state">
                                    <option value="" disabled="" selected="">اختر حالة الحساب</option>
                                    <option value="{{\App\Enums\LecturerState::OPEN}}" {{(old("state") == \App\Enums\LecturerState::OPEN ? "selected":"")}}>
                                        {{\App\Enums\LecturerState::getState(\App\Enums\LecturerState::OPEN)}}
                                    </option>
                                    <option value="{{\App\Enums\LecturerState::CLOSE}}" {{(old("state") == \App\Enums\LecturerState::CLOSE ? "selected":"")}}>
                                        {{\App\Enums\LecturerState::getState(\App\Enums\LecturerState::CLOSE)}}
                                    </option>
                                </select>
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