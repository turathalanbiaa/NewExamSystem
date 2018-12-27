@extends("ControlPanel.layout.app")

@section("title")
    <title>لوحة التحكم</title>
@endsection

@section("content")
    <div class="container">
        <div class="alert alert-info mt-1" role="alert" style="padding: 100px 0;">
            <h4 class="alert-heading font-weight-bold mb-4 text-center">لوحة التحكم النظام الإمتحاني</h4>
            <p class="font-weight-bold text-center">معهد تراث الأنبياء (عليهم السلام) للدراسات الحوزوية الإلكترونية</p>

            <h1 class="text-center">{{\App\Enums\AccountType::getType(session("EXAM_SYSTEM_ACCOUNT_TYPE"))}}</h1>
        </div>
    </div>
@endsection