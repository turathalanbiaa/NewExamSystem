<body>
    <style>
        body {
            direction: rtl;
            text-align: right;
        }

        .table {
           width: 100%;
           margin-bottom: 1rem;
           background-color: transparent;
           text-align: center;
        }

        table th {
           font-size: 1.0rem;
           font-weight: 400;
        }

        table td {
           font-size: 1.0rem;
           font-weight: 300;
        }

        .table th,
        .table td {
           padding: 0.6rem;
           vertical-align: middle;
           border-top: 1px solid #9da1a5;
        }

        .table-sm th,
        .table-sm td {
           padding: 0.3rem;
        }

        .table-bordered {
           border: 1px solid #9da1a5;
        }

        .table-bordered th,
        .table-bordered td {
           border: 1px solid #9da1a5;
        }

        .text-decoration {
            text-decoration: underline;
        }
    </style>
    {{-- Image Header --}}
    <img src="{{public_path("document-header.png")}}" style="width: 100%; height: 200px;">

    {{-- Title --}}
    <h1 style="text-align: center; font-weight: 500;">
        <span>وثيقة درجات الطالب</span>
    </h1>

    {{-- Manual Info --}}
    <div style="width: 150px; position: fixed; margin-top: -90px;">
        <p>العدد: </p>
        <p>التاريخ: </p>
    </div>

    {{-- Student Info --}}
    <p style="text-align: justify">
        <span>اسم الطالبـ ـ ـة: </span>
        <span>{{$studentName}}</span>

        <span>&nbsp;&nbsp;***&nbsp;&nbsp;</span>

        <span>المستوى الدراسي: </span>
        <span>{{\App\Enums\Level::get($level)}}</span>

        <span>&nbsp;&nbsp;***&nbsp;&nbsp;</span>

        <span>السنة الدراسية: </span>
        <span>{{$year}}</span>

        <span>&nbsp;&nbsp;***&nbsp;&nbsp;</span>

        <span>الدور: </span>
        <span>{{$role}}</span>
    </p>

    {{-- Datatable --}}
    <table class="table table-bordered" cellspacing="0">
        {{-- Table Head --}}
        <tr>
            <th rowspan="2">
                <span>ت</span>
            </th>

            <th rowspan="2">
                <span>المادة</span>
            </th>

            <th colspan="2">
                <span>الدرجه</span>
            </th>
        </tr>
        <tr>
            <th>
                <span>رقما</span>
            </th>
            <th>
                <span>كتابة</span>
            </th>
        </tr>

        {{-- Table Body --}}
        @php $i=0; @endphp
        {{-- TR With Data --}}
        @foreach($documents as $document)
            <tr>
                <td width="50px">
                    <span>{{$loop->iteration}}</span>
                </td>

                <td>
                    <span>{{$document->course->name}}</span>
                </td>

                <td width="100px">
                    <span class="{{($document->final_score <50)?"text-decoration":""}}">{{$document->final_score}}</span>
                </td>

                <td width="200px">
                    <span>{{\App\Enums\Number::getWrite($document->final_score)}}</span>
                </td>
            </tr>
            @if($loop->last)
                @php $i = $loop->iteration + 1; @endphp
            @endif
        @endforeach
        {{-- TR Empty --}}
        @for($i; $i<=10;$i++)
            <tr>
                <td>{{$i}}</td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
        @endfor
    </table>

    {{-- Datatable --}}
    <table class="table table-sm table-bordered" cellspacing="0">
        {{-- Table Head --}}
        <tr>
            <td width="33.333%">
                <span>المجموع: </span>
                <span>{{$documents->sum("final_score")}}</span>
            </td>

            <td width="33.333%">
                <span>المعدل: </span>
                <span>{{round($documents->avg("final_score"),3) . "%"}}</span>
            </td>

            <td width="33.333%">
                <span>النتيجة: </span>
                <span>{{$result}}</span>
            </td>
        </tr>
    </table>

    {{-- Managment --}}
    <p style="width: 250px; margin-top: 65px; direction: ltr; text-align: center;">
        <span>الادارة</span>
    </p>

    {{-- Footer --}}
    <h6 style="font-family: dejavusanscondensed; font-weight: 400; text-align: center; position: fixed; bottom: 0; margin: 0; width: 100%;">
        <span>Mobile: 07731881800</span>
        <span>&nbsp;&nbsp;</span>
        <span>E-mail: turath.alanbiaa@gmail.com</span>
        <span>&nbsp;&nbsp;</span>
        <span>Web: turathalanbiaa.com</span>
    </h6>
</body>