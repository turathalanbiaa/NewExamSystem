@extends("Website.layout.app")
@section("title")
    <title>النتائج</title>
@endsection
@section("content")
    <div class="container">
        <div class="row">
            {{-- Student Info --}}
            <div class="col-12">
                <div class="d-flex justify-content-between">
                    <div class="h5-responsive">
                        <span>اسم الطالب: </span>
                        {{$student->originalStudent->Name}}
                    </div>
                    <div class="h5-responsive">
                        <span>المرحلة: </span>
                        {{\App\Enums\Level::get($student->originalStudent->Level)}}
                    </div>
                </div>
            </div>

            {{-- Documents --}}
            <div class="col-12">
                <table class="table table-bordered text-center table-sm table-striped">
                    <caption>
                        <div class="h5-responsive">
                            <span>موسم الامتحانات: </span>
                            {{($sysVar->current_season==1) ? "الفصل الاول من سنة": "الفصل الثاني من سنة"}}
                            {{$sysVar->current_year}}
                        </div>
                    </caption>
                    <thead class="default-color white-text">
                    <tr>
                        <th scope="col" rowspan="2">#</th>
                        <th scope="col" rowspan="2">اسم المادة</th>
                        <th scope="col" colspan="2">درجة الامتحان النهائي</th>
                        <th scope="col" rowspan="2">درجة التقييم والمباحثات</th>
                        <th scope="col" rowspan="2">المجموع</th>
                        <th scope="col" rowspan="2">درجة القرار</th>
                        <th scope="col" rowspan="2">الدرجة النهائية</th>
                    </tr>
                    <tr>
                        <th scope="col">درجة الدور الاول</th>
                        <th scope="col">درجة الدور الثاني</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($documents as $document)
                        <tr>
                            <th scope="row">{{$loop->iteration}}</th>
                            <td>{{$document->course->name}}</td>
                            <td>{{$document->final_first_score}}</td>
                            <td>{{$document->final_second_score}}</td>
                            <td>{{$document->assessment_score}}</td>
                            <td>{{$document->total}}</td>
                            <td>{{$document->decision_score}}</td>
                            @if($document->final_score < 50)
                                <td class="text-danger">{{$document->final_score}}</td>
                            @else
                                <td class="text-success">{{$document->final_score}}</td>
                            @endif
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
