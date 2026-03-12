<div x-show="openCreate"
     x-transition
     class="fixed inset-0 z-50 flex items-center justify-center bg-black/40">

    <div @click.away="openCreate=false"
         class="bg-white rounded-xl w-full max-w-lg p-6 shadow-xl">

        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-semibold text-slate-800">Tambah Kunjungan</h2>
            <button @click="openCreate=false" class="text-slate-400 hover:text-slate-600">
                ✕
            </button>
        </div>

        <form method="POST" action="{{ route('kunjungan.store') }}">
            @csrf

            <div class="space-y-4">

                {{-- Pasien --}}
                <div>
                    <label class="text-sm text-slate-600">Pasien</label>
                    <select name="pasien_id"
                        class="w-full mt-1 border border-slate-200 rounded-lg px-3 py-2 text-sm">
                        @foreach(\App\Models\Pasien::orderBy('nama')->get() as $p)
                        <option value="{{ $p->id }}">
                            {{ $p->nama }} ({{ $p->no_rm }})
                        </option>
                        @endforeach
                    </select>
                </div>

                {{-- Dokter --}}
                <div>
                    <label class="text-sm text-slate-600">Dokter</label>
                    <select name="dokter_id"
                        class="w-full mt-1 border border-slate-200 rounded-lg px-3 py-2 text-sm">
                        @foreach(\App\Models\User::role('dokter')->orderBy('name')->get() as $d)
                        <option value="{{ $d->id }}">{{ $d->name }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Tanggal --}}
                <div>
                    <label class="text-sm text-slate-600">Tanggal</label>
                    <input type="datetime-local"
                        name="tanggal_kunjungan"
                        class="w-full mt-1 border border-slate-200 rounded-lg px-3 py-2 text-sm">
                </div>

                {{-- Keluhan --}}
                <div>
                    <label class="text-sm text-slate-600">Keluhan</label>
                    <textarea name="keluhan"
                        rows="3"
                        class="w-full mt-1 border border-slate-200 rounded-lg px-3 py-2 text-sm"></textarea>
                </div>

            </div>

            <div class="flex justify-end gap-2 mt-6">
                <button type="button"
                        @click="openCreate=false"
                        class="px-4 py-2 text-sm bg-slate-100 rounded-lg">
                    Batal
                </button>

                <button type="submit"
                        class="px-4 py-2 text-sm bg-teal-600 text-white rounded-lg hover:bg-teal-700">
                    Simpan
                </button>
            </div>
        </form>

    </div>
</div>