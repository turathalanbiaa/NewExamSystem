@extends("ControlPanel.layout.app")

@section("title")
    <title>ادارة الحسابات</title>
@endsection

@section("content")
    <div class="container pt-3">
        <div class="row">
            <div class="col-12">
                <table id="dtAdmins" class="table table-striped table-bordered table-hover w-100" cellspacing="0">
                    <thead>
                    <tr>
                        <th class="th-sm fa d-table-cell">
                            <span>رقم الحساب</span>
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
                            <span>تاريخ</span>
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
                            <td>{{$admin->date}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                    <tfoot>
                    <tr>
                        <th>رقم الحساب</th>
                        <th>الاسم الحقيقي</th>
                        <th>اسم المستخدم</th>
                        <th>نوع الحساب</th>
                        <th>اسم الاستاذ</th>
                        <th>حالة الحساب</th>
                        <th>تاريخ</th>
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
            $('#dtAdmins').DataTable();
            $('.dataTables_length').addClass('bs-select');
            $("div#dtAdmins_wrapper>.row:first-child").css("direction","ltr");
            $("div#dtAdmins_wrapper>.row:first-child").css("text-align","left");
        });
    </script>
@endsection