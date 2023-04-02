@extends("ControlPanel.layout.app")

@section("title")
    <title>تقييم الطلاب</title>
@endsection

@section("content")
    <div class="container">
        {{-- Session Create Assessment Message --}}
        @if (session('CreateAssessmentMessage'))
            <div class="row">
                <div class="col-12">
                    <div class="alert {{(session('TypeMessage')=="Error")?"alert-danger":"alert-success"}} text-center">
                        {{session('CreateAssessmentMessage')}}
                    </div>
                </div>
            </div>
        @endif

        <div class="row">
            {{-- Heading --}}
            <div class="col-12 mb-3">
                <div class="view shadow mdb-color p-3">
                    <h5 class="text-center text-white m-0">
                        <span>تقييم الطلاب في المادة </span>
                        <span>{{$course->name}}</span>
                    </h5>
                </div>
            </div>

            {{-- Assessments Students --}}
            <div class="col-lg-4">
                {{-- Evaluate all students who have not been evaluated --}}
                <div class="card mb-4">
                    <div class="card-body border-bottom border-default">
                        {{-- Session Evaluation Message --}}
                        @if (session('EvaluationMessage'))
                            <div class="alert alert-danger text-center">
                                {{session('EvaluationMessage')}}
                            </div>
                        @endif
                        {{-- Heading --}}
                        <h5 class="text-center pb-2 border-bottom border-primary">تقييم جميع الطلاب الذين لم يتم تقييمهم</h5>

                        {{-- Form --}}
                        <form class="mt-3" method="post" action="/control-panel/assessments/{{$course->id}}">
                            @csrf
                            @method("PUT")
                            <input type="hidden" name="tab" value="all-remaining-students">
                            <div class="form-inline w-100">
                                <label for="score" class="w-25 justify-content-start">الدرجة </label>
                                <input type="number" name="score" id="score" class="form-control w-75" value="">
                            </div>


                            {{-- Info --}}
                            <h6 class="my-3">
                                <i class="fa fa-star text-danger font-small"></i>
                                <span>يجب ان لا تتجاوز درجة التقييم (25 درجة).</span>
                            </h6>

                            <button class="btn btn-outline-default btn-block font-weight-bold" type="submit">ارسال</button>
                        </form>
                    </div>
                </div>

                {{-- Evaluate all students --}}
                <div class="card">
                    <div class="card-body border-bottom border-default">
                        {{-- Session Evaluation Message --}}
                        @if (session('EvaluationMessage'))
                            <div class="alert alert-danger text-center">
                                {{session('EvaluationMessage')}}
                            </div>
                        @endif
                        {{-- Heading --}}
                        <h5 class="text-center pb-2 border-bottom border-primary">تقييم جميع الطلاب</h5>

                        {{-- Form --}}
                        <form class="mt-3" method="post" action="/control-panel/assessments/{{$course->id}}">
                            @csrf
                            @method("PUT")
                            <input type="hidden" name="tab" value="all-students">
                            <div class="form-inline w-100">
                                <label for="score" class="w-25 justify-content-start">الدرجة </label>
                                <input type="number" name="score" id="score" class="form-control w-75" value="">
                            </div>


                            {{-- Info --}}
                            <h6 class="my-3">
                                <i class="fa fa-star text-danger font-small"></i>
                                <span>يجب ان لا تتجاوز درجة التقييم (25 درجة).</span>
                            </h6>

                            <button class="btn btn-outline-default btn-block font-weight-bold" type="submit">ارسال</button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Create Assessment --}}
            <div class="col-lg-8 col-sm-12">
                @if(count($students)>0)
                    {{-- Heading --}}
                    <div class="d-flex justify-content-between">
                        <p class="">
                            <span>عدد الطلاب المسجلين على المادة: </span>
                            <span>{{$noOfStudentsEnrolled}}</span>
                        </p>
                        <p>
                            <span>عدد الطلاب الذين تم تقييمهم: </span>
                            <span>{{$noOfResidentStudents}}</span>
                        </p>
                    </div>

                    <form method="post" action="/control-panel/assessments/{{$course->id}}">
                        @csrf
                        <input type="hidden" name="students" value="{{$students->pluck("id")->implode(',')}}">

                        {{-- Students --}}
                        <table class="table table-striped table-bordered w-100" cellspacing="0">
                            <thead class="default-color text-white">
                            <tr>
                                <th class="th-sm fa d-table-cell">
                                    <span>اسم الطالب</span>
                                </th>
                                <th class="th-sm fa d-table-cell">
                                    <span>الدرجه</span>
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($students as $student)
                                <tr>
                                    <td class="align-baseline">{{$student->originalStudent->Name}}</td>
                                    <td class="align-baseline">
                                        <input type="number" class="form-control" name="{{"student-".$student->id}}" value="0">
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>

                        {{-- Info --}}
                        <h6 class="mb-3">
                            <i class="fa fa-star text-danger font-small"></i>
                            <span>درجه تقييم الطالب يجب ان لاتتجاوز (25 درجه).</span>
                        </h6>

                        {{-- Button Submit --}}
                        <button type="submit" class="btn btn-outline-default btn-block font-weight-bold">
                            <span>حفظ المعلومات</span>
                        </button>
                    </form>
                @else
                    @if($noOfStudentsEnrolled>0)
                        <div class="card">
                            <div class="card-body px-4 border-bottom border-primary">
                                <div class="text-center py-5">
                                    <i class="fa fa-check fa-4x mb-3 text-success animated fadeIn"></i>
                                    <h4>شكرا، لقد قمت بتقييم جميع الطلاب</h4>
                                    <p class="mt-2">
                                        <span>عدد الطلاب المسجلين على المادة: </span>
                                        <span>{{$noOfStudentsEnrolled}}</span>
                                        <br>
                                        <span>عدد الطلاب الذين تم تقييمهم: </span>
                                        <span>{{$noOfResidentStudents}}</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="card">
                            <div class="card-body px-4 border-bottom border-primary">
                                <div class="text-center py-5">
                                    <i class="fa fa-lightbulb fa-4x mb-3 text-warning animated shake"></i>
                                    <h4>لا يوجد طلاب مسجلين على هذه المادة لتقييمهم</h4>
                                </div>
                            </div>
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </div>
@endsection
