@extends("ControlPanel.layout.app")

@section("title")
    <title>تقرير عن تطبيق نظام القرار</title>
@endsection

@section("content")
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="view shadow mdb-color p-3 mb-4">
                            <h5 class="text-center text-white m-0">تقرير عن طلاب هذا المستوى</h5>
                        </div>

                        <div class="col-12">
                            <h5>قبل تنفيذ القرار</h5>
                            <div class="blockquote right">
                                <p class="mb-0">
                                    <span>عدد الطلاب الكلي: </span>
                                    <span></span>
                                </p>

                                <p class="mb-0">
                                    <span>عدد الطلاب الناجحين: </span>
                                    <span></span>
                                </p>

                                <p class="mb-0">
                                    <span>عدد الطلاب الراسبين: </span>
                                    <span></span>
                                </p>

                                <p class="mb-0">
                                    <span>نسبة النجاح: </span>
                                    <span></span>
                                </p>
                            </div>
                        </div>

                        <div class="col-12">
                            <h5>بعد تنفيذ القرار</h5>
                            <div class="blockquote right">
                                <p class="mb-0">
                                    <span>عدد الطلاب الكلي: </span>
                                    <span></span>
                                </p>

                                <p class="mb-0">
                                    <span>عدد الطلاب الناجحين: </span>
                                    <span></span>
                                </p>

                                <p class="mb-0">
                                    <span>عدد الطلاب الراسبين: </span>
                                    <span></span>
                                </p>

                                <p class="mb-0">
                                    <span>نسبة النجاح: </span>
                                    <span></span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection