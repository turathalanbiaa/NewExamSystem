@extends("ControlPanel.layout.app")

@section("title")
    @if($_GET["type"] == "change-password")
        <title>{{"تغيير كلمة المرور"}}</title>
    @else
        <title>{{"تعديل الحساب"}}</title>
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

                    {{-- Card Body --}}
                    <div class="card-body px-4 border-bottom border-primary">
                        <form method="post" action="/control-panel/profile/{{$account->id}}">
                            @csrf
                            @method('PUT')

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
                                    <input type="text" name="name" id="name" class="form-control" value="{{$account->name}}">
                                </div>

                                <div class="mb-5">
                                    <label class="w-100" for="username">اسم المستخدم</label>
                                    <input type="text" name="username" id="username" class="form-control" value="{{$account->username}}">
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