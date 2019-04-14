@extends("ControlPanel.layout.app")

@section("title")
    <title>عرض الدرجات</title>
@endsection

@section("content")
    <div class="container">
        <div class="row">
            {{-- Heading --}}
            <div class="col-12">
                <h4 class="text-center">
                    <span>درجات الطلاب في المادة - </span>
                    <span>{{$course->name}}</span>
                </h4>
            </div>

            {{-- Body --}}
            <div class="col-12">
                <table class="table table-striped table-bordered text-center table-sm table-hover table-responsive-xl" cellspacing="0">
                    <thead class="default-color text-white font-weight-bold">
                    <tr>
                        <th class="th-sm font-weight-bold align-middle">
                            <span>ت</span>
                        </th>

                        <th class="th-sm font-weight-bold align-middle">
                            <span>اسم الطالب</span>
                        </th>

                        <th class="th-sm font-weight-bold align-middle" rowspan="2">
                            <span>الدرجة</span>
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($students as $student)
                        <tr>
                            <td class="th-sm">
                                <span>{{$loop->iteration}}</span>
                            </td>
                            <td class="th-sm">
                                <span>{{$student->OriginalStudent->Name}}</span>
                            </td>
                            @foreach($student->documentsForCurrentSeason() as $document)
                                @if($document->course_id == $course->id)
                                    <td class="th-sm">
                                        <span class="{{($document->final_score <50)?"text-decoration":""}}">{{$document->final_score}}</span>
                                    </td>
                                @endif
                            @endforeach
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Footer --}}
            <div class="col-12 mb-5">
                <a class="btn btn-outline-danger font-weight-bold" href="/control-panel/pdf/course/{{$course->id}}">
                    <span class="far fa-file-pdf ml-1"></span>
                    <span>تصدير ملف pdf</span>
                </a>
            </div>
        </div>
    </div>
@endsection