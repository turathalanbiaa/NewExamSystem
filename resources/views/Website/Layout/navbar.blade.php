{{-- Navbar --}}
<nav class="navbar fixed-top navbar-expand-lg navbar-dark primary-color">
    <div class="container">
        {{-- Navbar Brand --}}
        <a class="navbar-brand pull-right mr-0 ml-4" href="javascript:void(0);">
            <img src="{{asset("mdb/img/escp.png")}}" height="20">
            <span class="d-inline-block align-top mr-2">نضام الأمتحانات الالكتروني</span>
        </a>
            {{-- Navbar Collapse Content--}}
            <div class="collapse navbar-collapse" id="navbar-collapse">
                <ul class="w-100 navbar-nav nav-fill pr-1">
                    <li class="nav-item">
                        <a class="nav-link" href="{{URL::to('/')}}">
                            <span>الأمتحانات الحالية</span>
                        </a>
                    </li>

                    {{-- For Lecturer --}}
                    <li class="nav-item">
                        <a class="nav-link" href="{{URL::to('/finished-exams')}}">
                            <span>الامتحانات المنتهية</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="{{URL::to('/')}}">
                            <span>الامتحانات القادمة</span>
                        </a>
                    </li>
                </ul>
            </div>
    </div>
</nav>
