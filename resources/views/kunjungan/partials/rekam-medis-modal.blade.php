<div x-show="openRM" x-cloak
    class="fixed inset-0 z-50 flex items-center justify-center bg-black/40">

    <div @click.away="openRM=false"
    class="bg-white rounded-xl w-full max-w-2xl p-6 shadow-xl overflow-y-auto max-h-[90vh]">

    <h2 class="text-lg font-semibold mb-4">Buat Rekam Medis</h2>

        <form id="formRM" method="POST" action="{{ route('rekam-medis.store') }}">
            @csrf

            <input type="hidden" name="kunjungan_id" x-model="rm.kunjungan_id" :value="rm.kunjungan_id">

            <div class="grid grid-cols-2 gap-4">

            <div>
                <label class="text-sm">Tekanan Darah</label>
                <input type="text" name="tekanan_darah"
                class="w-full border rounded-lg px-3 py-2 text-sm">
            </div>

            <div>
                <label class="text-sm">Suhu</label>
                <input type="number" step="0.1" name="suhu"
                class="w-full border rounded-lg px-3 py-2 text-sm">
            </div>

            <div>
                <label class="text-sm">Nadi</label>
                <input type="number" name="nadi"
                class="w-full border rounded-lg px-3 py-2 text-sm">
            </div>

            <div>
                <label class="text-sm">Respirasi</label>
                <input type="number" name="respirasi"
                class="w-full border rounded-lg px-3 py-2 text-sm">
            </div>

            </div>

            <div class="mt-4">
                <label class="text-sm">Anamnesis</label>
                <textarea name="anamnesis"
                class="w-full border rounded-lg px-3 py-2 text-sm"></textarea>
            </div>

            <div class="mt-4">
                <label class="text-sm">Diagnosis</label>
                <input type="text" name="diagnosis"
                class="w-full border rounded-lg px-3 py-2 text-sm">
            </div>

            <div class="mt-4">
                <label class="text-sm">Tindakan</label>
                <textarea name="tindakan"
                class="w-full border rounded-lg px-3 py-2 text-sm"></textarea>
            </div>

            <div class="flex justify-end gap-2 mt-6">

            <button type="button"
                @click="openRM=false"
                class="px-4 py-2 bg-slate-100 rounded-lg text-sm">
                Batal
            </button>

            <button type="submit"
                class="px-4 py-2 bg-teal-600 text-white rounded-lg text-sm">
                Simpan Rekam Medis
            </button>

            </div>

        </form>

    </div>
</div>