@extends("ControlPanel.layout.app")

@section("title")
    <title>تقييم الطلاب</title>
@endsection

@section("content")
    <div class="container">
        <div class="row">
            {{-- Heading --}}
            <div class="col-12 mb-3">
                <div class="view shadow mdb-color p-3">
                    <h5 class="text-center text-white m-0">
                        <span>تقييم الطلاب في المادة </span>
                        <span>{{$course->name}}</span>
                    </h5>
                </div>
            </div>

            {{-- Info --}}
            <div class="col-lg-4">
                <h1 class="bg-info">ftopuo</h1>
            </div>

            {{-- Create Assessment --}}
            <div class="col-lg-8 col-sm-12">
                @if(!empty($students))
                    {{-- Heading --}}
                    <p class="font-weight-bold">
                        <span>عدد الطلاب المسجلين على المادة: </span>
                        <span>{{$students->count()}}</span>
                    </p>

                    {{-- Students --}}
                    <table class="table table-striped table-bordered w-100" cellspacing="0">
                        <thead class="default-color text-white">
                        <tr>
                            <th class="th-sm fa d-table-cell">
                                <span>اسم الطالب</span>
                            </th>
                            <th class="th-sm fa d-table-cell">
                                <span>الدرجه</span>
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($students as $student)
                            <tr>
                                <td class="align-baseline">{{$student->originalStudent->Name}}</td>
                                <td class="align-baseline">
                                    <input type="number" class="form-control" name="student-{{$student->id}}" value="0">
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                    {{-- Button Send --}}
                    <button class="btn btn-outline-default btn-block font-weight-bold">
                        <span>حفظ المعلومات</span>
                    </button>
                @else
                    <h1>Students, Not Found!</h1>
                @endif
            </div>
        </div>
    </div>
@endsection