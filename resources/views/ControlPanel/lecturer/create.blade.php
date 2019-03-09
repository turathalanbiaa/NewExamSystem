@extends("ControlPanel.layout.app")

@section("title")
    <title>اضافة استاذ</title>
@endsection

@section("content")
    <div class="container">
        {{-- Session Create Lecturer Message --}}
        @if (session('CreateLecturerMessage'))
            <div class="row">
                <div class="col-12">
                    <div class="alert {{((session('TypeMessage')=="Error")?"alert-danger":"alert-success")}} text-center">
                        {{session('CreateLecturerMessage')}}
                    </div>
                </div>
            </div>
        @endif

        <div class="row">
            {{-- Heading --}}
            <div class="col-12 mb-3">
                <div class="view shadow mdb-color p-3">
                    <h5 class="text-center text-white m-0">انشاء حساب استاذ</h5>
                </div>
            </div>

            {{-- Info --}}
            <div class="col-lg-4">
                {{-- Lecturer Alert Info --}}
                <div class="alert alert-info">
                    <h5 class="text-center pb-2 border-bottom border-primary">معلومات حول امكانيات الاستاذ</h5>
                    <ul class="mb-0 pr-3">
                        <li>له كل الامكانيات على المواد الدراسية الخاصة به من اضافة وتعديل وحذف.</li>
                        <li>له جميع امكانيات على الامتحانات من اضافة وتعديل وحذف وتغيير حالة الامتحان وتصحيح الامتحان.</li>
                    </ul>
                </div>
            </div>

            {{-- Create Lecturer --}}
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

                    {{-- Card Body --}}
                    <div class="card-body px-4 border-bottom border-primary">
                        <form method="post" action="/control-panel/lecturers">
                            @csrf

                            <div class="mb-4">
                                <label for="name">الاسم الحقيقي</label>
                                <input type="text" name="name" id="name" class="form-control" value="{{old("name")}}">
                            </div>

                            <div class="mb-4">
                                <label for="username">اسم المستخدم</label>
                                <input type="text" name="username" id="username" class="form-control" value="{{old("username")}}">
                            </div>

                            <div class="mb-4">
                                <label for="password">كلمة المرور</label>
                                <input type="password" name="password" id="password" class="form-control">
                            </div>

                            <div class="mb-4">
                                <label for="password_confirmation">اعد كتابة كلمة المرور</label>
                                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control">
                            </div>

                            <div class="mb-5">
                                <label for="state">اختر حالة الحساب</label>
                                <select class="browser-default custom-select" name="state" id="state">
                                    <option value="" disabled="" selected="">يرجى اختيار حالة الحساب</option>
                                    <option value="{{\App\Enums\AccountState::OPEN}}" {{(old("state") == \App\Enums\AccountState::OPEN ? "selected":"")}}>
                                        {{\App\Enums\AccountState::getState(\App\Enums\AccountState::OPEN)}}
                                    </option>
                                    <option value="{{\App\Enums\AccountState::CLOSE}}" {{(old("state") == \App\Enums\AccountState::CLOSE ? "selected":"")}}>
                                        {{\App\Enums\AccountState::getState(\App\Enums\AccountState::CLOSE)}}
                                    </option>
                                </select>
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