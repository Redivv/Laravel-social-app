<div dusk="search_results_box" class="search-results">
    <h3 dusk="search_results_header">
        @if (count($results) === 0)
            {{__('searcher.not_found')}}
        @else
            {{__('searcher.results',['number' => count($results)])}}
        @endif
    </h3>
    @foreach ($results as $result)
        {{$result->name}}
    @endforeach
</div>