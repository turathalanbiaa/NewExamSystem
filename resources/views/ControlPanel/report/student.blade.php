@extends("ControlPanel.layout.app")

@section("title")
    <title>{{$student->originalStudent->Name}}</title>
@endsection

@section("content")
    <div class="container">
        {{-- Student Info--}}
        <div class="row">
            <div class="col-12 mb-3">
                <div class="d-flex">
                    <div style="width: 100px">اسم الطالب </div>
                    <div>
                        <span class="ml-1">:</span>
                        <span>{{$student->originalStudent->Name}}</span>
                    </div>
                </div>
                <div class="d-flex">
                    <div style="width: 100px">المستوي الدراسي </div>
                    <div>
                        <span class="ml-1">:</span>
                        <span>{{\App\Enums\Level::get($student->originalStudent->Level)}}</span>
                    </div>
                </div>
                <div class="d-flex">
                    <div style="width: 100px">رقم الطالب </div>
                    <div>
                        <span>:</span>
                        <span>{{$student->id . "-" . $student->originalStudent->ID}}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Student DataTable --}}
        <div class="row">
            <div class="col-12">
                <table class="table table-striped table-bordered table-hover w-100" cellspacing="0">
                    <thead class="default-color text-white">
                    <tr>
                        <th class="th-sm fa d-table-cell">
                            <span>التسلسل</span>
                        </th>
                        <th class="th-sm fa d-table-cell">
                            <span>المادة</span>
                        </th>
                        <th class="th-sm fa d-table-cell">
                            <span>الشهر الاول</span>
                        </th>
                        <th class="th-sm fa d-table-cell">
                            <span>الشهر الثاني</span>
                        </th>
                        <th class="th-sm fa d-table-cell">
                            <span>التقييم</span>
                        </th>
                        <th class="th-sm fa d-table-cell">
                            <span>نهائي الدور الاول</span>
                        </th>
                        <th class="th-sm fa d-table-cell">
                            <span>نهائي الدور الثاني</span>
                        </th>
                        <th class="th-sm fa d-table-cell">
                            <span>الدرجة النهائية</span>
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($documents as $document)
                        <tr>
                            <td class="th-sm fa d-table-cell">
                                <span>{{$loop->iteration}}</span>
                            </td>
                            <td class="th-sm fa d-table-cell">
                                <span>{{$document->course->name}}</span>
                            </td>
                            <td class="th-sm fa d-table-cell">
                                <span>{{$document->first_month_score}}</span>
                            </td>
                            <td class="th-sm fa d-table-cell">
                                <span>{{$document->second_month_score}}</span>
                            </td>
                            <td class="th-sm fa d-table-cell">
                                <span>{{$document->assessment_score}}</span>
                            </td>
                            <td class="th-sm fa d-table-cell">
                                <span>{{$document->final_first_score}}</span>
                            </td>
                            <td class="th-sm fa d-table-cell">
                                <span>{{$document->final_second_score}}</span>
                            </td>
                            <td class="th-sm fa d-table-cell">
                                <span>{{$document->first_month_score + $document->second_month_score + $document->assessment_score + $document->final_first_score}}</span>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section("script")
    <script>

    </script>
@endsection