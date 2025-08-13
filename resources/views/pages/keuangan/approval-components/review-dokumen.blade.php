<!-- Modal Review Dokumen -->
<div id="modalReviewDokumen" class="fixed inset-0 backdrop-blur-xs bg-black/30 hidden items-center justify-center z-50 p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-6xl max-h-screen overflow-hidden my-4 mx-auto">
        <!-- Modal Header -->
        <div class="bg-blue-800 text-white p-6 flex items-center justify-between flex-shrink-0">
            <div class="flex items-center space-x-3">
                <div class="w-12 h-12 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                    <i class="fas fa-file-alt text-white text-xl"></i>
                </div>
                <div>
                    <h3 class="text-xl font-bold">Review Dokumen Pembayaran</h3>
                    <p class="text-blue-100 text-sm">Review dan verifikasi dokumen sebelum approval</p>
                </div>
            </div>
            <button onclick="closeReviewModal()" class="text-white hover:bg-white hover:text-blue-800 p-2 rounded-lg transition-colors duration-200">
                <i class="fas fa-times text-2xl"></i>
            </button>
        </div>

        <!-- Modal Body -->
        <div class="p-6 overflow-y-auto flex-1" style="max-height: calc(100vh - 200px);">
            <!-- Request Information -->
            <div class="bg-gray-50 rounded-xl p-6 mb-6">
                <h4 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                    Informasi Permintaan
                </h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Request ID</label>
                        <p id="reviewRequestId" class="text-lg font-semibold text-gray-900">#PAY-001</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Pemohon</label>
                        <p id="reviewRequester" class="text-lg font-semibold text-gray-900">Ahmad Rizki</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Departemen</label>
                        <p id="reviewDepartment" class="text-lg font-semibold text-gray-900">Marketing</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah</label>
                        <p id="reviewAmount" class="text-lg font-bold text-green-600">Rp 5,500,000</p>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                        <p id="reviewDescription" class="text-gray-900">Pembayaran vendor printing untuk kebutuhan marketing campaign Q3 2024</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Request</label>
                        <p id="reviewRequestDate" class="text-gray-900">10 Agustus 2024</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Deadline</label>
                        <p id="reviewDeadline" class="text-red-600 font-medium">15 Agustus 2024</p>
                    </div>
                </div>
            </div>

            <!-- Document Status -->
            <div class="bg-gray-50 rounded-xl p-6 mb-6">
                <h4 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-clipboard-check text-blue-600 mr-2"></i>
                    Status Kelengkapan Dokumen
                </h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Purchasing Documents -->
                    <div class="bg-white rounded-lg p-4 border-l-4 border-blue-500">
                        <h5 class="font-semibold text-gray-800 mb-3 flex items-center">
                            <i class="fas fa-shopping-cart text-blue-600 mr-2"></i>
                            Dokumen Purchasing
                        </h5>
                        <div class="space-y-2">
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600">Purchase Order</span>
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                    <i class="fas fa-check mr-1"></i>Lengkap
                                </span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600">Invoice Vendor</span>
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                    <i class="fas fa-check mr-1"></i>Lengkap
                                </span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600">Bukti Penerimaan Barang</span>
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                    <i class="fas fa-check mr-1"></i>Lengkap
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Marketing Documents -->
                    <div class="bg-white rounded-lg p-4 border-l-4 border-green-500">
                        <h5 class="font-semibold text-gray-800 mb-3 flex items-center">
                            <i class="fas fa-bullhorn text-green-600 mr-2"></i>
                            Dokumen Marketing
                        </h5>
                        <div class="space-y-2">
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600">Budget Approval</span>
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                    <i class="fas fa-check mr-1"></i>Lengkap
                                </span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600">Campaign Brief</span>
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                    <i class="fas fa-check mr-1"></i>Lengkap
                                </span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600">Cost Breakdown</span>
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                    <i class="fas fa-exclamation mr-1"></i>Review
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Document Gallery -->
            <div class="bg-gray-50 rounded-xl p-6 mb-6">
                <h4 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-images text-blue-600 mr-2"></i>
                    Galeri Dokumen
                </h4>

                <!-- Document Tabs -->
                <div class="mb-6">
                    <nav class="flex space-x-8" aria-label="Tabs">
                        <button onclick="switchDocumentTab('purchasing')" id="tab-purchasing" class="whitespace-nowrap py-2 px-1 border-b-2 border-blue-500 font-medium text-sm text-blue-600">
                            Dokumen Purchasing
                        </button>
                        <button onclick="switchDocumentTab('marketing')" id="tab-marketing" class="whitespace-nowrap py-2 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300">
                            Dokumen Marketing
                        </button>
                    </nav>
                </div>

                <!-- Purchasing Documents -->
                <div id="purchasing-docs" class="document-tab">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <!-- Purchase Order -->
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                            <div class="p-4 border-b border-gray-200">
                                <h5 class="font-semibold text-gray-800 flex items-center">
                                    <i class="fas fa-file-alt text-blue-600 mr-2"></i>
                                    Purchase Order
                                </h5>
                                <p class="text-sm text-gray-500 mt-1">PO-2024-0815-001.pdf</p>
                            </div>
                            <div class="aspect-w-16 aspect-h-9 bg-gray-100">
                                <img src="https://via.placeholder.com/400x300/4F46E5/FFFFFF?text=Purchase+Order" alt="Purchase Order" class="w-full h-48 object-cover cursor-pointer hover:opacity-90 transition-opacity" onclick="openImageModal(this.src, 'Purchase Order')">
                            </div>
                            <div class="p-4">
                                <div class="flex items-center justify-between">
                                    <span class="text-xs text-gray-500">Uploaded: 10 Aug 2024</span>
                                    <button onclick="openImageModal('https://via.placeholder.com/400x300/4F46E5/FFFFFF?text=Purchase+Order', 'Purchase Order')" class="text-blue-600 hover:text-blue-800 text-sm">
                                        <i class="fas fa-expand-alt mr-1"></i>View Full
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Invoice Vendor -->
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                            <div class="p-4 border-b border-gray-200">
                                <h5 class="font-semibold text-gray-800 flex items-center">
                                    <i class="fas fa-receipt text-blue-600 mr-2"></i>
                                    Invoice Vendor
                                </h5>
                                <p class="text-sm text-gray-500 mt-1">INV-VND-2024-001.pdf</p>
                            </div>
                            <div class="aspect-w-16 aspect-h-9 bg-gray-100">
                                <img src="https://via.placeholder.com/400x300/059669/FFFFFF?text=Invoice+Vendor" alt="Invoice Vendor" class="w-full h-48 object-cover cursor-pointer hover:opacity-90 transition-opacity" onclick="openImageModal(this.src, 'Invoice Vendor')">
                            </div>
                            <div class="p-4">
                                <div class="flex items-center justify-between">
                                    <span class="text-xs text-gray-500">Uploaded: 10 Aug 2024</span>
                                    <button onclick="openImageModal('https://via.placeholder.com/400x300/059669/FFFFFF?text=Invoice+Vendor', 'Invoice Vendor')" class="text-blue-600 hover:text-blue-800 text-sm">
                                        <i class="fas fa-expand-alt mr-1"></i>View Full
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Bukti Penerimaan Barang -->
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                            <div class="p-4 border-b border-gray-200">
                                <h5 class="font-semibold text-gray-800 flex items-center">
                                    <i class="fas fa-clipboard-check text-blue-600 mr-2"></i>
                                    Bukti Penerimaan
                                </h5>
                                <p class="text-sm text-gray-500 mt-1">BPB-2024-0815-001.pdf</p>
                            </div>
                            <div class="aspect-w-16 aspect-h-9 bg-gray-100">
                                <img src="https://via.placeholder.com/400x300/DC2626/FFFFFF?text=Bukti+Penerimaan" alt="Bukti Penerimaan" class="w-full h-48 object-cover cursor-pointer hover:opacity-90 transition-opacity" onclick="openImageModal(this.src, 'Bukti Penerimaan Barang')">
                            </div>
                            <div class="p-4">
                                <div class="flex items-center justify-between">
                                    <span class="text-xs text-gray-500">Uploaded: 11 Aug 2024</span>
                                    <button onclick="openImageModal('https://via.placeholder.com/400x300/DC2626/FFFFFF?text=Bukti+Penerimaan', 'Bukti Penerimaan Barang')" class="text-blue-600 hover:text-blue-800 text-sm">
                                        <i class="fas fa-expand-alt mr-1"></i>View Full
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Marketing Documents -->
                <div id="marketing-docs" class="document-tab hidden">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <!-- Budget Approval -->
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                            <div class="p-4 border-b border-gray-200">
                                <h5 class="font-semibold text-gray-800 flex items-center">
                                    <i class="fas fa-dollar-sign text-green-600 mr-2"></i>
                                    Budget Approval
                                </h5>
                                <p class="text-sm text-gray-500 mt-1">BUDGET-Q3-2024.pdf</p>
                            </div>
                            <div class="aspect-w-16 aspect-h-9 bg-gray-100">
                                <img src="https://via.placeholder.com/400x300/10B981/FFFFFF?text=Budget+Approval" alt="Budget Approval" class="w-full h-48 object-cover cursor-pointer hover:opacity-90 transition-opacity" onclick="openImageModal(this.src, 'Budget Approval')">
                            </div>
                            <div class="p-4">
                                <div class="flex items-center justify-between">
                                    <span class="text-xs text-gray-500">Uploaded: 9 Aug 2024</span>
                                    <button onclick="openImageModal('https://via.placeholder.com/400x300/10B981/FFFFFF?text=Budget+Approval', 'Budget Approval')" class="text-blue-600 hover:text-blue-800 text-sm">
                                        <i class="fas fa-expand-alt mr-1"></i>View Full
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Campaign Brief -->
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                            <div class="p-4 border-b border-gray-200">
                                <h5 class="font-semibold text-gray-800 flex items-center">
                                    <i class="fas fa-file-alt text-green-600 mr-2"></i>
                                    Campaign Brief
                                </h5>
                                <p class="text-sm text-gray-500 mt-1">BRIEF-Q3-CAMPAIGN.pdf</p>
                            </div>
                            <div class="aspect-w-16 aspect-h-9 bg-gray-100">
                                <img src="https://via.placeholder.com/400x300/8B5CF6/FFFFFF?text=Campaign+Brief" alt="Campaign Brief" class="w-full h-48 object-cover cursor-pointer hover:opacity-90 transition-opacity" onclick="openImageModal(this.src, 'Campaign Brief')">
                            </div>
                            <div class="p-4">
                                <div class="flex items-center justify-between">
                                    <span class="text-xs text-gray-500">Uploaded: 8 Aug 2024</span>
                                    <button onclick="openImageModal('https://via.placeholder.com/400x300/8B5CF6/FFFFFF?text=Campaign+Brief', 'Campaign Brief')" class="text-blue-600 hover:text-blue-800 text-sm">
                                        <i class="fas fa-expand-alt mr-1"></i>View Full
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Cost Breakdown -->
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                            <div class="p-4 border-b border-gray-200">
                                <h5 class="font-semibold text-gray-800 flex items-center">
                                    <i class="fas fa-chart-pie text-green-600 mr-2"></i>
                                    Cost Breakdown
                                </h5>
                                <p class="text-sm text-gray-500 mt-1">COST-BREAKDOWN-Q3.xlsx</p>
                            </div>
                            <div class="aspect-w-16 aspect-h-9 bg-gray-100">
                                <img src="https://via.placeholder.com/400x300/F59E0B/FFFFFF?text=Cost+Breakdown" alt="Cost Breakdown" class="w-full h-48 object-cover cursor-pointer hover:opacity-90 transition-opacity" onclick="openImageModal(this.src, 'Cost Breakdown')">
                            </div>
                            <div class="p-4">
                                <div class="flex items-center justify-between">
                                    <span class="text-xs text-gray-500">Uploaded: 11 Aug 2024</span>
                                    <button onclick="openImageModal('https://via.placeholder.com/400x300/F59E0B/FFFFFF?text=Cost+Breakdown', 'Cost Breakdown')" class="text-blue-600 hover:text-blue-800 text-sm">
                                        <i class="fas fa-expand-alt mr-1"></i>View Full
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Review Notes -->
            <div class="bg-gray-50 rounded-xl p-6 mb-6">
                <h4 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-sticky-note text-blue-600 mr-2"></i>
                    Catatan Review
                </h4>
                <div class="space-y-4">
                    <div>
                        <label for="reviewNotes" class="block text-sm font-medium text-gray-700 mb-2">Tambahkan catatan review (opsional)</label>
                        <textarea id="reviewNotes" rows="4" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Masukkan catatan atau komentar untuk permintaan ini..."></textarea>
                    </div>
                </div>
            </div>

            <!-- Document Checklist -->
            <div class="bg-blue-50 rounded-xl p-6">
                <h4 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-tasks text-blue-600 mr-2"></i>
                    Checklist Verifikasi
                </h4>
                <div class="space-y-3">
                    <label class="flex items-center">
                        <input type="checkbox" id="checkDocumentComplete" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        <span class="ml-2 text-sm text-gray-700">Semua dokumen purchasing sudah lengkap dan sesuai</span>
                    </label>
                    <label class="flex items-center">
                        <input type="checkbox" id="checkMarketingApproval" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        <span class="ml-2 text-sm text-gray-700">Dokumen marketing sudah diverifikasi dan sesuai budget</span>
                    </label>
                    <label class="flex items-center">
                        <input type="checkbox" id="checkAmountVerified" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        <span class="ml-2 text-sm text-gray-700">Jumlah pembayaran sudah diverifikasi dengan invoice</span>
                    </label>
                    <label class="flex items-center">
                        <input type="checkbox" id="checkBudgetAvailable" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        <span class="ml-2 text-sm text-gray-700">Budget tersedia dan mencukupi untuk pembayaran ini</span>
                    </label>
                </div>
            </div>
        </div>

        <!-- Modal Footer -->
        <div class="bg-gray-50 px-6 py-4 flex items-center justify-between space-x-3 border-t border-gray-200 flex-shrink-0">
            <button type="button" onclick="closeReviewModal()" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                <i class="fas fa-times mr-2"></i>Batal
            </button>
            <div class="flex space-x-3">
                <button type="button" onclick="rejectPayment()" class="px-6 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                    <i class="fas fa-times mr-2"></i>Tolak
                </button>
                <button type="button" onclick="approvePayment()" class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                    <i class="fas fa-check mr-2"></i>Approve
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Image Modal for Full View -->
<div id="imageModal" class="fixed inset-0 backdrop-blur-sm bg-black/70 hidden items-center justify-center z-[60] p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-4xl max-h-screen overflow-hidden">
        <div class="flex items-center justify-between p-4 border-b border-gray-200">
            <h3 id="imageModalTitle" class="text-lg font-semibold text-gray-800">Document Preview</h3>
            <button onclick="closeImageModal()" class="text-gray-400 hover:text-gray-600 p-2 rounded-lg transition-colors">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <div class="p-4">
            <img id="imageModalImg" src="" alt="Document" class="w-full h-auto max-h-[70vh] object-contain rounded-lg">
        </div>
        <div class="flex items-center justify-center p-4 border-t border-gray-200">
            <button onclick="closeImageModal()" class="px-6 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
                <i class="fas fa-times mr-2"></i>Tutup
            </button>
        </div>
    </div>
</div>
