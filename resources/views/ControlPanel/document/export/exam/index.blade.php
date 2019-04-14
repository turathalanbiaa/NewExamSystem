@extends("ControlPanel.layout.app")

@section("title")
<title>اصدار الدرجات</title>
@endsection

@section("content")
<div class="container">
    <div class="row bg-light p-5 justify-content-center">
        @foreach($exams as $exam)
        <div class="col-lg-4 col-md-6 col-sm-12 my-3">
            <a href="/control-panel/documents/export/exam/{{$exam->id}}" class="btn btn-block btn-outline-deep-purple py-4">
                <span class="h4-responsive">{{$exam->title}}</span>
            </a>
        </div>
        @endforeach
    </div>
</div>
@endsection