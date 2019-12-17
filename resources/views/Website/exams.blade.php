@extends("Website.layout.app")
@section("title")
    <title>الأمتحانات الحالية</title>
@endsection
@section("content")
    <div class="container">
        <div class="row">
            @forelse($studentNotFinishedExams->notFinishedExams->where('state',App\Enums\ExamState::OPEN) as $exam)
                <div class="col-lg-4 col-md-6 col-sm-12 mb-5">
                    <div class="card shadow h-100">
                        <div class="view shadow default-color px-3 py-4">
                            <h5 class="text-center text-white mb-3">
                                <a href="javascript:void(0)" class="text-white">{{$exam->title}}</a>
                            </h5>
                        </div>
                        <div class="card-body">
                            <h5>
                                درجة الامتحان :
                                {{$exam->real_score}}
                            </h5>
                            <p class="card-text text-justify"></p>
                            <div class="card-body-content-fixed">
                                <hr>
                                <div class="btn-group w-100">
                                    <a class="btn  btn-block btn-sm btn-outline-default font-weight-bold  ml-1 mr-0 waves-effect waves-light"
                                       href="{{URL::to('exam',$exam->id)}}">
                                        <i class="fa fa-play ml-1"></i>
                                        <span>بدء</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-success py-5 px-3 text-center" role="alert">
                        <h2 class="my-3">لا يوجد أمتحانات !</h2>
                    </div>
                </div>
            @endforelse

        </div>
    </div>
@endsection
