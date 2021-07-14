<ul class="pagination">
    @if ($paginator->onFirstPage())
        <li class="disabled"><a href="javascript:;">«</a></li>
    @else
        <li><a href="{{ $paginator->previousPageUrl() }}" rel="prev">«</a></li>
    @endif

    {{-- Pagination Elements --}}
    @foreach ($elements as $element)
        {{-- "Three Dots" Separator --}}
        @if (is_string($element))
            <li><a href="javascript:;">{{ $element }}</a></li>
        @endif

        {{-- Array Of Links --}}
        @if (is_array($element))
            @foreach ($element as $page => $url)
                @if ($page == $paginator->currentPage())
                    <li><a class="bg-info" href="javascript:;">{{ $page }}</a></li>
                @else
                    <li><a href="{{ $url }}">{{ $page }}</a></li>
                @endif
            @endforeach
        @endif
    @endforeach

    {{-- Next Page Link --}}
    @if ($paginator->hasMorePages())
        <li><a href="{{ $paginator->nextPageUrl() }}">»</a></li>
    @else
        <li class="disabled"><a href="javascript:;">»</a></li>
    @endif

    <select class="form-control paginator-select" onchange="_jM.paginatorSelect(this,'{{ $paginator->url(1) }}')">
        <option value="10" @if($paginator->perPage() == 10) selected @endif>10</option>
        <option value="20" @if($paginator->perPage() == 20) selected @endif>30</option>
        <option value="30" @if($paginator->perPage() == 30) selected @endif>50</option>
        <option value="100" @if($paginator->perPage() == 100) selected @endif>100</option>
    </select>
    <span class="paginator-total">{{ __('merchant_view.a_total_of_n', ['count' => $paginator->total()]) }}</span>

</ul>
