@extends("Website.layout.app")

@section("title")
    <title>{{$exam->title}}</title>
@endsection

@section("content")
    <div class="container">
        <div class="row">
            <div class="col-12 pb-4">
                <div class="row text-sm-right text-md-center">
                    <div class="col-sm-4">
                        <div class="h5-responsive">
                            <span>المادة: </span>
                            {{$exam->course->name}}
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="h5-responsive">
                            <span>الامتحان: </span>
                            {{$exam->title}}
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="h5-responsive">
                            <span>التاريخ: </span>
                            {{$exam->date}}
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 position-sticky pb-4" style="top: 56px; z-index: 1000">
                <div class="container px-0 z-depth-1">
                    <section class="bg-default text-center white-text p-4">
                        <div class="h4-responsive pb-2">
                          عدد الافرع التي تمت الاجابة عليها
                        </div>
                        <div class="row">
                            @foreach($questions as $question)
                                <div class="col-6">
                                    <div class="h5-responsive">{{$question["title"]}}</div>
                                    <div class="h5-responsive" id="{{"q-".$question["id"]}}">{{$question["count"]}}</div>
                                </div>
                            @endforeach
                        </div>
                    </section>
                </div>
            </div>

            @foreach($exam->questions as $question)
                <div class="col-12 pb-4">
                    <div class="card" data-content="{{$question->id}}">
                        <div class="card-body">
                            <div class="h3-responsive card-title">
                                {{$question->title}}
                                <span class="badge badge-pill badge-info float-left">
                                    <span>الدرجة : </span>
                                    {{$question->score}}
                                </span>
                            </div>
                            <hr/>

                            {{--true false or single choice--}}
                            @if (in_array($question->type, array(\App\Enums\QuestionType::TRUE_OR_FALSE, \App\Enums\QuestionType::SINGLE_CHOICE)))
                                @foreach($question->branches as $branch)
                                    <section>
                                        <div class="h5-responsive card-title">
                                            {{$loop->iteration}} - {{$branch->title}}
                                        </div>
                                        <div id="{{$branch->id}}" data-selected="{{($branch->getStudentAnswer) ? "1" : "0"}}">
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
                                        <button type="button" class="btn btn-sm btn-outline-danger waves-effect font-weight-bold"
                                                {{!($branch->getStudentAnswer)? "disabled" : ""}}
                                                onclick="deleteAnswer({{$branch->id}})">
                                            <span>حذف الاجابة</span>
                                        </button>
                                    </section>
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
        <button type="button" class="btn btn-outline-primary btn-lg btn-block" data-toggle="modal" data-target="#finishModal">
            انهاء الامتحان
        </button>
    </div>
@endsection

@section("extra-content")
    <!-- Notify Modal-->
    <div class="modal fade top" id="notifyModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="false">
        <div class="modal-dialog modal-notify modal-dialog-centered" role="document">
            <div class="modal-content" id="modal-bg">
                <div class="modal-body">
                    <div class="text-white text-center">
                        <i class="fa fa-check fa-3x mb-3" id="modal-icon"></i>
                        <p id="modal-message"></p>
                        <p id="demo"></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Finish Exam Modal-->
    <div class="modal fade" id="finishModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-notify modal-dialog-centered modal-primary" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <p class="heading lead">انهاء الامتحان</p>
                </div>
                <div class="modal-body">
                    <div class="text-center">
                        <i class="fa fa-user-graduate fa-3x mb-3"></i>
                        <p>
                            <span>هل انت متاكد من انهاء الامتحان </span>
                            <span class="font-weight-bold text-primary">{{$exam->title}}</span>
                        </p>
                    </div>
                </div>
                <div class="modal-footer justify-content-center">
                    <a type="button" class="btn btn-primary" onclick="finish()">نعم</a>
                    <a type="button" class="btn btn-outline-primary waves-effect" data-dismiss="modal">لا</a>
                </div>
            </div>
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
                    $('#modal-bg').removeClass().addClass('modal-content ' + response.background);
                    $('#modal-icon').removeClass().addClass('fa fa-3x mb-3 ' + response.icon);
                    $('#modal-message').html(response.message);
                    $('#notifyModal').modal('show');

                    setTimeout(function () {
                        $('#notifyModal').modal('hide');
                    }, 2000);

                    let devBranch = document.getElementById(branch);
                    if (response.status === true && devBranch.getAttribute('data-selected') === '0') {
                        devBranch.setAttribute('data-selected', '1');
                        devBranch.parentNode.lastElementChild.removeAttribute('disabled');
                        let question = devBranch.parentNode.parentNode.parentNode.getAttribute('data-content');
                        let questionCounter = document.getElementById('q-'+question);
                        questionCounter.innerHTML = (parseInt(questionCounter.innerHTML) + 1).toString();
                    }
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
                    }, 2000);

                    let devBranch = document.getElementById(branch);
                    if (response.status === true) {
                        devBranch.setAttribute('data-selected', '0');
                        $('#' + branch).find('input:radio').prop('checked', false);
                        devBranch.parentNode.lastElementChild.setAttribute('disabled', 'disabled');
                        let question = devBranch.parentNode.parentNode.parentNode.getAttribute('data-content');
                        let questionCounter = document.getElementById('q-'+question);
                        questionCounter.innerHTML = (parseInt(questionCounter.innerHTML) - 1).toString();
                    }
                }
            });
        }

        function finish() {
            $('#finishModal').modal('hide');
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ url('/finish') }}",
                method: 'post',
                data: {
                    exam: {{$exam->id}},
                },
                success: function (response) {
                    console.log(response);
                    $('#modal-bg').removeClass().addClass('modal-content ' + response.background);
                    $('#modal-icon').removeClass().addClass('fa fa-3x mb-3 ' + response.icon);
                    $('#modal-message').html(response.message);
                    $('#notifyModal').modal('show');

                    if (response.status === true) {
                        let countDown  = new Date();
                        countDown.setSeconds(countDown.getSeconds() + 5);
                        countDown = countDown.getTime();

                        setInterval(function() {
                            let now = new Date().getTime();
                            let distance = countDown - now;
                            let seconds = Math.floor((distance % (1000 * 60)) / 1000);
                            document.getElementById("demo").innerHTML = "سيتم تحويلك خلال " + seconds + " ثانية";

                            if (distance < 0) {
                                document.getElementById("demo").innerHTML = "";
                                $('#notifyModal').modal('hide');
                                window.location.replace('/');
                            }
                        }, 1000);
                    } else {
                        setTimeout(function () {
                            $('#notifyModal').modal('hide');
                        }, 2000);
                    }
                }
            });
        }
    </script>
@endsection
