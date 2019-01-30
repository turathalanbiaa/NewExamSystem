@extends("ControlPanel.layout.app")

@section("title")
    @if($_GET["type"] == "change-password")
        <title>{{"تغيير كلمة المرور-".$admin->name}}</title>
    @else
        <title>{{"تعديل الحساب-".$admin->name}}</title>
    @endif
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

                    <!-- Session Update Admin Message -->
                    @if (session('UpdateAdminMessage'))
                        <div class="alert alert-info text-center m-lg-4 m-3">
                            {{session('UpdateAdminMessage')}}
                        </div>
                    @endif

                    <div class="card-body px-lg-5 pt-0 border-bottom border-primary">
                        <form class="md-form" method="post" action="/control-panel/admins/{{$admin->id}}">
                            @method('PUT')
                            {{ csrf_field() }}

                            @if($_GET["type"] == "change-password")
                                <div class="md-form">
                                    <label class="w-100" for="password">كلمة المرور الجديدة</label>
                                    <input type="password" name="password" id="password" class="form-control">
                                </div>

                                <div class="md-form">
                                    <label class="w-100" for="password_confirmation">اعد كتابة كلمة المرور الجديدة</label>
                                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control">
                                </div>
                                <input type="hidden" name="type" value="change-password">
                            @else
                                <div class="md-form">
                                    <label class="w-100" for="name">الاسم الحقيقي</label>
                                    <input type="text" name="name" id="name" class="form-control" value="{{$admin->name}}">
                                </div>

                                <div class="md-form">
                                    <label class="w-100" for="username">اسم المستخدم</label>
                                    <input type="text" name="username" id="username" class="form-control" value="{{$admin->username}}">
                                </div>

                                <div class="md-form pt-3">
                                    <select class="browser-default custom-select" name="state">
                                        <option value="" disabled="" selected="">اختر حالة الحساب</option>
                                        <option value="{{\App\Enums\AccountState::OPEN}}" {{($admin->state == \App\Enums\AccountState::OPEN ? "selected":"")}}>
                                            {{\App\Enums\AccountState::getState(\App\Enums\AccountState::OPEN)}}
                                        </option>
                                        <option value="{{\App\Enums\AccountState::CLOSE}}" {{($admin->state == \App\Enums\AccountState::CLOSE ? "selected":"")}}>
                                            {{\App\Enums\AccountState::getState(\App\Enums\AccountState::CLOSE)}}
                                        </option>
                                    </select>
                                </div>
                            @endif

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