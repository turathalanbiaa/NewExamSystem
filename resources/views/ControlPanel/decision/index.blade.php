@extends("ControlPanel.layout.app")

@section("title")
    <title>نظام القرار</title>
@endsection

@section("content")
    <div class="container">
        <div class="row justify-content-center bg-light p-5">
            <div class="col-12 mb-3">
                <h4 class="text-center mb-0">تطبيق نظام القرار حسب المستوى الدراسي</h4>
            </div>

            <div class="col-lg-4 col-md-6 col-sm-12 my-3">
                <a href="/control-panel/decision/1" class="btn btn-block btn-outline-deep-purple py-4">
                    <span class="h3-responsive">المستوى التمهيدي</span>
                </a>
            </div>

            <div class="col-lg-4 col-md-6 col-sm-12 my-3">
                <a class="btn btn-block btn-outline-deep-purple py-4">
                    <span class="h3-responsive">المستوى الاول</span>
                </a>
            </div>

            <div class="col-lg-4 col-md-6 col-sm-12 my-3">
                <a class="btn btn-block btn-outline-deep-purple py-4">
                    <span class="h3-responsive">المستوى الثاني</span>
                </a>
            </div>

            <div class="col-lg-4 col-md-6 col-sm-12 my-3">
                <a class="btn btn-block btn-outline-deep-purple py-4">
                    <span class="h3-responsive">المستوى الثالث</span>
                </a>
            </div>

            <div class="col-lg-4 col-md-6 col-sm-12 my-3">
                <a class="btn btn-block btn-outline-deep-purple py-4">
                    <span class="h3-responsive">المستوى الرابع</span>
                </a>
            </div>

            <div class="col-lg-4 col-md-6 col-sm-12 my-3">
                <a class="btn btn-block btn-outline-deep-purple py-4">
                    <span class="h3-responsive">المستوى الخامس</span>
                </a>
            </div>

            <div class="col-lg-4 col-md-6 col-sm-12 my-3">
                <a class="btn btn-block btn-outline-deep-purple py-4">
                    <span class="h3-responsive">المستوى السادس</span>
                </a>
            </div>
        </div>
    </div>
@endsection