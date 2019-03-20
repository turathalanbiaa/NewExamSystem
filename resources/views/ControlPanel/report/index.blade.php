@extends("ControlPanel.layout.app")

@section("title")
    <title>التقارير</title>
@endsection

@section("content")
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-md-10 col-sm-12">
                <div class="row">
                    {{-- Card Students Document --}}
                    <div class="col-lg-6 col-md-6 col-sm-12 mb-4">
                        {{-- Card--}}
                        <div class="card card-image" style="background-image: url(https://mdbootstrap.com/img/Photos/Horizontal/Work/4-col/img%20%2814%29.jpg);">
                            <!-- Content -->
                            <div class="text-white text-center d-flex align-items-center rgba-black-strong py-5 px-4">
                                {{-- View --}}
                                <div class="w-100">
                                    {{-- Title --}}
                                    <h3>
                                        <i class="far fa-address-card pink-text"></i>
                                        <span>تقارير الطلاب</span>
                                    </h3>
                                    {{-- Button --}}
                                    <a class="btn btn-pink" href="/control-panel/reports/students">
                                        <i class="fa fa-sign-in-alt fa-rotate-180 ml-1"></i>
                                        <span> التقارير</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Card Courses Document --}}
                    <div class="col-lg-6 col-md-6 col-sm-12 mb-4">
                        {{-- Card--}}
                        <div class="card card-image" style="background-image: url(https://mdbootstrap.com/img/Photos/Horizontal/Work/4-col/img%20%2814%29.jpg);">
                            <!-- Content -->
                            <div class="text-white text-center d-flex align-items-center rgba-black-strong py-5 px-4">
                                {{-- View --}}
                                <div class="w-100">
                                    {{-- Title --}}
                                    <h3>
                                        <i class="far fa-clone pink-text"></i>
                                        <span>تقارير المواد الدراسية</span>
                                    </h3>
                                    {{-- Button --}}
                                    <a class="btn btn-pink" href="/control-panel/reports/courses">
                                        <i class="fa fa-sign-in-alt fa-rotate-180 ml-1"></i>
                                        <span> التقارير</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Card Exams Document --}}
                    <div class="col-lg-6 col-md-6 col-sm-12 mb-4">
                        {{-- Card--}}
                        <div class="card card-image" style="background-image: url(https://mdbootstrap.com/img/Photos/Horizontal/Work/4-col/img%20%2814%29.jpg);">
                            <!-- Content -->
                            <div class="text-white text-center d-flex align-items-center rgba-black-strong py-5 px-4">
                                {{-- View --}}
                                <div class="w-100">
                                    {{-- Title --}}
                                    <h3>
                                        <i class="far fa-edit pink-text"></i>
                                        <span>تقارير الامتحانات</span>
                                    </h3>
                                    {{-- Button --}}
                                    <a class="btn btn-pink" href="/control-panel/reports/exams">
                                        <i class="fa fa-sign-in-alt fa-rotate-180 ml-1"></i>
                                        <span> التقارير</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection