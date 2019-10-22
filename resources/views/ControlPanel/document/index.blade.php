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

        {{-- Session Execute Decision System Message --}}
        @if (session('ExecuteDecisionSystemMessage'))
            <div class="row">
                <div class="col-12">
                    <div class="alert {{(session('TypeMessage')=="Error")?"alert-danger":"alert-success"}} text-center">
                        {{session('ExecuteDecisionSystemMessage')}}
                    </div>
                </div>
            </div>
        @endif

       <div class="row bg-light p-5 justify-content-center">
           <div class="col-lg-4 col-md-6 col-sm-12 my-3">
               <a href="/control-panel/documents/creation" class="btn btn-block btn-outline-deep-purple py-4">
                   <span class="h4-responsive">ترحيل درجات الطلاب الى وثائقهم</span>
               </a>
           </div>

           <div class="col-lg-4 col-md-6 col-sm-12 my-3">
               <a href="/control-panel/execute-decision-system" class="btn btn-block btn-outline-deep-purple py-4">
                   <span class="h4-responsive">تطبيق نظام القرار</span>
               </a>
           </div>

           <div class="col-lg-4 col-md-6 col-sm-12 my-3">
               <a href="/control-panel/documents/search" class="btn btn-block btn-outline-deep-purple py-4">
                   <span class="h4-responsive">بحث عن وثيقة طالب واصدارها</span>
               </a>
           </div>

           <div class="col-lg-4 col-md-6 col-sm-12 my-3">
               <a href="/control-panel/documents/export/levels" class="btn btn-block btn-outline-deep-purple py-4">
                   <span class="h4-responsive">اصدار درجات الطلاب حسب المرحلة</span>
               </a>
           </div>

           <div class="col-lg-4 col-md-6 col-sm-12 my-3">
               <a href="/control-panel/documents/export/courses" class="btn btn-block btn-outline-deep-purple py-4">
                   <span class="h4-responsive">اصدار درجات الطلاب حسب المادة</span>
               </a>
           </div>

           <div class="col-lg-4 col-md-6 col-sm-12 my-3">
               <a href="/control-panel/documents/export/exams" class="btn btn-block btn-outline-deep-purple py-4">
                   <span class="h4-responsive">اصدار درجات الطلاب حسب الامتحان</span>
               </a>
           </div>

           <div class="col-lg-4 col-md-6 col-sm-12 my-3">
               <a class="btn btn-block btn-outline-deep-purple py-4 disabled">
                   <span class="h4-responsive">بدأ موسم دراسي جديد</span>
               </a>
           </div>
       </div>
    </div>
@endsection