/**
 * HPS Calculator JavaScript
 * Handles all calculations and interactions for the HPS (Harga Perkiraan Sendiri) modal
 */

// Global variables
let clientRequestCounter = 2; // Start from 3 since we have 2 sample rows
let vendorItemCounter = 1; // Start from 2 since we have 1 sample row
let hpsData = {
    clientRequests: [],
    vendorItems: []
};

// Sample data for barang and vendors (this would come from database)
const barangData = [
    {
        id: 1,
        name: "Meja Kayu Jati",
        vendors: [
            { id: 1, name: "PT Jati Indah", price: 200000 },
            { id: 2, name: "CV Kayu Lestari", price: 195000 }
        ]
    },
    {
        id: 2,
        name: "Kursi Kantor Executive",
        vendors: [
            { id: 3, name: "PT Furniture Modern", price: 380000 },
            { id: 4, name: "CV Office Solution", price: 400000 }
        ]
    },
    {
        id: 3,
        name: "Lemari Arsip Besi",
        vendors: [
            { id: 5, name: "PT Steel Works", price: 150000 }
        ]
    },
    {
        id: 4,
        name: "Komputer Set",
        vendors: [
            { id: 6, name: "CV Digital Tech", price: 5500000 }
        ]
    }
];

// Initialize when document is ready
document.addEventListener('DOMContentLoaded', function() {
    initializeCalculator();
    setupEventListeners();
});

/**
 * Initialize the calculator with default values
 */
function initializeCalculator() {
    // Initialize currency inputs
    initializeCurrencyInputs();
    
    // Calculate initial values
    updateAllClientRequests();
    calculateRow(1);
    updateSummary();
}

/**
 * Setup event listeners for currency formatting
 */
function setupEventListeners() {
    // Currency input formatting
    document.addEventListener('input', function(e) {
        if (e.target.classList.contains('currency-input')) {
            formatCurrencyInput(e.target);
        }
    });
    
    // Prevent wheel scrolling on number inputs
    document.addEventListener('wheel', function(e) {
        if (e.target.type === 'number') {
            e.preventDefault();
        }
    }, { passive: false });
    
    // Prevent arrow keys from changing number values when not focused
    document.addEventListener('keydown', function(e) {
        if (e.target.type === 'number' && (e.key === 'ArrowUp' || e.key === 'ArrowDown')) {
            if (document.activeElement !== e.target) {
                e.preventDefault();
            }
        }
    });
}

/**
 * Load vendor data when barang is selected
 */
function loadVendorData(rowId) {
    const barangSelect = document.getElementById(`barang-select-${rowId}`);
    const vendorSelect = document.getElementById(`vendor-select-${rowId}`);
    const hargaVendorInput = document.getElementById(`harga-vendor-${rowId}`);
    
    if (!barangSelect || !vendorSelect) return;
    
    const selectedBarangId = parseInt(barangSelect.value);
    
    // Clear vendor options
    vendorSelect.innerHTML = '<option value="">Pilih Vendor...</option>';
    
    if (selectedBarangId) {
        // Find barang data
        const barang = barangData.find(b => b.id === selectedBarangId);
        
        if (barang && barang.vendors) {
            // Populate vendor options
            barang.vendors.forEach(vendor => {
                const option = document.createElement('option');
                option.value = vendor.id;
                option.textContent = vendor.name;
                option.dataset.price = vendor.price;
                vendorSelect.appendChild(option);
            });
            
            // Auto-select first vendor if available
            if (barang.vendors.length > 0) {
                vendorSelect.value = barang.vendors[0].id;
                updateVendorPrice(rowId);
            }
        }
    }
    
    // Clear harga vendor if no barang selected
    if (!selectedBarangId && hargaVendorInput) {
        hargaVendorInput.value = '0';
        formatCurrencyInput(hargaVendorInput);
    }
    
    calculateRow(rowId);
}

/**
 * Update vendor price when vendor is selected
 */
function updateVendorPrice(rowId) {
    const vendorSelect = document.getElementById(`vendor-select-${rowId}`);
    const hargaVendorInput = document.getElementById(`harga-vendor-${rowId}`);
    
    if (!vendorSelect || !hargaVendorInput) return;
    
    const selectedOption = vendorSelect.options[vendorSelect.selectedIndex];
    
    if (selectedOption && selectedOption.dataset.price) {
        const price = parseInt(selectedOption.dataset.price);
        hargaVendorInput.value = price.toLocaleString('id-ID');
        calculateRow(rowId);
    }
}

/**
 * Initialize all currency inputs with proper formatting
 */
function initializeCurrencyInputs() {
    const currencyInputs = document.querySelectorAll('.currency-input');
    currencyInputs.forEach(input => {
        formatCurrencyInput(input);
    });
}

/**
 * Format currency input while typing
 */
function formatCurrencyInput(input) {
    let value = input.value.replace(/[^\d]/g, '');
    if (value) {
        input.value = parseInt(value).toLocaleString('id-ID');
    }
}

/**
 * Convert formatted currency string to number
 */
function parseCurrency(currencyString) {
    if (!currencyString) return 0;
    return parseInt(currencyString.toString().replace(/[^\d]/g, '')) || 0;
}

/**
 * Format number to currency display
 */
function formatCurrency(number) {
    return 'Rp ' + parseInt(number).toLocaleString('id-ID');
}

/**
 * CLIENT REQUEST FUNCTIONS
 */

/**
 * Add new client request row
 */
function addClientRequest() {
    clientRequestCounter++;
    const tableBody = document.getElementById('client-request-table');
    
    const newRow = document.createElement('tr');
    newRow.id = `client-row-${clientRequestCounter}`;
    newRow.innerHTML = `
        <td class="px-3 py-2 text-sm text-gray-900 border-r border-blue-200">${clientRequestCounter}</td>
        <td class="px-3 py-2 border-r border-blue-200">
            <input type="text" placeholder="Nama barang..." class="w-full border-0 bg-transparent text-sm focus:outline-none focus:ring-0" onchange="updateClientRequest(${clientRequestCounter})">
        </td>
        <td class="px-3 py-2 border-r border-blue-200">
            <input type="number" value="1" min="1" class="w-full border-0 bg-transparent text-sm text-center focus:outline-none focus:ring-0" onchange="updateClientRequest(${clientRequestCounter})">
        </td>
        <td class="px-3 py-2 border-r border-blue-200">
            <select class="w-full border-0 bg-transparent text-sm focus:outline-none focus:ring-0" onchange="updateClientRequest(${clientRequestCounter})">
                <option value="unit">unit</option>
                <option value="set">set</option>
                <option value="pack">pack</option>
                <option value="pcs">pcs</option>
                <option value="meter">meter</option>
                <option value="kg">kg</option>
            </select>
        </td>
        <td class="px-3 py-2 border-r border-blue-200">
            <input type="text" placeholder="0" class="w-full border-0 bg-transparent text-sm currency-input focus:outline-none focus:ring-0" onchange="updateClientRequest(${clientRequestCounter})">
        </td>
        <td class="px-3 py-2 text-sm font-semibold text-gray-900 border-r border-blue-200" id="client-total-${clientRequestCounter}">Rp 0</td>
        <td class="px-3 py-2 text-center">
            <button onclick="removeClientRequest(${clientRequestCounter})" class="text-red-500 hover:text-red-700">
                <i class="fas fa-trash text-xs"></i>
            </button>
        </td>
    `;
    
    tableBody.appendChild(newRow);
    
    // Initialize currency input for the new row
    const currencyInput = newRow.querySelector('.currency-input');
    currencyInput.addEventListener('input', function() {
        formatCurrencyInput(this);
    });
}

/**
 * Remove client request row
 */
function removeClientRequest(rowId) {
    const row = document.getElementById(`client-row-${rowId}`);
    if (row) {
        row.remove();
        updateClientRequestNumbers();
        updateAllClientRequests();
    }
}

/**
 * Update client request row numbers after deletion
 */
function updateClientRequestNumbers() {
    const rows = document.querySelectorAll('#client-request-table tr');
    rows.forEach((row, index) => {
        const numberCell = row.querySelector('td:first-child');
        if (numberCell) {
            numberCell.textContent = index + 1;
        }
    });
}

/**
 * Update specific client request calculation
 */
function updateClientRequest(rowId) {
    const row = document.getElementById(`client-row-${rowId}`);
    if (!row) return;
    
    const qty = parseInt(row.querySelector('input[type="number"]').value) || 0;
    const hargaTarget = parseCurrency(row.querySelector('.currency-input').value);
    const total = qty * hargaTarget;
    
    // Update total display
    const totalCell = document.getElementById(`client-total-${rowId}`);
    totalCell.textContent = formatCurrency(total);
    
    // Update grand total
    updateClientGrandTotal();
}

/**
 * Update all client request calculations
 */
function updateAllClientRequests() {
    const rows = document.querySelectorAll('#client-request-table tr');
    rows.forEach(row => {
        const rowId = row.id.split('-')[2];
        if (rowId) {
            updateClientRequest(parseInt(rowId));
        }
    });
}

/**
 * Update client grand total
 */
function updateClientGrandTotal() {
    let grandTotal = 0;
    const totalCells = document.querySelectorAll('[id^="client-total-"]');
    
    totalCells.forEach(cell => {
        const value = parseCurrency(cell.textContent);
        grandTotal += value;
    });
    
    document.getElementById('grand-total-client').textContent = formatCurrency(grandTotal);
}

/**
 * VENDOR ITEM FUNCTIONS
 */

/**
 * Add new vendor item row
 */
function addVendorItem() {
    vendorItemCounter++;
    const tableBody = document.getElementById('kalkulasi-table-body');
    
    // Create barang options HTML
    let barangOptionsHtml = '<option value="">Pilih Barang...</option>';
    barangData.forEach(barang => {
        barangOptionsHtml += `<option value="${barang.id}">${barang.name}</option>`;
    });
    
    const newRow = document.createElement('tr');
    newRow.id = `vendor-row-${vendorItemCounter}`;
    newRow.innerHTML = `
        <td class="px-4 py-3 text-center border-r border-gray-200">${vendorItemCounter}</td>
        <td class="px-4 py-3 border-r border-gray-200">
            <select class="w-full border border-gray-300 rounded-md text-sm p-2 focus:outline-none focus:ring-2 focus:ring-blue-500" onchange="loadVendorData(${vendorItemCounter})" id="barang-select-${vendorItemCounter}">
                ${barangOptionsHtml}
            </select>
        </td>
        <td class="px-4 py-3 border-r border-gray-200">
            <select class="w-full border border-gray-300 rounded-md text-sm p-2 focus:outline-none focus:ring-2 focus:ring-blue-500" onchange="updateVendorPrice(${vendorItemCounter})" id="vendor-select-${vendorItemCounter}">
                <option value="">Pilih Vendor...</option>
            </select>
        </td>
        <td class="px-4 py-3 border-r border-gray-200">
            <input type="number" value="1" min="1" step="1" class="w-full border border-gray-300 rounded-md text-sm p-2 text-center focus:outline-none focus:ring-2 focus:ring-blue-500 no-spin" onchange="calculateRow(${vendorItemCounter})" style="appearance: textfield; -moz-appearance: textfield;">
        </td>
        <td class="px-4 py-3 border-r border-gray-200">
            <select class="w-full border border-gray-300 rounded-md text-sm p-2 focus:outline-none focus:ring-2 focus:ring-blue-500" onchange="calculateRow(${vendorItemCounter})">
                <option value="unit">unit</option>
                <option value="set">set</option>
                <option value="pcs">pcs</option>
                <option value="meter">meter</option>
                <option value="kg">kg</option>
            </select>
        </td>
        <td class="px-4 py-3 border-r border-gray-200">
            <input type="text" value="0" class="w-full border border-gray-300 rounded-md text-sm p-2 currency-input focus:outline-none focus:ring-2 focus:ring-blue-500" onchange="calculateRow(${vendorItemCounter})" id="harga-vendor-${vendorItemCounter}">
        </td>
        <td class="px-4 py-3 border-r border-gray-200">
            <input type="number" value="0" min="0" max="100" step="0.1" class="w-full border border-gray-300 rounded-md text-sm p-2 text-center focus:outline-none focus:ring-2 focus:ring-blue-500 no-spin" onchange="calculateRow(${vendorItemCounter})" style="appearance: textfield; -moz-appearance: textfield;">
        </td>
        <td class="px-4 py-3 border-r border-gray-200 text-sm text-gray-600" id="total-diskon-${vendorItemCounter}">Rp 0</td>
        <td class="px-4 py-3 border-r border-gray-200 text-sm font-semibold bg-yellow-50" id="total-harga-${vendorItemCounter}">Rp 0</td>
        <td class="px-4 py-3 border-r border-gray-200">
            <input type="number" value="0" min="0" step="0.1" class="w-full border border-gray-300 rounded-md text-sm p-2 text-center focus:outline-none focus:ring-2 focus:ring-blue-500 no-spin" onchange="calculateRow(${vendorItemCounter})" style="appearance: textfield; -moz-appearance: textfield;">
        </td>
        <td class="px-4 py-3 border-r border-gray-200 text-sm text-gray-600" id="proyeksi-kenaikan-${vendorItemCounter}">Rp 0</td>
        <td class="px-4 py-3 border-r border-gray-200 text-sm text-gray-600" id="pph-${vendorItemCounter}">Rp 0</td>
        <td class="px-4 py-3 border-r border-gray-200 text-sm text-gray-600" id="ppn-${vendorItemCounter}">Rp 0</td>
        <td class="px-4 py-3 border-r border-gray-200">
            <input type="text" value="0" class="w-full border border-gray-300 rounded-md text-sm p-2 currency-input focus:outline-none focus:ring-2 focus:ring-blue-500" onchange="calculateRow(${vendorItemCounter})">
        </td>
        <td class="px-4 py-3 border-r border-gray-200 text-sm font-semibold bg-blue-50" id="hps-${vendorItemCounter}">Rp 0</td>
        <td class="px-4 py-3 border-r border-gray-200">
            <input type="text" value="0" class="w-full border border-gray-300 rounded-md text-sm p-2 currency-input focus:outline-none focus:ring-2 focus:ring-blue-500" onchange="calculateRow(${vendorItemCounter})">
        </td>
        <td class="px-4 py-3 border-r border-gray-200">
            <input type="text" value="0" class="w-full border border-gray-300 rounded-md text-sm p-2 currency-input focus:outline-none focus:ring-2 focus:ring-blue-500" onchange="calculateRow(${vendorItemCounter})">
        </td>
        <td class="px-4 py-3 border-r border-gray-200">
            <input type="text" value="0" class="w-full border border-gray-300 rounded-md text-sm p-2 currency-input focus:outline-none focus:ring-2 focus:ring-blue-500" onchange="calculateRow(${vendorItemCounter})">
        </td>
        <td class="px-4 py-3 border-r border-gray-200 text-sm font-bold bg-green-50" id="nett-${vendorItemCounter}">Rp 0</td>
        <td class="px-4 py-3 border-r border-gray-200 text-sm font-semibold text-center" id="nett-percent-${vendorItemCounter}">0%</td>
        <td class="px-4 py-3 text-center">
            <button onclick="removeVendorRow(${vendorItemCounter})" class="text-red-500 hover:text-red-700 p-1">
                <i class="fas fa-trash text-sm"></i>
            </button>
        </td>
    `;
    
    tableBody.appendChild(newRow);
    
    // Initialize currency inputs for the new row
    const currencyInputs = newRow.querySelectorAll('.currency-input');
    currencyInputs.forEach(input => {
        input.addEventListener('input', function() {
            formatCurrencyInput(this);
        });
        formatCurrencyInput(input); // Format initial value
    });
}

/**
 * Remove vendor row
 */
function removeVendorRow(rowId) {
    const row = document.getElementById(`vendor-row-${rowId}`);
    if (row) {
        row.remove();
        updateVendorRowNumbers();
        updateSummary();
    }
}

/**
 * Update vendor row numbers after deletion
 */
function updateVendorRowNumbers() {
    const rows = document.querySelectorAll('#kalkulasi-table-body tr');
    rows.forEach((row, index) => {
        const numberCell = row.querySelector('td:first-child');
        if (numberCell) {
            numberCell.textContent = index + 1;
        }
    });
}

/**
 * Calculate all values for a specific vendor row
 */
function calculateRow(rowId) {
    const row = document.getElementById(`vendor-row-${rowId}`);
    if (!row) return;
    
    // Get input values - updated to handle new structure
    const qtyInput = row.querySelector('input[type="number"]');
    const hargaVendorInput = row.querySelector('.currency-input');
    const diskonInput = row.querySelectorAll('input[type="number"]')[1];
    const kenaikanInput = row.querySelectorAll('input[type="number"]')[2];
    const ongkirInput = row.querySelectorAll('.currency-input')[1];
    const bankCostInput = row.querySelectorAll('.currency-input')[2];
    const biayaOpsInput = row.querySelectorAll('.currency-input')[3];
    const benderaInput = row.querySelectorAll('.currency-input')[4];
    
    const qty = parseInt(qtyInput?.value) || 0;
    const hargaVendor = parseCurrency(hargaVendorInput?.value);
    const diskonPercent = parseFloat(diskonInput?.value) || 0;
    const kenaikanPercent = parseFloat(kenaikanInput?.value) || 0;
    const ongkir = parseCurrency(ongkirInput?.value);
    const bankCost = parseCurrency(bankCostInput?.value);
    const biayaOps = parseCurrency(biayaOpsInput?.value);
    const bendera = parseCurrency(benderaInput?.value);
    
    // Calculate step by step according to your formula
    
    // 1. Total Diskon = harga_vendor × qty × (diskon% / 100)
    const totalDiskon = hargaVendor * qty * (diskonPercent / 100);
    
    // 2. Total Harga (HPP) = (harga_vendor × qty) - total_diskon
    const totalHarga = (hargaVendor * qty) - totalDiskon;
    
    // 3. Proyeksi Kenaikan = total_harga × (kenaikan% / 100)
    const proyeksiKenaikan = totalHarga * (kenaikanPercent / 100);
    
    // 4. PPH 1.5% = total_harga × 1.5%
    const pph = totalHarga * 0.015;
    
    // 5. PPN 11% = total_harga × 11%
    const ppn = totalHarga * 0.11;
    
    // 6. HPS = total_harga + proyeksi_kenaikan + pph + ppn + ongkir
    const hps = totalHarga + proyeksiKenaikan + pph + ppn + ongkir;
    
    // 7. Nett = hps - (bank_cost + biaya_ops + bendera)
    const nett = hps - (bankCost + biayaOps + bendera);
    
    // 8. Persentase Nett = ((nett - total_harga) / total_harga) × 100%
    const nettPercent = totalHarga > 0 ? ((nett - totalHarga) / totalHarga) * 100 : 0;
    
    // Update display elements
    const elements = {
        totalDiskon: document.getElementById(`total-diskon-${rowId}`),
        totalHarga: document.getElementById(`total-harga-${rowId}`),
        proyeksiKenaikan: document.getElementById(`proyeksi-kenaikan-${rowId}`),
        pph: document.getElementById(`pph-${rowId}`),
        ppn: document.getElementById(`ppn-${rowId}`),
        hps: document.getElementById(`hps-${rowId}`),
        nett: document.getElementById(`nett-${rowId}`),
        nettPercent: document.getElementById(`nett-percent-${rowId}`)
    };
    
    // Update display if elements exist
    if (elements.totalDiskon) elements.totalDiskon.textContent = formatCurrency(totalDiskon);
    if (elements.totalHarga) elements.totalHarga.textContent = formatCurrency(totalHarga);
    if (elements.proyeksiKenaikan) elements.proyeksiKenaikan.textContent = formatCurrency(proyeksiKenaikan);
    if (elements.pph) elements.pph.textContent = formatCurrency(pph);
    if (elements.ppn) elements.ppn.textContent = formatCurrency(ppn);
    if (elements.hps) elements.hps.textContent = formatCurrency(hps);
    if (elements.nett) elements.nett.textContent = formatCurrency(nett);
    if (elements.nettPercent) elements.nettPercent.textContent = nettPercent.toFixed(1) + '%';
    
    // Update summary
    updateSummary();
}

/**
 * Update summary totals
 */
function updateSummary() {
    let totalHPP = 0;
    let totalHPS = 0;
    let totalNett = 0;
    let totalDiskon = 0;
    let totalPPH = 0;
    let totalPPN = 0;
    let totalOngkir = 0;
    let totalBankCost = 0;
    let totalBiayaOps = 0;
    let totalBendera = 0;
    let avgNettPercent = 0;
    let rowCount = 0;
    
    // Iterate through all vendor rows
    const rows = document.querySelectorAll('#kalkulasi-table-body tr');
    rows.forEach(row => {
        const rowId = row.id.split('-')[2];
        if (rowId && document.getElementById(`total-harga-${rowId}`)) {
            totalHPP += parseCurrency(document.getElementById(`total-harga-${rowId}`).textContent);
            totalHPS += parseCurrency(document.getElementById(`hps-${rowId}`).textContent);
            totalNett += parseCurrency(document.getElementById(`nett-${rowId}`).textContent);
            totalDiskon += parseCurrency(document.getElementById(`total-diskon-${rowId}`).textContent);
            totalPPH += parseCurrency(document.getElementById(`pph-${rowId}`).textContent);
            totalPPN += parseCurrency(document.getElementById(`ppn-${rowId}`).textContent);
            
            // Get operational costs from inputs
            const currencyInputs = row.querySelectorAll('.currency-input');
            if (currencyInputs.length >= 5) {
                totalOngkir += parseCurrency(currencyInputs[1].value);
                totalBankCost += parseCurrency(currencyInputs[2].value);
                totalBiayaOps += parseCurrency(currencyInputs[3].value);
                totalBendera += parseCurrency(currencyInputs[4].value);
            }
            
            // Calculate average percentage
            const percentText = document.getElementById(`nett-percent-${rowId}`).textContent;
            const percent = parseFloat(percentText.replace('%', '')) || 0;
            avgNettPercent += percent;
            rowCount++;
        }
    });
    
    // Calculate averages
    avgNettPercent = rowCount > 0 ? avgNettPercent / rowCount : 0;
    const totalBiayaTidakLangsung = totalBankCost + totalBiayaOps + totalBendera;
    
    // Update summary displays
    document.getElementById('grand-total-hpp').textContent = formatCurrency(totalHPP);
    document.getElementById('grand-total-hps').textContent = formatCurrency(totalHPS);
    document.getElementById('grand-total-nett').textContent = formatCurrency(totalNett);
    document.getElementById('grand-avg-nett').textContent = avgNettPercent.toFixed(1) + '%';
    
    // Update breakdown displays
    document.getElementById('breakdown-diskon').textContent = formatCurrency(totalDiskon);
    document.getElementById('breakdown-pph').textContent = formatCurrency(totalPPH);
    document.getElementById('breakdown-ppn').textContent = formatCurrency(totalPPN);
    document.getElementById('breakdown-ongkir').textContent = formatCurrency(totalOngkir);
    document.getElementById('breakdown-bank').textContent = formatCurrency(totalBankCost);
    document.getElementById('breakdown-ops').textContent = formatCurrency(totalBiayaOps);
    document.getElementById('breakdown-bendera').textContent = formatCurrency(totalBendera);
    document.getElementById('breakdown-total-biaya').textContent = formatCurrency(totalBiayaTidakLangsung);
    
    // Update top summary cards
    document.getElementById('total-hpp').textContent = formatCurrency(totalHPP);
    document.getElementById('total-penawaran').textContent = formatCurrency(totalHPS);
    document.getElementById('total-margin').textContent = formatCurrency(totalNett - totalHPP);
    document.getElementById('nilai-nett').textContent = formatCurrency(totalNett);
}

/**
 * UTILITY FUNCTIONS
 */

/**
 * Clear only vendor data (not client requests)
 */
function clearVendorData() {
    if (confirm('Apakah Anda yakin ingin menghapus semua data vendor? Tindakan ini tidak dapat dibatalkan.')) {
        // Clear vendor items (keep headers)
        const vendorTable = document.getElementById('kalkulasi-table-body');
        vendorTable.innerHTML = '';
        vendorItemCounter = 0;
        
        // Reset summary
        updateSummary();
        
        // Show success modal
        showSuccessModal('Semua data vendor berhasil dihapus! Tabel telah dibersihkan.');
    }
}

/**
 * Clear all data
 */
function clearAllData() {
    // Redirect to clearVendorData since we don't want to clear client requests
    clearVendorData();
}

/**
 * Recalculate all rows
 */
function recalculateAll() {
    // Show loading state
    const recalcButton = document.querySelector('button[onclick="recalculateAll()"]');
    const originalContent = recalcButton.innerHTML;
    recalcButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i>Menghitung...';
    recalcButton.disabled = true;
    
    // Simulate calculation process
    setTimeout(() => {
        // Recalculate all vendor rows (client requests are read-only)
        const vendorRows = document.querySelectorAll('#kalkulasi-table-body tr');
        vendorRows.forEach(row => {
            const rowId = row.id.split('-')[2];
            if (rowId) {
                calculateRow(parseInt(rowId));
            }
        });
        
        // Reset button state
        recalcButton.innerHTML = originalContent;
        recalcButton.disabled = false;
        
        // Show success modal
        showSuccessModal('Semua kalkulasi vendor telah diperbarui! Perhitungan telah disesuaikan.');
    }, 800);
}

/**
 * MODAL FUNCTIONS
 */

/**
 * Close HPS modal
 */
function closeHpsModal() {
    document.getElementById('hps-modal').classList.add('hidden');
}

/**
 * Save calculation data
 */
function saveKalkulasi() {
    // Show loading state
    const saveButton = document.querySelector('button[onclick="saveKalkulasi()"]');
    const originalContent = saveButton.innerHTML;
    saveButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Menyimpan...';
    saveButton.disabled = true;
    
    // Collect all data
    const data = {
        clientRequests: collectClientRequestData(),
        vendorItems: collectVendorItemData(),
        summary: collectSummaryData(),
        timestamp: new Date().toISOString()
    };
    
    // Simulate API call (replace with actual AJAX request to Laravel backend)
    setTimeout(() => {
        // Here you would typically send data to server
        console.log('Saving HPS calculation:', data);
        
        // Reset button state
        saveButton.innerHTML = originalContent;
        saveButton.disabled = false;
        
        // Show success modal with custom message
        showSuccessModal('Kalkulasi HPS berhasil disimpan! Data telah tersimpan dalam sistem.');
        
        // Optional: Close HPS modal after successful save
        // setTimeout(() => {
        //     closeHpsModal();
        // }, 2000);
        
    }, 1500); // Simulate loading time
}

/**
 * Export to Excel
 */
function exportToExcel() {
    // Show loading state
    const exportButton = document.querySelector('button[onclick="exportToExcel()"]');
    const originalContent = exportButton.innerHTML;
    exportButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Export...';
    exportButton.disabled = true;
    
    // Collect all data for export
    const data = {
        clientRequests: collectClientRequestData(),
        vendorItems: collectVendorItemData(),
        summary: collectSummaryData()
    };
    
    // Simulate export process (replace with actual export functionality)
    setTimeout(() => {
        // Here you would typically generate and download Excel file
        console.log('Exporting to Excel:', data);
        
        // Reset button state
        exportButton.innerHTML = originalContent;
        exportButton.disabled = false;
        
        // Show success modal for export
        showSuccessModal('Data berhasil diekspor ke Excel! File akan segera diunduh.');
        
    }, 2000); // Simulate export time
}

/**
 * Template functions
 */
function importFromTemplate() {
    // Show loading state
    const importButton = document.querySelector('button[onclick="importFromTemplate()"]');
    const originalContent = importButton.innerHTML;
    importButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Import...';
    importButton.disabled = true;
    
    // Simulate import process
    setTimeout(() => {
        // Reset button state
        importButton.innerHTML = originalContent;
        importButton.disabled = false;
        
        // Show success modal
        showSuccessModal('Template vendor berhasil diimport! Data telah ditambahkan ke tabel.');
        
        // Here you would actually load template data
        // addVendorItem(); // Example: add a new row with template data
        
    }, 1500);
}

function saveAsTemplate() {
    const templateName = prompt('Masukkan nama template:');
    if (templateName) {
        // Show loading state
        const saveTemplateButton = document.querySelector('button[onclick="saveAsTemplate()"]');
        const originalContent = saveTemplateButton.innerHTML;
        saveTemplateButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Menyimpan...';
        saveTemplateButton.disabled = true;
        
        // Simulate save process
        setTimeout(() => {
            // Reset button state
            saveTemplateButton.innerHTML = originalContent;
            saveTemplateButton.disabled = false;
            
            // Show success modal
            showSuccessModal(`Template "${templateName}" berhasil disimpan! Template dapat digunakan untuk proyek lain.`);
            
        }, 1000);
    }
}

/**
 * DATA COLLECTION FUNCTIONS
 */

function collectClientRequestData() {
    const data = [];
    const rows = document.querySelectorAll('#client-request-table tr');
    
    rows.forEach(row => {
        const inputs = row.querySelectorAll('input, select');
        if (inputs.length >= 4) {
            data.push({
                namaBarang: inputs[0].value,
                qty: parseInt(inputs[1].value) || 0,
                satuan: inputs[2].value,
                hargaTarget: parseCurrency(inputs[3].value)
            });
        }
    });
    
    return data;
}

function collectVendorItemData() {
    const data = [];
    const rows = document.querySelectorAll('#kalkulasi-table-body tr');
    
    rows.forEach(row => {
        const inputs = row.querySelectorAll('input, select');
        const rowId = row.id.split('-')[2];
        
        if (inputs.length >= 8 && rowId) {
            data.push({
                namaBarang: inputs[0].value,
                namaVendor: inputs[1].value,
                qty: parseInt(inputs[2].value) || 0,
                satuan: inputs[3].value,
                hargaVendor: parseCurrency(inputs[4].value),
                diskonPercent: parseFloat(inputs[5].value) || 0,
                kenaikanPercent: parseFloat(inputs[6].value) || 0,
                ongkir: parseCurrency(inputs[7].value),
                bankCost: parseCurrency(inputs[8].value),
                biayaOps: parseCurrency(inputs[9].value),
                bendera: parseCurrency(inputs[10].value),
                // Calculated values
                totalDiskon: parseCurrency(document.getElementById(`total-diskon-${rowId}`).textContent),
                totalHarga: parseCurrency(document.getElementById(`total-harga-${rowId}`).textContent),
                proyeksiKenaikan: parseCurrency(document.getElementById(`proyeksi-kenaikan-${rowId}`).textContent),
                pph: parseCurrency(document.getElementById(`pph-${rowId}`).textContent),
                ppn: parseCurrency(document.getElementById(`ppn-${rowId}`).textContent),
                hps: parseCurrency(document.getElementById(`hps-${rowId}`).textContent),
                nett: parseCurrency(document.getElementById(`nett-${rowId}`).textContent),
                nettPercent: parseFloat(document.getElementById(`nett-percent-${rowId}`).textContent.replace('%', ''))
            });
        }
    });
    
    return data;
}

function collectSummaryData() {
    return {
        totalHPP: parseCurrency(document.getElementById('grand-total-hpp').textContent),
        totalHPS: parseCurrency(document.getElementById('grand-total-hps').textContent),
        totalNett: parseCurrency(document.getElementById('grand-total-nett').textContent),
        avgNettPercent: parseFloat(document.getElementById('grand-avg-nett').textContent.replace('%', '')),
        totalClientRequest: parseCurrency(document.getElementById('grand-total-client').textContent)
    };
}
