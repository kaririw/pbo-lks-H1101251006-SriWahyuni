async function renderDashboard() {
    const app = document.getElementById('app');

    app.innerHTML = '<p>Memuat dashboard...</p>';

    try {
        const hasil = await Promise.all([
            api.get('/books'),
            api.get('/categories')
        ]);

        const books = hasil[0].data || [];
        const categories = hasil[1].data || [];

        const bukuTerbaru = [...books]
            .sort((a, b) => b.id - a.id)
            .slice(0, 5);

        app.innerHTML = `
            <h2 class="text-2xl font-bold mb-6">Dashboard</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
                <div class="bg-blue-500 text-white p-6 rounded-lg">
                    <h3 class="text-lg">Total Buku</h3>
                    <p class="text-3xl font-bold">${books.length}</p>
                </div>

                <div class="bg-orange-500 text-white p-6 rounded-lg">
                    <h3 class="text-lg">Kategori</h3>
                    <p class="text-3xl font-bold">${categories.length}</p>
                </div>
            </div>

            <h3 class="text-xl font-bold mb-4">5 Buku Terbaru</h3>

            <div class="bg-white rounded-lg shadow overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="p-3">Judul</th>
                            <th class="p-3">Penulis</th>
                            <th class="p-3">Tahun</th>
                            <th class="p-3">Stok</th>
                        </tr>
                    </thead>

                    <tbody>
                        ${bukuTerbaru.map(buku => `
                            <tr class="border-t">
                                <td class="p-3">${escapeHtml(buku.judul)}</td>
                                <td class="p-3">${escapeHtml(buku.penulis)}</td>
                                <td class="p-3">${buku.tahun_terbit}</td>
                                <td class="p-3">${buku.stok}</td>
                            </tr>
                        `).join('')}
                    </tbody>
                </table>
            </div>
        `;

    } catch (error) {
        app.innerHTML = `
            <p class="text-red-500">
                Gagal memuat dashboard. Periksa koneksi API.
            </p>
        `;
        console.error(error);
    }
}