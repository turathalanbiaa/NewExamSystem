@extends("ControlPanel.layout.app")

@section("title")
    <title>الاساتذة</title>
@endsection

@section("content")
    <div class="container pt-4">
        <div class="row">
            <div class="col-12">
                <a href="/control-panel/lecturers/create" class="btn btn-outline-secondary">
                    <i class="fa fa-plus ml-1"></i>
                    <span>اضافة</span>
                </a>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <table id="dtLecturers" class="table table-striped table-bordered table-hover w-100" cellspacing="0">
                    <thead class="secondary-color text-white">
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
                            <td>{{\App\Enums\LecturerState::getState($lecturer->state)}}</td>
                            <td class="text-center">
                                <a class="btn btn-sm btn-dark-green mx-0" href="/control-panel/lecturers/{{$lecturer->id}}" data-toggle="tooltip" title="عرض جميع المعلومات">
                                    <i class="fa fa-info-circle"></i>
                                </a>

                                <a class="btn btn-sm btn-indigo mx-2" href="/control-panel/lecturers/{{$lecturer->id}}/edit?type=change-info" data-toggle="tooltip" title="تحرير معلومات الحساب">
                                    <i class="fa fa-pencil-alt"></i>
                                </a>

                                <a class="btn btn-sm btn-amber mx-0" href="/control-panel/lecturers/{{$lecturer->id}}/edit?type=change-password" data-toggle="tooltip" title="تغيير كلمة المرور">
                                    <i class="fa fa-unlock-alt"></i>
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
            $('#dtLecturers').DataTable();
            $('.dataTables_length').addClass('bs-select');
            $("div#dtLecturers_wrapper>.row:first-child").css("direction","ltr");
            $("div#dtLecturers_wrapper>.row:first-child").css("text-align","left");

            // Tooltips Initialization
            $('[data-toggle="tooltip"]').tooltip()
        });
    </script>
@endsection