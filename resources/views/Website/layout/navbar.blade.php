{{-- Navbar --}}
<nav class="navbar fixed-top navbar-expand-lg navbar-dark primary-color">
    <div class="container">
        {{-- Navbar Brand --}}
        <a class="navbar-brand m-0 ml-3" href="javascript:void(0);">
            نضام الأمتحانات الالكتروني
        </a>

        <!-- Toggle button -->
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-collapse" aria-expanded="false" aria-label="Toggle navigation">
            <i class="fas fa-bars"></i>
        </button>

        {{-- Navbar Collapse Content--}}
        <div class="collapse navbar-collapse" id="navbar-collapse">
            <div class="w-100 navbar-nav nav-fill">
                <div class="nav-item">
                    <a class="nav-link" href="{{URL::to('/')}}">
                        <span>الأمتحانات الحالية</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a class="nav-link" href="{{URL::to('/finished-exams')}}">
                        <span>الامتحانات المنتهية</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a class="nav-link" href="{{URL::to('/next-exams')}}">
                        <span>الامتحانات القادمة</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a class="nav-link" href="{{URL::to('/student-logout')}}">
                        <span>تسجيل خروج</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</nav>
