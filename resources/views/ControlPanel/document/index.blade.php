@extends("ControlPanel.layout.app")

@section("title")
    <title>الدرجات والوثائق</title>
@endsection

@section("content")
    <div class="container">
        {{-- Session Document Creation Message --}}
        @if (session('DocumentCreationMessage'))
            <div class="row">
                <div class="col-12">
                    <div class="alert {{(session('TypeMessage')=="Error")?"alert-danger":"alert-success"}} text-center">
                        {{session('DocumentCreationMessage')}}
                    </div>
                </div>
            </div>
        @endif

       <div class="row bg-light p-5">
           <div class="col-lg-4 col-md-6 col-sm-12 my-3">
               <a href="/control-panel/decision" class="btn btn-block btn-outline-deep-purple py-4">
                   <span class="h3-responsive">تطبيق نظام القرار</span>
               </a>
           </div>

           <div class="col-lg-4 col-md-6 col-sm-12 my-3">
               <a href="/control-panel/documents/creation" class="btn btn-block btn-outline-deep-purple py-4">
                   <span class="h3-responsive">ترحيل درجات الطلاب الى وثائقهم</span>
               </a>
           </div>

           <div class="col-lg-4 col-md-6 col-sm-12 my-3">
               <a href="/control-panel/documents/search" class="btn btn-block btn-outline-deep-purple py-4">
                   <span class="h3-responsive">بحث عن وثيقة طالب واصدارها</span>
               </a>
           </div>

           <div class="col-lg-4 col-md-6 col-sm-12 my-3">
               <a class="btn btn-block btn-outline-deep-purple py-4">
                   <span class="h3-responsive">اصدار درجات الطلاب لمادة ما </span>
               </a>
           </div>

           <div class="col-lg-4 col-md-6 col-sm-12 my-3">
               <a class="btn btn-block btn-outline-deep-purple py-4">
                   <span class="h3-responsive">اصدار درجات الطلاب لامتحان ما</span>
               </a>
           </div>

           <div class="col-lg-4 col-md-6 col-sm-12 my-3">
               <a class="btn btn-block btn-outline-deep-purple py-4 disabled">
                   <span class="h3-responsive">بدأ موسم دراسي جديد</span>
               </a>
           </div>
       </div>
    </div>
@endsection