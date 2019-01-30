@extends("ControlPanel.layout.app")

@section("title")
    @if($_GET["type"] == "change-password")
        <title>{{"تغيير كلمة المرور-".$lecturer->name}}</title>
    @else
        <title>{{"تعديل الحساب-".$lecturer->name}}</title>
    @endif
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

                    {{-- Session Update Lecturer Message --}}
                    @if (session('UpdateLecturerMessage'))
                        <div class="alert alert-danger text-center mx-4 mt-4">
                            {{session('UpdateLecturerMessage')}}
                        </div>
                    @endif


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