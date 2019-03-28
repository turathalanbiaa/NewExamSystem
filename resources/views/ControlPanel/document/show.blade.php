@extends("ControlPanel.layout.app")

@section("title")
    <title>{{$student->originalStudent->Name}}</title>
@endsection

@section("content")
    <div class="container">
        {{-- Documents --}}
        @php $groupingDocumentsByYear = $student->documents->groupBy("year"); @endphp
        @foreach($groupingDocumentsByYear as $year => $notGroupingDocumentsBySeason)
            @php $groupingDocumentsBySeason = $notGroupingDocumentsBySeason->groupBy("season"); @endphp
            @foreach($groupingDocumentsBySeason as $season => $documents)
                <div class="row">
                    {{-- Info --}}
                    <div class="col-12 mb-2">
                        {{-- Heading --}}
                        <h4 class="text-center">
                            <span>وثيقة درجات الطالب /</span>
                            <span>{{\App\Enums\Level::get($documents[0]->course->level)}}</span>
                        </h4>

                        {{-- Body --}}
                        <div class="d-flex">
                            <div style="width: 60px">اسم الطالب </div>
                            <div>
                                <span class="ml-1">:</span>
                                <span>{{$student->originalStudent->Name}}</span>
                            </div>
                        </div>
                        <div class="d-flex">
                            <div style="width: 60px">السنة </div>
                            <div>
                                <span class="ml-1">:</span>
                                <span>{{($season==1)?"النصف الاول":"النصف الثاني"}}</span>
                                <span>{{" من سنة " . $year}}</span>
                            </div>
                        </div>
                    </div>

                    {{-- DataTable --}}
                    <div class="col-12 mb-2">
                        <table class="table table-striped table-bordered text-center table-sm table-hover w-100" cellspacing="0">
                            <thead class="default-color text-white font-weight-bold">
                            <tr>
                                <th class="th-sm font-weight-bold align-middle" rowspan="2">
                                    <span>ت</span>
                                </th>

                                <th class="th-sm font-weight-bold align-middle" rowspan="2">
                                    <span>المادة</span>
                                </th>

                                <th class="th-sm font-weight-bold align-middle" colspan="2">
                                    <span>مجموع درجة الشهرين يساوي 25</span>
                                </th>

                                <th class="th-sm font-weight-bold align-middle" rowspan="2">
                                    <span>التقييم من 15</span>
                                </th>

                                <th class="th-sm font-weight-bold align-middle" rowspan="2">
                                    <span>نهائي الدور الاول من 60</span>
                                </th>

                                <th class="th-sm font-weight-bold align-middle" rowspan="2">
                                    <span>نهائي الدور الثاني من 60</span>
                                </th>

                                <th class="th-sm font-weight-bold align-middle" rowspan="2">
                                    <span>الدرجه النهائية</span>
                                </th>
                            </tr>

                            <tr>
                                <th class="th-sm font-weight-bold align-middle">
                                    <span>الشهر الاول</span>
                                </th>

                                <th class="th-sm font-weight-bold align-middle">
                                    <span>الشهر الثاني</span>
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($documents as $document)
                                <tr>
                                    <td class="th-sm">
                                        <span>{{$loop->iteration}}</span>
                                    </td>
                                    <td class="th-sm">
                                        <span>{{$document->course->name}}</span>
                                    </td>
                                    <td class="th-sm">
                                        <span>{{($document->first_month_score != null)?$document->first_month_score:"---"}}</span>
                                    </td>
                                    <td class="th-sm">
                                        <span>{{($document->second_month_score != null)?$document->second_month_score:"---"}}</span>
                                    </td>
                                    <td class="th-sm">
                                        <span>{{($document->assessment_score != null)?$document->assessment_score:"---"}}</span>
                                    </td>
                                    <td class="th-sm">
                                        <span>{{($document->final_first_score != null)?$document->final_first_score:"---"}}</span>
                                    </td>
                                    <td class="th-sm">
                                        <span>{{($document->final_second_score != null)?$document->final_second_score:"---"}}</span>
                                    </td>
                                    <td class="th-sm">
                                        <span>{{$document->first_month_score + $document->second_month_score + $document->assessment_score + $document->final_first_score}}</span>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{--Button Print --}}
                    <div class="col-12 mb-5">
                        <button class="btn btn-outline-deep-purple font-weight-bold">طباعة</button>
                        <a class="btn btn-outline-deep-purple font-weight-bold" href="/control-panel/pdf/{{$student->id}}/{{$year}}/{{$season}}">
                            <span class="far fa-file-pdf"></span>
                            <span>pdf</span>
                        </a>
                    </div>
                </div>
            @endforeach
        @endforeach
    </div>
@endsection