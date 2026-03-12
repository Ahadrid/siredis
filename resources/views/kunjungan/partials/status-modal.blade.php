<div 
    x-show="openStatus"
    x-cloak
    class="fixed inset-0 flex items-center justify-center bg-black/40 z-50">

    <div @click.outside="openStatus=false"
        class="bg-white w-full max-w-md rounded-xl p-6">

        <h2 class="text-lg font-semibold mb-4">
            Update Status
        </h2>

        <form
            :action="'/kunjungan/'+kunjungan.id+'/status'"
            method="POST"
            class="space-y-4">

            @csrf
            @method('PUT')

            <select name="status"
                class="w-full border rounded-lg px-3 py-2">
                <option value="menunggu">Menunggu</option>
                <option value="dalam_proses">Dalam Proses</option>
                <option value="selesai">Selesai</option>
                <option value="batal">Batal</option>
            </select>

            <div class="flex justify-end gap-2">
                <button
                    type="button"
                    @click="openStatus=false"
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