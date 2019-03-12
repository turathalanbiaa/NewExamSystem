@extends("ControlPanel.layout.app")

@section("title")
    <title>تقييم الطلاب</title>
@endsection

@section("content")
    <div class="container">
        {{-- Session Update Assessment Message --}}
        @if (session('UpdateAssessmentMessage'))
            <div class="row">
                <div class="col-12">
                    <div class="alert {{(session('TypeMessage')=="Error")?"alert-danger":"alert-success"}} text-center">
                        {{session('UpdateAssessmentMessage')}}
                    </div>
                </div>
            </div>
        @endif

        {{-- Button Create --}}
        <div class="row">
            <div class="col-12">
                <a href="/control-panel/assessments/{{$course->id}}/create" class="btn btn-outline-default font-weight-bold">
                    <i class="fa fa-plus ml-1"></i>
                    <span>اضافة تقييم الى الطلاب</span>
                </a>
            </div>
        </div>

        {{-- Assessments DataTable --}}
        <div class="row">
            <div class="col-12">
                <table id="dtAssessments" class="table table-striped table-bordered table-hover w-100" cellspacing="0">
                    <thead class="default-color text-white">
                    <tr>
                        <th class="th-sm fa d-table-cell">
                            <span>رقم</span>
                        </th>
                        <th class="th-sm fa d-table-cell">
                            <span>الطالب</span>
                        </th>
                        <th class="th-sm fa d-table-cell">
                            <span>الدرجة</span>
                        </th>
                        <th class="th-sm fa d-table-cell">
                            <span>خيارات</span>
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($assessments as $assessment)
                        <tr>
                            <td class="align-baseline">{{$assessment->id}}</td>
                            <td class="align-baseline">{{$assessment->student->originalStudent->Name}}</td>
                            <td class="align-baseline">{{$assessment->score}}</td>
                            <td class="align-baseline text-center">
                                <a class="btn btn-sm btn-outline-dark m-1" href="#modelEditAssessment" rel="tooltip" title="تحرير" data-toggle="modal" data-action="fillEditAssessmentForm" data-assessment-id="{{$assessment->id}}" data-assessment-student="{{$assessment->student->originalStudent->Name}}" data-assessment-score="{{$assessment->score}}">
                                    <i class="fa fa-edit"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section("extra-content")
    {{-- Edit Assessment Modal --}}
    <div class="modal fade" id="modelEditAssessment" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-notify modal-success" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <p class="heading lead">اسم الطالب</p>

                    <a href="javascript:void(0)" class="close ml-0" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" class="white-text">&times;</span>
                    </a>
                </div>

                <div class="modal-body">
                    <div class="text-center">
                        <i class="fa fa-edit fa-4x mb-3 animated fadeIn"></i>
                        <h2 class="text-success">تعديل درجة التققيم للطالب</h2>
                    </div>

                    {{-- Edit Assessment Form --}}
                    <form id="editAssessment" method="post" action="">
                        @csrf
                        @method("PUT")
                        <div class="d-flex justify-content-center">
                            <input type="number" name="score" id="score" class="form-control w-50" value="">
                        </div>
                    </form>
                </div>

                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-success font-weight-bold" onclick="$('form#editAssessment').submit();">تعديل الدرجة</button>
                    <button type="button" class="btn btn-outline-success font-weight-bold" data-dismiss="modal">لا شكرا</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section("script")
    <script>
        $(document).ready(function () {
            // DataTable Initialization
            $('#dtAssessments').DataTable();
            $('.dataTables_length').addClass('bs-select');
            let firstChild = $("div#dtAssessments_wrapper>.row:first-child");
            firstChild.css("direction","ltr");
            firstChild.css("text-align","left");

            // Tooltips Initialization
            $('[rel="tooltip"]').tooltip()
        });

        $("[data-action='fillEditAssessmentForm']").click(function () {
            //For Fill Modal
            let student = $(this).data("assessment-student");
            $("#modelEditAssessment .heading.lead").html(student);

            //For Fill Form
            let courseId = '{{$course->id}}';
            let assessmentId = $(this).data("assessment-id");
            let assessmentScore = $(this).data("assessment-score");

            $("form#editAssessment").attr("action","/control-panel/assessments/" + courseId + "/" + assessmentId);
            $("form#editAssessment input[name='score']").val(assessmentScore);
        });
    </script>
@endsection