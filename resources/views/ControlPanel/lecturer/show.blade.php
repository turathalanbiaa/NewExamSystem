@extends("ControlPanel.layout.app")

@section("title")
    <title>{{$lecturer->name}}</title>
@endsection

@section("content")
    <div class="container">
        <div class="row">
            <div class="col-12">
                {{-- Nav Tabs --}}
                <ul class="nav nav-tabs nav-justified shadow default-color p-2" role="tablist">
                    {{-- Nav Item Events Log --}}
                    <li class="nav-item">
                        <a class="nav-link btn-default active" data-toggle="tab" href="#event-log" role="tab">
                            <i class="fa fa-heart pl-2"></i>
                            <span>سجل الاحداث</span>
                        </a>
                    </li>

                    {{-- Nav Item Profile --}}
                    <li class="nav-item">
                        <a class="nav-link btn-default" data-toggle="tab" href="#profile" role="tab">
                            <i class="fa fa-user pl-2"></i>
                            {{$lecturer->name}}
                        </a>
                    </li>
                </ul>

                {{-- Tab Panels --}}
                <div class="tab-content">
                    {{-- Panel Events Log --}}
                    <div class="tab-pane fade in show active" id="event-log" role="tabpanel">
                        <div class="row mt-2">
                            {{-- Nav Tabs --}}
                            <div class="col-md-3">
                                <ul class="nav flex-column p-0 pl-4" role="tablist">
                                    <li class="nav-item mb-1">
                                        <a class="nav-link btn btn-default btn-block active" data-toggle="tab" href="#latest-event-log" role="tab">
                                            <span>آخر الاحداث</span>
                                        </a>
                                    </li>

                                    <li class="nav-item mb-1">
                                        <a class="nav-link btn btn-default btn-block" data-toggle="tab" href="#profile-event-log" role="tab">
                                            <span>الملف الشخصي</span>
                                        </a>
                                    </li>

                                    <li class="nav-item mb-1">
                                        <a class="nav-link btn btn-default btn-block" data-toggle="tab" href="#courses-event-log" role="tab">
                                            <span>المواد الدراسية</span>
                                        </a>
                                    </li>

                                    <li class="nav-item mb-1">
                                        <a class="nav-link btn btn-default btn-block" data-toggle="tab" href="#assessments-event-log" role="tab">
                                            <span>تقييم الطلاب</span>
                                        </a>
                                    </li>

                                    <li class="nav-item mb-1">
                                        <a class="nav-link btn btn-default btn-block" data-toggle="tab" href="#exams-event-log" role="tab">
                                            <span>الامتحانات</span>
                                        </a>
                                    </li>

                                    <li class="nav-item mb-1">
                                        <a class="nav-link btn btn-default btn-block" data-toggle="tab" href="#questions-event-log" role="tab">
                                            <span>الاسئلة</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>

                            {{-- Tab Panels --}}
                            <div class="col-md-9">
                                <div class="tab-content vertical">
                                    <div class="tab-pane fade in show active" id="latest-event-log" role="tabpanel">
                                        <table data-table="dtEventLog" class="table table-striped table-bordered table-hover w-100" cellspacing="0">
                                            <thead class="default-color text-white">
                                                <tr>
                                                    <th class="th-sm fa d-table-cell">
                                                        <span>رقم الحدث</span>
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
                                                @foreach($events as $event)
                                                    <tr>
                                                        <td>{{$event->id}}</td>
                                                        <td>{{$event->event}}</td>
                                                        <td>{{$event->time}}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="tab-pane fade" id="profile-event-log" role="tabpanel">
                                        <table data-table="dtEventLog" class="table table-striped table-bordered table-hover w-100" cellspacing="0">
                                            <thead class="default-color text-white">
                                                <tr>
                                                    <th class="th-sm fa d-table-cell">
                                                        <span>رقم الحدث</span>
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
                                                @foreach($events as $event)
                                                    @if($event->type == \App\Enums\EventLogType::LECTURER)
                                                        <tr>
                                                            <td>{{$event->id}}</td>
                                                            <td>{{$event->event}}</td>
                                                            <td>{{$event->time}}</td>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="tab-pane fade" id="courses-event-log" role="tabpanel">
                                        <table data-table="dtEventLog" class="table table-striped table-bordered table-hover w-100" cellspacing="0">
                                            <thead class="default-color text-white">
                                            <tr>
                                                <th class="th-sm fa d-table-cell">
                                                    <span>رقم الحدث</span>
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
                                                @foreach($events as $event)
                                                    @if($event->type == \App\Enums\EventLogType::COURSE)
                                                        <tr>
                                                            <td>{{$event->id}}</td>
                                                            <td>{{$event->event}}</td>
                                                            <td>{{$event->time}}</td>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="tab-pane fade" id="assessments-event-log" role="tabpanel">
                                        <table data-table="dtEventLog" class="table table-striped table-bordered table-hover w-100" cellspacing="0">
                                            <thead class="default-color text-white">
                                            <tr>
                                                <th class="th-sm fa d-table-cell">
                                                    <span>رقم الحدث</span>
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
                                                @foreach($events as $event)
                                                    @if($event->type == \App\Enums\EventLogType::COURSE)
                                                        <tr>
                                                            <td>{{$event->id}}</td>
                                                            <td>{{$event->event}}</td>
                                                            <td>{{$event->time}}</td>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="tab-pane fade" id="exams-event-log" role="tabpanel">
                                        <table data-table="dtEventLog" class="table table-striped table-bordered table-hover w-100" cellspacing="0">
                                            <thead class="default-color text-white">
                                            <tr>
                                                <th class="th-sm fa d-table-cell">
                                                    <span>رقم الحدث</span>
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
                                                @foreach($events as $event)
                                                    @if($event->type == \App\Enums\EventLogType::EXAM)
                                                        <tr>
                                                            <td>{{$event->id}}</td>
                                                            <td>{{$event->event}}</td>
                                                            <td>{{$event->time}}</td>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="tab-pane fade" id="questions-event-log" role="tabpanel">
                                        <table data-table="dtEventLog" class="table table-striped table-bordered table-hover w-100" cellspacing="0">
                                            <thead class="default-color text-white">
                                            <tr>
                                                <th class="th-sm fa d-table-cell">
                                                    <span>رقم الحدث</span>
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
                                                @foreach($events as $event)
                                                    @if(($event->type == \App\Enums\EventLogType::QUESTION) || ($event->type == \App\Enums\EventLogType::BRANCH))
                                                        <tr>
                                                            <td>{{$event->id}}</td>
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

                    {{-- Panel Profile --}}
                    <div class="tab-pane fade" id="profile" role="tabpanel">
                        <div class="card rounded-0 py-3 shadow">
                            <div class="card-body">
                                <h5>
                                    <span>الاسم الحقيقي:</span>
                                    {{$lecturer->name}}
                                </h5>
                                <h5>
                                    <span>اسم المستخدم:</span>
                                    {{$lecturer->username}}
                                </h5>
                                <h5>
                                    <span>حالة الحساب:</span>
                                    {{\App\Enums\AccountState::getState($lecturer->state)}}
                                </h5>

                                <div class="mt-4">
                                    <a href="/control-panel/lecturers/{{$lecturer->id}}/edit?type=change-info" class="btn btn-outline-default font-weight-bold">
                                        <i class="fa fa-pencil-alt ml-1"></i>
                                        <span>تعديل الحساب</span>
                                    </a>

                                    <a href="/control-panel/lecturers/{{$lecturer->id}}/edit?type=change-password" class="btn btn-outline-default font-weight-bold">
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
    </div>
@endsection

@section("script")
    <script>
        $(document).ready(function () {
            // DataTable Initialization
            $('table[data-table="dtEventLog"]').DataTable({"ordering": false});
            $('.dataTables_length').addClass('bs-select');
            $("div.dataTables_wrapper>.row:first-child").css("direction","ltr");
            $("div.dataTables_wrapper>.row:first-child").css("text-align","left");
        });
    </script>
@endsection