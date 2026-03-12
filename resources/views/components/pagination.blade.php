@if($paginator->hasPages())
<div class="px-5 py-3 border-t border-slate-100 flex items-center justify-between">
    <p class="text-xs text-slate-500">
        Menampilkan {{ $paginator->firstItem() }}–{{ $paginator->lastItem() }} dari {{ $paginator->total() }} {{ $label ?? 'data' }}
    </p>

    <div class="flex items-center gap-1">
        {{-- Tombol Previous --}}
        @if($paginator->onFirstPage())
            <span class="px-3 py-1 text-xs text-slate-300 border border-slate-200 rounded cursor-not-allowed">
                &laquo;
            </span>
        @else
            <a href="{{ $paginator->withQueryString()->previousPageUrl() }}"
               class="px-3 py-1 text-xs text-slate-600 border border-slate-200 rounded hover:bg-slate-50 transition">
                &laquo;
            </a>
        @endif

        {{-- Nomor Halaman dengan Ellipsis --}}
        @php
            $current = $paginator->currentPage();
            $last = $paginator->lastPage();
            $range = 2;
        @endphp

        @for($page = 1; $page <= $last; $page++)
            @if($page == 1 || $page == $last || ($page >= $current - $range && $page <= $current + $range))
                @if($page == $current)
                    <span class="px-3 py-1 text-xs text-white bg-teal-600 border border-teal-600 rounded">
                        {{ $page }}
                    </span>
                @else
                    <a href="{{ $paginator->withQueryString()->url($page) }}"
                       class="px-3 py-1 text-xs text-slate-600 border border-slate-200 rounded hover:bg-slate-50 transition">
                        {{ $page }}
                    </a>
                @endif
            @elseif($page == $current - $range - 1 || $page == $current + $range + 1)
                <span class="px-3 py-1 text-xs text-slate-400">...</span>
            @endif
        @endfor

        {{-- Tombol Next --}}
        @if($paginator->hasMorePages())
            <a href="{{ $paginator->withQueryString()->nextPageUrl() }}"
               class="px-3 py-1 text-xs text-slate-600 border border-slate-200 rounded hover:bg-slate-50 transition">
                &raquo;
            </a>
        @else
            <span class="px-3 py-1 text-xs text-slate-300 border border-slate-200 rounded cursor-not-allowed">
                &raquo;
            </span>
        @endif
    </div>
</div>
@endif