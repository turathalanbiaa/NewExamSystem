@extends("ControlPanel.layout.app")

@section("title")
    <title>{{$student->originalStudent->Name}}</title>
@endsection

@section("content")
    <div class="container">
        {{-- Documents --}}
        @php $groupingDocumentsByYear = $student->documents->SortByDesc("year")->groupBy("year"); @endphp
        @foreach($groupingDocumentsByYear as $year => $notGroupingDocumentsBySeason)
            @php $groupingDocumentsBySeason = $notGroupingDocumentsBySeason->SortByDesc("season")->groupBy("season"); @endphp
            @foreach($groupingDocumentsBySeason as $season => $documents)
                <div class="row">
                    {{-- Heading --}}
                    <div class="col-12">
                        <h4 class="text-center">
                            <span>{{\App\Enums\Level::get($documents[0]->course->level)}}</span>
                            <span>{{($season==1)?"في النصف الاول":"في النصف الثاني"}}</span>
                            <span>{{" من سنة " . $year}}</span>
                        </h4>
                    </div>

                    {{-- Body --}}
                    <div class="col-12">
                        <table class="table table-striped table-bordered text-center table-sm table-hover table-responsive-xl" cellspacing="0">
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
                                    <span>المجموع</span>
                                </th>

                                <th class="th-sm font-weight-bold align-middle" rowspan="2">
                                    <span>درجة القرار</span>
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
                                        <span>{{$document->total}}</span>
                                    </td>
                                    <td class="th-sm">
                                        <span>{{$document->decision_score}}</span>
                                    </td><td class="th-sm">
                                        <span>{{$document->final_score}}</span>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Footer --}}
                    <div class="col-12 mb-5">
                        <a class="btn btn-danger font-weight-bold" href="/control-panel/pdf/{{$student->id}}/{{$year}}/{{$season}}" data-toggle="tooltip"  title="بكل الدرجات">
                            <span class="far fa-file-pdf ml-1"></span>
                            <span>تصدير ملف pdf</span>
                        </a>
                        <a class="btn btn-outline-danger font-weight-bold" href="/control-panel/pdf/{{$student->id}}/{{$year}}/{{$season}}" data-toggle="tooltip" title="بالدرجات النهائية فقط">
                            <span class="far fa-file-pdf ml-1"></span>
                            <span>تصدير ملف pdf</span>
                        </a>
                    </div>
                </div>
            @endforeach
        @endforeach
    </div>
@endsection

@section("script")
    <script>
        // Tooltips Initialization
        $(function () {
            $('[data-toggle="tooltip"]').tooltip()
        })
    </script>
@endsection