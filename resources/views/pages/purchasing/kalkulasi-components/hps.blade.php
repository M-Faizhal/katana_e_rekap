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
            <!-- Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <div class="text-blue-600 text-sm font-medium">Total HPP</div>
                    <div class="text-2xl font-bold text-blue-700" id="total-hpp">Rp 0</div>
                </div>
                <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                    <div class="text-green-600 text-sm font-medium">Total Harga Penawaran</div>
                    <div class="text-2xl font-bold text-green-700" id="total-penawaran">Rp 0</div>
                </div>
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                    <div class="text-yellow-600 text-sm font-medium">Total Margin</div>
                    <div class="text-2xl font-bold text-yellow-700" id="total-margin">Rp 0</div>
                </div>
                <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                    <div class="text-red-600 text-sm font-medium">Nilai Nett</div>
                    <div class="text-2xl font-bold text-red-700" id="nilai-nett">Rp 0</div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="mb-4 flex justify-between items-center">
                <button onclick="addNewRow()" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg flex items-center gap-2">
                    <i class="fas fa-plus"></i>
                    Tambah Item Baru
                </button>
                <div class="flex gap-2">
                    <button onclick="clearAllRows()" class="bg-gray-500 hover:bg-gray-600 text-white px-3 py-2 rounded-lg text-sm">
                        <i class="fas fa-trash-alt mr-1"></i>
                        Hapus Semua
                    </button>
                    <button onclick="duplicateLastRow()" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded-lg text-sm">
                        <i class="fas fa-copy mr-1"></i>
                        Duplikasi Terakhir
                    </button>
                </div>
            </div>

            <!-- Kalkulasi Table -->
            <div class="overflow-x-auto bg-white rounded-lg border border-gray-200">
                <!-- Permintaan Klien Section -->
                <div class="mb-6">
                    <h4 class="text-lg font-semibold text-gray-800 mb-3">
                        <i class="fas fa-user-tie text-blue-600 mr-2"></i>
                        Permintaan Klien
                    </h4>
                    <div class="bg-blue-50 border border-blue-200 rounded-lg overflow-hidden">
                        <table class="w-full">
                            <thead class="bg-blue-100 sticky top-0 z-10">
                                <tr>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-blue-700 uppercase border-r border-blue-200">No</th>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-blue-700 uppercase border-r border-blue-200">Nama Barang</th>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-blue-700 uppercase border-r border-blue-200">Qty</th>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-blue-700 uppercase border-r border-blue-200">Harga Target</th>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-blue-700 uppercase">Total Target</th>
                                </tr>
                            </thead>
                            <tbody id="client-request-table" class="bg-white divide-y divide-blue-200">
                                <!-- Client requests will be loaded here -->
                                <tr>
                                    <td class="px-3 py-2 text-sm text-gray-900 border-r border-blue-200">1</td>
                                    <td class="px-3 py-2 text-sm text-gray-900 border-r border-blue-200">Meja Kayu Jati</td>
                                    <td class="px-3 py-2 text-sm text-gray-900 border-r border-blue-200">10 unit</td>
                                    <td class="px-3 py-2 text-sm text-gray-900 border-r border-blue-200">Rp 250,000</td>
                                    <td class="px-3 py-2 text-sm font-semibold text-gray-900">Rp 2,500,000</td>
                                </tr>
                                <tr>
                                    <td class="px-3 py-2 text-sm text-gray-900 border-r border-blue-200">2</td>
                                    <td class="px-3 py-2 text-sm text-gray-900 border-r border-blue-200">Kursi Kantor Executive</td>
                                    <td class="px-3 py-2 text-sm text-gray-900 border-r border-blue-200">15 unit</td>
                                    <td class="px-3 py-2 text-sm text-gray-900 border-r border-blue-200">Rp 400,000</td>
                                    <td class="px-3 py-2 text-sm font-semibold text-gray-900">Rp 6,000,000</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Daftar Barang Vendor Section -->
                <div>
                    <h4 class="text-lg font-semibold text-gray-800 mb-3">
                        <i class="fas fa-boxes text-green-600 mr-2"></i>
                        Kalkulasi HPS (Harga Perkiraan Sendiri)
                    </h4>
                    <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
                        <div class="max-h-96 overflow-y-auto">
                            <table class="w-full text-xs">
                                <thead class="bg-gray-50 sticky top-0 z-10">
                                    <tr>
                                        <th class="px-2 py-2 text-left text-xs font-medium text-gray-500 uppercase border-r border-gray-200 w-12">No</th>
                                        <th class="px-2 py-2 text-left text-xs font-medium text-gray-500 uppercase border-r border-gray-200 min-w-[120px]">Nama Barang</th>
                                        <th class="px-2 py-2 text-left text-xs font-medium text-gray-500 uppercase border-r border-gray-200 min-w-[100px]">Nama Vendor</th>
                                        <th class="px-2 py-2 text-left text-xs font-medium text-gray-500 uppercase border-r border-gray-200 w-16">Qty</th>
                                        <th class="px-2 py-2 text-left text-xs font-medium text-gray-500 uppercase border-r border-gray-200 min-w-[90px]">Harga Vendor</th>
                                        <th class="px-2 py-2 text-left text-xs font-medium text-gray-500 uppercase border-r border-gray-200 w-20">Diskon (%)</th>
                                        <th class="px-2 py-2 text-left text-xs font-medium text-gray-500 uppercase border-r border-gray-200 min-w-[80px]">Total Diskon</th>
                                        <th class="px-2 py-2 text-left text-xs font-medium text-gray-500 uppercase border-r border-gray-200 min-w-[90px]">Total Harga</th>
                                        <th class="px-2 py-2 text-left text-xs font-medium text-gray-500 uppercase border-r border-gray-200 w-20">Kenaikan (%)</th>
                                        <th class="px-2 py-2 text-left text-xs font-medium text-gray-500 uppercase border-r border-gray-200 min-w-[90px]">Proyeksi Kenaikan</th>
                                        <th class="px-2 py-2 text-left text-xs font-medium text-gray-500 uppercase border-r border-gray-200 min-w-[70px]">PPH 1.5%</th>
                                        <th class="px-2 py-2 text-left text-xs font-medium text-gray-500 uppercase border-r border-gray-200 min-w-[70px]">PPN 11%</th>
                                        <th class="px-2 py-2 text-left text-xs font-medium text-gray-500 uppercase border-r border-gray-200 min-w-[80px] bg-blue-50">HPS</th>
                                        <th class="px-2 py-2 text-left text-xs font-medium text-gray-500 uppercase border-r border-gray-200 min-w-[80px]">Harga/Pcs</th>
                                        <th class="px-2 py-2 text-left text-xs font-medium text-gray-500 uppercase border-r border-gray-200 min-w-[80px]">Pagu/Pcs</th>
                                        <th class="px-2 py-2 text-left text-xs font-medium text-gray-500 uppercase border-r border-gray-200 min-w-[80px]">Pagu Total</th>
                                        <th class="px-2 py-2 text-left text-xs font-medium text-gray-500 uppercase border-r border-gray-200 min-w-[70px]">Selisih</th>
                                        <th class="px-2 py-2 text-left text-xs font-medium text-gray-500 uppercase border-r border-gray-200 min-w-[80px]">Nilai SP</th>
                                        <th class="px-2 py-2 text-left text-xs font-medium text-gray-500 uppercase border-r border-gray-200 min-w-[80px]">DPP Dinas</th>
                                        <th class="px-2 py-2 text-left text-xs font-medium text-gray-500 uppercase border-r border-gray-200 min-w-[70px]">PPN Dinas</th>
                                        <th class="px-2 py-2 text-left text-xs font-medium text-gray-500 uppercase border-r border-gray-200 min-w-[70px]">PPH Dinas</th>
                                        <th class="px-2 py-2 text-left text-xs font-medium text-gray-500 uppercase border-r border-gray-200 min-w-[80px]">Asumsi Cair</th>
                                        <th class="px-2 py-2 text-left text-xs font-medium text-gray-500 uppercase border-r border-gray-200 w-20">Ongkir</th>
                                        <th class="px-2 py-2 text-left text-xs font-medium text-gray-500 uppercase border-r border-gray-200 w-20">Dinas</th>
                                        <th class="px-2 py-2 text-left text-xs font-medium text-gray-500 uppercase border-r border-gray-200 w-20">Bank Cost</th>
                                        <th class="px-2 py-2 text-left text-xs font-medium text-gray-500 uppercase border-r border-gray-200 w-20">Bendera</th>
                                        <th class="px-2 py-2 text-left text-xs font-medium text-gray-500 uppercase border-r border-gray-200 w-20">Biaya Ops</th>
                                        <th class="px-2 py-2 text-left text-xs font-medium text-gray-500 uppercase border-r border-gray-200 min-w-[80px] bg-green-50">Nett</th>
                                        <th class="px-2 py-2 text-left text-xs font-medium text-gray-500 uppercase border-r border-gray-200 w-20">% Nett</th>
                                        <th class="px-2 py-2 text-left text-xs font-medium text-gray-500 uppercase w-20">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="kalkulasi-table-body" class="bg-white divide-y divide-gray-200">
                                    <!-- Data akan dimuat dari JavaScript -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Summary Section -->
            <div class="mt-8 bg-gray-50 rounded-lg p-4">
                <h4 class="text-lg font-semibold text-gray-800 mb-4">Ringkasan Total</h4>
                <div class="grid grid-cols-1 md:grid-cols-6 gap-4 text-center">
                    <div class="bg-white rounded-lg p-3 border">
                        <div class="text-sm text-gray-600">Total HPS</div>
                        <div class="text-lg font-bold text-blue-700" id="grand-total-hps">Rp 0</div>
                    </div>
                    <div class="bg-white rounded-lg p-3 border">
                        <div class="text-sm text-gray-600">Total Pagu</div>
                        <div class="text-lg font-bold text-green-700" id="grand-total-pagu">Rp 0</div>
                    </div>
                    <div class="bg-white rounded-lg p-3 border">
                        <div class="text-sm text-gray-600">Total Selisih</div>
                        <div class="text-lg font-bold text-yellow-700" id="grand-total-selisih">Rp 0</div>
                    </div>
                    <div class="bg-white rounded-lg p-3 border">
                        <div class="text-sm text-gray-600">Total Asumsi Cair</div>
                        <div class="text-lg font-bold text-purple-700" id="grand-total-cair">Rp 0</div>
                    </div>
                    <div class="bg-white rounded-lg p-3 border">
                        <div class="text-sm text-gray-600">Total Nett</div>
                        <div class="text-lg font-bold text-red-700" id="grand-total-nett">Rp 0</div>
                    </div>
                    <div class="bg-white rounded-lg p-3 border">
                        <div class="text-sm text-gray-600">Rata-rata % Nett</div>
                        <div class="text-lg font-bold text-orange-700" id="grand-avg-nett">0%</div>
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
                <button onclick="exportToExcel()" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                    <i class="fas fa-file-excel mr-2"></i>
                    Export Excel
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Include HPS Calculator JavaScript -->
<script src="{{ asset('js/hps-calculator.js') }}"></script>
