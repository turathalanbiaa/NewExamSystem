@extends("ControlPanel.layout.app")

@section("title")
    <title>{{$exam->title}}</title>
@endsection

@section("content")
    <div class="container">
        {{-- Session Message --}}
        @if (session('Message'))
            <div class="row">
                <div class="col-12">
                    <div class="alert {{(session('TypeMessage')=="Error")?"alert-danger":"alert-success"}} text-center">
                        {{session('Message')}}
                    </div>
                </div>
            </div>
        @endif

        {{-- Burron Create--}}
        <div class="row">
            <div class="col-12 mb-3">
                <a href="/control-panel/questions/create?exam={{$exam->id}}" class="btn btn-outline-default font-weight-bold">
                    <i class="fa fa-plus ml-1"></i>
                    <span>اضافة سؤال</span>
                </a>
            </div>
        </div>

        <div class="row">
            {{-- Operation --}}
            <div class="col-lg-4">
                {{-- Questions --}}
                <div class="row">
                    {{-- Questions Heading --}}
                    <div class="col-12 mb-3">
                        <h5 class="bg-light p-3 m-0" data-toggle="collapse" data-target="#questions" aria-expanded="false" aria-controls="collapseExams">
                            <i class="fa fa-caret-left text-default ml-1"></i>
                            <span>جميع الاسئلة</span>
                        </h5>
                    </div>

                    {{-- Questions Collapes --}}
                    <div class="col-12 mb-3 collapse" id="questions">
                        <div class="card">
                            <div class="card-body">
                                <ul class="list-group list-group-flush pr-0">
                                    @foreach($exam->questions as $question)
                                        <li class="list-group-item list-group-item-action">
                                            {{$question->title}}
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Curve --}}
                <div class="row">
                    {{-- Curve Heading --}}
                    <div class="col-12 mb-3">
                        <h5 class="bg-light p-3 m-0" data-toggle="collapse" data-target="#curve" aria-expanded="false" aria-controls="collapseExams">
                            <i class="fa fa-caret-left text-default ml-1"></i>
                            <span>كيرف (اضافة درجة على الامتحان)</span>
                        </h5>
                    </div>

                    {{-- Curve Collapes --}}
                    <div class="col-12 mb-3 collapse" id="curve">
                        <div class="card">
                            <div class="card-body">
                                Hello...
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Exam --}}
            <div class="col-lg-8 col-sm-12">
                <div class="card">
                    {{-- Heading --}}
                    <div class="view shadow mdb-color px-3 py-4">
                        <h5 class="text-center text-white m-0">{{$exam->title}}</h5>
                    </div>

                    {{-- Questions --}}
                    <div class="card-body">
                        @forelse($exam->questions as $question)
                            {{-- Question Title --}}
                            <h5>{{$question->title}}</h5>

                            {{-- Question Branches --}}
                            @forelse($question->branches as $branch)
                                @if ($loop->first)
                                    <ol>
                                @endif
                                        <li>{{$branch->title}}</li>

                                @if ($loop->last)
                                    </ol>
                                @endif
                            @empty
                                <h5 class="text-center text-warning">هذا السؤال لا يحتوي على اي نقطة</h5>
                            @endforelse

                            {{-- Question End --}}
                            <hr class="mb-4">
                        @empty
                            <h4>هذا النموذج الامتحاني لايحتوي بعد على اي سؤال</h4>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section("extra-content")
@endsection

@section("script")
    <script></script>
@endsection