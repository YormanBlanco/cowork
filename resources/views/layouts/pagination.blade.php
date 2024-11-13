@if ($paginator->hasPages())
    <nav aria-label="Page navigation">
        <ul class="pagination">
            <!-- Botón de Anterior -->
            @if ($paginator->onFirstPage())
                <li class="page-item disabled">
                    <span class="page-link">
                        <span class="material-symbols-rounded">keyboard_arrow_left</span>
                        <span class="sr-only">Previous</span>
                    </span>
                </li>
            @else
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->previousPageUrl() }}" aria-label="Previous">
                        <span class="material-symbols-rounded">keyboard_arrow_left</span>
                        <span class="sr-only">Previous</span>
                    </a>
                </li>
            @endif

            <!-- Páginas -->
            @foreach ($elements as $element)
                @if (is_string($element))
                    <li class="page-item disabled"><span class="page-link">{{ $element }}</span></li>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="page-item active">
                                <span class="page-link">{{ $page }} <span class="sr-only">(current)</span></span>
                            </li>
                        @else
                            <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            <!-- Botón de Siguiente -->
            @if ($paginator->hasMorePages())
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->nextPageUrl() }}" aria-label="Next">
                        <span class="material-symbols-rounded">keyboard_arrow_right</span>
                        <span class="sr-only">Next</span>
                    </a>
                </li>
            @else
                <li class="page-item disabled">
                    <span class="page-link">
                        <span class="material-symbols-rounded">keyboard_arrow_right</span>
                        <span class="sr-only">Next</span>
                    </span>
                </li>
            @endif
        </ul>
    </nav>
@endif