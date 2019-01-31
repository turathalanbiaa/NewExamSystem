@extends("ControlPanel.layout.app")

@section("title")
    <title>{{$currentQuestion->exam->title}}</title>
@endsection

@section("content")
    <div class="container">
        {{-- Burron Create--}}
        <div class="row">
            <div class="col-12 mb-3">
                <a href="/control-panel/branches/create?question={{$currentQuestion->id}}" class="btn btn-outline-default font-weight-bold">
                    <i class="fa fa-plus ml-1"></i>
                    <span>اضافة نقطة جديدة الى السؤال</span>
                </a>
            </div>
        </div>

        <div class="row">
            {{-- Questions --}}
            <div class="col-lg-4">
                <div class="card">
                    {{-- Card View --}}
                    <div class="view shadow mdb-color px-3 py-3">
                        <h5 class="text-center text-white m-0">جميع الاسئلة</h5>
                    </div>

                    {{-- Card Body --}}
                    <div class="card-body px-4 border-bottom border-primary">
                        <ul class="list-group list-group-flush pr-0">
                            @foreach($questions as $question)
                                <li class="list-group-item list-group-item-action">
                                    {{$question->title}}
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>

            {{-- Current Question --}}
            <div class="col-lg-8 col-sm-12">
                <div class="card">
                    {{-- Card View --}}
                    <div class="view shadow mdb-color px-3 py-3">
                        <h5 class="text-center text-white m-0">السؤال الحالي</h5>
                    </div>

                    {{-- Card Body --}}
                    <div class="card-body px-4 border-bottom border-primary">
                        <p>
                            <span class="font-weight-bold">السؤال: </span>
                            {{$currentQuestion->title}}
                        </p>

                        <p>
                            <span class="font-weight-bold">درجة السؤال: </span>
                            {{$currentQuestion->score}}
                        </p>

                        <p>
                            <span class="font-weight-bold">نوع السؤال: </span>
                            {{\App\Enums\QuestionType::getType($currentQuestion->type)}}
                        </p>

                        <div>
                            <p class="font-weight-bold">
                                <u>النقاط التابعة للسؤال الحالي</u>
                            </p>

                            <ul class="list-group list-group-flush pr-0">
                                @foreach($currentQuestion->branches as $branch)
                                    <li class="list-group-item list-group-item-action">
                                        {{$branch->title}}
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection