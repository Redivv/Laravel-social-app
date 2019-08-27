<div dusk="search_results_box" class="search-results">
    <h3 dusk="search_results_header">
        {{__('searcher.results-age')}}
    </h3>
    @foreach ($resultsVar as $result)
        {{$result->name}}<br>
    @endforeach
</div>