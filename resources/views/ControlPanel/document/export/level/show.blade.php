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
                    <span>درجات الطلاب في </span>
                    <span>{{\App\Enums\Level::get($level)}}</span>
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

                        @foreach($courses as $course)
                            <th class="th-sm font-weight-bold align-middle" rowspan="2">
                                <span>{{$course->name}}</span>
                            </th>
                        @endforeach
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
                            @forelse($student->documentsForCurrentSeason() as $document)
                                @if($document->course->state == \App\Enums\CourseState::OPEN)
                                    <td class="th-sm">
                                        <span class="{{($document->final_score <50)?"text-decoration":""}}">{{$document->final_score}}</span>
                                    </td>
                                @endif
                                @if($loop->last)
                                    @php $i = $loop->iteration; @endphp
                                @endif

                            @empty
                                @for($i=0;$i<$numberOfCourses;$i++)
                                    <td>---</td>
                                @endfor
                            @endforelse
                            @for($i;$i<$courses->count();$i++)
                                <td>---</td>
                            @endfor
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Footer --}}
{{--            <div class="col-12 mb-5">--}}
{{--                <a class="btn btn-outline-danger font-weight-bold" href="/control-panel/pdf/level/{{$level}}">--}}
{{--                    <span class="far fa-file-pdf ml-1"></span>--}}
{{--                    <span>تصدير ملف pdf</span>--}}
{{--                </a>--}}
{{--            </div>--}}
        </div>
    </div>
@endsection
