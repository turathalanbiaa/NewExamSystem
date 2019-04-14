@extends("ControlPanel.layout.app")

@section("title")
    <title>بحث عن طالب</title>
@endsection

@section("content")
    <div class="container">
        {{-- Students DataTable --}}
        <div class="row">
            <div class="col-12">
                <table id="dtStudents" class="table table-striped table-bordered table-hover w-100" cellspacing="0">
                    <thead class="default-color text-white">
                    <tr>
                        <th class="th-sm fa d-table-cell">
                            <span>رقم</span>
                        </th>
                        <th class="th-sm fa d-table-cell">
                            <span>الطالب</span>
                        </th>
                        <th class="th-sm fa d-table-cell">
                            <span>المستوى الدراسي</span>
                        </th>
                        <th class="th-sm fa d-table-cell">
                            <span>خيارات</span>
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($students as $student)
                        <tr>
                            <td class="align-baseline">{{$student->id}}</td>
                            <td class="align-baseline">{{$student->originalStudent->Name}}</td>
                            <td class="align-baseline">{{\App\Enums\Level::get($student->originalStudent->Level)}}</td>
                            <td class="align-baseline text-center">
                                <a class="btn btn-outline-dark m-1" href="/control-panel/documents/{{$student->id}}">
                                    <i class="far fa-file-alt ml-1"></i>
                                    <span>عرض الوثائق</span>
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

@section("script")
    <script>
        $(document).ready(function () {
            // DataTable Initialization
            $('#dtStudents').DataTable();
            $('.dataTables_length').addClass('bs-select');
            let firstChild = $("div#dtStudents_wrapper>.row:first-child");
            firstChild.css("direction","ltr");
            firstChild.css("text-align","left");

            // Tooltips Initialization
            $('[rel="tooltip"]').tooltip()
        });
    </script>
@endsection