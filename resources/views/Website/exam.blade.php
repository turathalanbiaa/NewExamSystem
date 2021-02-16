@extends("Website.layout.app")

@section("title")
    <title>{{$exam->title}}</title>
@endsection

@section("content")
    <div class="container">
        <div class="row">
            <div class="col-12 py-4">
                <div class="row text-center">
                    <div class="col-sm-4">
                        <h5>
                            <span>المادة: </span>
                            {{$exam->course->name}}
                        </h5>
                    </div>
                    <div class="col-sm-4">
                        <h5>
                            <span>الامتحان: </span>
                            {{$exam->title}}
                        </h5>
                    </div>
                    <div class="col-sm-4">
                        <h5>
                            <span>التاريخ: </span>
                            {{$exam->date}}
                        </h5>
                    </div>
                </div>
            </div>
            @foreach($exam->questions as $question)
                <div class="col-12 pb-5">
                    <div class="card">
                        <div class="card-body">
                            <h3 class="card-title">
                                {{$question->title}}
                                <span class="badge badge-pill badge-info float-left">
                                    <span>الدرجة : </span>
                                    {{$question->score}}
                                </span>
                            </h3>
                            <hr/>
                            {{--true false--}}
                            @if ($question->type== \App\Enums\QuestionType::TRUE_OR_FALSE)
                                @foreach($question->branches as $branch)
                                    <h5 class="card-title">
                                        {{$loop->iteration}} - {{$branch->title}}
                                    </h5>
                                    <div id="{{$branch->id}}">
                                        @foreach(json_decode($branch->options) as $option)
                                            <div class="custom-control custom-radio">
                                                <input type="radio" class="custom-control-input"
                                                       id="{{$branch->id."-".$loop->index}}" name="{{$branch->id}}"
                                                       onchange="saveAnswer({{$branch->id}},'{{$option}}')"
                                                    {{($branch->getStudentAnswer && $branch->getStudentAnswer->text==$option)? "checked" : ""}}>
                                                <label class="custom-control-label" for="{{$branch->id."-".$loop->index}}">
                                                    {{$option}}
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                    <button type="button" class="btn btn-sm  btn-outline-danger  waves-effect font-weight-bold" onclick="deleteAnswer({{$branch->id}})">
                                        <span>حذف الاجابة</span>
                                    </button>
                                    <hr/>
                                @endforeach
                            @endif
                            {{--singal choice--}}
                            @if ($question->type==\App\Enums\QuestionType::SINGLE_CHOICE)
                                @foreach($question->branches as $branch)
                                    <h5 class="card-title">
                                        {{$loop->iteration}} - {{$branch->title}}
                                    </h5>
                                    <div id="{{$branch->id}}">
                                        @foreach(json_decode($branch->options) as $option)
                                            <div class="custom-control custom-radio">
                                                <input type="radio" class="custom-control-input"
                                                       id="{{$branch->id."-".$loop->index}}" name="{{$branch->id}}"
                                                       onchange="saveAnswer({{$branch->id}},'{{$option}}')"
                                                    {{($branch->getStudentAnswer && $branch->getStudentAnswer->text==$option)? "checked" : ""}}>
                                                <label class="custom-control-label" for="{{$branch->id."-".$loop->index}}">
                                                    {{$option}}
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                    <button type="button" class="btn btn-sm  btn-outline-danger  waves-effect font-weight-bold" onclick="deleteAnswer({{$branch->id}})">
                                        <span>حذف الاجابة</span>
                                    </button>
                                    <hr/>
                                @endforeach
                            @endif
                            {{--fill in the blanck--}}
                            @if ($question->type==\App\Enums\QuestionType::FILL_BLANK)
                                @php $i=1; @endphp
                                @foreach($question->branches as $branch)
                                    <div class="md-form">
                                        <input type="text" id="txt{{$branch->id}}" class="form-control"
                                               value="@if(!empty($branch->getStudentAnswer)){{$branch->getStudentAnswer->text}}@endif">
                                        <label class="w-100" for="txt{{$branch->id}}">
                                            <span>{{ $i++ . "-" }}</span>
                                            {{$branch->title}}
                                        </label>
                                    </div>
                                    <button type="button"
                                            id="btn{{$branch->id}}"
                                            class="btn btn-sm  @if(!empty($branch->getStudentAnswer)){{$branch->getStudentAnswer->text}} btn-outline-success @else btn-outline-primary @endif waves-effect font-weight-bold"
                                            onclick="saveAnswer({{$branch->id}},'',this)">حفظ
                                    </button>
                                    <button type="button"
                                            class="btn btn-sm  btn-outline-danger  waves-effect font-weight-bold"
                                            onclick="deleteAnswer({{$branch->id}},this)">ترك
                                    </button>
                                    <hr/>
                                @endforeach
                            @endif
                            {{--expalin--}}
                            @if ($question->type==\App\Enums\QuestionType::EXPLAIN)
                                @php $i=1; @endphp
                                @foreach($question->branches as $branch)
                                    <div class="md-form">
                                        <input type="text" id="txt{{$branch->id}}" class="form-control"
                                               value="@if(!empty($branch->getStudentAnswer)){{$branch->getStudentAnswer->text}}@endif">
                                        <label class="w-100" for="txt{{$branch->id}}">
                                            <span>{{ $i++ . "-" }}</span>
                                            {{$branch->title}}
                                        </label>
                                    </div>
                                    <button type="button"
                                            id="btn{{$branch->id}}"
                                            class="btn btn-sm @if(!empty($branch->getStudentAnswer)){{$branch->getStudentAnswer->text}} btn-outline-success @else btn-outline-primary @endif waves-effect font-weight-bold"
                                            onclick="saveAnswer({{$branch->id}},'',this)">حفظ
                                    </button>
                                    <button id="{{$branch->id}}" type="button"
                                            class="btn btn-sm  btn-outline-danger  waves-effect font-weight-bold"
                                            onclick="deleteAnswer({{$branch->id}},this)">ترك
                                    </button>
                                    <hr/>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <button type="button" class="btn btn-outline-primary btn-lg btn-block mb-5" onclick="finishExam()">
            انهاء الامتحان
        </button>
    </div>

    <!-- Finish Exam Model-->
    <div class="modal fade" id="finishExamModel" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-notify modal-danger" role="document">
            <!--Content-->
            <div class="modal-content">
                <!--Header-->
                <div class="modal-header">
                    <p class="heading lead">تأكيد</p>
                </div>
                <!--Body-->
                <div class="modal-body">
                    <div class="text-center">
                        <i class="fas fa-question fa-3x mb-3 animated rotateIn text-danger"></i>
                        <p>هل انت متأكد .</p>
                    </div>
                </div>
                <!--Footer-->
                <div class="modal-footer justify-content-center">
                    <a type="button" class="btn btn-danger" onclick="finishExamConfirmed({{$exam->id}})">تأكيد</a>
                    <a type="button" class="btn btn-outline-danger waves-effect" data-dismiss="modal">الغاء</a>
                </div>
            </div>
            <!--/.Content-->
        </div>
    </div>
    <!-- Finish Exam Model-->
@endsection

@section("extra-content")
    <!-- Notify Model-->
    <div class="modal fade top" id="notifyModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="false">
        <div class="modal-dialog modal-notify modal-dialog-centered" role="document">
            <!--Content-->
            <div class="modal-content" id="modal-bg">
                <div class="modal-body">
                    <div class="text-white text-center">
                        <i class="fa fa-check fa-3x mb-3" id="modal-icon"></i>
                        <p id="modal-message"></p>
                    </div>
                </div>
            </div>
            <!--/.Content-->
        </div>
    </div>
@endsection

@section('script')
    <script>
        function saveAnswer (branch, answer) {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ url('/store') }}",
                method: 'post',
                data: {
                    exam: {{$exam->id}},
                    branch: branch,
                    answer: answer
                },
                success: function (response) {
                    console.log(response);
                    $('#modal-bg').removeClass().addClass('modal-content ' + response.background);
                    $('#modal-icon').removeClass().addClass('fa fa-3x mb-3 ' + response.icon);
                    $('#modal-message').html(response.message);
                    $('#notifyModal').modal('show');
                    setTimeout(function () {
                        $('#notifyModal').modal('hide');
                    }, 1500);
                }
            });
        }

        function deleteAnswer (branch) {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ url('/delete') }}",
                method: 'post',
                data: {
                    exam: {{$exam->id}},
                    branch: branch
                },
                success: function (response) {
                    console.log(response);
                    $('#modal-bg').removeClass().addClass('modal-content ' + response.background);
                    $('#modal-icon').removeClass().addClass('fa fa-3x mb-3 ' + response.icon);
                    $('#modal-message').html(response.message);
                    $('#notifyModal').modal('show');
                    setTimeout(function () {
                        $('#notifyModal').modal('hide');
                    }, 1500);
                    $('#' + branch).find('input:radio').prop('checked', false);
                }
            });
        }


        finishExam = function () {
            $('#finishExamModel').modal('show');
        };
        finishExamConfirmed = function (id) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: "{{ url('/finish') }}",
                method: 'post',
                data: {
                    id: id,
                },
                success: function (result) {
                    console.log(result);
                    $('#finishExamModel').modal('hide');
                    window.location.replace('/');
                }
            });
        };
    </script>
@endsection
