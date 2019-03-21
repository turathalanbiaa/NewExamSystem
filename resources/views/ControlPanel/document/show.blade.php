@extends("ControlPanel.layout.app")

@section("title")
    <title>{{$student->originalStudent->Name}}</title>
@endsection

@section("content")
    <div class="container">
        {{-- Documents DataTables --}}
        @php $groupingDocumentsByYear = $student->documents->groupBy("year"); @endphp
        @foreach($groupingDocumentsByYear as $year => $notGroupingDocumentsBySeason)
            <p>year:{{$year}}</p>
            @php $groupingDocumentsBySeason = $notGroupingDocumentsBySeason->groupBy("season"); @endphp
            @foreach($groupingDocumentsBySeason as $season => $documents)
                <p>season:{{$season}}</p>
                @foreach($documents as $document)
                    <p>document:{{$document->id}}</p>
                @endforeach
            @endforeach
        @endforeach
    </div>
@endsection