@extends("ControlPanel.layout.app")

@section("title")
    <title>لوحة التحكم</title>
@endsection

@section("content")
    <div class="container">
        <div class="alert alert-info mt-1" role="alert" style="padding: 100px 0;">
            <h4 class="alert-heading font-weight-bold mb-4 text-center">لوحة التحكم النظام الإمتحاني</h4>
            <p class="font-weight-bold text-center">معهد تراث الأنبياء (عليهم السلام) للدراسات الحوزوية الإلكترونية</p>

            <h3 class="text-center">
                <span>اهلا بك معنا ايها </span>
                <span>{{\App\Enums\AccountType::getType(session()->get("EXAM_SYSTEM_ACCOUNT_TYPE"))}}</span>
                <span>{{session()->get("EXAM_SYSTEM_ACCOUNT_NAME")}}</span>
            </h3>
        </div>
    </div>
@endsection