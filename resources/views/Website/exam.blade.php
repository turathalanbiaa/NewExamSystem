@extends("Website.layout.app")
@section("title")
    <title>{{$exam->title}}</title>
@endsection
@section("content")
    <div class="container">
        <div class="row">
            <h4 class="alert-heading font-weight-bold mb-4 text-center">{{$exam->title}}</h4>
            @foreach($exam->questions as $question)
                <div class="col-12 pb-5">
                    <div class="card">
                        <div class="card-body">
                            <h3 class="card-title"><a>{{$question->title}}</a></h3>
                            <hr/>
                            {{--true false--}}
                            @if ($question->type== \App\Enums\QuestionType::TRUE_OR_FALSE)
								@php $i=1; @endphp
                                @foreach($question->branches as $branch)
                                    <h5 class="card-title">
										<span>{{ $i++ . "-" }}</span>
										<a>{{$branch->title}}</a>
									</h5>
                                    <div id="{{$branch->id}}">
                                        @foreach(json_decode($branch->options) as $option)
                                            <div class="custom-control custom-radio">
                                                <input type="radio" class="custom-control-input"
                                                       id="{{$branch->id."-".$loop->index}}" name="{{$branch->id}}"
                                                       onchange="saveAnswer({{$branch->id}},'{{$option}}')"
                                                       @if(!empty($branch->getStudentAnswer))
                                                       @if ($branch->getStudentAnswer->text==$option) checked @endif
                                                        @endif >
                                                <label class="custom-control-label"
                                                       for="{{$branch->id."-".$loop->index}}">{{$option}}</label>
                                            </div>
                                        @endforeach
                                    </div>
                                    <button type="button"
                                            class="btn btn-sm  btn-outline-danger  waves-effect font-weight-bold"
                                            onclick="deleteAnswer({{$branch->id}},this)">ترك
                                    </button>
                                    <hr/>
                                @endforeach
                            @endif
                            {{--singal choice--}}
                            @if ($question->type==\App\Enums\QuestionType::SINGLE_CHOICE)
                                @foreach($question->branches as $branch)

                                    <h5 class="card-title"><a>{{$branch->title}}</a></h5>
                                    <div id="{{$branch->id}}">
                                        @foreach(json_decode($branch->options) as $option)
                                            <div class="custom-control custom-radio">
                                                <input type="radio" class="custom-control-input"
                                                       id="{{$branch->id."-".$loop->index}}" name="{{$branch->id}}"
                                                       onchange="saveAnswer({{$branch->id}},'{{$option}}')"
                                                       @if(!empty($branch->getStudentAnswer))
                                                       @if ($branch->getStudentAnswer->text==$option) checked @endif
                                                        @endif>
                                                <label class="custom-control-label"
                                                       for="{{$branch->id."-".$loop->index}}">{{$option}}</label>
                                            </div>
                                        @endforeach
                                    </div>
                                    <button type="button"
                                            class="btn btn-sm  btn-outline-danger  waves-effect font-weight-bold"
                                            onclick="deleteAnswer({{$branch->id}},this)">ترك
                                    </button>
                                    <hr/>
                                @endforeach
                            @endif
                            {{--fill in the blanck--}}
                            @if ($question->type==\App\Enums\QuestionType::FILL_BLANK)
                                @foreach($question->branches as $branch)
                                    <div class="md-form">
                                        <input type="text" id="txt{{$branch->id}}" class="form-control"
                                               value="@if(!empty($branch->getStudentAnswer)){{$branch->getStudentAnswer->text}}@endif">
                                        <label class="w-100" for="txt{{$branch->id}}">{{$branch->title}}</label>
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
                                @foreach($question->branches as $branch)
                                    <div class="md-form">
                                        <input type="text" id="txt{{$branch->id}}" class="form-control"
                                               value="@if(!empty($branch->getStudentAnswer)){{$branch->getStudentAnswer->text}}@endif">
                                        <label class="w-100" for="txt{{$branch->id}}">{{$branch->title}}</label>
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
        <button type="button" class="btn btn-outline-danger btn-lg btn-block mb-5" onclick="finishExam()">انهاء
            الأمتحان
        </button>
    </div>
    <!-- Notify Answer Model-->
    <div class="modal fade top" id="notifyAnswerModel" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
         aria-hidden="true" data-backdrop="false">
        <div class="modal-dialog modal-notify modal-success" role="document">
            <!--Content-->
            <div class="modal-content border-bottom border-success">
                <div class="modal-body">
                    <div class="text-center">
                        <i class="fas fa-check fa-3x mb-3 animated rotateIn text-success"></i>
                        <p>تم الحفظ بنجاح .
                        </p>
                    </div>
                </div>
            </div>
            <!--/.Content-->
        </div>
    </div>
    <!-- Notify Answer Model-->
    <!-- Notify Delete Model-->
    <div class="modal fade top" id="notifyDeleteModel" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
         aria-hidden="true" data-backdrop="false">
        <div class="modal-dialog modal-notify modal-danger" role="document">
            <!--Content-->
            <div class="modal-content border-bottom border-danger">
                <div class="modal-body">
                    <div class="text-center">
                        <i class="fas fa-check fa-3x mb-3 animated rotateIn text-danger"></i>
                        <p>تم الترك بنجاح .
                        </p>
                    </div>
                </div>
            </div>
            <!--/.Content-->
        </div>
    </div>
    <!-- Notify Delete Model-->
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
@section('script')
    <script>
        saveAnswer = function (id, val, element) {
            if (val === '') {
                if ($('#txt' + id).val() !== '') {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax({
                        url: "{{ url('/store') }}",
                        method: 'post',
                        data: {
                            id: id,
                            val: $('#txt' + id).val()
                        },
                        success: function (result) {
                            console.log(result);
                            $('#notifyAnswerModel').modal('show');
                            setTimeout(function () {
                                $('#notifyAnswerModel').modal('hide');
                            }, 1000);
                            $(element).removeClass('btn-outline-primary');
                            $(element).addClass('btn-outline-success');
                        }
                    });
                }
            }
            else {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: "{{ url('/store') }}",
                    method: 'post',
                    data: {
                        id: id,
                        val: val
                    },
                    success: function (result) {
                        console.log(result);
                        $('#notifyAnswerModel').modal('show');
                        setTimeout(function () {
                            $('#notifyAnswerModel').modal('hide');
                        }, 1000);
                    }
                });
            }
        };
        deleteAnswer = function (id) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: "{{ url('/delete') }}",
                method: 'post',
                data: {
                    id: id,
                },
                success: function (result) {
                    $('#notifyDeleteModel').modal('show');
                    setTimeout(function () {
                        $('#notifyDeleteModel').modal('hide');
                    }, 1000);
                    $('#' + id).find('input:radio').prop('checked', false);
                    $('#txt' + id).val('');
                    $('#btn' + id).removeClass('btn-outline-success');
                    $('#btn' + id).addClass('btn-outline-primary');
                }
            });
        };
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