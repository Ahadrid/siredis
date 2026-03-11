@if(session('error'))
                <div 
                    x-data="{ show:true }"
                    x-init="setTimeout(() => show=false, 4000)"
                    x-show="show"
                    x-transition

                    class="flex items-center gap-3 px-4 py-3 bg-red-50 border border-red-200 text-red-800 rounded-lg text-sm shadow-lg"
                >
                    <svg class="w-4 h-4 text-red-500 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"/>
                    </svg>

                    <span class="flex-1">
                        {{ session('error') }}
                    </span>

                    <button @click="show=false" class="text-slate-400 hover:text-slate-700">
                        ✕
                    </button>
                </div>
@endif