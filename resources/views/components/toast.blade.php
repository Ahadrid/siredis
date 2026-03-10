<div
    x-data="{
        show: true,
        init() {
            setTimeout(() => this.show = false, 3500)
        }
    }"
    x-show="show"
    x-transition:enter="transform ease-out duration-300"
    x-transition:enter-start="translate-x-10 opacity-0"
    x-transition:enter-end="translate-x-0 opacity-100"

    x-transition:leave="transform ease-in duration-200"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"

    class="flex items-center gap-3 px-4 py-3 rounded-lg shadow-lg text-sm font-medium
           {{ $type === 'success' ? 'bg-green-50 text-green-700 border border-green-200' : '' }}
           {{ $type === 'error' ? 'bg-red-50 text-red-700 border border-red-200' : '' }}
           {{ $type === 'warning' ? 'bg-yellow-50 text-yellow-700 border border-yellow-200' : '' }}
    "
>

    <span>{{ $message }}</span>

    <button @click="show=false" class="ml-auto text-slate-400 hover:text-slate-700">
        ✕
    </button>

</div>