<!-- Modal Tambah Proyek -->
<div id="modalTambahProyek" class="fixed inset-0 backdrop-blur-xs bg-black/30 hidden items-center justify-center z-50 p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-6xl max-h-screen overflow-hidden my-4 mx-auto">
        <!-- Modal Header -->
        <div class="bg-red-800 text-white p-6 flex items-center justify-between flex-shrink-0">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-white bg-opacity-20 rounded-full flex items-center justify-center overflow-hidden">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-10 h-10 object-contain">
                </div>
                <div>
                    <h3 class="text-xl font-bold">Tambah Proyek Baru</h3>
                    <p class="text-red-100 text-sm">Buat proyek baru</p>
                </div>
            </div>
            <button onclick="closeModal('modalTambahProyek')" class="text-white hover:bg-white hover:text-red-800 p-2">
                <i class="fas fa-times text-2xl"></i>
            </button>
        </div>


        <!-- Modal Body -->
        <div class="p-6 overflow-y-auto flex-1" style="max-height: calc(100vh - 200px);">
            <form id="formTambahProyek" class="space-y-6">
                <!-- Informasi Dasar -->
                <div class="bg-gray-50 rounded-xl p-6">
                    <h4 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-info-circle text-red-600 mr-2"></i>
                        Informasi Dasar
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">ID Proyek</label>
                            <input type="text" name="id" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500" placeholder="Masukkan ID proyek">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal</label>
                            <input type="date" name="tanggal" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Kabupaten/Kota</label>
                            <input type="text" name="kabupaten_kota" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500" placeholder="Masukkan kabupaten/kota">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nama Instansi</label>
                            <input type="text" name="nama_instansi" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500" placeholder="Masukkan nama instansi">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Pengadaan</label>
                            <select name="jenis_pengadaan" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
                                <option value="">Pilih jenis pengadaan</option>
                                <option value="Pelelangan Umum">Pelelangan Umum</option>
                                <option value="Pelelangan Terbatas">Pelelangan Terbatas</option>
                                <option value="Pemilihan Langsung">Pemilihan Langsung</option>
                                <option value="Penunjukan Langsung">Penunjukan Langsung</option>
                                <option value="Tender">Tender</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Admin Marketing</label>
                            <input type="text" name="admin_marketing" class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-100 text-gray-600" value="[Nama User Login]" readonly>
                            <small class="text-gray-500 text-xs mt-1">Otomatis diisi dengan nama user yang login</small>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Admin Purchasing</label>
                            <select name="admin_purchasing" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
                                <option value="">Pilih admin purchasing</option>
                                <option value="Sari Wijaya">Sari Wijaya</option>
                                <option value="Maya Indah">Maya Indah</option>
                                <option value="Roni Hidayat">Roni Hidayat</option>
                                <option value="Lisa Permata">Lisa Permata</option>
                                <option value="Nina Kartika">Nina Kartika</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Potensi</label>
                            <div class="flex gap-2">
                                <button type="button" id="potensiYa" onclick="togglePotensi('ya')" class="flex-1 px-4 py-3 border border-gray-300 rounded-lg text-center hover:bg-gray-50 transition-colors duration-200 potensi-btn">
                                    <i class="fas fa-thumbs-up mr-2"></i>Ya
                                </button>
                                <button type="button" id="potensiTidak" onclick="togglePotensi('tidak')" class="flex-1 px-4 py-3 border border-gray-300 rounded-lg text-center hover:bg-gray-50 transition-colors duration-200 potensi-btn">
                                    <i class="fas fa-thumbs-down mr-2"></i>Tidak
                                </button>
                            </div>
                            <input type="hidden" name="potensi" id="potensiValue">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tahun Potensi</label>
                            <input type="number" name="tahun_potensi" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500" placeholder="2024" value="2024" min="2020" max="2030">
                        </div>
                    </div>

                    <!-- Catatan -->
                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Catatan</label>
                        <textarea name="catatan" rows="3" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500" placeholder="Masukkan catatan proyek..."></textarea>
                    </div>
                </div>

                <!-- Daftar Barang -->
                <div class="bg-gray-50 rounded-xl p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h4 class="text-lg font-semibold text-gray-800 flex items-center">
                            <i class="fas fa-boxes text-red-600 mr-2"></i>
                            Daftar Barang
                        </h4>
                        <button type="button" onclick="tambahBarang()" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors duration-200 flex items-center">
                            <i class="fas fa-plus mr-2"></i>
                            Tambah Barang
                        </button>
                    </div>

                    <div id="daftarBarang" class="space-y-4">
                        <!-- Item Barang Template -->
                        <div class="barang-item bg-white border border-gray-200 rounded-lg p-4">
                            <div class="flex items-center justify-between mb-3">
                                <h5 class="font-medium text-gray-800">Item 1</h5>
                                <button type="button" onclick="hapusBarang(this)" class="text-red-600 hover:bg-red-100 rounded-lg p-2 transition-colors duration-200" style="display: none;">
                                    <i class="fas fa-trash text-sm"></i>
                                </button>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-3">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Barang</label>
                                    <input type="text" name="barang[0][nama]" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm" placeholder="Nama barang">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Qty</label>
                                    <input type="number" name="barang[0][qty]" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm qty-input" placeholder="0" min="1" onchange="hitungTotal(this)">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Satuan</label>
                                    <select name="barang[0][satuan]" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm">
                                        <option value="">Pilih satuan</option>
                                        <option value="pcs">Pcs</option>
                                        <option value="unit">Unit</option>
                                        <option value="set">Set</option>
                                        <option value="buah">Buah</option>
                                        <option value="kg">Kg</option>
                                        <option value="meter">Meter</option>
                                        <option value="liter">Liter</option>
                                        <option value="paket">Paket</option>
                                        <option value="sistem">Sistem</option>
                                        <option value="layanan">Layanan</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Harga Satuan</label>
                                    <input type="number" name="barang[0][harga_satuan]" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm harga-satuan-input" placeholder="0" min="0" onchange="hitungTotal(this)">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Harga Total</label>
                                    <input type="number" name="barang[0][harga_total]" class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-100 text-gray-600 text-sm harga-total-input" placeholder="0" readonly>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Total Keseluruhan -->
                    <div class="mt-6 bg-white border border-gray-200 rounded-lg p-4">
                        <div class="flex justify-between items-center">
                            <h5 class="text-lg font-semibold text-gray-800">Total Keseluruhan:</h5>
                            <div class="text-2xl font-bold text-red-600" id="totalKeseluruhan">Rp 0</div>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Modal Footer -->
        <div class="bg-gray-50 px-6 py-4 flex items-center justify-end space-x-3 border-t border-gray-200 flex-shrink-0">
            <button type="button" onclick="closeModal('modalTambahProyek')" class="px-6 py-3 text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                Batal
            </button>
            <button type="submit" form="formTambahProyek" class="px-6 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors duration-200 flex items-center">
                <i class="fas fa-save mr-2"></i>
                Simpan Proyek
            </button>
        </div>
    </div>
</div>

<script>
let itemCounter = 1;

// Toggle potensi buttons
function togglePotensi(value) {
    const yaBtn = document.getElementById('potensiYa');
    const tidakBtn = document.getElementById('potensiTidak');
    const hiddenInput = document.getElementById('potensiValue');

    if (!yaBtn || !tidakBtn || !hiddenInput) {
        console.error('Potensi elements not found');
        return;
    }

    // Reset all buttons
    yaBtn.classList.remove('bg-green-500', 'text-white', 'border-green-500');
    tidakBtn.classList.remove('bg-red-500', 'text-white', 'border-red-500');
    yaBtn.classList.add('border-gray-300', 'text-gray-700');
    tidakBtn.classList.add('border-gray-300', 'text-gray-700');

    if (value === 'ya') {
        yaBtn.classList.remove('border-gray-300', 'text-gray-700');
        yaBtn.classList.add('bg-green-500', 'text-white', 'border-green-500');
        hiddenInput.value = 'ya';
    } else if (value === 'tidak') {
        tidakBtn.classList.remove('border-gray-300', 'text-gray-700');
        tidakBtn.classList.add('bg-red-500', 'text-white', 'border-red-500');
        hiddenInput.value = 'tidak';
    }
}

function tambahBarang() {
    const container = document.getElementById('daftarBarang');
    const template = document.querySelector('.barang-item');

    if (!container || !template) {
        console.error('Container atau template tidak ditemukan');
        return;
    }

    const clonedTemplate = template.cloneNode(true);

    // Update header dan input names
    const titleElement = clonedTemplate.querySelector('h5');
    if (titleElement) {
        titleElement.textContent = `Item ${itemCounter + 1}`;
    }

    clonedTemplate.querySelectorAll('input, select').forEach(input => {
        const name = input.getAttribute('name');
        if (name) {
            input.setAttribute('name', name.replace('[0]', `[${itemCounter}]`));
        }
        if (input.type !== 'hidden') {
            input.value = '';
        }
    });

    // Show delete button for new items
    const deleteButton = clonedTemplate.querySelector('button[onclick="hapusBarang(this)"]');
    if (deleteButton) {
        deleteButton.style.display = 'block';
    }

    container.appendChild(clonedTemplate);
    itemCounter++;
    updateDeleteButtons();
}

function hapusBarang(button) {
    const item = button.closest('.barang-item');
    item.remove();
    updateItemNumbers();
    hitungTotalKeseluruhan();
    updateDeleteButtons();
}

function updateItemNumbers() {
    const items = document.querySelectorAll('.barang-item');
    items.forEach((item, index) => {
        item.querySelector('h5').textContent = `Item ${index + 1}`;
        item.querySelectorAll('input, select').forEach(input => {
            const name = input.getAttribute('name');
            if (name) {
                input.setAttribute('name', name.replace(/\[\d+\]/, `[${index}]`));
            }
        });
    });
}

function updateDeleteButtons() {
    const items = document.querySelectorAll('.barang-item');
    items.forEach((item, index) => {
        const deleteButton = item.querySelector('button[onclick="hapusBarang(this)"]');
        if (deleteButton) {
            if (items.length > 1) {
                deleteButton.style.display = 'block';
            } else {
                deleteButton.style.display = 'none';
            }
        }
    });
}

function hitungTotal(input) {
    const row = input.closest('.barang-item');
    const qty = parseFloat(row.querySelector('.qty-input').value) || 0;
    const hargaSatuan = parseFloat(row.querySelector('.harga-satuan-input').value) || 0;
    const total = qty * hargaSatuan;

    const totalInput = row.querySelector('.harga-total-input');
    if (totalInput) {
        totalInput.value = total;
    }
    hitungTotalKeseluruhan();
}

function hitungTotalKeseluruhan() {
    let total = 0;
    document.querySelectorAll('.barang-item').forEach(item => {
        const qtyInput = item.querySelector('.qty-input');
        const hargaSatuanInput = item.querySelector('.harga-satuan-input');

        if (qtyInput && hargaSatuanInput) {
            const qty = parseFloat(qtyInput.value) || 0;
            const hargaSatuan = parseFloat(hargaSatuanInput.value) || 0;
            total += qty * hargaSatuan;
        }
    });

    const totalElement = document.getElementById('totalKeseluruhan');
    if (totalElement) {
        totalElement.textContent = formatRupiah(total);
    }
}

function formatRupiah(angka) {
    return 'Rp ' + angka.toLocaleString('id-ID');
}

// Function to clear file input in tambah modal
function clearTambahFile(inputId) {
    const input = document.getElementById(inputId);
    const preview = document.getElementById(inputId + 'Preview');

    if (input) {
        input.value = '';
    }
    if (preview) {
        preview.classList.add('hidden');
    }
}

// File upload preview handlers for tambah modal
document.addEventListener('DOMContentLoaded', function() {
    const fileInputs = ['suratPenawaran', 'suratPersetujuan', 'suratKontrak', 'suratSelesai'];

    fileInputs.forEach(inputId => {
        const input = document.getElementById(inputId);
        const preview = document.getElementById(inputId + 'Preview');

        if (input && preview) {
            input.addEventListener('change', function(e) {
                const file = e.target.files[0];
                const filenameSpan = preview.querySelector('.filename');

                if (file) {
                    // Check file size (5MB limit)
                    if (file.size > 5 * 1024 * 1024) {
                        alert('Ukuran file terlalu besar. Maksimal 5MB.');
                        this.value = '';
                        preview.classList.add('hidden');
                        return;
                    }

                    filenameSpan.textContent = file.name;
                    preview.classList.remove('hidden');
                } else {
                    preview.classList.add('hidden');
                }
            });
        }
    });
});

// Form submission
document.getElementById('formTambahProyek').addEventListener('submit', function(e) {
    e.preventDefault();

    // Simulate form submission
    const submitButton = e.target.querySelector('button[type="submit"]');
    const originalText = submitButton.innerHTML;

    submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Menyimpan...';
    submitButton.disabled = true;

    setTimeout(() => {
        submitButton.innerHTML = originalText;
        submitButton.disabled = false;
        closeModal('modalTambahProyek');

        // Show success message
        alert('Proyek berhasil ditambahkan!');

        // Reset form
        this.reset();

        // Reset file previews
        ['suratPenawaran', 'suratPersetujuan', 'suratKontrak', 'suratSelesai'].forEach(inputId => {
            const preview = document.getElementById(inputId + 'Preview');
            if (preview) {
                preview.classList.add('hidden');
            }
        });

        // Reset potensi buttons
        const potensiYa = document.getElementById('potensiYa');
        const potensiTidak = document.getElementById('potensiTidak');
        const potensiValue = document.getElementById('potensiValue');

        if (potensiYa) {
            potensiYa.classList.remove('bg-green-500', 'text-white', 'border-green-500');
            potensiYa.classList.add('border-gray-300', 'text-gray-700');
        }
        if (potensiTidak) {
            potensiTidak.classList.remove('bg-red-500', 'text-white', 'border-red-500');
            potensiTidak.classList.add('border-gray-300', 'text-gray-700');
        }
        if (potensiValue) {
            potensiValue.value = '';
        }

        // Reset items to 1
        const container = document.getElementById('daftarBarang');
        const items = container.querySelectorAll('.barang-item');
        for (let i = 1; i < items.length; i++) {
            items[i].remove();
        }
        itemCounter = 1;
        updateDeleteButtons();
        hitungTotalKeseluruhan();
    }, 2000);
});
</script>
