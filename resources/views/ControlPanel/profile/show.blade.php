@extends("ControlPanel.layout.app")

@section("title")
    <title>{{$account->name}}</title>
@endsection

@section("content")
    <div class="container pt-4">
        <div class="row">
            <div class="col-12">
                <!-- Nav tabs -->
                <ul class="nav nav-tabs nav-justified shadow secondary-color p-3" role="tablist">
                    <!-- Nav item events log -->
                    <li class="nav-item">
                        <a class="nav-link btn-secondary active" data-toggle="tab" href="#events-log" role="tab">
                            <i class="fa fa-heart pl-2"></i>
                            <span>سجل الاحداث</span>
                        </a>
                    </li>
                    <!-- Nav item profile -->
                    <li class="nav-item">
                        <a class="nav-link btn-secondary" data-toggle="tab" href="#profile" role="tab">
                            <i class="fa fa-user pl-2"></i>
                            <span>الملف الشخصي</span>
                        </a>
                    </li>
                </ul>

                <!-- Tab panels -->
                <div class="tab-content">
                    <!-- Panel events log -->
                    <div class="tab-pane fade in show active" id="events-log" role="tabpanel">
                        <div class="row mt-2">
                            <!-- Nav tabs  -->
                            <div class="col-md-3">
                                <ul class="nav flex-column" role="tablist" style="padding: 0 0 0 40px;">
                                    <li class="nav-item mb-1">
                                        <a class="nav-link btn btn-secondary btn-block active" data-toggle="tab" href="#last-event" role="tab">
                                            <span>آخر الاحداث</span>
                                        </a>
                                    </li>

                                    <li class="nav-item mb-1">
                                        <a class="nav-link btn btn-secondary btn-block" data-toggle="tab" href="#admin-event" role="tab">
                                            <span>ادارة الحسابات</span>
                                        </a>
                                    </li>

                                    <li class="nav-item mb-1">
                                        <a class="nav-link btn btn-secondary btn-block" data-toggle="tab" href="#lecturer-event" role="tab">
                                            <span>الاساتذة</span>
                                        </a>
                                    </li>

                                    <li class="nav-item mb-1">
                                        <a class="nav-link btn btn-secondary btn-block" data-toggle="tab" href="#course-event" role="tab">
                                            <span>الدورات</span>
                                        </a>
                                    </li>

                                    <li class="nav-item mb-1">
                                        <a class="nav-link btn btn-secondary btn-block" data-toggle="tab" href="#exam-event" role="tab">
                                            <span>الامتحانات</span>
                                        </a>
                                    </li>

                                    <li class="nav-item">
                                        <a class="nav-link btn btn-secondary btn-block" data-toggle="tab" href="#exam-event" role="tab">
                                            <span>اسئلة الامتحانات</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>

                            <!-- Tab panels -->
                            <div class="col-md-9">
                                <div class="tab-content vertical">
                                    <div class="tab-pane fade in show active" id="last-event" role="tabpanel">
                                        <table data-table="dtEvents" class="table table-striped table-bordered table-hover w-100" cellspacing="0">
                                            <thead class="secondary-color text-white">
                                            <tr>
                                                <th class="th-sm fa d-table-cell">
                                                    <span>رقم</span>
                                                </th>
                                                <th class="th-sm fa d-table-cell">
                                                    <span>النوع</span>
                                                </th>
                                                <th class="th-sm fa d-table-cell">
                                                    <span>الحدث</span>
                                                </th>
                                                <th class="th-sm fa d-table-cell">
                                                    <span>التاريخ والوقت</span>
                                                </th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @php $i=0; @endphp
                                            @foreach($events as $event)
                                                <tr data-content="{{$event->id}}">
                                                    <td>{{++$i}}</td>
                                                    <td>{{\App\Enums\EventLogType::getType($event->type)}}</td>
                                                    <td>{{$event->event}}</td>
                                                    <td>{{$event->time}}</td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="tab-pane fade" id="admin-event" role="tabpanel">
                                        <table data-table="dtEvents" class="table table-striped table-bordered table-hover w-100" cellspacing="0">
                                            <thead class="secondary-color text-white">
                                            <tr>
                                                <th class="th-sm fa d-table-cell">
                                                    <span>رقم</span>
                                                </th>
                                                <th class="th-sm fa d-table-cell">
                                                    <span>الحدث</span>
                                                </th>
                                                <th class="th-sm fa d-table-cell">
                                                    <span>التاريخ والوقت</span>
                                                </th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @php $i=0; @endphp
                                            @foreach($events as $event)
                                                @if($event->type == \App\Enums\EventLogType::ADMIN)
                                                    <tr data-content="{{$event->id}}">
                                                        <td>{{++$i}}</td>
                                                        <td>{{$event->event}}</td>
                                                        <td>{{$event->time}}</td>
                                                    </tr>
                                                @endif
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="tab-pane fade" id="lecturer-event" role="tabpanel">
                                        <table data-table="dtEvents" class="table table-striped table-bordered table-hover w-100" cellspacing="0">
                                            <thead class="secondary-color text-white">
                                            <tr>
                                                <th class="th-sm fa d-table-cell">
                                                    <span>رقم</span>
                                                </th>
                                                <th class="th-sm fa d-table-cell">
                                                    <span>الحدث</span>
                                                </th>
                                                <th class="th-sm fa d-table-cell">
                                                    <span>التاريخ والوقت</span>
                                                </th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @php $i=0; @endphp
                                            @foreach($events as $event)
                                                @if($event->type == \App\Enums\EventLogType::LECTURER)
                                                    <tr data-content="{{$event->id}}">
                                                        <td>{{++$i}}</td>
                                                        <td>{{$event->event}}</td>
                                                        <td>{{$event->time}}</td>
                                                    </tr>
                                                @endif
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="tab-pane fade" id="course-event" role="tabpanel">
                                        <table data-table="dtEvents" class="table table-striped table-bordered table-hover w-100" cellspacing="0">
                                            <thead class="secondary-color text-white">
                                            <tr>
                                                <th class="th-sm fa d-table-cell">
                                                    <span>رقم</span>
                                                </th>
                                                <th class="th-sm fa d-table-cell">
                                                    <span>الحدث</span>
                                                </th>
                                                <th class="th-sm fa d-table-cell">
                                                    <span>التاريخ والوقت</span>
                                                </th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @php $i=0; @endphp
                                            @foreach($events as $event)
                                                @if($event->type == \App\Enums\EventLogType::COURSE)
                                                    <tr data-content="{{$event->id}}">
                                                        <td>{{++$i}}</td>
                                                        <td>{{$event->event}}</td>
                                                        <td>{{$event->time}}</td>
                                                    </tr>
                                                @endif
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="tab-pane fade" id="exam-event" role="tabpanel">
                                        <table data-table="dtEvents" class="table table-striped table-bordered table-hover w-100" cellspacing="0">
                                            <thead class="secondary-color text-white">
                                            <tr>
                                                <th class="th-sm fa d-table-cell">
                                                    <span>رقم</span>
                                                </th>
                                                <th class="th-sm fa d-table-cell">
                                                    <span>الحدث</span>
                                                </th>
                                                <th class="th-sm fa d-table-cell">
                                                    <span>التاريخ والوقت</span>
                                                </th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @php $i=0; @endphp
                                            @foreach($events as $event)
                                                @if(($event->type == \App\Enums\EventLogType::ROOT_EXAM) || ($event->type == \App\Enums\EventLogType::EXAM))
                                                    <tr data-content="{{$event->id}}">
                                                        <td>{{++$i}}</td>
                                                        <td>{{$event->event}}</td>
                                                        <td>{{$event->time}}</td>
                                                    </tr>
                                                @endif
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="tab-pane fade" id="question-event" role="tabpanel">
                                        <table data-table="dtEvents" class="table table-striped table-bordered table-hover w-100" cellspacing="0">
                                            <thead class="secondary-color text-white">
                                            <tr>
                                                <th class="th-sm fa d-table-cell">
                                                    <span>رقم</span>
                                                </th>
                                                <th class="th-sm fa d-table-cell">
                                                    <span>الحدث</span>
                                                </th>
                                                <th class="th-sm fa d-table-cell">
                                                    <span>التاريخ والوقت</span>
                                                </th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @php $i=0; @endphp
                                            @foreach($events as $event)
                                                @if(($event->type == \App\Enums\EventLogType::ROOT_QUESTION) || ($event->type == \App\Enums\EventLogType::QUESTION))
                                                    <tr data-content="{{$event->id}}">
                                                        <td>{{++$i}}</td>
                                                        <td>{{$event->event}}</td>
                                                        <td>{{$event->time}}</td>
                                                    </tr>
                                                @endif
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Panel profile -->
                    <div class="tab-pane fade" id="profile" role="tabpanel">
                        <div class="card rounded-0 py-3 shadow">
                            <div class="card-body">
                                <h5>
                                    <span>الاسم الحقيقي:</span>
                                    {{$account->name}}
                                </h5>
                                <h5>
                                    <span>اسم المستخدم:</span>
                                    {{$account->username}}
                                </h5>
                                <h5>
                                    <span>حالة الحساب:</span>
                                    @if(session()->get("EXAM_SYSTEM_ACCOUNT_TYPE") == \App\Enums\AccountType::MANAGER))
                                        {{\App\Enums\AdminState::getState($account->state)}}
                                    @else
                                        {{\App\Enums\LecturerState::getState($account->state)}}
                                    @endif
                                </h5>

                                <div class="p-2"></div>

                                <a href="/control-panel/profile/{{$account->id}}/edit?type=change-info" class="btn btn-indigo">
                                    <i class="fa fa-pencil-alt ml-1"></i>
                                    <span>تعديل الحساب</span>
                                </a>

                                <a href="/control-panel/profile/{{$account->id}}/edit?type=change-password" class="btn btn-amber">
                                    <i class="fa fa-unlock-alt ml-1"></i>
                                    <span>تغيير كلمة المرور</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section("script")
    <script>
        $(document).ready(function () {
            // DataTable Initialization
            $('table[data-table="dtEvents"]').DataTable({"ordering": false});
            $('.dataTables_length').addClass('bs-select');
            $("div.dataTables_wrapper>.row:first-child").css("direction","ltr");
            $("div.dataTables_wrapper>.row:first-child").css("text-align","left");
        });
    </script>
@endsection