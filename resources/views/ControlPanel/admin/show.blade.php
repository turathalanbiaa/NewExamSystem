@extends("ControlPanel.layout.app")

@section("title")
    <title>بيانات الحساب</title>
@endsection

@section("content")
    <div class="container pt-4">
        <div class="row">
            <div class="col-12">
                <!-- Nav tabs -->
                <ul class="nav nav-tabs nav-justified shadow default-color p-3" role="tablist">
                    <!-- Nav item profile -->
                    <li class="nav-item">
                        <a class="nav-link btn-default active" data-toggle="tab" href="#profile" role="tab">
                            <i class="fa fa-user pl-2"></i>
                            <span>معلومات الحساب</span>
                        </a>
                    </li>
                    <!-- Nav item events log -->
                    <li class="nav-item">
                        <a class="nav-link btn-default" data-toggle="tab" href="#events-log" role="tab">
                            <i class="fa fa-heart pl-2"></i>
                            <span>سجل الاحداث</span>
                        </a>
                    </li>
                </ul>

                <!-- Tab panels -->
                <div class="tab-content">
                    <!-- Panel profile -->
                    <div class="tab-pane fade in show active" id="profile" role="tabpanel">
                       <div class="card rounded-0 py-3 shadow">
                           <div class="card-body">
                               <h5>
                                   <span>الاسم الحقيقي:</span>
                                   {{$admin->name}}
                               </h5>
                               <h5>
                                   <span>اسم المستخدم:</span>
                                   {{$admin->username}}
                               </h5>
                               <h5>
                                   <span>نوع الحساب:</span>
                                   {{\App\Enums\AdminType::getType($admin->type)}}
                               </h5>
                               <h5>
                                   <span>حالة الحساب:</span>
                                   {{\App\Enums\AdminState::getState($admin->state)}}
                               </h5>

                               <div class="p-2"></div>

                               <a href="/control-panel/admins/{{$admin->id}}/edit?type=change-info" class="btn btn-indigo">
                                   <i class="fa fa-pencil-alt ml-1"></i>
                                   <span>تعديل الحساب</span>
                               </a>

                               <a href="/control-panel/admins/{{$admin->id}}/edit?type=change-password" class="btn btn-amber">
                                   <i class="fa fa-unlock-alt ml-1"></i>
                                   <span>تغيير كلمة المرور</span>
                               </a>
                           </div>
                       </div>
                    </div>

                    <!-- Panel events log -->
                    <div class="tab-pane fade" id="events-log" role="tabpanel">
                        <div class="row">
                            <!-- Nav tabs  -->
                            <div class="col-md-3">
                                <ul class="nav md-pills pills-primary flex-column" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" data-toggle="tab" href="#panel21" role="tab">Downloads
                                            <i class="fa fa-download ml-2"></i>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-toggle="tab" href="#panel22" role="tab">Orders & invoices
                                            <i class="fa fa-file-text ml-2"></i>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-toggle="tab" href="#panel23" role="tab">Billing details
                                            <i class="fa fa-address-card ml-2"></i>
                                        </a>
                                    </li>
                                </ul>
                            </div>

                            <!-- Nav panels  -->
                            <div class="col-md-9">
                                <!-- Tab panels -->
                                <div class="tab-content vertical">
                                    <!-- Panel 1 -->
                                    <div class="tab-pane fade in show active" id="panel21" role="tabpanel">
                                        <h5 class="my-2 h5">Panel 1</h5>
                                    </div>
                                    <!-- Panel 1 -->
                                    <!-- Panel 2 -->
                                    <div class="tab-pane fade" id="panel22" role="tabpanel">
                                        <h5 class="my-2 h5">Panel 2</h5>
                                    </div>
                                    <!-- Panel 2 -->
                                    <!-- Panel 3 -->
                                    <div class="tab-pane fade" id="panel23" role="tabpanel">
                                        <h5 class="my-2 h5">Panel 3</h5>
                                    </div>
                                    <!-- Panel 3 -->
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection