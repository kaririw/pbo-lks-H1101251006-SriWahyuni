
async function renderBooks() {
    const app = document.getElementById('app');

    try {
        const hasil = await api.get('/books');
        const books = hasil.data || [];

        app.innerHTML = `
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold">Data Buku</h2>

                <button onclick="showBookForm()"
                    class="bg-blue-600 text-white px-4 py-2 rounded">
                    + Tambah Buku
                </button>
            </div>

            <div class="bg-white rounded-lg shadow overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="p-3">ID</th>
                            <th class="p-3">Judul</th>
                            <th class="p-3">Penulis</th>
                            <th class="p-3">Tahun</th>
                            <th class="p-3">Stok</th>
                            <th class="p-3">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        ${books.map(buku => `
                            <tr class="border-t">
                                <td class="p-3">${buku.id}</td>
                                <td class="p-3">${escapeHtml(buku.judul)}</td>
                                <td class="p-3">${escapeHtml(buku.penulis)}</td>
                                <td class="p-3">${buku.tahun_terbit}</td>
                                <td class="p-3">${buku.stok}</td>
                                <td class="p-3">
                                    <button
                                        onclick="editBook(${buku.id})"
                                        class="bg-yellow-500 text-white px-3 py-1 rounded">
                                        Edit
                                    </button>

                                    <button
                                        onclick="deleteBook(${buku.id})"
                                        class="bg-red-500 text-white px-3 py-1 rounded">
                                        Hapus
                                    </button>
                                </td>
                            </tr>
                        `).join('')}
                    </tbody>
                </table>
            </div>

            <div id="bookModal"></div>
        `;

    } catch (error) {
        app.innerHTML = '<p class="text-red-500">Gagal memuat data buku.</p>';
        console.error(error);
    }
}


function showBookForm(buku = null) {
    const modal = document.getElementById('bookModal');

    modal.innerHTML = `
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4">
            <div class="bg-white p-6 rounded-lg w-full max-w-md">
                <h3 class="text-xl font-bold mb-4">
                    ${buku ? 'Edit Buku' : 'Tambah Buku'}
                </h3>

                <form id="bookForm">
                    <label class="block mb-2">Judul Buku</label>
                    <input type="text" id="judul"
                        value="${buku ? escapeHtml(buku.judul) : ''}"
                        class="border p-2 w-full mb-3 rounded" required>

                    <label class="block mb-2">Penulis</label>
                    <input type="text" id="penulis"
                        value="${buku ? escapeHtml(buku.penulis) : ''}"
                        class="border p-2 w-full mb-3 rounded" required>

                    <label class="block mb-2">Tahun Terbit</label>
                    <input type="number" id="tahun_terbit"
                        value="${buku ? buku.tahun_terbit : ''}"
                        class="border p-2 w-full mb-3 rounded" required>

                    <label class="block mb-2">Stok</label>
                    <input type="number" id="stok"
                        value="${buku ? buku.stok : ''}"
                        class="border p-2 w-full mb-4 rounded" required>

                    <div class="flex justify-end gap-2">
                        <button type="button"
                            onclick="closeBookForm()"
                            class="bg-gray-400 text-white px-4 py-2 rounded">
                            Batal
                        </button>

                        <button type="submit"
                            class="bg-blue-600 text-white px-4 py-2 rounded">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    `;

    document.getElementById('bookForm').addEventListener('submit', async function(event) {
        event.preventDefault();

        const data = {
            judul: document.getElementById('judul').value,
            penulis: document.getElementById('penulis').value,
            tahun_terbit: Number(document.getElementById('tahun_terbit').value),
            stok: Number(document.getElementById('stok').value)
        };

        try {
            let hasilSimpan;

            if (buku) {
                hasilSimpan = await api.put(`/books/${buku.id}`, data);
            } else {
                hasilSimpan = await api.post('/books', data);
            }

            console.log('Hasil penyimpanan:', hasilSimpan);

            if (hasilSimpan.success) {
                showToast(hasilSimpan.message);
                closeBookForm();
                await renderBooks();
            } else {
                showToast(
                    hasilSimpan.message || 'Gagal menyimpan buku!',
                    'error'
                );
            }

        } catch (error) {
            showToast('Gagal menyimpan buku!', 'error');
            console.error(error);
        }
    });
}


function closeBookForm() {
    document.getElementById('bookModal').innerHTML = '';
}


async function editBook(id) {
    try {
        const hasil = await api.get(`/books/${id}`);
        showBookForm(hasil.data);
    } catch (error) {
        showToast('Gagal mengambil data buku!', 'error');
        console.error(error);
    }
}


async function deleteBook(id) {
    if (confirm('Yakin ingin menghapus buku ini?')) {
        try {
            const hasil = await api.delete(`/books/${id}`);

            if (hasil.success) {
                showToast(hasil.message);
                await renderBooks();
            } else {
                showToast(hasil.message || 'Gagal menghapus buku!', 'error');
            }

        } catch (error) {
            showToast('Gagal menghapus buku!', 'error');
            console.error(error);
        }
    }
}
