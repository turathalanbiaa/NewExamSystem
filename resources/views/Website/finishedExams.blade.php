@extends("Website.layout.app")
@section("title")
    <title>الأمتحانات المنتهية</title>
@endsection
@section("content")
    <div class="container">
        <div class="row">
            @forelse($studentFinishedExams->finishedExams as $exam)
                <div class="col-lg-4 col-md-6 col-sm-12 mb-5">
                    <div class="card shadow h-100">
                        <div class="view shadow default-color px-3 py-4">
                            <h5 class="text-center text-white mb-3">
                                <a href="javascript:void(0)" class="text-white">{{$exam->title}}</a>
                            </h5>
                        </div>
                        <div class="card-body">
                            @if($exam->state == \App\Enums\ExamState::OPEN)
                                <div class="h-100 d-flex justify-content-center align-items-center">
                                    <h5 class="text-center text-primary m-0">
                                        يرجى انتظار التصحيح
                                    </h5>
                                </div>
                            @else
                                @php $sum = 0; @endphp
                                <h5>
                                    <span>درجة الامتحان :</span>
                                    {{(int) $exam->pivot->score}}
                                    @php $sum += (int) $exam->pivot->score; @endphp
                                </h5>
                                <h5>
                                    درجة القرار :
                                    {{$exam->course->getDecisionScore()}}
                                    @php $sum += $exam->course->getDecisionScore(); @endphp
                                </h5>
                                <h5>
                                    تقييم المباحثات :
                                    {{$exam->course->getAssessmentScore()}}
                                    @php $sum += $exam->course->getAssessmentScore(); @endphp
                                </h5>
                                <h5>
                                    الدرجة النهائية :
                                    {{$sum}}
                                </h5>
                            @endif

                            @if($exam->state == \App\Enums\ExamState::END)
                                <div class="card-body-content-fixed">
                                    <hr>
                                    <div class="btn-group w-100">
                                        <a class="btn btn-block btn-outline-default waves-effect waves-light"
                                           href="{{URL::to('finished-exam',$exam->id)}}">
                                            <i class="fa fa-eye ml-1"></i>
                                            <span>معاينة الاجوبة</span>
                                        </a>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-success py-5 px-3 text-center" role="alert">
                        <h2 class="my-3">لا يوجد امتحانات !</h2>
                    </div>
                </div>
            @endforelse

        </div>
    </div>
@endsection
