@extends("ControlPanel.layout.app")

@section("title")
    <title>اصدار الدرجات</title>
@endsection

@section("content")
    <div class="container">
        <div class="row bg-light p-5 justify-content-center">
            <div class="col-lg-4 col-md-6 col-sm-12 my-3">
                <a href="/control-panel/documents/export/level/{{\App\Enums\Level::BEGINNER}}" class="btn btn-block btn-outline-deep-purple py-4">
                    <span class="h4-responsive">{{\App\Enums\Level::get(\App\Enums\Level::BEGINNER)}}</span>
                </a>
            </div>

            <div class="col-lg-4 col-md-6 col-sm-12 my-3">
                <a href="/control-panel/documents/export/level/{{\App\Enums\Level::FIRST}}" class="btn btn-block btn-outline-deep-purple py-4">
                    <span class="h4-responsive">{{\App\Enums\Level::get(\App\Enums\Level::FIRST)}}</span>
                </a>
            </div>

            <div class="col-lg-4 col-md-6 col-sm-12 my-3">
                <a href="/control-panel/documents/export/level/{{\App\Enums\Level::SECOND}}" class="btn btn-block btn-outline-deep-purple py-4">
                    <span class="h4-responsive">{{\App\Enums\Level::get(\App\Enums\Level::SECOND)}}</span>
                </a>
            </div>

            <div class="col-lg-4 col-md-6 col-sm-12 my-3">
                <a href="/control-panel/documents/export/level/{{\App\Enums\Level::THIRD}}" class="btn btn-block btn-outline-deep-purple py-4">
                    <span class="h4-responsive">{{\App\Enums\Level::get(\App\Enums\Level::THIRD)}}</span>
                </a>
            </div>

            <div class="col-lg-4 col-md-6 col-sm-12 my-3">
                <a href="/control-panel/documents/export/level/{{\App\Enums\Level::FOURTH}}" class="btn btn-block btn-outline-deep-purple py-4">
                    <span class="h4-responsive">{{\App\Enums\Level::get(\App\Enums\Level::FOURTH)}}</span>
                </a>
            </div>

            <div class="col-lg-4 col-md-6 col-sm-12 my-3">
                <a href="/control-panel/documents/export/level/{{\App\Enums\Level::FIFTH}}" class="btn btn-block btn-outline-deep-purple py-4">
                    <span class="h4-responsive">{{\App\Enums\Level::get(\App\Enums\Level::FIFTH)}}</span>
                </a>
            </div>

            <div class="col-lg-4 col-md-6 col-sm-12 my-3">
                <a href="/control-panel/documents/export/level/{{\App\Enums\Level::SIXTH}}" class="btn btn-block btn-outline-deep-purple py-4">
                    <span class="h4-responsive">{{\App\Enums\Level::get(\App\Enums\Level::SIXTH)}}</span>
                </a>
            </div>
        </div>
    </div>
@endsection