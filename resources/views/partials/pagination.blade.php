@if ($paginator->hasPages())
<nav class="pagination-container" role="navigation" aria-label="Pagination Navigation">
    <div class="pagination-info">
        Menampilkan <strong>{{ $paginator->firstItem() }}</strong> &ndash; <strong>{{ $paginator->lastItem() }}</strong> dari <strong>{{ $paginator->total() }}</strong> data
    </div>
    <ul class="pagination-list">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <li class="page-item disabled" aria-disabled="true"><span>&laquo; Prev</span></li>
        @else
            <li class="page-item"><a href="{{ $paginator->previousPageUrl() }}" rel="prev">&laquo; Prev</a></li>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
                <li class="page-item disabled" aria-disabled="true"><span>{{ $element }}</span></li>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <li class="page-item active" aria-current="page"><span>{{ $page }}</span></li>
                    @else
                        <li class="page-item"><a href="{{ $url }}">{{ $page }}</a></li>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <li class="page-item"><a href="{{ $paginator->nextPageUrl() }}" rel="next">Next &raquo;</a></li>
        @else
            <li class="page-item disabled" aria-disabled="true"><span>Next &raquo;</span></li>
        @endif
    </ul>
</nav>
@elseif ($paginator->total() > 0)
<div class="pagination-container">
    <div class="pagination-info">
        Menampilkan seluruh <strong>{{ $paginator->total() }}</strong> data
    </div>
</div>
@endif
