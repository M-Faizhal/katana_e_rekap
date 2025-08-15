<!-- HPS Modal -->
<div id="hps-modal" class="hidden fixed inset-0 bg-black/20  backdrop-blur-xs overflow-y-auto h-full w-full z-50">
    <div class="relative top-4 mx-auto p-5 border w-11/12 max-w-7xl shadow-lg rounded-md bg-white" id="hps-modal-content">
        <!-- Modal Header -->
        <div class="flex items-center justify-between p-4 border-b border-gray-200">
            <div>
                <h3 class="text-lg font-semibold text-gray-900">Kalkulasi HPS (Harga Perkiraan Sendiri)</h3>
                <div class="text-sm text-gray-600 mt-1">
                    <span class="font-medium">Proyek:</span> <span id="modal-project-name">-</span> |
                    <span class="font-medium">ID:</span> <span id="modal-project-id">-</span> |
                    <span class="font-medium">Klien:</span> <span id="modal-client-name">-</span>
                </div>
            </div>
            <button onclick="closeHpsModal()" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>

        <!-- Modal Content -->
        <div class="p-4 max-h-[80vh] overflow-y-auto">
            <!-- Action Buttons -->
            <div class="mb-6 flex justify-between items-center bg-gray-50 p-4 rounded-lg">
                
                <div class="flex gap-2">
                    <button onclick="clearVendorData()" class="bg-gray-500 hover:bg-gray-600 text-white px-3 py-2 rounded-lg text-sm">
                        <i class="fas fa-trash-alt mr-1"></i>
                        Hapus Data Vendor
                    </button>
                    <button onclick="recalculateAll()" class="bg-orange-600 hover:bg-orange-700 text-white px-3 py-2 rounded-lg text-sm">
                        <i class="fas fa-calculator mr-1"></i>
                        Hitung Ulang
                    </button>
                </div>
            </div>

            <!-- Kalkulasi Table -->
            <div class="overflow-x-auto bg-white rounded-lg border border-gray-200">
                <!-- Permintaan Klien Section -->
                <div class="mb-6">
                    <div class="flex justify-between items-center mb-3">
                        <h4 class="text-lg font-semibold text-gray-800">
                            <i class="fas fa-user-tie text-blue-600 mr-2"></i>
                            Permintaan Klien
                            <span class="text-sm font-normal text-gray-500 ml-2">(Dari Admin Marketing - Read Only)</span>
                        </h4>
                        <div class="bg-blue-100 text-blue-700 px-3 py-2 rounded-lg text-sm flex items-center gap-2">
                            <i class="fas fa-lock"></i>
                            Data Terkunci
                        </div>
                    </div>
                    <div class="bg-blue-50 border border-blue-200 rounded-lg overflow-hidden">
                        <div class="max-h-60 overflow-y-auto">
                            <table class="w-full">
                                <thead class="bg-blue-100 sticky top-0 z-10">
                                    <tr>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-blue-700 uppercase border-r border-blue-200 w-12">No</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-blue-700 uppercase border-r border-blue-200 min-w-[150px]">Nama Barang</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-blue-700 uppercase border-r border-blue-200 w-20">Qty</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-blue-700 uppercase border-r border-blue-200 w-24">Satuan</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-blue-700 uppercase border-r border-blue-200 min-w-[100px]">Harga Target</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-blue-700 uppercase border-r border-blue-200 min-w-[100px]">Total Target</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-blue-700 uppercase w-20">Status</th>
                                    </tr>
                                </thead>
                                <tbody id="client-request-table" class="bg-white divide-y divide-blue-200">
                                    <!-- Client requests will be loaded here -->
                                    <tr id="client-row-1">
                                        <td class="px-3 py-2 text-sm text-gray-900 border-r border-blue-200">1</td>
                                        <td class="px-3 py-2 border-r border-blue-200">
                                            <span class="text-sm text-gray-800">Meja Kayu Jati</span>
                                        </td>
                                        <td class="px-3 py-2 border-r border-blue-200">
                                            <span class="text-sm text-gray-800 text-center block">10</span>
                                        </td>
                                        <td class="px-3 py-2 border-r border-blue-200">
                                            <span class="text-sm text-gray-800">unit</span>
                                        </td>
                                        <td class="px-3 py-2 border-r border-blue-200">
                                            <span class="text-sm text-gray-800">Rp 250,000</span>
                                        </td>
                                        <td class="px-3 py-2 text-sm font-semibold text-gray-900 border-r border-blue-200" id="client-total-1">Rp 2,500,000</td>
                                        <td class="px-3 py-2 text-center">
                                            <i class="fas fa-eye text-gray-400"></i>
                                        </td>
                                    </tr>
                                    <tr id="client-row-2">
                                        <td class="px-3 py-2 text-sm text-gray-900 border-r border-blue-200">2</td>
                                        <td class="px-3 py-2 border-r border-blue-200">
                                            <span class="text-sm text-gray-800">Kursi Kantor Executive</span>
                                        </td>
                                        <td class="px-3 py-2 border-r border-blue-200">
                                            <span class="text-sm text-gray-800 text-center block">15</span>
                                        </td>
                                        <td class="px-3 py-2 border-r border-blue-200">
                                            <span class="text-sm text-gray-800">unit</span>
                                        </td>
                                        <td class="px-3 py-2 border-r border-blue-200">
                                            <span class="text-sm text-gray-800">Rp 400,000</span>
                                        </td>
                                        <td class="px-3 py-2 text-sm font-semibold text-gray-900 border-r border-blue-200" id="client-total-2">Rp 6,000,000</td>
                                        <td class="px-3 py-2 text-center">
                                            <i class="fas fa-eye text-gray-400"></i>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="bg-blue-100 px-3 py-2 border-t border-blue-200">
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-medium text-blue-700">Total Permintaan Klien:</span>
                                <span class="text-lg font-bold text-blue-800" id="grand-total-client">Rp 8,500,000</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Daftar Barang Vendor Section -->
                <div>
                    <div class="flex justify-between items-center mb-3">
                        <h4 class="text-lg font-semibold text-gray-800">
                            <i class="fas fa-boxes text-green-600 mr-2"></i>
                            Kalkulasi HPS (Harga Perkiraan Sendiri)
                            <span class="text-sm font-normal text-green-600 ml-2">(Area Admin Purchasing)</span>
                        </h4>
                        <button onclick="addVendorItem()" class="bg-green-600 hover:bg-green-700 text-white px-3 py-2 rounded-lg text-sm flex items-center gap-2">
                            <i class="fas fa-plus"></i>
                            Tambah Item Vendor
                        </button>
                    </div>
                    <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
                        <div class="max-h-96 overflow-y-auto overflow-x-auto">
                            <table class="w-full text-sm hps-table">
                                <thead class="bg-gray-50 sticky top-0 z-10">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase border-r border-gray-200 w-12">No</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase border-r border-gray-200 min-w-[180px]">Nama Barang</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase border-r border-gray-200 min-w-[150px]">Nama Vendor</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase border-r border-gray-200 w-20">Qty</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase border-r border-gray-200 w-24">Satuan</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase border-r border-gray-200 min-w-[120px]">Harga Vendor</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase border-r border-gray-200 w-24">Diskon</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase border-r border-gray-200 min-w-[100px]">Total Diskon</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase border-r border-gray-200 min-w-[120px] bg-yellow-50">Total Harga (HPP)</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase border-r border-gray-200 w-24">Kenaikan (%)</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase border-r border-gray-200 min-w-[100px]">Proyeksi Kenaikan</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase border-r border-gray-200 min-w-[90px]">PPH 1.5%</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase border-r border-gray-200 min-w-[90px]">PPN 11%</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase border-r border-gray-200 min-w-[100px]">Ongkir</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase border-r border-gray-200 min-w-[120px] bg-blue-50">HPS</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase border-r border-gray-200 min-w-[100px]">Bank Cost</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase border-r border-gray-200 min-w-[100px]">Biaya Ops</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase border-r border-gray-200 min-w-[100px]">Bendera</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase border-r border-gray-200 min-w-[120px] bg-green-50">Nett</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase border-r border-gray-200 w-20">% Nett</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase w-20">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="kalkulasi-table-body" class="bg-white divide-y divide-gray-200">
                                    <!-- Sample data -->
                                    <tr id="vendor-row-1">
                                        <td class="px-4 py-3 text-center border-r border-gray-200">1</td>
                                        <td class="px-4 py-3 border-r border-gray-200">
                                            <select class="w-full border border-gray-300 rounded-md text-sm p-2 focus:outline-none focus:ring-2 focus:ring-blue-500" onchange="loadVendorData(1)" id="barang-select-1">
                                                <option value="">Pilih Barang...</option>
                                                <option value="1" data-vendors='[{"id":1,"name":"PT Jati Indah","price":"200000"},{"id":2,"name":"CV Kayu Lestari","price":"195000"}]' selected>Meja Kayu Jati</option>
                                                <option value="2" data-vendors='[{"id":3,"name":"PT Furniture Modern","price":"380000"},{"id":4,"name":"CV Office Solution","price":"400000"}]'>Kursi Kantor Executive</option>
                                                <option value="3" data-vendors='[{"id":5,"name":"PT Steel Works","price":"150000"}]'>Lemari Arsip Besi</option>
                                                <option value="4" data-vendors='[{"id":6,"name":"CV Digital Tech","price":"5500000"}]'>Komputer Set</option>
                                            </select>
                                        </td>
                                        <td class="px-4 py-3 border-r border-gray-200">
                                            <select class="w-full border border-gray-300 rounded-md text-sm p-2 focus:outline-none focus:ring-2 focus:ring-blue-500" onchange="updateVendorPrice(1)" id="vendor-select-1">
                                                <option value="">Pilih Vendor...</option>
                                                <option value="1" data-price="200000" selected>PT Jati Indah</option>
                                                <option value="2" data-price="195000">CV Kayu Lestari</option>
                                            </select>
                                        </td>
                                        <td class="px-4 py-3 border-r border-gray-200">
                                            <input type="number" value="10" min="1" step="1" class="w-full border border-gray-300 rounded-md text-sm p-2 text-center focus:outline-none focus:ring-2 focus:ring-blue-500 no-spin" onchange="calculateRow(1)" style="appearance: textfield; -moz-appearance: textfield;">
                                        </td>
                                        <td class="px-4 py-3 border-r border-gray-200">
                                            <select class="w-full border border-gray-300 rounded-md text-sm p-2 focus:outline-none focus:ring-2 focus:ring-blue-500" onchange="calculateRow(1)">
                                                <option value="unit" selected>unit</option>
                                                <option value="set">set</option>
                                                <option value="pcs">pcs</option>
                                                <option value="meter">meter</option>
                                                <option value="kg">kg</option>
                                            </select>
                                        </td>
                                        <td class="px-4 py-3 border-r border-gray-200">
                                            <input type="text" value="200,000" class="w-full border border-gray-300 rounded-md text-sm p-2 currency-input focus:outline-none focus:ring-2 focus:ring-blue-500" onchange="calculateRow(1)" id="harga-vendor-1">
                                        </td>
                                        <td class="px-4 py-3 border-r border-gray-200">
                                            <input type="number" value="5" min="0" max="100" step="0.1" class="w-full border border-gray-300 rounded-md text-sm p-2 text-center focus:outline-none focus:ring-2 focus:ring-blue-500 no-spin" onchange="calculateRow(1)" style="appearance: textfield; -moz-appearance: textfield;">
                                        </td>
                                        <td class="px-4 py-3 border-r border-gray-200 text-sm text-gray-600" id="total-diskon-1">Rp 100,000</td>
                                        <td class="px-4 py-3 border-r border-gray-200 text-sm font-semibold bg-yellow-50" id="total-harga-1">Rp 1,900,000</td>
                                        <td class="px-4 py-3 border-r border-gray-200">
                                            <input type="number" value="0" min="0" step="0.1" class="w-full border border-gray-300 rounded-md text-sm p-2 text-center focus:outline-none focus:ring-2 focus:ring-blue-500 no-spin" onchange="calculateRow(1)" style="appearance: textfield; -moz-appearance: textfield;">
                                        </td>
                                        <td class="px-4 py-3 border-r border-gray-200 text-sm text-gray-600" id="proyeksi-kenaikan-1">Rp 0</td>
                                        <td class="px-4 py-3 border-r border-gray-200 text-sm text-gray-600" id="pph-1">Rp 28,500</td>
                                        <td class="px-4 py-3 border-r border-gray-200 text-sm text-gray-600" id="ppn-1">Rp 209,000</td>
                                        <td class="px-4 py-3 border-r border-gray-200">
                                            <input type="text" value="50,000" class="w-full border border-gray-300 rounded-md text-sm p-2 currency-input focus:outline-none focus:ring-2 focus:ring-blue-500" onchange="calculateRow(1)">
                                        </td>
                                        <td class="px-4 py-3 border-r border-gray-200 text-sm font-semibold bg-blue-50" id="hps-1">Rp 2,187,500</td>
                                        <td class="px-4 py-3 border-r border-gray-200">
                                            <input type="text" value="25,000" class="w-full border border-gray-300 rounded-md text-sm p-2 currency-input focus:outline-none focus:ring-2 focus:ring-blue-500" onchange="calculateRow(1)">
                                        </td>
                                        <td class="px-4 py-3 border-r border-gray-200">
                                            <input type="text" value="15,000" class="w-full border border-gray-300 rounded-md text-sm p-2 currency-input focus:outline-none focus:ring-2 focus:ring-blue-500" onchange="calculateRow(1)">
                                        </td>
                                        <td class="px-4 py-3 border-r border-gray-200">
                                            <input type="text" value="10,000" class="w-full border border-gray-300 rounded-md text-sm p-2 currency-input focus:outline-none focus:ring-2 focus:ring-blue-500" onchange="calculateRow(1)">
                                        </td>
                                        <td class="px-4 py-3 border-r border-gray-200 text-sm font-bold bg-green-50" id="nett-1">Rp 2,137,500</td>
                                        <td class="px-4 py-3 border-r border-gray-200 text-sm font-semibold text-center" id="nett-percent-1">12.5%</td>
                                        <td class="px-4 py-3 text-center">
                                            <button onclick="removeVendorRow(1)" class="text-red-500 hover:text-red-700 p-1">
                                                <i class="fas fa-trash text-sm"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Summary Section -->
            <div class="mt-8 bg-gray-50 rounded-lg p-4">
                <h4 class="text-lg font-semibold text-gray-800 mb-4">Ringkasan Total</h4>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 text-center">
                    <div class="bg-white rounded-lg p-3 border">
                        <div class="text-sm text-gray-600">Total HPP (Modal)</div>
                        <div class="text-lg font-bold text-yellow-700" id="grand-total-hpp">Rp 1,900,000</div>
                        <div class="text-xs text-gray-500">Harga beli dari vendor</div>
                    </div>
                    <div class="bg-white rounded-lg p-3 border">
                        <div class="text-sm text-gray-600">Total HPS</div>
                        <div class="text-lg font-bold text-blue-700" id="grand-total-hps">Rp 2,187,500</div>
                        <div class="text-xs text-gray-500">Harga penawaran ke klien</div>
                    </div>
                    <div class="bg-white rounded-lg p-3 border">
                        <div class="text-sm text-gray-600">Total Nett</div>
                        <div class="text-lg font-bold text-green-700" id="grand-total-nett">Rp 2,137,500</div>
                        <div class="text-xs text-gray-500">Pendapatan bersih</div>
                    </div>
                    <div class="bg-white rounded-lg p-3 border">
                        <div class="text-sm text-gray-600">Rata-rata % Nett</div>
                        <div class="text-lg font-bold text-red-700" id="grand-avg-nett">12.5%</div>
                        <div class="text-xs text-gray-500">Margin bersih dari modal</div>
                    </div>
                </div>
                
                <!-- Detailed Breakdown -->
                <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-white rounded-lg p-4 border">
                        <h5 class="font-semibold text-gray-800 mb-3">Breakdown Biaya</h5>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span>Total Diskon:</span>
                                <span class="font-medium text-green-600" id="breakdown-diskon">Rp 100,000</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Total PPH 1.5%:</span>
                                <span class="font-medium text-orange-600" id="breakdown-pph">Rp 28,500</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Total PPN 11%:</span>
                                <span class="font-medium text-orange-600" id="breakdown-ppn">Rp 209,000</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Total Ongkir:</span>
                                <span class="font-medium text-blue-600" id="breakdown-ongkir">Rp 50,000</span>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white rounded-lg p-4 border">
                        <h5 class="font-semibold text-gray-800 mb-3">Biaya Operasional</h5>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span>Total Bank Cost:</span>
                                <span class="font-medium text-red-600" id="breakdown-bank">Rp 25,000</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Total Biaya Ops:</span>
                                <span class="font-medium text-red-600" id="breakdown-ops">Rp 15,000</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Total Bendera:</span>
                                <span class="font-medium text-red-600" id="breakdown-bendera">Rp 10,000</span>
                            </div>
                            <hr class="my-2">
                            <div class="flex justify-between font-semibold">
                                <span>Total Biaya Tidak Langsung:</span>
                                <span class="text-red-700" id="breakdown-total-biaya">Rp 50,000</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Footer -->
        <div class="flex items-center justify-between p-4 border-t border-gray-200">
            <div class="text-sm text-gray-600">
                Terakhir diupdate: <span class="font-medium">{{ date('d M Y, H:i') }}</span>
            </div>
            <div class="flex gap-3">
                <button onclick="closeHpsModal()" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                    Tutup
                </button>
                <button onclick="saveKalkulasi()" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                    <i class="fas fa-save mr-2"></i>
                    Simpan Kalkulasi
                </button>
           
            </div>
        </div>
    </div>
</div>

<!-- Include Success Modal -->
@include('components.success-modal')

<!-- Include HPS Calculator JavaScript -->
<script src="{{ asset('js/hps-calculator.js') }}"></script>

<!-- Custom CSS for removing number input spinners -->
<style>
/* Remove spinner arrows from number inputs */
.no-spin::-webkit-outer-spin-button,
.no-spin::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
}

.no-spin[type=number] {
    -moz-appearance: textfield;
}

/* Better table layout */
.hps-table {
    min-width: 2000px; /* Ensure table has minimum width for better spacing */
}

/* Focus styles for dropdowns and inputs */
select:focus, input:focus {
    box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.5);
    border-color: #3b82f6;
}


</style>
