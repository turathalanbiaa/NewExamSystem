@extends("ControlPanel.layout.app")

@section("title")
    <title>تسجيل الدخول</title>
@endsection

@section("content")
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-sm-12">
                <!-- Card login-->
                <div class="card mt-4">
                    <div class="card-body">
                        <form method="post" action="/control-panel/login" class="text-left" dir="ltr">
                            <p class="h4 text-center text-secondary py-4">Login</p>
                            <!-- Errors -->
                            @if ($errors->any())
                                <div class="alert alert-info mb-5" style="direction: rtl; text-align: right;">
                                    <ul class="mb-0 pr-3">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <!-- Session Error Login Message -->
                            @if (session('ErrorLoginMessage'))
                                <div class="alert alert-info text-center">
                                    {{ session('ErrorLoginMessage') }}
                                </div>
                            @endif

                            {{ csrf_field() }}

                            <div class="md-form">
                                <i class="fa fa-user prefix grey-text"></i>
                                <input type="text" name="username" value="{{old("username")}}" id="username" class="form-control">
                                <label for="username" class="font-weight-light">Username</label>
                            </div>

                            <div class="md-form">
                                <i class="fa fa-lock prefix grey-text"></i>
                                <input type="password" name="password" id="password" class="form-control">
                                <label for="password" class="font-weight-light">Password</label>
                            </div>

                            <div class="text-center py-4 mt-3">
                                <button class="btn btn-outline-purple" type="submit">
                                    <span>Login</span>
                                    <i class="fa fa-paper-plane ml-2"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection