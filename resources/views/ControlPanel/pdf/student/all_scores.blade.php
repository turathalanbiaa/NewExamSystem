<body>
<style>
    body {
        direction: rtl;
        text-align: right;
    }
</style>
{{-- Bootstrap --}}
<style>
    .table {
        width: 100%;
        margin-bottom: 1rem;
        background-color: transparent;
    }

    .table th,
    .table td {
        padding: 0.75rem;
        vertical-align: middle;
        border-top: 1px solid #dee2e6;
    }

    .table thead th {
        vertical-align: middle;
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

    @media (max-width: 575.98px) {
        .table-responsive-sm {
            display: block;
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            -ms-overflow-style: -ms-autohiding-scrollbar;
        }
        .table-responsive-sm > .table-bordered {
            border: 0;
        }
    }

    @media (max-width: 767.98px) {
        .table-responsive-md {
            display: block;
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            -ms-overflow-style: -ms-autohiding-scrollbar;
        }
        .table-responsive-md > .table-bordered {
            border: 0;
        }
    }

    @media (max-width: 991.98px) {
        .table-responsive-lg {
            display: block;
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            -ms-overflow-style: -ms-autohiding-scrollbar;
        }
        .table-responsive-lg > .table-bordered {
            border: 0;
        }
    }

    @media (max-width: 1199.98px) {
        .table-responsive-xl {
            display: block;
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            -ms-overflow-style: -ms-autohiding-scrollbar;
        }
        .table-responsive-xl > .table-bordered {
            border: 0;
        }
    }

    .table-responsive {
        display: block;
        width: 100%;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        -ms-overflow-style: -ms-autohiding-scrollbar;
    }

    .table-responsive > .table-bordered {
        border: 0;
    }
</style>

{{-- MDBootstrap --}}
<style>
    table th {
        font-size: 0.9rem;
        font-weight: 400; }

    table td {
        font-size: 0.9rem;
        font-weight: 300; }

    table.table thead th {
        border-top: none; }

    table.table th,
    table.table td {
        padding-top: 1.1rem;
        padding-bottom: 1rem; }

    table.table a {
        margin: 0;
        color: #212529; }

    table.table .label-table {
        margin: 0;
        padding: 0;
        line-height: 0.94rem;
        height: 0.94rem; }

    table.table.btn-table td {
        vertical-align: middle; }

    table.table-hover tbody tr:hover {
        -webkit-transition: 0.5s;
        -o-transition: 0.5s;
        transition: 0.5s;
        background-color: rgba(0, 0, 0, 0.075); }

    table .th-lg {
        min-width: 9rem; }

    table .th-sm {
        min-width: 6rem; }

    table.table-sm th,
    table.table-sm td {
        padding-top: 0.6rem;
        padding-bottom: 0.6rem; }

    .table-scroll-vertical {
        max-height: 300px;
        overflow-y: auto; }

    .table-fixed {
        table-layout: fixed; }

    .table-responsive > .table-bordered,
    .table-responsive-sm > .table-bordered,
    .table-responsive-md > .table-bordered,
    .table-responsive-lg > .table-bordered,
    .table-responsive-xl > .table-bordered {
        border-top: 1px solid #dee2e6; }

</style>

{{-- Content HTML --}}
<table class="table table-bordered" cellspacing="0">
    <thead class="default-color text-white font-weight-bold">
    <tr>
        <th class="th-sm font-weight-bold align-middle" rowspan="2">
            <span>ت</span>
        </th>

        <th class="th-sm font-weight-bold align-middle" rowspan="2">
            <span>المادة</span>
        </th>

        <th class="th-sm font-weight-bold align-middle" colspan="2">
            <span>مجموع درجة الشهرين يساوي 25</span>
        </th>

        <th class="th-sm font-weight-bold align-middle" rowspan="2">
            <span>التقييم من 15</span>
        </th>

        <th class="th-sm font-weight-bold align-middle" rowspan="2">
            <span>نهائي الدور الاول من 60</span>
        </th>

        <th class="th-sm font-weight-bold align-middle" rowspan="2">
            <span>نهائي الدور الثاني من 60</span>
        </th>

        <th class="th-sm font-weight-bold align-middle" rowspan="2">
            <span>المجموع</span>
        </th>

        <th class="th-sm font-weight-bold align-middle" rowspan="2">
            <span>درجة القرار</span>
        </th>

        <th class="th-sm font-weight-bold align-middle" rowspan="2">
            <span>الدرجه النهائية</span>
        </th>
    </tr>

    <tr>
        <th class="th-sm font-weight-bold align-middle">
            <span>الشهر الاول</span>
        </th>

        <th class="th-sm font-weight-bold align-middle">
            <span>الشهر الثاني</span>
        </th>
    </tr>
    </thead>
    <tbody>
    @foreach($documents as $document)
        <tr>
            <td class="th-sm">
                <span>{{$loop->iteration}}</span>
            </td>
            <td class="th-sm">
                <span>{{$document->course->name}}</span>
            </td>
            <td class="th-sm">
                <span>{{($document->first_month_score != null)?$document->first_month_score:"---"}}</span>
            </td>
            <td class="th-sm">
                <span>{{($document->second_month_score != null)?$document->second_month_score:"---"}}</span>
            </td>
            <td class="th-sm">
                <span>{{($document->assessment_score != null)?$document->assessment_score:"---"}}</span>
            </td>
            <td class="th-sm">
                <span>{{($document->final_first_score != null)?$document->final_first_score:"---"}}</span>
            </td>
            <td class="th-sm">
                <span>{{($document->final_second_score != null)?$document->final_second_score:"---"}}</span>
            </td>
            <td class="th-sm">
                <span>{{$document->total}}</span>
            </td>
            <td class="th-sm">
                <span>{{$document->decision_score}}</span>
            </td><td class="th-sm">
                <span>{{$document->final_score}}</span>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
</body>