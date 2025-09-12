@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation" class="flex items-center justify-between">
        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-start">
            <p class="text-sm text-gray-700 leading-5">
                Menampilkan
                <span class="font-medium">{{ $paginator->firstItem() }}</span>
                sampai
                <span class="font-medium">{{ $paginator->lastItem() }}</span>
                dari
                <span class="font-medium">{{ $paginator->total() }}</span>
                hasil
            </p>
        </div>

        <div class="flex-1 flex justify-center sm:justify-end">
            <span class="relative z-0 inline-flex shadow-sm rounded-md">
                {{-- Tombol "Previous" --}}
                @if ($paginator->onFirstPage())
                    <span aria-disabled="true" class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-400 bg-gray-200 cursor-default rounded-l-md">&lt; Prev</span>
                @else
                    <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-l-md hover:bg-gray-50 transition">&lt; Prev</a>
                @endif

                {{-- Elemen Paginasi --}}
                @foreach ($elements as $element)
                    @if (is_string($element))
                        <span aria-disabled="true" class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-gray-500 bg-white border border-gray-300 cursor-default">{{ $element }}</span>
                    @endif
                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <span aria-current="page" class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-semibold text-dark-blue bg-yellow-100 border border-primary-yellow cursor-default">{{ $page }}</span>
                            @else
                                <a href="{{ $url }}" class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 transition">{{ $page }}</a>
                            @endif
                        @endforeach
                    @endif
                @endforeach

                {{-- Tombol "Next" --}}
                @if ($paginator->hasMorePages())
                    <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-dark-blue bg-primary-yellow rounded-r-md hover:opacity-80 transition">Next &gt;</a>
                @else
                    <span aria-disabled="true" class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-gray-400 bg-gray-200 cursor-default rounded-r-md">Next &gt;</span>
                @endif
            </span>
        </div>
    </nav>
@endif