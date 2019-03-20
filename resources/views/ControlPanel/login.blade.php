@extends("ControlPanel.layout.app")

@section("title")
    <title>تسجيل الدخول</title>
@endsection

@section("content")
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-sm-12">
                <div class="card">
                    {{-- Heading --}}
                    <p class="h4 text-center text-default py-4 m-0">Login</p>

                    {{-- Alert Errors --}}
                    @if ($errors->any())
                        <div class="alert alert-danger mx-4 my-3">
                            <ul class="mb-0 pr-3">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- Session Error Login Message --}}
                    @if (session('ErrorLoginMessage'))
                        <div class="px-4 my-3">
                            <div class="alert alert-danger text-center">
                                {{session('ErrorLoginMessage')}}
                            </div>
                        </div>
                    @endif

                    {{-- Session Change Password Message --}}
                    @if (session('ChangePasswordMessage'))
                        <div class="px-4 my-3">
                            <div class="alert alert-success text-center">
                                {{session('ChangePasswordMessage')}}
                            </div>
                        </div>
                    @endif

                    {{-- Session Logout Message --}}
                    @if (session('LogoutMessage'))
                        <div class="px-4 my-3">
                            <div class="alert alert-success text-center">
                                {{session('LogoutMessage')}}
                            </div>
                        </div>
                    @endif

                    {{-- Card Body --}}
                    <div class="card-body">
                        <form method="post" action="/control-panel/login" class="text-left" dir="ltr">
                            @csrf

                            <div class="md-form">
                                <i class="fa fa-user prefix text-default"></i>
                                <input type="text" name="username" value="{{old("username")}}" id="username" class="form-control">
                                <label for="username" class="font-weight-light">Username</label>
                            </div>

                            <div class="md-form">
                                <i class="fa fa-lock prefix text-default"></i>
                                <input type="password" name="password" id="password" class="form-control">
                                <label for="password" class="font-weight-light">Password</label>
                            </div>

                            <div class="md-form">
                                <select class="browser-default custom-select" name="accountType">
                                    <option disabled selected value="">Type Account</option>
                                    <option value="{{\App\Enums\AccountType::MANAGER}}">
                                        {{\App\Enums\AccountType::getType(\App\Enums\AccountType::MANAGER)}}
                                    </option>
                                    <option value="{{\App\Enums\AccountType::LECTURER}}">
                                        {{\App\Enums\AccountType::getType(\App\Enums\AccountType::LECTURER)}}
                                    </option>
                                </select>
                            </div>

                            <div class="text-center py-4">
                                <button class="btn btn-outline-default" type="submit">
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