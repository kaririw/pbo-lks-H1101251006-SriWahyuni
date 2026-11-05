
async function renderCategories() {
    const app = document.getElementById('app');

    app.innerHTML = '<p>Memuat data kategori...</p>';

    try {
        const hasil = await api.get('/categories');

        const categories = Array.isArray(hasil)
            ? hasil
            : (hasil.data || []);

        app.innerHTML = `
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold">Data Kategori</h2>

                <button onclick="showCategoryForm()"
                    class="bg-blue-600 text-white px-4 py-2 rounded">
                    + Tambah Kategori
                </button>
            </div>

            <div class="bg-white rounded-lg shadow overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="p-3">ID</th>
                            <th class="p-3">Nama Kategori</th>
                            <th class="p-3">Deskripsi</th>
                            <th class="p-3">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        ${categories.map(kategori => `
                            <tr class="border-t">
                                <td class="p-3">${kategori.id}</td>
                                <td class="p-3">${escapeHtml(kategori.nama)}</td>
                                <td class="p-3">${escapeHtml(kategori.deskripsi || '-')}</td>
                                <td class="p-3">
                                    <button onclick="editCategory(${kategori.id})"
                                        class="bg-yellow-500 text-white px-3 py-1 rounded">
                                        Edit
                                    </button>

                                    <button onclick="deleteCategory(${kategori.id})"
                                        class="bg-red-500 text-white px-3 py-1 rounded">
                                        Hapus
                                    </button>
                                </td>
                            </tr>
                        `).join('')}
                    </tbody>
                </table>
            </div>

            <div id="categoryModal"></div>
        `;

    } catch (error) {
        app.innerHTML = '<p class="text-red-500">Gagal memuat data kategori.</p>';
        console.error(error);
    }
}


function showCategoryForm(kategori = null) {
    const modal = document.getElementById('categoryModal');

    modal.innerHTML = `
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4">
            <div class="bg-white p-6 rounded-lg w-full max-w-md">

                <h3 class="text-xl font-bold mb-4">
                    ${kategori ? 'Edit Kategori' : 'Tambah Kategori'}
                </h3>

                <form id="categoryForm">
                    <label class="block mb-2">Nama Kategori</label>
                    <input type="text" id="namaKategori"
                        value="${kategori ? escapeHtml(kategori.nama) : ''}"
                        class="border p-2 w-full mb-3 rounded" required>

                    <label class="block mb-2">Deskripsi</label>
                    <textarea id="deskripsiKategori"
                        class="border p-2 w-full mb-4 rounded">${kategori ? escapeHtml(kategori.deskripsi || '') : ''}</textarea>

                    <div class="flex justify-end gap-2">
                        <button type="button" onclick="closeCategoryForm()"
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

    document.getElementById('categoryForm').addEventListener('submit', async function(event) {
        event.preventDefault();

        const data = {
            nama: document.getElementById('namaKategori').value,
            deskripsi: document.getElementById('deskripsiKategori').value
        };

        try {
            let hasil;

            if (kategori) {
                hasil = await api.put(`/categories/${kategori.id}`, data);
            } else {
                hasil = await api.post('/categories', data);
            }

            showToast(hasil.message || 'Kategori berhasil disimpan!');
            closeCategoryForm();
            await renderCategories();

        } catch (error) {
            showToast('Gagal menyimpan kategori!', 'error');
            console.error(error);
        }
    });
}


function closeCategoryForm() {
    document.getElementById('categoryModal').innerHTML = '';
}


async function editCategory(id) {
    try {
        const hasil = await api.get(`/categories/${id}`);
        const kategori = hasil.data || hasil;

        showCategoryForm(kategori);

    } catch (error) {
        showToast('Gagal mengambil data kategori!', 'error');
        console.error(error);
    }
}


async function deleteCategory(id) {
    if (confirm('Yakin ingin menghapus kategori ini?')) {
        try {
            const hasil = await api.delete(`/categories/${id}`);

            showToast(hasil.message || 'Kategori berhasil dihapus!');
            await renderCategories();

        } catch (error) {
            showToast('Gagal menghapus kategori!', 'error');
            console.error(error);
        }
    }
}
