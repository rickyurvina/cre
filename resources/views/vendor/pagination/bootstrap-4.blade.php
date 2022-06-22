@if ($paginator->hasPages())
    <nav>
        <ul class="pagination justify-content-end">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li class="page-item disabled">
                    <a class="page-link" href="#" aria-label="@lang('pagination.previous')">
                        <span aria-hidden="true"><i class="fal fa-chevron-left"></i></span>
                    </a>
                </li>
            @else
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="@lang('pagination.previous')">
                        <span aria-hidden="true"><i class="fal fa-chevron-left"></i></span>
                    </a>
                </li>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <li class="page-item disabled" aria-disabled="true"><span class="page-link">{{ $element }}</span></li>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="page-item active" aria-current="page"><span class="page-link">{{ $page }}</span></li>
                        @else
                            <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="@lang('pagination.next')">
                        <span aria-hidden="true"><i class="fal fa-chevron-right"></i></span>
                    </a>
                </li>
            @else
                <li class="page-item disabled">
                    <a class="page-link" href="#" aria-label="@lang('pagination.next')">
                        <span aria-hidden="true"><i class="fal fa-chevron-right"></i></span>
                    </a>
                </li>
            @endif
        </ul>
    </nav>
@else
    <nav>
        <ul class="pagination justify-content-end">
            <li class="page-item disabled">
                <a class="page-link" href="#" aria-label="@lang('pagination.previous')">
                    <span aria-hidden="true"><i class="fal fa-chevron-left"></i></span>
                </a>
            </li>
            <li class="page-item disabled" aria-disabled="true"><span class="page-link">1</span></li>
            <li class="page-item disabled">
                <a class="page-link" href="#" aria-label="@lang('pagination.next')">
                    <span aria-hidden="true"><i class="fal fa-chevron-right"></i></span>
                </a>
            </li>
        </ul>
    </nav>
@endif
