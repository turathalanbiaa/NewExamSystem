@extends("ControlPanel.layout.app")

@section("title")
    <title>تسجيل الدخول</title>
@endsection

@section("content")
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-sm-12">
                <div class="card shadow mt-5">
                    <div class="card-body">
                        <!--Header-->
                        <div class="form-header turquoise p-3 shadow mb-3" style="margin-top: -30px;">
                            <h3 class="text-white">
                                <i class="fa fa-lock"></i>
                                <span>تسجيل الدخول</span>
                            </h3>
                        </div>

                        <!--Body-->
                        <form method="post" action="/control-panel/login">
                            <div class="md-form">
                                <i class="fa fa-envelope prefix grey-text"></i>
                                <input type="text" id="defaultForm-email" class="form-control">
                                <label for="defaultForm-email">Your email</label>
                            </div>

                            <div class="md-form">
                                <i class="fa fa-lock prefix grey-text"></i>
                                <input type="password" id="defaultForm-pass" class="form-control">
                                <label for="defaultForm-pass">Your password</label>
                            </div>

                            <div class="text-center">
                                <button class="btn btn-default waves-effect waves-light">Login</button>
                            </div>
                        </form>

                    </div>

                    <!--Footer-->
                    <div class="modal-footer">
                        <div class="options">
                            <p>Not a member?
                                <a href="#">Sign Up</a>
                            </p>
                            <p>Forgot
                                <a href="#">Password?</a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection