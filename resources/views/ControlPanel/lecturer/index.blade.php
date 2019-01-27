@extends("ControlPanel.layout.app")

@section("title")
    <title>الاساتذة</title>
@endsection

@section("content")
    <div class="container">
        {{-- Session Update Lecturer Message --}}
        @if (session('UpdateLecturerMessage'))
            <div class="row">
                <div class="col-12">
                    <div class="alert alert-success text-center my-3">
                        {{session('UpdateLecturerMessage')}}
                    </div>
                </div>
            </div>
        @endif

        {{-- Session Archive Lecturer Message --}}
        @if (session('ArchiveLecturerMessage'))
            <div class="row">
                <div class="col-12">
                    <div class="alert {{(session('TypeMessage')=="Error")?"alert-danger":"alert-success"}} text-center my-3">
                        {{session('ArchiveLecturerMessage')}}
                    </div>
                </div>
            </div>
        @endif

        {{-- Button Create --}}
        <div class="row">
            <div class="col-12">
                <a href="/control-panel/lecturers/create" class="btn btn-outline-default font-weight-bold">
                    <i class="fa fa-plus ml-1"></i>
                    <span>اضافة</span>
                </a>
            </div>
        </div>

        {{-- Lecturers DataTable --}}
        <div class="row">
            <div class="col-12">
                <table id="dtLecturers" class="table table-striped table-bordered table-hover w-100" cellspacing="0">
                    <thead class="default-color text-white">
                        <tr>
                            <th class="th-sm fa d-table-cell">
                                <span>رقم</span>
                            </th>
                            <th class="th-sm fa d-table-cell">
                                <span>الاسم الحقيقي</span>
                            </th>
                            <th class="th-sm fa d-table-cell">
                                <span>اسم المستخدم</span>
                            </th>
                            <th class="th-sm fa d-table-cell">
                                <span>حالة الحساب</span>
                            </th>
                            <th class="th-sm fa d-table-cell">
                                <span>خيارات</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($lecturers as $lecturer)
                            <tr>
                                <td>{{$lecturer->id}}</td>
                                <td>{{$lecturer->name}}</td>
                                <td>{{$lecturer->username}}</td>
                                <td>{{\App\Enums\AccountState::getState($lecturer->state)}}</td>
                                <td class="text-center">
                                    <a class="btn btn-sm btn-outline-dark m-1" href="/control-panel/lecturers/{{$lecturer->id}}" rel="tooltip" title="مزيد من المعلومات">
                                        <i class="fa fa-info-circle"></i>
                                    </a>

                                    <a class="btn btn-sm btn-outline-dark m-1" href="/control-panel/lecturers/{{$lecturer->id}}/edit?type=change-info" rel="tooltip" title="تحرير معلومات الحساب">
                                        <i class="fa fa-pencil-alt"></i>
                                    </a>

                                    <a class="btn btn-sm btn-outline-dark m-1" href="/control-panel/lecturers/{{$lecturer->id}}/edit?type=change-password" rel="tooltip" title="تغيير كلمة المرور">
                                        <i class="fa fa-unlock-alt"></i>
                                    </a>

                                    @if($lecturer->state == \App\Enums\AccountState::OPEN)
                                        <a class="btn btn-sm btn-outline-dark m-1" href="#modelArchiveLecturer" rel="tooltip" title="ارشفة الحساب" data-toggle="modal" data-action="fillArchiveLecturerForm" data-lecturer-id="{{$lecturer->id}}" data-lecturer-name="{{$lecturer->name}}">
                                            <i class="fa fa-file-archive"></i>
                                        </a>
                                    @else
                                        <button class="btn btn-sm btn-outline-dark m-1" rel="tooltip" title="هذا الحساب مغلق">
                                            <i class="fa fa-file-archive"></i>
                                        </button>
                                    @endif
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
    {{-- Archive Lecturer Modal --}}
    <div class="modal fade" id="modelArchiveLecturer" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-notify modal-danger" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <p class="heading lead">اسم الاستاذ</p>

                    <a href="javascript:void(0)" class="close ml-0" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" class="white-text">&times;</span>
                    </a>
                </div>

                <div class="modal-body">
                    <div class="text-center">
                        <i class="fa fa-lock fa-4x mb-3 animated fadeIn"></i>
                        <h2 class="text-danger">هل تريد ارشفة الحساب</h2>
                        <p>بعد ارشفة الحساب يستطيع هذا الاستاذ تسجيل الدخول فقط.</p>
                    </div>
                </div>

                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-danger font-weight-bold" onclick="$('form#archiveLecturer').submit();">ارشفة الحساب</button>
                    <button type="button" class="btn btn-outline-danger font-weight-bold" data-dismiss="modal">لا شكرا</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Archive Lecturer Form --}}
    <form id="archiveLecturer" method="post" action="">
        @csrf
        @method("DELETE")
    </form>
@endsection

@section("script")
    <script>
        $(document).ready(function () {
            // DataTable Initialization
            $('#dtLecturers').DataTable();
            $('.dataTables_length').addClass('bs-select');
            $("div#dtLecturers_wrapper>.row:first-child").css("direction","ltr");
            $("div#dtLecturers_wrapper>.row:first-child").css("text-align","left");

            // Tooltips Initialization
            $('[rel="tooltip"]').tooltip()
        });

        $("[data-action='fillArchiveLecturerForm']").click(function () {
            //For Fill Modal
            let lecturerName = $(this).data("lecturer-name");
            $("#modelArchiveLecturer .heading.lead").html(lecturerName);

            //For Fill Form
            let lecturerId = $(this).data("lecturer-id");
            $("form#archiveLecturer").attr("action","/control-panel/lecturers/" + lecturerId);
        });
    </script>
@endsection