<div x-show="openShow"
     x-transition
     class="fixed inset-0 z-50 flex items-center justify-center bg-black/40">

    <div @click.away="openShow=false"
         class="bg-white rounded-xl w-full max-w-lg p-6 shadow-xl">

        <h2 class="text-lg font-semibold mb-4">Detail Kunjungan</h2>

        <div class="space-y-2 text-sm">

            <p><b>No Kunjungan :</b>
                <span x-text="kunjungan.no_kunjungan"></span>
            </p>

            <p><b>Pasien :</b>
                <span x-text="kunjungan.pasien?.nama"></span>
            </p>

            <p><b>Dokter :</b>
                <span x-text="kunjungan.dokter?.name"></span>
            </p>

            <p><b>Keluhan :</b>
                <span x-text="kunjungan.keluhan"></span>
            </p>

            <p><b>Status :</b>
                <span x-text="kunjungan.status"></span>
            </p>

        </div>

        <div class="flex justify-end mt-6">
            <button @click="openShow=false"
                class="px-4 py-2 bg-slate-100 rounded-lg text-sm">
                Tutup
            </button>
        </div>

    </div>
</div>