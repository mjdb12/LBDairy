@props(['paginator'])

@if ($paginator->hasPages())
    <div class="pagination-container">
        <div class="pagination-info">
            Showing {{ $paginator->firstItem() ?? 0 }} to {{ $paginator->lastItem() ?? 0 }} of {{ $paginator->total() }} results
        </div>
        
        <div class="pagination-nav">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <button class="pagination-btn prev disabled" disabled>
                    <i class="fas fa-chevron-left pagination-icon"></i>
                    <span class="sr-only">Previous</span>
                </button>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="pagination-btn prev">
                    <i class="fas fa-chevron-left pagination-icon"></i>
                    <span class="sr-only">Previous</span>
                </a>
            @endif

            {{-- First Page Link --}}
            @if ($paginator->currentPage() > 3)
                <a href="{{ $paginator->url(1) }}" class="pagination-btn first">1</a>
                @if ($paginator->currentPage() > 4)
                    <span class="pagination-btn disabled">...</span>
                @endif
            @endif

            {{-- Page Number Links --}}
            @foreach ($paginator->getUrlRange(max(1, $paginator->currentPage() - 2), min($paginator->lastPage(), $paginator->currentPage() + 2)) as $page => $url)
                @if ($page == $paginator->currentPage())
                    <span class="pagination-btn active page-number">{{ $page }}</span>
                @else
                    <a href="{{ $url }}" class="pagination-btn page-number">{{ $page }}</a>
                @endif
            @endforeach

            {{-- Last Page Link --}}
            @if ($paginator->currentPage() < $paginator->lastPage() - 2)
                @if ($paginator->currentPage() < $paginator->lastPage() - 3)
                    <span class="pagination-btn disabled">...</span>
                @endif
                <a href="{{ $paginator->url($paginator->lastPage()) }}" class="pagination-btn last">{{ $paginator->lastPage() }}</a>
            @endif

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="pagination-btn next">
                    <i class="fas fa-chevron-right pagination-icon"></i>
                    <span class="sr-only">Next</span>
                </a>
            @else
                <button class="pagination-btn next disabled" disabled>
                    <i class="fas fa-chevron-right pagination-icon"></i>
                    <span class="sr-only">Next</span>
                </button>
            @endif
        </div>
    </div>
@endif
