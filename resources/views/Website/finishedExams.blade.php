@extends("Website.layout.app")
@section("title")
    <title>الأمتحانات المنتهية</title>
@endsection
@section("content")
    <div class="container">
        <div class="row">
            @forelse($student->finishedExams as $exam)
                @if ($exam->state==App\Enums\ExamState::OPEN)
                    <div class="col-lg-4 col-md-6 col-sm-12 mb-5">

                        <div class="card shadow h-100">

                            <div class="view shadow mdb-color px-3 py-4">
                                <h5 class="text-center text-white mb-3">
                                    <a href="javascript:void(0)" class="text-white">{{$exam->title}}</a>
                                </h5>
                            </div>


                            <div class="card-body" style="padding-bottom: 75px;">
                                <h5>
                                    التاريخ :
                                    {{$exam->date}}
                                </h5>
                                <p class="card-text text-justify">

                                </p>

                                <div class="card-body-content-fixed">

                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @empty
              <div class="col-12">
                  <div class="alert alert-primary py-5 px-3 text-center" role="alert">
                      <h2 class="my-3">لا يوجد أمتحانات !</h2>
                  </div>
              </div>
            @endforelse

        </div>
    </div>
@endsection
