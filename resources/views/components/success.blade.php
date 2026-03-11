@if(session('success'))
                <div 
                    x-data="{ show:true }"
                    x-init="setTimeout(() => show=false, 3500)"
                    x-show="show"
                    x-transition:enter="transform ease-out duration-300"
                    x-transition:enter-start="translate-x-10 opacity-0"
                    x-transition:enter-end="translate-x-0 opacity-100"
                    x-transition:leave="transform ease-in duration-200"
                    x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0"

                    class="flex items-center gap-3 px-4 py-3 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-lg text-sm shadow-lg"
                >
                    <svg class="w-4 h-4 text-emerald-500 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>

                    <span class="flex-1">
                        {{ session('success') }}
                    </span>

                    <button @click="show=false" class="text-slate-400 hover:text-slate-700">
                        ✕
                    </button>
                </div>
                @endif