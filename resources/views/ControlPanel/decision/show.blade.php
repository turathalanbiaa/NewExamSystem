@extends("ControlPanel.layout.app")

@section("title")
    <title>تقرير عن تطبيق نظام القرار</title>
@endsection

@section("content")
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="view shadow mdb-color p-3 mb-4">
                            <h5 class="text-center text-white m-0">
                                <span>تقرير عن طلاب </span>
                                <span>{{\App\Enums\Level::get($level)}}</span>
                            </h5>
                        </div>

                        <div class="col-12">
                            <h4>قبل تنفيذ القرار</h4>
                            @php $resultBefore = $resultBeforeExecuteDecisionSystem; @endphp
                            <div class="row">
                                <div class="col-6">
                                    <div class="blockquote right">
                                        <p class="mb-0">
                                            <span>عدد الطلاب الناجحين: </span>
                                            <span>{{$resultBefore["numberOfSuccessfulStudents"]}}</span>
                                        </p>

                                        <p class="mb-0">
                                            <span>عدد الطلاب المكملين: </span>
                                            <span>{{$resultBefore["numberOfSupplementaryStudents"]}}</span>
                                        </p>

                                        <p class="mb-0">
                                            <span>عدد الطلاب الراسبين: </span>
                                            <span>{{$resultBefore["numberOfFailedStudents"]}}</span>
                                        </p>

                                        <p class="mb-0">
                                            <span>نسبة النجاح: </span>
                                            <span>{{$resultBefore["ratioSuccess"]}}</span>
                                        </p>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <canvas id="myChartBefore"></canvas>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <h4>بعد تنفيذ القرار</h4>
                            @php $resultAfter = $resultAfterExecuteDecisionSystem; @endphp
                            <div class="row">
                                <div class="col-6">
                                    <div class="blockquote right">
                                        <p class="mb-0">
                                            <span>عدد الطلاب الناجحين: </span>
                                            <span>{{$resultAfter["numberOfSuccessfulStudents"]}}</span>
                                        </p>

                                        <p class="mb-0">
                                            <span>عدد الطلاب المكملين: </span>
                                            <span>{{$resultAfter["numberOfSupplementaryStudents"]}}</span>
                                        </p>

                                        <p class="mb-0">
                                            <span>عدد الطلاب الراسبين: </span>
                                            <span>{{$resultAfter["numberOfFailedStudents"]}}</span>
                                        </p>

                                        <p class="mb-0">
                                            <span>نسبة النجاح: </span>
                                            <span>{{$resultAfter["ratioSuccess"]}}</span>
                                        </p>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <canvas id="myChartAfter"></canvas>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <h5 class="text-center">
                                <span>عدد الطلاب في </span>
                                <span>{{\App\Enums\Level::get($level)}}</span>
                                <span> هو </span>
                                <span>{{$totalNumberOfStudents}}</span>
                            </h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section("script")
    <script>
        var ctx = document.getElementById("myChartBefore").getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ["الطلاب الناجحين", "الطلاب المكملين", "الطلاب الراسبين"],
                datasets: [
                    {
                        label: 'قبل تنفيذ القرار',
                        data: [{{$resultBefore["numberOfSuccessfulStudents"]}}, {{$resultBefore["numberOfSupplementaryStudents"]}}, {{$resultBefore["numberOfFailedStudents"]}}],
                        backgroundColor: ["#F7464A", "#46BFBD", "#FDB45C"],
                        hoverBackgroundColor: ["#FF5A5E", "#5AD3D1", "#FFC870"]
                    }
                ]
            },
            options: {
                responsive: true
            }
        });

        var ctx = document.getElementById("myChartAfter").getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ["الطلاب الناجحين", "الطلاب المكملين", "الطلاب الراسبين"],
                datasets: [
                    {
                        label: 'بعد تنفيذ القرار',
                        data: [{{$resultAfter["numberOfSuccessfulStudents"]}}, {{$resultAfter["numberOfSupplementaryStudents"]}}, {{$resultAfter["numberOfFailedStudents"]}}],
                        backgroundColor: ["#F7464A", "#46BFBD", "#FDB45C"],
                        hoverBackgroundColor: ["#FF5A5E", "#5AD3D1", "#FFC870"]
                    }
                ]
            },
            options: {
                responsive: true
            }
        });
    </script>
@endsection