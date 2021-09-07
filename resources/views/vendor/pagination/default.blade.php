
@if ($paginator->hasPages())
<div class="paginationWrap">
    <ul class="pagination" role="navigation">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <li  aria-disabled="true" aria-label="@lang('pagination.previous')">
                «
            </li>
        @else
            <li>
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="@lang('pagination.previous')">«</a>
            </li>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
                <li aria-disabled="true">{{ $element }}</li>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <li aria-current="page"><a class="active" href="#">{{ $page }}</a></li>
                    @else
                        <li><a href="{{ $url }}">{{ $page }}</a></li>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <li>
                <a href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="@lang('pagination.next')">»</a>
            </li>
        @else
            <li aria-disabled="true" aria-label="@lang('pagination.next')">
            »
            </li>
        @endif
    </ul>
</div>
@endif

<style>
    .paginationWrap {
    display: flex;
    justify-content: center;
    margin-top: 38px;
    margin-bottom: 40px;
}

.paginationWrap ul.pagination {
    display: inline-block;
    padding: 0;
    margin: 0;
}

.paginationWrap ul.pagination li {
  display: inline;
  margin-right: 4px;
}

.paginationWrap ul.pagination li a {
    color: #2f3859;
    padding: 8px 14px;
    text-decoration: none;
}

.paginationWrap ul.pagination li a.active {
    background-color: #4b90f6;
    color: white;
    border-radius: 40px;
    width: 38px;
    height: 38px;
}

.paginationWrap ul.pagination li a:hover:not(.active) {
    background-color: #e1e7f0;
    border-radius: 40px;
}

</style>