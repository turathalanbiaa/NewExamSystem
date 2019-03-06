@extends("ControlPanel.layout.app")

@section("title")
    <title>المدراء</title>
@endsection

@section("content")
    <div class="container">
        {{-- Session Update Admin Message --}}
        @if (session('UpdateAdminMessage'))
            <div class="row">
                <div class="col-12">
                    <div class="alert alert-success text-center">
                        {{session('UpdateAdminMessage')}}
                    </div>
                </div>
            </div>
        @endif

        {{-- Session Archive Admin Message --}}
        @if (session('ArchiveAdminMessage'))
            <div class="row">
                <div class="col-12">
                    <div class="alert {{(session('TypeMessage')=="Error")?"alert-danger":"alert-success"}} text-center">
                        {{session('ArchiveAdminMessage')}}
                    </div>
                </div>
            </div>
        @endif

        {{-- Button Create --}}
        <div class="row">
            <div class="col-12">
                <a href="/control-panel/admins/create" class="btn btn-outline-default font-weight-bold">
                    <i class="fa fa-plus ml-1"></i>
                    <span>اضافة</span>
                </a>
            </div>
        </div>

        {{-- Admins DataTable --}}
        <div class="row">
            <div class="col-12">
                <table id="dtAdmins" class="table table-striped table-bordered table-hover w-100" cellspacing="0">
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
                        @foreach($admins as $admin)
                            <tr>
                                <td class="align-baseline">{{$admin->id}}</td>
                                <td class="align-baseline">{{$admin->name}}</td>
                                <td class="align-baseline">{{$admin->username}}</td>
                                <td class="align-baseline">{{\App\Enums\AccountState::getState($admin->state)}}</td>
                                <td class="align-baseline text-center">
                                    <a class="btn btn-sm btn-outline-dark m-1" href="/control-panel/admins/{{$admin->id}}" rel="tooltip" title="مزيد من المعلومات">
                                        <i class="fa fa-info-circle"></i>
                                    </a>

                                    <a class="btn btn-sm btn-outline-dark m-1" href="/control-panel/admins/{{$admin->id}}/edit?type=change-info" rel="tooltip" title="تحرير معلومات الحساب">
                                        <i class="fa fa-pencil-alt"></i>
                                    </a>

                                    <a class="btn btn-sm btn-outline-dark m-1" href="/control-panel/admins/{{$admin->id}}/edit?type=change-password" rel="tooltip" title="تغيير كلمة المرور">
                                        <i class="fa fa-unlock-alt"></i>
                                    </a>

                                    @if($admin->state == \App\Enums\AccountState::OPEN)
                                        <a class="btn btn-sm btn-outline-dark m-1" href="#modelArchiveAdmin" rel="tooltip" title="ارشفة الحساب" data-toggle="modal" data-action="fillArchiveAdminForm" data-admin-id="{{$admin->id}}" data-admin-name="{{$admin->name}}">
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
    {{-- Archive Admin Modal --}}
    <div class="modal fade" id="modelArchiveAdmin" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-notify modal-danger" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <p class="heading lead">اسم المدير</p>

                    <a href="javascript:void(0)" class="close ml-0" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" class="white-text">&times;</span>
                    </a>
                </div>

                <div class="modal-body">
                    <div class="text-center">
                        <i class="fa fa-lock fa-4x mb-3 animated fadeIn"></i>
                        <h2 class="text-danger">هل تريد ارشفة الحساب</h2>
                        <p>بعد ارشفة الحساب يستطيع هذا المدير تسجيل الدخول فقط.</p>
                    </div>
                </div>

                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-danger font-weight-bold" onclick="$('form#archiveAdmin').submit();">ارشفة الحساب</button>
                    <button type="button" class="btn btn-outline-danger font-weight-bold" data-dismiss="modal">لا شكرا</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Archive Admin Form --}}
    <form id="archiveAdmin" method="post" action="">
        @csrf
        @method("DELETE")
    </form>
@endsection

@section("script")
    <script>
        $(document).ready(function () {
            // DataTable Initialization
            $('#dtAdmins').DataTable();
            $('.dataTables_length').addClass('bs-select');
            let firstChild = $("div#dtAdmins_wrapper>.row:first-child");
            firstChild.css("direction","ltr");
            firstChild.css("text-align","left");

            // Tooltips Initialization
            $('[rel="tooltip"]').tooltip()
        });

        $("[data-action='fillArchiveAdminForm']").click(function () {
            //For Fill Modal
            let adminName = $(this).data("admin-name");
            $("#modelArchiveAdmin .heading.lead").html(adminName);

            //For Fill Form
            let adminId = $(this).data("admin-id");
            $("form#archiveAdmin").attr("action","/control-panel/admins/" + adminId);
        });
    </script>
@endsection