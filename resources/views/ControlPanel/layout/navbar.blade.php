<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-default">
    <div class="container">
        <!-- Navbar Brand -->
        <a class="navbar-brand pull-right mr-0 ml-4 py-3" href="#">
            <img src="https://mdbootstrap.com/img/logo/mdb-transparent.png" height="20" alt="Route 66 in navbar">
            <span class="d-inline-block align-top mr-2">لوحة التحكم</span>
        </a>

        @php
            $currentPath = \Illuminate\Support\Facades\Route::getFacadeRoot()->current()->uri();
        @endphp

        @if($currentPath !== "login")
            <!-- Navbar Collapse -->
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarExampleDef" aria-controls="navbarExampleDef"
                        aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse pull-right" id="navbarExampleDef">
                    <ul class="navbar-nav ml-auto pr-0">
                        <li class="nav-item active">
                            <a class="nav-link" href="#">
                                <span>الرئيسية</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <span>الدورات</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <span>الاساتذة</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <span>الامتحانات</span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <span>الدرجات</span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <span>الحسابات</span>
                            </a>
                        </li>

                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span>الاعدادات</span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right text-center" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="#">
                                    <span>عرض المعلومات الشخصية</span>
                                </a>
                                <a class="dropdown-item" href="#">
                                    <span>عرض سجل الاحدات</span>
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#">
                                    <span>تغيير الاسم الحقيقي</span>
                                </a>
                                <a class="dropdown-item" href="#">
                                    <span>تغيير اسم المستخدم</span>
                                </a>
                                <a class="dropdown-item" href="#">
                                    <span>تغيير كلمة المرور</span>
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#">
                                    <span>تسجيل خروج</span>
                                </a>
                            </div>
                        </li>
                    </ul>
                </div>
            @endif
    </div>
</nav>
