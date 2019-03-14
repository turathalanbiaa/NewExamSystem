{{-- Navbar --}}
<nav class="navbar fixed-top navbar-expand-lg navbar-dark primary-color">
    <div class="container">
        {{-- Navbar Brand --}}
        <a class="navbar-brand pull-right mr-0 ml-4" href="javascript:void(0);">
            <img src="{{asset("mdb/img/escp.png")}}" height="20">
            <span class="d-inline-block align-top mr-2">لوحة التحكم</span>
        </a>

        @if(!request()->is("control-panel/login"))
            {{-- Navbar Collapse --}}
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-collapse" aria-controls="navbar-collapse" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            {{-- Navbar Collapse Content--}}
            <div class="collapse navbar-collapse" id="navbar-collapse">
                <ul class="w-100 navbar-nav nav-fill pr-1">
                    <li class="nav-item @if(request()->is("control-panel")) active @endif">
                        <a class="nav-link" href="/control-panel">
                            <span>الرئيسية</span>
                        </a>
                    </li>

                    {{-- For Super Admin --}}
                    @if((session("EXAM_SYSTEM_ACCOUNT_TYPE") == \App\Enums\AccountType::MANAGER) && (session("EXAM_SYSTEM_ACCOUNT_ID") == 1))
                        <li class="nav-item @if(request()->is("control-panel/admins*")) active @endif">
                            <a class="nav-link" href="/control-panel/admins">
                                <span>المدراء</span>
                            </a>
                        </li>
                    @endif

                    {{-- ِ For Admin --}}
                    @if((session("EXAM_SYSTEM_ACCOUNT_TYPE") == \App\Enums\AccountType::MANAGER))
                        <li class="nav-item @if(request()->is("control-panel/lecturers*")) active @endif">
                            <a class="nav-link" href="/control-panel/lecturers">
                                <span>الاساتذة</span>
                            </a>
                        </li>
                    @endif

                    {{-- For Admin And Lecturer --}}
                    @if(session()->has("EXAM_SYSTEM_ACCOUNT_TYPE"))
                        <li class="nav-item @if(request()->is("control-panel/courses*")) active @endif">
                            <a class="nav-link" href="/control-panel/courses">
                                <span>المواد الدراسية</span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="/control-panel/exams">
                                <span>الامتحانات</span>
                            </a>
                        </li>

                        <li class="nav-item dropdown @if(request()->is("control-panel/profile/*")) active @endif">
                            <a class="nav-link dropdown-toggle" id="navbarDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span>الاعدادات</span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right text-center border-top-0 border-right-0 border-bottom border-left-0 border-primary rounded-0" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="/control-panel/profile/{{session("EXAM_SYSTEM_ACCOUNT_ID")}}/show-info">
                                    <span>عرض الملف الشخصي</span>
                                </a>
                                <a class="dropdown-item" href="/control-panel/profile/{{session("EXAM_SYSTEM_ACCOUNT_ID")}}/show-event-log">
                                    <span>عرض سجل الاحدات</span>
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="/control-panel/profile/{{session("EXAM_SYSTEM_ACCOUNT_ID")}}/edit/change-info">
                                    <span>تعديل الحساب</span>
                                </a>
                                <a class="dropdown-item" href="/control-panel/profile/{{session("EXAM_SYSTEM_ACCOUNT_ID")}}/edit/change-password">
                                    <span>تغيير كلمة المرور</span>
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="/control-panel/logout?device=current">
                                    <span>تسجيل خروج من جهاز الحالي</span>
                                </a>
                                <a class="dropdown-item" href="/control-panel/logout?device=all">
                                    <span>تسجيل خروج من جميع الاجهزه</span>
                                </a>
                            </div>
                        </li>
                    @endif
                </ul>
            </div>
        @endif
    </div>
</nav>
