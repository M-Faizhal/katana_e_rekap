// Common Modal Functions
function openModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        // Add modal-open class to body to prevent scrolling
        document.body.classList.add('modal-open');
        
        // Show modal with animation
        modal.classList.remove('hidden');
        modal.classList.add('flex', 'modal-enter');
        
        // Remove animation class after animation completes
        setTimeout(() => {
            modal.classList.remove('modal-enter');
        }, 300);
        
        // Focus on first input if exists
        const firstInput = modal.querySelector('input:not([type="hidden"]), select, textarea');
        if (firstInput) {
            setTimeout(() => firstInput.focus(), 100);
        }
        
        // Add escape key listener
        document.addEventListener('keydown', handleEscapeKey);
    }
}

function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        // Add exit animation
        modal.classList.add('modal-exit');
        
        // Hide modal after animation
        setTimeout(() => {
            modal.classList.add('hidden');
            modal.classList.remove('flex', 'modal-exit');
            
            // Remove modal-open class from body
            document.body.classList.remove('modal-open');
            
            // Reset forms if exists
            const form = modal.querySelector('form');
            if (form) {
                form.reset();
                
                // Reset any dynamic content if it's the add/edit modal
                if (modalId === 'modalTambahProyek') {
                    resetTambahModal();
                } else if (modalId === 'modalEditProyek') {
                    resetEditModal();
                } else if (modalId === 'modalEditWilayah') {
                    resetWilayahModal();
                }
            }
            
            // Remove escape key listener
            document.removeEventListener('keydown', handleEscapeKey);
        }, 300);
    }
}

// Success Modal Functions
function showSuccessModal(message = 'Data berhasil diperbarui!') {
    // Check if success modal exists, if not create it
    let successModal = document.getElementById('successModal');
    if (!successModal) {
        createSuccessModal();
        successModal = document.getElementById('successModal');
    }
    
    document.getElementById('successMessage').textContent = message;
    const content = document.getElementById('successModalContent');
    
    successModal.classList.remove('hidden');
    successModal.classList.add('flex');
    
    // Animation
    setTimeout(() => {
        content.classList.remove('scale-95');
        content.classList.add('scale-100');
    }, 10);
}

function closeSuccessModal() {
    const modal = document.getElementById('successModal');
    const content = document.getElementById('successModalContent');
    
    if (modal && content) {
        content.classList.remove('scale-100');
        content.classList.add('scale-95');
        
        setTimeout(() => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }, 200);
    }
}

function showSuccessModalWithAutoClose(message = 'Data berhasil diperbarui!', autoCloseDelay = 3000) {
    showSuccessModal(message);
    
    if (autoCloseDelay > 0) {
        setTimeout(() => {
            closeSuccessModal();
        }, autoCloseDelay);
    }
}

// Create success modal dynamically if it doesn't exist
function createSuccessModal() {
    const modalHTML = `
        <!-- Success Modal -->
        <div id="successModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50" style="backdrop-filter: blur(5px);">
            <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full mx-4 transform transition-all duration-300 scale-95" id="successModalContent">
                <!-- Header -->
                <div class="p-6 border-b border-gray-200">
                    <div class="flex items-center justify-center mb-4">
                        <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-check text-green-600 text-2xl"></i>
                        </div>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 text-center">Berhasil!</h3>
                </div>

                <!-- Body -->
                <div class="p-6">
                    <p class="text-gray-600 text-center mb-6" id="successMessage">
                        Data berhasil diperbarui!
                    </p>
                    
                    <!-- Action Button -->
                    <div class="flex justify-center">
                        <button onclick="closeSuccessModal()" 
                                class="px-6 py-3 bg-green-600 text-white font-medium rounded-xl hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-colors duration-200">
                            <i class="fas fa-check mr-2"></i>
                            OK
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    document.body.insertAdjacentHTML('beforeend', modalHTML);
    
    // Add click outside to close functionality
    const successModal = document.getElementById('successModal');
    successModal.addEventListener('click', function(e) {
        if (e.target === successModal) {
            closeSuccessModal();
        }
    });
}

function handleEscapeKey(e) {
    if (e.key === 'Escape') {
        // Find the topmost visible modal
        const visibleModals = document.querySelectorAll('[id^="modal"]:not(.hidden)');
        if (visibleModals.length > 0) {
            const topModal = visibleModals[visibleModals.length - 1];
            closeModal(topModal.id);
        }
    }
}

// Reset functions for different modals
function resetTambahModal() {
    // Reset item counter and remove extra items
    const container = document.getElementById('daftarBarang');
    if (container) {
        const items = container.querySelectorAll('.barang-item');
        for (let i = 1; i < items.length; i++) {
            items[i].remove();
        }   
        
        // Reset first item
        const firstItem = container.querySelector('.barang-item');
        if (firstItem) {
            firstItem.querySelectorAll('input, select').forEach(input => {
                if (input.type !== 'hidden') {
                    input.value = '';
                }
            });
            const totalHargaElement = firstItem.querySelector('.total-harga');
            if (totalHargaElement) {
                totalHargaElement.value = 'Rp 0';
            }
        }
        
        // Reset counters and totals
        if (typeof itemCounter !== 'undefined') {
            itemCounter = 1;
        }
        if (typeof updateDeleteButtons === 'function') {
            updateDeleteButtons();
        }
        if (typeof hitungTotalKeseluruhan === 'function') {
            hitungTotalKeseluruhan();
        }
    }
}

function resetEditModal() {
    // Reset any edit-specific content
    const form = document.getElementById('formEditProyek');
    if (form) {
        form.reset();
    }
}

function resetWilayahModal() {
    // Reset wilayah-specific content
    const form = document.getElementById('formEditWilayah');
    if (form) {
        form.reset();
    }
}

// Utility functions for notifications
function showNotification(message, type = 'success') {
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg transition-all duration-300 transform translate-x-full`;
    
    if (type === 'success') {
        notification.classList.add('bg-green-600', 'text-white');
        notification.innerHTML = `<i class="fas fa-check-circle mr-2"></i>${message}`;
    } else if (type === 'error') {
        notification.classList.add('bg-red-600', 'text-white');
        notification.innerHTML = `<i class="fas fa-exclamation-circle mr-2"></i>${message}`;
    } else if (type === 'warning') {
        notification.classList.add('bg-yellow-600', 'text-white');
        notification.innerHTML = `<i class="fas fa-exclamation-triangle mr-2"></i>${message}`;
    } else {
        notification.classList.add('bg-blue-600', 'text-white');
        notification.innerHTML = `<i class="fas fa-info-circle mr-2"></i>${message}`;
    }
    
    document.body.appendChild(notification);
    
    // Slide in
    setTimeout(() => {
        notification.classList.remove('translate-x-full');
    }, 100);
    
    // Auto hide after 3 seconds
    setTimeout(() => {
        notification.classList.add('translate-x-full');
        setTimeout(() => {
            document.body.removeChild(notification);
        }, 300);
    }, 3000);
}

// HPS Modal Functions for Kalkulasi

/**
 * Format number to Indonesian currency format
 * @param {number} number - The number to format
 * @returns {string} Formatted currency string
 */
function formatCurrency(number) {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0,
        maximumFractionDigits: 0
    }).format(number);
}

/**
 * Open HPS Modal and fetch project data via AJAX
 * @param {number} projectId - Project ID
 */
function openHpsModal(projectId) {
    // Show modal
    const modal = document.getElementById('hps-modal');
    const loading = document.getElementById('modal-loading');
    const contentContainer = document.getElementById('modal-content-container');
    
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    
    // Show loading state
    loading.classList.remove('hidden');
    contentContainer.classList.add('hidden');
    
    // Clear previous data
    document.getElementById('modal-project-id').textContent = '-';
    document.getElementById('modal-project-name').textContent = '-';
    document.getElementById('modal-client-name').textContent = '-';
    
    // Fetch project data via AJAX
    fetch(`/purchasing/kalkulasi/proyek/${projectId}`)
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                // Populate modal with project data
                populateHpsModal(data);
                
                // Hide loading and show content
                loading.classList.add('hidden');
                contentContainer.classList.remove('hidden');
            } else {
                throw new Error(data.message || 'Failed to load project data');
            }
        })
        .catch(error => {
            console.error('Error fetching project data:', error);
            
            // Show error message
            loading.innerHTML = `
                <div class="text-center py-8">
                    <i class="fas fa-exclamation-circle text-3xl text-red-400 mb-4"></i>
                    <p class="text-red-600 mb-4">Gagal memuat data proyek</p>
                    <p class="text-gray-500 text-sm">${error.message}</p>
                    <button onclick="closeHpsModal()" class="mt-4 bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">
                        Tutup
                    </button>
                </div>
            `;
        });
}

/**
 * Populate HPS Modal with project data
 */
function populateHpsModal(data) {
    const { proyek, kalkulasi_data, barang_list, vendors } = data;
    
    // Set project information
    document.getElementById('modal-project-id').textContent = proyek.id_proyek;
    document.getElementById('modal-project-name').textContent = proyek.nama_barang || '-';
    document.getElementById('modal-client-name').textContent = proyek.nama_klien || '-';
    
    // Populate vendor dropdown
    const vendorSelect = document.getElementById('vendor-select');
    vendorSelect.innerHTML = '<option value="">Pilih Vendor</option>';
    vendors.forEach(vendor => {
        vendorSelect.innerHTML += `<option value="${vendor.id_vendor}">${vendor.nama_vendor}</option>`;
    });
    
    // Set up vendor change event to populate barang
    vendorSelect.addEventListener('change', function() {
        const selectedVendorId = this.value;
        const barangSelect = document.getElementById('barang-select');
        
        barangSelect.innerHTML = '<option value="">Pilih Barang</option>';
        
        if (selectedVendorId) {
            // Filter barang by selected vendor
            const vendorBarang = barang_list.filter(barang => barang.id_vendor == selectedVendorId);
            vendorBarang.forEach(barang => {
                barangSelect.innerHTML += `<option value="${barang.id_barang}">${barang.nama_barang}</option>`;
            });
            barangSelect.disabled = false;
        } else {
            barangSelect.disabled = true;
        }
    });
    
    // Populate kalkulasi data table
    populateKalkulasiTable(kalkulasi_data);
    
    // Show/hide action buttons based on data availability
    const deleteBtn = document.getElementById('btn-delete-vendor-data');
    const recalculateBtn = document.getElementById('btn-recalculate');
    
    if (kalkulasi_data && kalkulasi_data.length > 0) {
        deleteBtn.style.display = 'inline-block';
        recalculateBtn.style.display = 'inline-block';
    } else {
        deleteBtn.style.display = 'none';
        recalculateBtn.style.display = 'none';
    }
}

/**
 * Populate kalkulasi data table
 */
function populateKalkulasiTable(kalkulasiData) {
    const tbody = document.getElementById('kalkulasi-data-tbody');
    const summarySection = document.getElementById('summary-section');
    
    if (!kalkulasiData || kalkulasiData.length === 0) {
        tbody.innerHTML = '<tr><td colspan="9" class="text-center py-6 text-gray-500">Belum ada data kalkulasi</td></tr>';
        summarySection.style.display = 'none';
        return;
    }
    
    // Clear existing rows
    tbody.innerHTML = '';
    
    // Populate table rows
    kalkulasiData.forEach((item, index) => {
        const row = document.createElement('tr');
        row.className = 'hover:bg-gray-50';
        row.innerHTML = `
            <td class="px-3 py-2 text-sm text-gray-900">${index + 1}</td>
            <td class="px-3 py-2 text-sm text-gray-900">${item.vendor?.nama_vendor || '-'}</td>
            <td class="px-3 py-2 text-sm text-gray-900">${item.barang?.nama_barang || '-'}</td>
            <td class="px-3 py-2 text-sm text-gray-900">${item.qty}</td>
            <td class="px-3 py-2 text-sm text-gray-900">Rp ${formatNumber(item.harga_vendor)}</td>
            <td class="px-3 py-2 text-sm text-gray-900">Rp ${formatNumber(item.total_harga_hpp)}</td>
            <td class="px-3 py-2 text-sm text-gray-900">Rp ${formatNumber(item.hps)}</td>
            <td class="px-3 py-2 text-sm text-gray-900">Rp ${formatNumber(item.nett)}</td>
            <td class="px-3 py-2 text-sm">
                <button onclick="editKalkulasiItem(${item.id})" class="text-blue-600 hover:text-blue-800 mr-2" title="Edit">
                    <i class="fas fa-edit text-xs"></i>
                </button>
                <button onclick="deleteKalkulasiItem(${item.id})" class="text-red-600 hover:text-red-800" title="Hapus">
                    <i class="fas fa-trash text-xs"></i>
                </button>
            </td>
        `;
        tbody.appendChild(row);
    });
    
    // Calculate and show summary
    updateSummary(kalkulasiData);
    summarySection.style.display = 'block';
}

/**
 * Update summary section
 */
function updateSummary(kalkulasiData) {
    let totalHPP = 0;
    let totalHPS = 0;
    let totalNett = 0;
    
    kalkulasiData.forEach(item => {
        totalHPP += parseFloat(item.total_harga_hpp || 0);
        totalHPS += parseFloat(item.hps || 0);
        totalNett += parseFloat(item.nett || 0);
    });
    
    document.getElementById('summary-total-hpp').textContent = `Rp ${formatNumber(totalHPP)}`;
    document.getElementById('summary-total-hps').textContent = `Rp ${formatNumber(totalHPS)}`;
    document.getElementById('summary-total-nett').textContent = `Rp ${formatNumber(totalNett)}`;
}

/**
 * Format number with thousand separators
 */
function formatNumber(num) {
    return new Intl.NumberFormat('id-ID').format(num);
}

/**
 * Show add item form
 */
function showAddItemForm() {
    document.getElementById('add-item-form').style.display = 'block';
}

/**
 * Hide add item form
 */
function hideAddItemForm() {
    document.getElementById('add-item-form').style.display = 'none';
    
    // Clear form
    document.getElementById('vendor-select').value = '';
    document.getElementById('barang-select').value = '';
    document.getElementById('qty-input').value = '';
    document.getElementById('harga-vendor-input').value = '';
    document.getElementById('diskon-input').value = '';
    document.getElementById('kenaikan-input').value = '';
    document.getElementById('ongkir-input').value = '';
    document.getElementById('bank-cost-input').value = '';
    document.getElementById('biaya-ops-input').value = '';
    document.getElementById('bendera-input').value = '';
    document.getElementById('catatan-input').value = '';
}

/**
 * Save kalkulasi item
 */
function saveKalkulasiItem() {
    const projectId = document.getElementById('modal-project-id').textContent;
    
    const formData = {
        id_vendor: document.getElementById('vendor-select').value,
        id_barang: document.getElementById('barang-select').value,
        qty: document.getElementById('qty-input').value,
        harga_vendor: document.getElementById('harga-vendor-input').value,
        diskon_amount: document.getElementById('diskon-input').value || 0,
        kenaikan_percent: document.getElementById('kenaikan-input').value || 0,
        ongkir: document.getElementById('ongkir-input').value || 0,
        bank_cost: document.getElementById('bank-cost-input').value || 0,
        biaya_ops: document.getElementById('biaya-ops-input').value || 0,
        bendera: document.getElementById('bendera-input').value || 0,
        catatan: document.getElementById('catatan-input').value
    };
    
    // Basic validation
    if (!formData.id_vendor || !formData.id_barang || !formData.qty || !formData.harga_vendor) {
        alert('Harap lengkapi data yang wajib diisi');
        return;
    }
    
    // Send AJAX request to save
    fetch('/purchasing/kalkulasi/save', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
        },
        body: JSON.stringify({
            proyek_id: projectId,
            kalkulasi_items: [formData]
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Item berhasil disimpan');
            hideAddItemForm();
            // Refresh modal data
            openHpsModal(projectId);
        } else {
            alert('Gagal menyimpan item: ' + (data.message || 'Unknown error'));
        }
    })
    .catch(error => {
        console.error('Error saving item:', error);
        alert('Terjadi kesalahan saat menyimpan item');
    });
}

/**
 * Save all kalkulasi
 */
function saveAllKalkulasi() {
    const projectId = document.getElementById('modal-project-id').textContent;
    
    fetch('/purchasing/kalkulasi/save', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
        },
        body: JSON.stringify({
            proyek_id: projectId
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Kalkulasi berhasil disimpan');
        } else {
            alert('Gagal menyimpan kalkulasi: ' + (data.message || 'Unknown error'));
        }
    })
    .catch(error => {
        console.error('Error saving kalkulasi:', error);
        alert('Terjadi kesalahan saat menyimpan kalkulasi');
    });
}

/**
 * Create penawaran
 */
function createPenawaran() {
    const projectId = document.getElementById('modal-project-id').textContent;
    
    if (confirm('Yakin ingin membuat penawaran dari kalkulasi ini?')) {
        fetch('/purchasing/kalkulasi/create-penawaran', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
            },
            body: JSON.stringify({
                proyek_id: projectId
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Penawaran berhasil dibuat');
                closeHpsModal();
                // Refresh the main page
                window.location.reload();
            } else {
                alert('Gagal membuat penawaran: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Error creating penawaran:', error);
            alert('Terjadi kesalahan saat membuat penawaran');
        });
    }
}

/**
 * Close HPS Modal
 */
function closeHpsModal() {
    document.getElementById('hps-modal').classList.add('hidden');
    document.body.style.overflow = 'auto';
    
    // Reset modal state
    document.getElementById('modal-loading').classList.remove('hidden');
    document.getElementById('modal-content-container').classList.add('hidden');
    hideAddItemForm();
}

/**
 * Calculate row values based on inputs
 * @param {number} rowNum - Row number to calculate
 */
function calculateRow(rowNum) {
    const row = document.querySelector(`tr[data-row="${rowNum}"]`);
    if (!row) return;

    // Get input values
    const qty = parseFloat(row.querySelector('.qty-input').value) || 0;
    const hargaVendor = parseFloat(row.querySelector('.harga-vendor-input').value) || 0;
    const diskonAmount = parseFloat(row.querySelector('.diskon-input').value) || 0;
    const persenKenaikan = parseFloat(row.querySelector('.kenaikan-input').value) || 0;
    const paguPcs = parseFloat(row.querySelector('.pagu-pcs-input').value) || 0;
    const nilaiSP = parseFloat(row.querySelector('.nilai-sp-input').value) || 0;
    const ongkir = parseFloat(row.querySelector('.ongkir-input').value) || 0;
    const dinas = parseFloat(row.querySelector('.dinas-input').value) || 0;
    const bankCost = parseFloat(row.querySelector('.bank-cost-input').value) || 0;
    const bendera = parseFloat(row.querySelector('.bendera-input').value) || 0;
    const biayaOps = parseFloat(row.querySelector('.biaya-ops-input').value) || 0;

    // Calculations based on your formula
    // 1. Total Diskon = diskon amount (fixed amount, not percentage)
    const totalDiskon = diskonAmount;
    
    // 2. Total Harga = (Harga Vendor × Qty) - Total Diskon
    const totalHarga = (hargaVendor * qty) - totalDiskon;
    
    // 3. Proyeksi Kenaikan = Total Harga × (Persen Kenaikan / 100)
    const proyeksiKenaikan = totalHarga * (persenKenaikan / 100);
    
    // 4. DPP (setelah kenaikan) = Total Harga + Proyeksi Kenaikan
    const dpp = totalHarga + proyeksiKenaikan;
    
    // 5. PPH 1.5% = DPP × 1.5%
    const pph = dpp * 0.015;
    
    // 6. PPN 11% = DPP × 11%
    const ppn = dpp * 0.11;
    
    // 7. HPS = DPP + PPN
    const hps = dpp + ppn;
    
    // 8. Harga per PCS = HPS / Qty
    const hargaPcs = qty > 0 ? hps / qty : 0;
    
    // 9. Pagu Total = Pagu per PCS × Qty
    const paguTotal = paguPcs * qty;
    
    // 10. Selisih = Pagu Total - HPS
    const selisih = paguTotal - hps;
    
    // 11. DPP Dinas = Nilai SP / 1.11 (karena SP sudah termasuk PPN)
    const dppDinas = nilaiSP / 1.11;
    
    // 12. PPN Dinas = Nilai SP - DPP Dinas
    const ppnDinas = nilaiSP - dppDinas;
    
    // 13. PPH Dinas = DPP Dinas × 1.5%
    const pphDinas = dppDinas * 0.015;
    
    // 14. Asumsi Nilai Cair = Nilai SP - PPH Dinas
    const asumsiCair = nilaiSP - pphDinas;
    
    // 15. Total Biaya = Ongkir + Dinas + Bank Cost + Bendera + Biaya Ops + Total Harga
    const totalBiaya = ongkir + dinas + bankCost + bendera + biayaOps + totalHarga;
    
    // 16. Nett = Asumsi Nilai Cair - Total Biaya
    const nett = asumsiCair - totalBiaya;
    
    // 17. Persentase Nett = (Nett / Asumsi Nilai Cair) × 100%
    const persenNett = asumsiCair > 0 ? (nett / asumsiCair) * 100 : 0;

    // Update display values
    row.querySelector('.total-diskon-value').textContent = formatNumber(totalDiskon);
    row.querySelector('.total-harga-value').textContent = formatNumber(totalHarga);
    row.querySelector('.proyeksi-kenaikan-value').textContent = formatNumber(proyeksiKenaikan);
    row.querySelector('.pph-value').textContent = formatNumber(pph);
    row.querySelector('.ppn-value').textContent = formatNumber(ppn);
    row.querySelector('.hps-value').textContent = formatNumber(hps);
    row.querySelector('.harga-pcs-value').textContent = formatNumber(hargaPcs);
    row.querySelector('.pagu-total-value').textContent = formatNumber(paguTotal);
    row.querySelector('.selisih-value').textContent = formatNumber(selisih);
    row.querySelector('.dpp-dinas-value').textContent = formatNumber(dppDinas);
    row.querySelector('.ppn-dinas-value').textContent = formatNumber(ppnDinas);
    row.querySelector('.pph-dinas-value').textContent = formatNumber(pphDinas);
    row.querySelector('.asumsi-cair-value').textContent = formatNumber(asumsiCair);
    row.querySelector('.nett-value').textContent = formatNumber(nett);
    row.querySelector('.persen-nett-value').textContent = persenNett.toFixed(1) + '%';

    // Update grand totals
    updateGrandTotals();
}

/**
 * Calculate total values and update summary
 */
function calculateTotal() {
    updateGrandTotals();
}

/**
 * Update grand totals in summary section
 */
function updateGrandTotals() {
    const rows = document.querySelectorAll('.kalkulasi-row');
    let totalHPS = 0;
    let totalPagu = 0;
    let totalSelisih = 0;
    let totalAsumsiCair = 0;
    let totalNett = 0;
    let totalPersenNett = 0;
    let validRows = 0;

    rows.forEach(row => {
        const hps = parseNumber(row.querySelector('.hps-value')?.textContent || '0');
        const pagu = parseNumber(row.querySelector('.pagu-total-value')?.textContent || '0');
        const selisih = parseNumber(row.querySelector('.selisih-value')?.textContent || '0');
        const asumsiCair = parseNumber(row.querySelector('.asumsi-cair-value')?.textContent || '0');
        const nett = parseNumber(row.querySelector('.nett-value')?.textContent || '0');
        const persenNett = parseFloat(row.querySelector('.persen-nett-value')?.textContent?.replace('%', '') || '0');

        totalHPS += hps;
        totalPagu += pagu;
        totalSelisih += selisih;
        totalAsumsiCair += asumsiCair;
        totalNett += nett;
        totalPersenNett += persenNett;
        validRows++;
    });

    const avgPersenNett = validRows > 0 ? totalPersenNett / validRows : 0;

    // Update summary cards
    updateElementIfExists('grand-total-hps', formatCurrency(totalHPS));
    updateElementIfExists('grand-total-pagu', formatCurrency(totalPagu));
    updateElementIfExists('grand-total-selisih', formatCurrency(totalSelisih));
    updateElementIfExists('grand-total-cair', formatCurrency(totalAsumsiCair));
    updateElementIfExists('grand-total-nett', formatCurrency(totalNett));
    updateElementIfExists('grand-avg-nett', avgPersenNett.toFixed(1) + '%');

    // Update the original summary cards
    updateElementIfExists('total-hpp', formatCurrency(totalHPS));
    updateElementIfExists('total-penawaran', formatCurrency(totalHPS));
    updateElementIfExists('total-margin', formatCurrency(totalSelisih));
    updateElementIfExists('nilai-nett', formatCurrency(totalNett));
}

/**
 * Parse number from formatted text
 * @param {string} text - Formatted number text
 * @returns {number} - Parsed number
 */
function parseNumber(text) {
    return parseFloat(text.replace(/[^\d.-]/g, '')) || 0;
}

/**
 * Format number with thousand separators
 * @param {number} num - Number to format
 * @returns {string} - Formatted number
 */
function formatNumber(num) {
    return Math.round(num).toLocaleString('id-ID');
}

/**
 * Update element text content if element exists
 * @param {string} id - Element ID
 * @param {string} content - Content to set
 */
function updateElementIfExists(id, content) {
    const element = document.getElementById(id);
    if (element) {
        element.textContent = content;
    }
}

/**
 * Update vendor information when vendor is selected
 * @param {HTMLSelectElement} selectElement - The select element
 * @param {number} rowNum - Row number
 */
function updateVendorInfo(selectElement, rowNum) {
    const selectedValue = selectElement.value;
    if (selectedValue) {
        const [product, vendor, price] = selectedValue.split('|');
        const row = document.querySelector(`tr[data-row="${rowNum}"]`);
        
        if (row) {
            const vendorNameElement = row.querySelector('.vendor-name');
            const hargaVendorElement = row.querySelector('.harga-vendor-input');
            
            if (vendorNameElement) vendorNameElement.textContent = vendor;
            if (hargaVendorElement) hargaVendorElement.value = price;
            
            calculateRow(rowNum);
        }
    }
}

/**
 * Update client request information when client request is selected
 * @param {HTMLSelectElement} selectElement - The select element
 * @param {number} rowNum - Row number
 */
function updateClientRequest(selectElement, rowNum) {
    const selectedValue = selectElement.value;
    if (selectedValue) {
        const [itemName, qty, targetPrice] = selectedValue.split('|');
        const row = document.querySelector(`tr[data-row="${rowNum}"]`);
        
        if (row) {
            const qtyInput = row.querySelector('.qty-input');
            
            if (qtyInput) {
                qtyInput.value = qty;
            }
            
            // You can add more logic here to compare with vendor price
            // and show alerts if vendor price is higher than target price
            
            calculateRow(rowNum);
        }
    }
}

/**
 * Update client request information when client request is selected
 * @param {HTMLSelectElement} selectElement - The select element
 * @param {number} rowNumber - Row number
 */
function updateClientRequest(selectElement, rowNumber) {
    const value = selectElement.value;
    const row = document.querySelector(`tr[data-row="${rowNumber}"]`);
    
    if (value && row) {
        const [itemName, qty, targetPrice] = value.split('|');
        
        // Update quantity input with client request quantity
        const qtyInput = row.querySelector('.qty-input');
        if (qtyInput) {
            qtyInput.value = qty;
        }
        
        // You can add more logic here to compare with vendor price
        // and show alerts if vendor price is higher than target price
        
        // Recalculate the row
        calculateRow(rowNumber);
    }
}

/**
 * Update barang information when barang is selected
 * @param {HTMLSelectElement} selectElement - The select element
 * @param {number} rowNum - Row number
 */
function updateBarang(selectElement, rowNum) {
    const selectedValue = selectElement.value;
    if (selectedValue) {
        // You can add logic here to update related fields when barang is changed
        calculateRow(rowNum);
    }
}

/**
 * Add new row to the calculation table
 */
function addNewRow() {
    if (typeof window.hpsRowCounter === 'undefined') {
        window.hpsRowCounter = 2;
    }
    
    window.hpsRowCounter++;
    const tbody = document.getElementById('kalkulasi-table-body');
    
    if (!tbody) return;
    
    const newRow = document.createElement('tr');
    newRow.className = 'kalkulasi-row';
    newRow.setAttribute('data-row', window.hpsRowCounter);
    
    newRow.innerHTML = `
        <td class="px-2 py-3 text-sm text-gray-900 border-r border-gray-200">${window.hpsRowCounter}</td>
        <td class="px-2 py-3 border-r border-gray-200">
            <select class="w-full text-sm border border-gray-300 rounded px-2 py-1 focus:outline-none focus:ring-1 focus:ring-red-500" onchange="updateClientRequest(this, ${window.hpsRowCounter})">
                <option value="">Pilih Permintaan Klien</option>
                <option value="meja-kayu|10|250000">Meja Kayu (Qty: 10, Target: Rp 250,000)</option>
                <option value="meja-besi|5|300000">Meja Besi (Qty: 5, Target: Rp 300,000)</option>
                <option value="kursi-kantor|15|400000">Kursi Kantor (Qty: 15, Target: Rp 400,000)</option>
                <option value="kursi-rapat|20|350000">Kursi Rapat (Qty: 20, Target: Rp 350,000)</option>
                <option value="komputer-desktop|8|8000000">Komputer Desktop (Qty: 8, Target: Rp 8,000,000)</option>
                <option value="printer-laser|3|2000000">Printer Laser (Qty: 3, Target: Rp 2,000,000)</option>
                <option value="lemari-arsip|12|500000">Lemari Arsip (Qty: 12, Target: Rp 500,000)</option>
            </select>
        </td>
        <td class="px-2 py-3 border-r border-gray-200">
            <select class="w-full text-sm border border-gray-300 rounded px-2 py-1 focus:outline-none focus:ring-1 focus:ring-red-500" onchange="updateVendorInfo(this, ${window.hpsRowCounter})">
                <option value="">Pilih Barang Vendor</option>
                <option value="meja-a|PT XYZ|30000">Meja A - PT XYZ</option>
                <option value="meja-b|PT ABC|35000">Meja B - PT ABC</option>
                <option value="kursi-a|PT ABC|450000">Kursi Executive A - PT ABC</option>
                <option value="kursi-b|PT XYZ|480000">Kursi Executive B - PT XYZ</option>
                <option value="komputer-a|PT DEF|8500000">Komputer Desktop A - PT DEF</option>
                <option value="printer-a|CV GHI|2500000">Printer Laser A - CV GHI</option>
            </select>
        </td>
        <td class="px-2 py-3 text-sm text-gray-900 border-r border-gray-200 vendor-name">-</td>
        <td class="px-2 py-3 border-r border-gray-200">
            <input type="number" value="1" min="1" class="qty-input w-full text-sm border border-gray-300 rounded px-2 py-1 focus:outline-none focus:ring-1 focus:ring-red-500" onchange="calculateRow(${window.hpsRowCounter})">
        </td>
        <td class="px-2 py-3 border-r border-gray-200">
            <input type="number" value="0" min="0" class="harga-vendor-input w-full text-sm border border-gray-300 rounded px-2 py-1 focus:outline-none focus:ring-1 focus:ring-red-500" onchange="calculateRow(${window.hpsRowCounter})">
        </td>
        <td class="px-2 py-3 border-r border-gray-200">
            <input type="number" value="0" min="0" step="1" class="diskon-input w-full text-sm border border-gray-300 rounded px-2 py-1 focus:outline-none focus:ring-1 focus:ring-red-500" onchange="calculateRow(${window.hpsRowCounter})" placeholder="Diskon (Rp)">
        </td>
        <td class="px-2 py-3 text-sm text-gray-900 border-r border-gray-200 hpp-value">Rp 0</td>
        <td class="px-2 py-3 border-r border-gray-200">
            <input type="number" value="0" min="0" max="1000" step="0.1" class="kenaikan-input w-full text-sm border border-gray-300 rounded px-2 py-1 focus:outline-none focus:ring-1 focus:ring-red-500" onchange="calculateRow(${window.hpsRowCounter})">
        </td>
        <td class="px-2 py-3 text-sm text-gray-900 border-r border-gray-200 dpp-jual-value">Rp 0</td>
        <td class="px-2 py-3 text-sm text-gray-900 border-r border-gray-200 pph-value">Rp 0</td>
        <td class="px-2 py-3 text-sm text-gray-900 border-r border-gray-200 ppn-value">Rp 0</td>
        <td class="px-2 py-3 text-sm text-gray-900 border-r border-gray-200 font-semibold harga-penawaran-value">Rp 0</td>
        <td class="px-2 py-3 text-sm text-gray-900 border-r border-gray-200 margin-value">Rp 0</td>
        <td class="px-2 py-3 text-sm">
            <button onclick="deleteRow(${window.hpsRowCounter})" class="text-red-600 hover:text-red-800">
                <i class="fas fa-trash"></i>
            </button>
        </td>
    `;
    
    tbody.appendChild(newRow);
}

/**
 * Delete a row from the calculation table
 * @param {number} rowNum - Row number to delete
 */
function deleteRow(rowNum) {
    const row = document.querySelector(`tr[data-row="${rowNum}"]`);
    if (row && confirm('Apakah Anda yakin ingin menghapus item ini?')) {
        row.remove();
        calculateTotal();
        updateRowNumbers();
    }
}

/**
 * Update row numbers after deletion
 */
function updateRowNumbers() {
    const rows = document.querySelectorAll('.kalkulasi-row');
    rows.forEach((row, index) => {
        const firstCell = row.querySelector('td:first-child');
        if (firstCell) {
            firstCell.textContent = index + 1;
        }
    });
}

/**
 * Save calculation data
 */
function saveKalkulasi() {
    // Collect all data from the form
    const projectId = document.getElementById('modal-project-id')?.textContent;
    const data = {
        project_id: projectId,
        items: [],
        additional_costs: {
            ongkir: parseFloat(document.getElementById('ongkir')?.value) || 0,
            biaya_operasional: parseFloat(document.getElementById('biaya-operasional')?.value) || 0,
            bank_cost: parseFloat(document.getElementById('bank-cost')?.value) || 0,
            biaya_lain: parseFloat(document.getElementById('biaya-lain')?.value) || 0
        },
        pagu_dinas: parseFloat(document.getElementById('pagu-dinas')?.value) || 0
    };

    // Collect row data
    document.querySelectorAll('.kalkulasi-row').forEach(row => {
        const item = {
            barang_diminta: row.querySelector('input[type="text"]')?.value || '',
            barang_vendor: row.querySelector('select')?.selectedOptions[0]?.text || '',
            vendor: row.querySelector('.vendor-name')?.textContent || '',
            qty: parseFloat(row.querySelector('.qty-input')?.value) || 0,
            harga_vendor: parseFloat(row.querySelector('.harga-vendor-input')?.value) || 0,
            diskon: parseFloat(row.querySelector('.diskon-input')?.value) || 0,
            kenaikan: parseFloat(row.querySelector('.kenaikan-input')?.value) || 0,
            hpp: parseFloat(row.querySelector('.hpp-value')?.textContent.replace(/[^\d]/g, '')) || 0,
            dpp_jual: parseFloat(row.querySelector('.dpp-jual-value')?.textContent.replace(/[^\d]/g, '')) || 0,
            pph: parseFloat(row.querySelector('.pph-value')?.textContent.replace(/[^\d]/g, '')) || 0,
            ppn: parseFloat(row.querySelector('.ppn-value')?.textContent.replace(/[^\d]/g, '')) || 0,
            harga_penawaran: parseFloat(row.querySelector('.harga-penawaran-value')?.textContent.replace(/[^\d]/g, '')) || 0,
            margin: parseFloat(row.querySelector('.margin-value')?.textContent.replace(/[^\d]/g, '')) || 0
        };
        data.items.push(item);
    });

    // Here you would typically send the data to the server
    console.log('Saving data:', data);
    
    // Simulate API call
    alert('Kalkulasi berhasil disimpan!');
}

/**
 * Export calculation to Excel
 */
function exportToExcel() {
    const projectId = document.getElementById('modal-project-id')?.textContent;
    const projectName = document.getElementById('modal-project-name')?.textContent;
    
    // Here you would typically generate and download an Excel file
    console.log('Exporting to Excel for project:', projectId);
    alert(`Export Excel untuk proyek "${projectName}" sedang diproses...`);
}

// Initialize HPS functions
if (typeof window.hpsInitialized === 'undefined') {
    window.hpsInitialized = true;
    window.hpsRowCounter = 2;
    
    // Add event listeners when DOM is loaded
    document.addEventListener('DOMContentLoaded', function() {
        // Close modal when clicking outside
        document.addEventListener('click', function(event) {
            const modal = document.getElementById('hps-modal');
            const modalContent = document.getElementById('hps-modal-content');
            
            if (modal && !modal.classList.contains('hidden') && modalContent && !modalContent.contains(event.target)) {
                closeHpsModal();
            }
        });

        // Prevent modal from closing when clicking inside modal content
        const modalContent = document.getElementById('hps-modal-content');
        if (modalContent) {
            modalContent.addEventListener('click', function(event) {
                event.stopPropagation();
            });
        }

        // ESC key to close modal
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                const modal = document.getElementById('hps-modal');
                if (modal && !modal.classList.contains('hidden')) {
                    closeHpsModal();
                }
            }
        });
    });
}

// Auto initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    // Initialize success modal functionality
    if (typeof window.showSuccessModal === 'undefined') {
        window.showSuccessModal = showSuccessModal;
        window.closeSuccessModal = closeSuccessModal;
        window.showSuccessModalWithAutoClose = showSuccessModalWithAutoClose;
    }
});
