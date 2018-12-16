@extends("ControlPanel.layout.app")

@section("title")
    <title>اضافة حساب</title>
@endsection

@section("content")
    <div class="container pt-4">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-sm-12">
                <div class="card">
                    <div class="card-body px-lg-5 pt-0">
                        <!-- Errors -->
                        @if ($errors->any())
                            <div class="alert alert-primary mb-5" style="direction: rtl; text-align: right;">
                                <ul class="mb-0 pr-3">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form class="md-form" method="post" action="/control-panel/admins">
                            {{csrf_field()}}
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
                                <select class="browser-default custom-select" name="type">
                                    <option value="" disabled="" selected="">اختر نوع الحساب</option>
                                    <option value="{{\App\Enums\AdminType::MANAGER}}">
                                        {{\App\Enums\AdminType::getType(\App\Enums\AdminType::MANAGER)}}
                                    </option>
                                    <option value="{{\App\Enums\AdminType::LECTURER}}">
                                        {{\App\Enums\AdminType::getType(\App\Enums\AdminType::LECTURER)}}
                                    </option>
                                </select>
                            </div>

                            <div class="md-form pt-3">
                                <select class="browser-default custom-select" name="lecturer_id">
                                    <option value="" disabled="" selected="">اختر الاستاذ</option>
                                    @forelse($lecturers as $lecturer)
                                        <option value="{{$lecturer->id}}">
                                            {{$lecturer->name}}
                                        </option>
                                    @empty
                                        <option value="" disabled="">لايوجد استاذ، يرجى اضافة اساتذة اولا.</option>
                                    @endforelse
                                </select>
                            </div>

                            <div class="md-form pt-3">
                                <select class="browser-default custom-select" name="state">
                                    <option value="" disabled="" selected="">اختر حالة الحساب</option>
                                    <option value="{{\App\Enums\AdminState::OPEN}}">
                                        {{\App\Enums\AdminState::getState(\App\Enums\AdminState::OPEN)}}
                                    </option>
                                    <option value="{{\App\Enums\AdminState::CLOSE}}">
                                        {{\App\Enums\AdminState::getState(\App\Enums\AdminState::CLOSE)}}
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

@section("script")
@endsection