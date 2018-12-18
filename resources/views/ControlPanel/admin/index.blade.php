@extends("ControlPanel.layout.app")

@section("title")
    <title>ادارة الحسابات</title>
@endsection

@section("content")
    <div class="container pt-4">
        <div class="row">
            <div class="col-12">
                <a href="/control-panel/admins/create" class="btn btn-default">
                    <i class="fa fa-plus ml-1"></i>
                    <span>اضافة</span>
                </a>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <table id="dtAdmins" class="table table-striped table-bordered table-hover w-100" cellspacing="0">
                    <thead>
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
                            <span>نوع الحساب</span>
                        </th>
                        <th class="th-sm fa d-table-cell">
                            <span title="سوف يضهر اسم الاستاذ اذا كان نوع الحساب هو خاص للاستاذ">اسم الاستاذ</span>
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
                            <td>{{$admin->id}}</td>
                            <td>{{$admin->name}}</td>
                            <td>{{$admin->username}}</td>
                            <td>{{\App\Enums\AdminType::getType($admin->type)}}</td>
                            <td>{{$admin->getLecturerName()}}</td>
                            <td>{{\App\Enums\AdminState::getState($admin->state)}}</td>
                            <td class="text-center">
                                <div class="btn-group btn-group-sm" role="group" aria-label="Basic example">
                                    <a href="/control-panel/admins/{{$admin->id}}" class="btn btn-dark-green mx-0" data-toggle="tooltip" title="عرض جميع المعلومات">
                                        <i class="fa fa-info-circle"></i>
                                    </a>

                                    <a href="/control-panel/admins/{{$admin->id}}/edit?type=change-info" class="btn btn-indigo mx-2" data-toggle="tooltip" title="تحرير معلومات الحساب">
                                        <i class="fa fa-pencil-alt"></i>
                                    </a>

                                    <a href="/control-panel/admins/{{$admin->id}}/edit?type=change-password" class="btn btn-amber mx-0" data-toggle="tooltip" title="تغيير كلمة المرور">
                                        <i class="fa fa-unlock-alt"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                    <tfoot>
                    <tr>
                        <th>رقم</th>
                        <th>الاسم الحقيقي</th>
                        <th>اسم المستخدم</th>
                        <th>نوع الحساب</th>
                        <th>اسم الاستاذ</th>
                        <th>حالة الحساب</th>
                        <th>خيارات</th>
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
@endsection

@section("script")
    <script>
        $(document).ready(function () {
            // DataTable Initialization
            $('#dtAdmins').DataTable();
            $('.dataTables_length').addClass('bs-select');
            $("div#dtAdmins_wrapper>.row:first-child").css("direction","ltr");
            $("div#dtAdmins_wrapper>.row:first-child").css("text-align","left");

            // Tooltips Initialization
            $('[data-toggle="tooltip"]').tooltip()
        });
    </script>
@endsection