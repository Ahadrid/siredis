<div
    x-cloak
    x-show="openShow"
    x-transition.opacity
    class="fixed inset-0 flex items-center justify-center bg-black/40 z-50"
    >

    <div
        @click.outside="openShow=false"
            x-transition
            class="bg-white w-full max-w-2xl rounded-xl shadow-xl p-6">

        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-semibold">Detail Pasien</h2>

            <button
                @click="openShow=false"
                class="text-slate-400 hover:text-slate-700">
                ✕
            </button>
        </div>

        <div class="grid grid-cols-2 gap-4 text-sm">

            <div>
                <label class="text-slate-500">No RM</label>
                <p class="font-medium mono" x-text="pasien.no_rm"></p>
            </div>

            <div>
                <label class="text-slate-500">Nama</label>
                <p class="font-medium" x-text="pasien.nama"></p>
            </div>

            <div>
                <label class="text-slate-500">Jenis Kelamin</label>
                <p x-text="pasien.jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan'"></p>
            </div>

            <div>
                <label class="text-slate-500">Tanggal Lahir</label>
                <p x-text="pasien.tanggal_lahir"></p>
            </div>

            <div>
                <label class="text-slate-500">NIK</label>
                <p x-text="pasien.nik || '-'"></p>
            </div>

            <div>
                <label class="text-slate-500">No HP</label>
                <p x-text="pasien.no_hp || '-'"></p>
            </div>

            <div class="col-span-2">
                <label class="text-slate-500">Alamat</label>
                <p x-text="pasien.alamat || '-'"></p>
            </div>

            <div>
                <label class="text-slate-500">Golongan Darah</label>
                <p x-text="pasien.golongan_darah"></p>
            </div>

            <div>
                <label class="text-slate-500">Riwayat Alergi</label>
                <p x-text="pasien.riwayat_alergi || '-'"></p>
            </div>

        </div>

        <div class="mt-6 text-right">
            <button
                @click="openShow=false"
                    class="px-4 py-2 bg-slate-100 rounded-lg text-sm">
                    Tutup
            </button>
        </div>

    </div>
</div>