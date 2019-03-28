<body>
<style>
    body {
        direction: rtl;
        text-align: right;
    }

    table {
        direction: rtl;
        text-align: right;
    }


    .table {
        width: 100%;
        margin-bottom: 1rem;
        background-color: transparent;
    }

    .table th,
    .table td {
        padding: 0.75rem;
        vertical-align: top;
        border-top: 1px solid #dee2e6;
    }

    .table thead th {
        vertical-align: bottom;
        border-bottom: 2px solid #dee2e6;
    }

    .table tbody + tbody {
        border-top: 2px solid #dee2e6;
    }

    .table .table {
        background-color: #fff;
    }

    .table-sm th,
    .table-sm td {
        padding: 0.3rem;
    }

    .table-bordered {
        border: 1px solid #dee2e6;
    }

    .table-bordered th,
    .table-bordered td {
        border: 1px solid #dee2e6;
    }

    .table-bordered thead th,
    .table-bordered thead td {
        border-bottom-width: 2px;
    }

    .table-borderless th,
    .table-borderless td,
    .table-borderless thead th,
    .table-borderless tbody + tbody {
        border: 0;
    }

    .table-striped tbody tr:nth-of-type(odd) {
        background-color: rgba(0, 0, 0, 0.05);
    }

    .table-hover tbody tr:hover {
        background-color: rgba(0, 0, 0, 0.075);
    }

    .table-primary,
    .table-primary > th,
    .table-primary > td {
        background-color: #b8daff;
    }

    .table-hover .table-primary:hover {
        background-color: #9fcdff;
    }

    .table-hover .table-primary:hover > td,
    .table-hover .table-primary:hover > th {
        background-color: #9fcdff;
    }

    .table-secondary,
    .table-secondary > th,
    .table-secondary > td {
        background-color: #d6d8db;
    }

    .table-hover .table-secondary:hover {
        background-color: #c8cbcf;
    }

    .table-hover .table-secondary:hover > td,
    .table-hover .table-secondary:hover > th {
        background-color: #c8cbcf;
    }

    .table-success,
    .table-success > th,
    .table-success > td {
        background-color: #c3e6cb;
    }

    .table-hover .table-success:hover {
        background-color: #b1dfbb;
    }

    .table-hover .table-success:hover > td,
    .table-hover .table-success:hover > th {
        background-color: #b1dfbb;
    }

    .table-info,
    .table-info > th,
    .table-info > td {
        background-color: #bee5eb;
    }

    .table-hover .table-info:hover {
        background-color: #abdde5;
    }

    .table-hover .table-info:hover > td,
    .table-hover .table-info:hover > th {
        background-color: #abdde5;
    }

    .table-warning,
    .table-warning > th,
    .table-warning > td {
        background-color: #ffeeba;
    }

    .table-hover .table-warning:hover {
        background-color: #ffe8a1;
    }

    .table-hover .table-warning:hover > td,
    .table-hover .table-warning:hover > th {
        background-color: #ffe8a1;
    }

    .table-danger,
    .table-danger > th,
    .table-danger > td {
        background-color: #f5c6cb;
    }

    .table-hover .table-danger:hover {
        background-color: #f1b0b7;
    }

    .table-hover .table-danger:hover > td,
    .table-hover .table-danger:hover > th {
        background-color: #f1b0b7;
    }

    .table-light,
    .table-light > th,
    .table-light > td {
        background-color: #fdfdfe;
    }

    .table-hover .table-light:hover {
        background-color: #ececf6;
    }

    .table-hover .table-light:hover > td,
    .table-hover .table-light:hover > th {
        background-color: #ececf6;
    }

    .table-dark,
    .table-dark > th,
    .table-dark > td {
        background-color: #c6c8ca;
    }

    .table-hover .table-dark:hover {
        background-color: #b9bbbe;
    }

    .table-hover .table-dark:hover > td,
    .table-hover .table-dark:hover > th {
        background-color: #b9bbbe;
    }

    .table-active,
    .table-active > th,
    .table-active > td {
        background-color: rgba(0, 0, 0, 0.075);
    }

    .table-hover .table-active:hover {
        background-color: rgba(0, 0, 0, 0.075);
    }

    .table-hover .table-active:hover > td,
    .table-hover .table-active:hover > th {
        background-color: rgba(0, 0, 0, 0.075);
    }

    .table .thead-dark th {
        color: #fff;
        background-color: #212529;
        border-color: #32383e;
    }

    .table .thead-light th {
        color: #495057;
        background-color: #e9ecef;
        border-color: #dee2e6;
    }

    .table-dark {
        color: #fff;
        background-color: #212529;
    }

    .table-dark th,
    .table-dark td,
    .table-dark thead th {
        border-color: #32383e;
    }

    .table-dark.table-bordered {
        border: 0;
    }

    .table-dark.table-striped tbody tr:nth-of-type(odd) {
        background-color: rgba(255, 255, 255, 0.05);
    }

    .table-dark.table-hover tbody tr:hover {
        background-color: rgba(255, 255, 255, 0.075);
    }

</style>
<table class="table table-striped table-bordered table-hover w-100" cellspacing="0">
    <thead class="default-color text-white">
    <tr>
        <th class="th-sm fa d-table-cell">
            <span>ت</span>
        </th>
        <th class="th-sm fa d-table-cell">
            <span>المادة</span>
        </th>
        <th class="th-sm fa d-table-cell">
            <span>الشهر الاول</span>
        </th>
        <th class="th-sm fa d-table-cell">
            <span>الشهر الثاني</span>
        </th>
        <th class="th-sm fa d-table-cell">
            <span>التقييم</span>
        </th>
        <th class="th-sm fa d-table-cell">
            <span>نهائي الدور الاول</span>
        </th>
        <th class="th-sm fa d-table-cell">
            <span>نهائي الدور الثاني</span>
        </th>
        <th class="th-sm fa d-table-cell">
            <span>الدرجة النهائية</span>
        </th>
    </tr>
    </thead>
    <tbody>
    @foreach($documents as $document)
        <tr>
            <td class="th-sm fa d-table-cell">
                <span>{{$loop->iteration}}</span>
            </td>
            <td class="th-sm fa d-table-cell">
                <span>{{$document->course->name}}</span>
            </td>
            <td class="th-sm fa d-table-cell">
                <span>{{$document->first_month_score}}</span>
            </td>
            <td class="th-sm fa d-table-cell">
                <span>{{$document->second_month_score}}</span>
            </td>
            <td class="th-sm fa d-table-cell">
                <span>{{$document->assessment_score}}</span>
            </td>
            <td class="th-sm fa d-table-cell">
                <span>{{$document->final_first_score}}</span>
            </td>
            <td class="th-sm fa d-table-cell">
                <span>{{$document->final_second_score}}</span>
            </td>
            <td class="th-sm fa d-table-cell">
                <span>{{$document->first_month_score + $document->second_month_score + $document->assessment_score + $document->final_first_score}}</span>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
</body>

