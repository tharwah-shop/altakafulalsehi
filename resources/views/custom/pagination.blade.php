@if ($paginator->hasPages())
    <nav aria-label="ترقيم الصفحات">
        <ul class="pagination justify-content-center">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li class="page-item disabled" aria-disabled="true">
                    <span class="page-link">
                        <i class="bi bi-chevron-right me-1"></i>السابق
                    </span>
                </li>
            @else
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev">
                        <i class="bi bi-chevron-right me-1"></i>السابق
                    </a>
                </li>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <li class="page-item disabled" aria-disabled="true">
                        <span class="page-link">{{ $element }}</span>
                    </li>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="page-item active" aria-current="page">
                                <span class="page-link">{{ $page }}</span>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                            </li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next">
                        التالي<i class="bi bi-chevron-left ms-1"></i>
                    </a>
                </li>
            @else
                <li class="page-item disabled" aria-disabled="true">
                    <span class="page-link">
                        التالي<i class="bi bi-chevron-left ms-1"></i>
                    </span>
                </li>
            @endif
        </ul>
    </nav>

    {{-- Results Info --}}
    <div class="d-flex justify-content-between align-items-center mt-3 text-muted small">
        <div>
            عرض {{ $paginator->firstItem() }} إلى {{ $paginator->lastItem() }} من أصل {{ $paginator->total() }} نتيجة
        </div>
        <div>
            صفحة {{ $paginator->currentPage() }} من {{ $paginator->lastPage() }}
        </div>
    </div>
@endif
