@extends("ControlPanel.layout.app")

@section("title")
    @if($_GET["type"] == "change-password")
        <title>تغيير كلمة المرور</title>
    @else
        <title>تحرير الحساب</title>
    @endif
@endsection

@section("content")
    <div class="container">
        {{-- Session Update Lecturer Message --}}
        @if (session('UpdateLecturerMessage'))
            <div class="row">
                <div class="col-12">
                    <div class="alert alert-info text-center">
                        {{session('UpdateLecturerMessage')}}
                    </div>
                </div>
            </div>
        @endif

        <div class="row">
            {{-- Heading --}}
            <div class="col-12 mb-3">
                <div class="view shadow mdb-color p-3">
                    <h5 class="text-center text-white m-0">
                        @if($_GET["type"] == "change-password")
                            <span>{{"تغيير كلمة مرور الاستاذ ".$lecturer->name}}</span>
                        @else
                            <span>{{"تحرير حساب الاستاذ ".$lecturer->name}}</span>
                        @endif
                    </h5>
                </div>
            </div>

            {{-- Info --}}
            <div class="col-lg-4">
                {{-- Lecturer Alert Info --}}
                <div class="alert alert-info">
                    <h5 class="text-center pb-2 border-bottom border-primary">معلومات حول امكانيات الاستاذ</h5>
                    <ul class="mb-0 pr-3">
                        <li>يمكنك تعديل كل بيانات الاستاذ.</li>
                        <li>اذا قمت بتغيير كلمة المرور سوف يتم تسجيل خروج تلقائي للحساب.</li>
                        <li>اذا قمت بتغيير حالة الحساب الى مفلق فيستطيع صاحب الحساب تسجيل الدخول فقط.</li>
                    </ul>
                </div>
            </div>

            {{-- Edit Lecturer --}}
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
                    <div class="card-body px-4 border-bottom border-default">
                        <form method="post" action="/control-panel/lecturers/{{$lecturer->id}}">
                            @method('PUT')
                            @csrf

                            @if($_GET["type"] == "change-password")
                                <div class="mb-4">
                                    <label for="password">كلمة المرور الجديدة</label>
                                    <input type="password" name="password" id="password" class="form-control">
                                </div>

                                <div class="mb-5">
                                    <label for="password_confirmation">اعد كتابة كلمة المرور الجديدة</label>
                                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control">
                                </div>

                                <input type="hidden" name="type" value="change-password">
                            @else
                                <div class="mb-4">
                                    <label for="name">الاسم الحقيقي</label>
                                    <input type="text" name="name" id="name" class="form-control" value="{{$lecturer->name}}">
                                </div>

                                <div class="mb-4">
                                    <label for="username">اسم المستخدم</label>
                                    <input type="text" name="username" id="username" class="form-control" value="{{$lecturer->username}}">
                                </div>

                                <div class="mb-5">
                                    <label for="state">اختر حالة الحساب</label>
                                    <select class="browser-default custom-select" name="state" id="state">
                                        <option value="" disabled="" selected="">يرجى اختيار حالة الحساب</option>
                                        <option value="{{\App\Enums\AccountState::OPEN}}" {{($lecturer->state == \App\Enums\AccountState::OPEN ? "selected":"")}}>
                                            {{\App\Enums\AccountState::getState(\App\Enums\AccountState::OPEN)}}
                                        </option>
                                        <option value="{{\App\Enums\AccountState::CLOSE}}" {{($lecturer->state == \App\Enums\AccountState::CLOSE ? "selected":"")}}>
                                            {{\App\Enums\AccountState::getState(\App\Enums\AccountState::CLOSE)}}
                                        </option>
                                    </select>
                                </div>

                                <input type="hidden" name="type" value="change-info">
                            @endif

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