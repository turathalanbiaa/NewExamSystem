@extends("ControlPanel.layout.app")

@section("title")
    <title>تسجيل الدخول</title>
@endsection

@section("content")
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-sm-12">
                <!-- Card -->
                <div class="card mt-4">

                    <!-- Card body -->
                    <div class="card-body">

                        <!-- Material form register -->
                        <form class="text-left" dir="ltr">
                            <p class="h4 text-center py-4">Login</p>

                            <!-- Material input username -->
                            <div class="md-form">
                                <i class="fa fa-user prefix grey-text"></i>
                                <input type="text" id="username" class="form-control">
                                <label for="username" class="font-weight-light">Username</label>
                            </div>

                            <!-- Material input password -->
                            <div class="md-form">
                                <i class="fa fa-lock prefix grey-text"></i>
                                <input type="password" id="password" class="form-control">
                                <label for="password" class="font-weight-light">Password</label>
                            </div>

                            <div class="text-center py-4 mt-3">
                                <button class="btn btn-outline-purple" type="submit">
                                    <span>Login</span>
                                    <i class="fa fa-paper-plane ml-2"></i>
                                </button>
                            </div>
                        </form>
                        <!-- Material form register -->

                    </div>
                    <!-- Card body -->

                </div>
                <!-- Card -->
            </div>
        </div>
    </div>
@endsection