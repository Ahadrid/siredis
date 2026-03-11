<div
    x-cloak
    x-show="openEdit"
    x-transition.opacity
    class="fixed inset-0 flex items-center justify-center bg-black/40 z-50">

    <div
        @click.outside="openEdit=false"
        class="bg-white w-full max-w-2xl rounded-xl shadow-xl p-6">

        <h2 class="text-lg font-semibold mb-4">Edit Pasien</h2>

        <form
            :action="'{{ route('pasien.update', ':id') }}'.replace(':id', pasien.id)"
            method="POST"
            class="grid grid-cols-2 gap-4 text-sm">

            @csrf
            @method('PUT')

            <div>
                <label>Nama</label>
                <input type="text"
                name="nama"
                x-model="pasien.nama"
                class="w-full border rounded-lg px-3 py-2">
            </div>

            <div>
                <label>NIK</label>
                <input type="text"
                name="nik"
                x-model="pasien.nik"
                class="w-full border rounded-lg px-3 py-2">
            </div>

            <div>
                <label>Jenis Kelamin</label>
                <select name="jenis_kelamin"
                    x-model="pasien.jenis_kelamin"
                    class="w-full border rounded-lg px-3 py-2">

                    <option value="L">Laki-laki</option>
                    <option value="P">Perempuan</option>
                </select>
            </div>

            <div>
                <label>Tanggal Lahir</label>
                <input type="date"
                    name="tanggal_lahir"
                    x-model="pasien.tanggal_lahir"
                    class="w-full border rounded-lg px-3 py-2">
            </div>

            <div>
                <label>No HP</label>
                <input type="text"
                name="no_hp"
                x-model="pasien.no_hp"
                class="w-full border rounded-lg px-3 py-2">
            </div>

            <div>
                <label>Golongan Darah</label>
                <select name="golongan_darah"
                    x-model="pasien.golongan_darah"
                    class="w-full border rounded-lg px-3 py-2">

                    <option>A</option>
                    <option>B</option>
                    <option>AB</option>
                    <option>O</option>
                </select>
            </div>

            <div class="col-span-2">
                <label>Alamat</label>
                <textarea
                name="alamat"
                x-model="pasien.alamat"
                class="w-full border rounded-lg px-3 py-2"></textarea>
            </div>

            <div class="col-span-2">
                <label>Riwayat Alergi</label>
                <textarea
                name="riwayat_alergi"
                x-model="pasien.riwayat_alergi"
                class="w-full border rounded-lg px-3 py-2"></textarea>
            </div>

            <div class="col-span-2 flex justify-end gap-2 mt-4">
                <button
                    type="button"
                    @click="openEdit=false"
                    class="px-4 py-2 bg-slate-100 rounded-lg">
                    Batal
                </button>

                <button
                    type="submit"
                    class="px-4 py-2 bg-teal-600 text-white rounded-lg">
                    Update
                </button>
            </div>
        </form>
    </div>
</div>