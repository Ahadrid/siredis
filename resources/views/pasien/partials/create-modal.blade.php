<div
    x-cloak
    x-show="openCreate"
    x-transition
    class="fixed inset-0 flex items-center justify-center bg-black/50 z-50">

    <div 
        @click.outside="openCreate=false"
        x-transition
        class="bg-white w-full max-w-2xl rounded-lg shadow-lg p-6">

        <h2 class="text-lg font-semibold mb-4">Tambah Pasien</h2>

        <form action="{{ route('pasien.store') }}" method="POST" class="space-y-3">
            @csrf

            <div class="grid grid-cols-2 gap-4">

                <div>
                    <label class="text-sm">Nama</label>
                    <input type="text" name="nama"
                        class="w-full border rounded px-3 py-2">
                </div>

                <div>
                    <label class="text-sm">Jenis Kelamin</label>
                    <select name="jenis_kelamin"
                        class="w-full border rounded px-3 py-2">
                        <option value="L">Laki-laki</option>
                        <option value="P">Perempuan</option>
                    </select>
                </div>

                <div>
                    <label class="text-sm">Tanggal Lahir</label>
                    <input type="date" name="tanggal_lahir"
                        class="w-full border rounded px-3 py-2">
                </div>

                <div>
                    <label class="text-sm">NIK</label>
                    <input type="text" name="nik"
                        class="w-full border rounded px-3 py-2">
                </div>

                <div>
                    <label class="text-sm">No HP</label>
                    <input type="text" name="no_hp"
                        class="w-full border rounded px-3 py-2">
                </div>

                <div>
                    <label class="text-sm">Golongan Darah</label>
                    <select name="golongan_darah"
                        class="w-full border rounded px-3 py-2">
                        <option value="">-</option>
                        <option>A</option>
                        <option>B</option>
                        <option>AB</option>
                        <option>O</option>
                    </select>
                </div>

            </div>

            <div>
                <label class="text-sm">Alamat</label>
                <textarea name="alamat"
                    class="w-full border rounded px-3 py-2"></textarea>
            </div>

            <div>
                <label class="text-sm">Riwayat Alergi</label>
                <textarea name="riwayat_alergi"
                    class="w-full border rounded px-3 py-2"></textarea>
            </div>

            <div class="flex justify-end gap-2 pt-4">

                <button 
                    type="button"
                    @click="openCreate=false"
                    class="px-4 py-2 bg-gray-200 rounded">
                    Batal
                </button>

                <button 
                    type="submit"
                    class="px-4 py-2 bg-teal-600 text-white rounded">
                    Simpan
                </button>

            </div>

        </form>

    </div>
</div>