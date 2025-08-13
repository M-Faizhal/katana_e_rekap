@extends('layouts.app')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Approval Pembayaran</h1>
    <p class="text-gray-600">Kelola dan approve permintaan pembayaran</p>
</div>

<!-- Statistics Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">Pending Approval</p>
                <p class="text-2xl font-bold text-yellow-600">15</p>
                <p class="text-sm text-yellow-500">
                    <i class="fas fa-clock"></i> Menunggu
                </p>
            </div>
            <div class="p-3 bg-yellow-100 rounded-full">
                <i class="fas fa-hourglass-half text-yellow-600 text-xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">Approved Today</p>
                <p class="text-2xl font-bold text-green-600">8</p>
                <p class="text-sm text-green-500">
                    <i class="fas fa-arrow-up"></i> +2 dari kemarin
                </p>
            </div>
            <div class="p-3 bg-green-100 rounded-full">
                <i class="fas fa-check-circle text-green-600 text-xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">Rejected Today</p>
                <p class="text-2xl font-bold text-red-600">2</p>
                <p class="text-sm text-red-500">
                    <i class="fas fa-times-circle"></i> Ditolak
                </p>
            </div>
            <div class="p-3 bg-red-100 rounded-full">
                <i class="fas fa-times-circle text-red-600 text-xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">Total Amount</p>
                <p class="text-2xl font-bold text-blue-600">Rp 125M</p>
                <p class="text-sm text-blue-500">
                    <i class="fas fa-money-bill-wave"></i> Pending
                </p>
            </div>
            <div class="p-3 bg-blue-100 rounded-full">
                <i class="fas fa-money-bill-wave text-blue-600 text-xl"></i>
            </div>
        </div>
    </div>
</div>

<!-- Filter and Search -->
<div class="bg-white rounded-lg shadow-md mb-6">
    <div class="px-6 py-4 border-b border-gray-200">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0">
            <h3 class="text-lg font-semibold text-gray-800">Filter Pembayaran</h3>
            <div class="flex flex-col md:flex-row space-y-2 md:space-y-0 md:space-x-4">
                <select class="px-3 py-2 border border-gray-300 rounded-md text-sm">
                    <option>Semua Status</option>
                    <option>Pending</option>
                    <option>Approved</option>
                    <option>Rejected</option>
                </select>
                <select class="px-3 py-2 border border-gray-300 rounded-md text-sm">
                    <option>Semua Departemen</option>
                    <option>Marketing</option>
                    <option>Purchasing</option>
                    <option>Operations</option>
                    <option>HR</option>
                </select>
                <input type="text" placeholder="Cari pembayaran..." class="px-3 py-2 border border-gray-300 rounded-md text-sm">
            </div>
        </div>
    </div>
</div>

<!-- Payment Requests Table -->
<div class="bg-white rounded-lg shadow-md">
    <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
        <h3 class="text-lg font-semibold text-gray-800">Permintaan Pembayaran</h3>
        <div class="flex space-x-2">
            <button class="bg-gray-100 text-gray-600 px-4 py-2 rounded-md hover:bg-gray-200 text-sm">
                <i class="fas fa-download mr-2"></i>Export
            </button>
            <button class="bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700 text-sm">
                <i class="fas fa-plus mr-2"></i>Tambah Request
            </button>
        </div>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <input type="checkbox" class="rounded border-gray-300 text-red-600">
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Request ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Requester</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Departemen</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Deskripsi</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Request</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <input type="checkbox" class="rounded border-gray-300 text-red-600">
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#PAY-001</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Ahmad Rizki</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Marketing</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Pembayaran vendor printing</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Rp 5,500,000</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">10 Agustus 2024</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                            Pending
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex space-x-2">
                            <button onclick="openReviewModal('PAY-001', 'Ahmad Rizki', 'Marketing', 'Rp 5,500,000', 'Pembayaran vendor printing untuk kebutuhan marketing campaign Q3 2024', '10 Agustus 2024', '15 Agustus 2024')" class="text-blue-600 hover:text-blue-900" title="Review Documents">
                                <i class="fas fa-file-alt"></i>
                            </button>
                            <button onclick="quickApprove('PAY-001')" class="text-green-600 hover:text-green-900" title="Quick Approve">
                                <i class="fas fa-check"></i>
                            </button>
                            <button onclick="quickReject('PAY-001')" class="text-red-600 hover:text-red-900" title="Quick Reject">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <input type="checkbox" class="rounded border-gray-300 text-red-600">
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#PAY-002</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Siti Nurhaliza</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Purchasing</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Pembayaran supplier bahan baku</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Rp 25,000,000</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">9 Agustus 2024</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                            Approved
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex space-x-2">
                            <button class="text-blue-600 hover:text-blue-900" title="View Details">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="text-gray-600 hover:text-gray-900" title="Print">
                                <i class="fas fa-print"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <input type="checkbox" class="rounded border-gray-300 text-red-600">
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#PAY-003</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Budi Santoso</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Operations</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Maintenance equipment</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Rp 12,750,000</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">8 Agustus 2024</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                            Pending
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex space-x-2">
                            <button onclick="openReviewModal('PAY-003', 'Budi Santoso', 'Operations', 'Rp 12,750,000', 'Maintenance equipment untuk mesin produksi utama', '8 Agustus 2024', '13 Agustus 2024')" class="text-blue-600 hover:text-blue-900" title="Review Documents">
                                <i class="fas fa-file-alt"></i>
                            </button>
                            <button onclick="quickApprove('PAY-003')" class="text-green-600 hover:text-green-900" title="Quick Approve">
                                <i class="fas fa-check"></i>
                            </button>
                            <button onclick="quickReject('PAY-003')" class="text-red-600 hover:text-red-900" title="Quick Reject">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <input type="checkbox" class="rounded border-gray-300 text-red-600">
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#PAY-004</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Linda Wijaya</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">HR</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Reimburse training karyawan</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Rp 3,250,000</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">7 Agustus 2024</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                            Rejected
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex space-x-2">
                            <button class="text-blue-600 hover:text-blue-900" title="View Details">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="text-orange-600 hover:text-orange-900" title="Resubmit">
                                <i class="fas fa-redo"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="px-6 py-4 border-t border-gray-200 flex items-center justify-between">
        <div class="flex items-center">
            <p class="text-sm text-gray-700">
                Showing <span class="font-medium">1</span> to <span class="font-medium">4</span> of <span class="font-medium">25</span> results
            </p>
        </div>
        <div class="flex items-center space-x-2">
            <button class="px-3 py-1 border border-gray-300 rounded-md text-sm text-gray-500 hover:bg-gray-50">Previous</button>
            <button class="px-3 py-1 border border-red-300 rounded-md text-sm bg-red-50 text-red-600">1</button>
            <button class="px-3 py-1 border border-gray-300 rounded-md text-sm text-gray-500 hover:bg-gray-50">2</button>
            <button class="px-3 py-1 border border-gray-300 rounded-md text-sm text-gray-500 hover:bg-gray-50">3</button>
            <button class="px-3 py-1 border border-gray-300 rounded-md text-sm text-gray-500 hover:bg-gray-50">Next</button>
        </div>
    </div>
</div>

<!-- Bulk Actions -->
<div class="mt-6 bg-white rounded-lg shadow-md p-6">
    <h3 class="text-lg font-semibold text-gray-800 mb-4">Bulk Actions</h3>
    <div class="flex flex-wrap gap-4">
        <button class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 text-sm">
            <i class="fas fa-check mr-2"></i>Approve Selected
        </button>
        <button class="bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700 text-sm">
            <i class="fas fa-times mr-2"></i>Reject Selected
        </button>
        <button class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 text-sm">
            <i class="fas fa-download mr-2"></i>Export Selected
        </button>
    </div>
</div>

@include('pages.keuangan.approval-components.review-dokumen')
@include('components.success-modal')

<script>
// Sample documents data for different requests
const documentsData = {
    'PAY-001': {
        purchasing: [
            { name: 'Purchase Order', file: 'PO-2024-0815-001.pdf', image: 'https://via.placeholder.com/400x300/4F46E5/FFFFFF?text=Purchase+Order', date: '10 Aug 2024' },
            { name: 'Invoice Vendor', file: 'INV-VND-2024-001.pdf', image: 'https://via.placeholder.com/400x300/059669/FFFFFF?text=Invoice+Vendor', date: '10 Aug 2024' },
            { name: 'Bukti Penerimaan', file: 'BPB-2024-0815-001.pdf', image: 'https://via.placeholder.com/400x300/DC2626/FFFFFF?text=Bukti+Penerimaan', date: '11 Aug 2024' }
        ],
        marketing: [
            { name: 'Budget Approval', file: 'BUDGET-Q3-2024.pdf', image: 'https://via.placeholder.com/400x300/10B981/FFFFFF?text=Budget+Approval', date: '9 Aug 2024' },
            { name: 'Campaign Brief', file: 'BRIEF-Q3-CAMPAIGN.pdf', image: 'https://via.placeholder.com/400x300/8B5CF6/FFFFFF?text=Campaign+Brief', date: '8 Aug 2024' },
            { name: 'Cost Breakdown', file: 'COST-BREAKDOWN-Q3.xlsx', image: 'https://via.placeholder.com/400x300/F59E0B/FFFFFF?text=Cost+Breakdown', date: '11 Aug 2024' }
        ]
    },
    'PAY-003': {
        purchasing: [
            { name: 'Purchase Order', file: 'PO-2024-0808-003.pdf', image: 'https://via.placeholder.com/400x300/4F46E5/FFFFFF?text=Maintenance+PO', date: '8 Aug 2024' },
            { name: 'Service Invoice', file: 'INV-SRV-2024-003.pdf', image: 'https://via.placeholder.com/400x300/059669/FFFFFF?text=Service+Invoice', date: '8 Aug 2024' },
            { name: 'Work Completion', file: 'WC-2024-0808-003.pdf', image: 'https://via.placeholder.com/400x300/DC2626/FFFFFF?text=Work+Complete', date: '8 Aug 2024' }
        ],
        marketing: [
            { name: 'Operational Budget', file: 'OP-BUDGET-2024.pdf', image: 'https://via.placeholder.com/400x300/10B981/FFFFFF?text=Operational+Budget', date: '7 Aug 2024' },
            { name: 'Equipment Report', file: 'EQ-REPORT-2024.pdf', image: 'https://via.placeholder.com/400x300/8B5CF6/FFFFFF?text=Equipment+Report', date: '6 Aug 2024' }
        ]
    }
};

// Modal functions
function openReviewModal(requestId, requester, department, amount, description, requestDate, deadline) {
    // Populate request information
    document.getElementById('reviewRequestId').textContent = '#' + requestId;
    document.getElementById('reviewRequester').textContent = requester;
    document.getElementById('reviewDepartment').textContent = department;
    document.getElementById('reviewAmount').textContent = amount;
    document.getElementById('reviewDescription').textContent = description;
    document.getElementById('reviewRequestDate').textContent = requestDate;
    document.getElementById('reviewDeadline').textContent = deadline;

    // Load documents for this request
    loadDocuments(requestId);

    // Show modal
    document.getElementById('modalReviewDokumen').classList.remove('hidden');
    document.getElementById('modalReviewDokumen').classList.add('flex');
    document.body.style.overflow = 'hidden';

    // Set active tab to purchasing
    switchDocumentTab('purchasing');
}

function closeReviewModal() {
    document.getElementById('modalReviewDokumen').classList.add('hidden');
    document.getElementById('modalReviewDokumen').classList.remove('flex');
    document.body.style.overflow = 'auto';

    // Reset form
    document.getElementById('reviewNotes').value = '';
    const checkboxes = document.querySelectorAll('#modalReviewDokumen input[type="checkbox"]');
    checkboxes.forEach(cb => cb.checked = false);
}

function loadDocuments(requestId) {
    const docs = documentsData[requestId] || documentsData['PAY-001']; // fallback

    // Load purchasing documents
    const purchasingContainer = document.querySelector('#purchasing-docs .grid');
    purchasingContainer.innerHTML = docs.purchasing.map(doc => `
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            <div class="p-4 border-b border-gray-200">
                <h5 class="font-semibold text-gray-800 flex items-center">
                    <i class="fas fa-file-alt text-blue-600 mr-2"></i>
                    ${doc.name}
                </h5>
                <p class="text-sm text-gray-500 mt-1">${doc.file}</p>
            </div>
            <div class="aspect-w-16 aspect-h-9 bg-gray-100">
                <img src="${doc.image}" alt="${doc.name}" class="w-full h-48 object-cover cursor-pointer hover:opacity-90 transition-opacity" onclick="openImageModal('${doc.image}', '${doc.name}')">
            </div>
            <div class="p-4">
                <div class="flex items-center justify-between">
                    <span class="text-xs text-gray-500">Uploaded: ${doc.date}</span>
                    <button onclick="openImageModal('${doc.image}', '${doc.name}')" class="text-blue-600 hover:text-blue-800 text-sm">
                        <i class="fas fa-expand-alt mr-1"></i>View Full
                    </button>
                </div>
            </div>
        </div>
    `).join('');

    // Load marketing documents
    const marketingContainer = document.querySelector('#marketing-docs .grid');
    marketingContainer.innerHTML = docs.marketing.map(doc => `
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            <div class="p-4 border-b border-gray-200">
                <h5 class="font-semibold text-gray-800 flex items-center">
                    <i class="fas fa-file-alt text-green-600 mr-2"></i>
                    ${doc.name}
                </h5>
                <p class="text-sm text-gray-500 mt-1">${doc.file}</p>
            </div>
            <div class="aspect-w-16 aspect-h-9 bg-gray-100">
                <img src="${doc.image}" alt="${doc.name}" class="w-full h-48 object-cover cursor-pointer hover:opacity-90 transition-opacity" onclick="openImageModal('${doc.image}', '${doc.name}')">
            </div>
            <div class="p-4">
                <div class="flex items-center justify-between">
                    <span class="text-xs text-gray-500">Uploaded: ${doc.date}</span>
                    <button onclick="openImageModal('${doc.image}', '${doc.name}')" class="text-blue-600 hover:text-blue-800 text-sm">
                        <i class="fas fa-expand-alt mr-1"></i>View Full
                    </button>
                </div>
            </div>
        </div>
    `).join('');
}

function switchDocumentTab(tabName) {
    // Update tab buttons
    document.getElementById('tab-purchasing').className = tabName === 'purchasing'
        ? 'whitespace-nowrap py-2 px-1 border-b-2 border-blue-500 font-medium text-sm text-blue-600'
        : 'whitespace-nowrap py-2 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300';

    document.getElementById('tab-marketing').className = tabName === 'marketing'
        ? 'whitespace-nowrap py-2 px-1 border-b-2 border-blue-500 font-medium text-sm text-blue-600'
        : 'whitespace-nowrap py-2 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300';

    // Show/hide document sections
    document.getElementById('purchasing-docs').style.display = tabName === 'purchasing' ? 'block' : 'none';
    document.getElementById('marketing-docs').style.display = tabName === 'marketing' ? 'block' : 'none';
}

function openImageModal(imageSrc, title) {
    document.getElementById('imageModalImg').src = imageSrc;
    document.getElementById('imageModalTitle').textContent = title;
    document.getElementById('imageModal').classList.remove('hidden');
    document.getElementById('imageModal').classList.add('flex');
}

function closeImageModal() {
    document.getElementById('imageModal').classList.add('hidden');
    document.getElementById('imageModal').classList.remove('flex');
}

function approvePayment() {
    const notes = document.getElementById('reviewNotes').value;
    const requestId = document.getElementById('reviewRequestId').textContent;

    // Check if all required checkboxes are checked
    const requiredChecks = [
        'checkDocumentComplete',
        'checkMarketingApproval',
        'checkAmountVerified',
        'checkBudgetAvailable'
    ];

    const allChecked = requiredChecks.every(id => document.getElementById(id).checked);

    if (!allChecked) {
        alert('Silakan centang semua item checklist sebelum melakukan approval.');
        return;
    }

    // Simulate API call
    setTimeout(() => {
        closeReviewModal();
        showSuccessModal(`Pembayaran ${requestId} telah disetujui!`);

        // Update table row status (in real app, this would refresh from server)
        updateRowStatus(requestId.replace('#', ''), 'approved');
    }, 500);
}

function rejectPayment() {
    const notes = document.getElementById('reviewNotes').value;
    const requestId = document.getElementById('reviewRequestId').textContent;

    if (!notes.trim()) {
        alert('Silakan berikan alasan penolakan di bagian catatan review.');
        return;
    }

    // Simulate API call
    setTimeout(() => {
        closeReviewModal();
        showSuccessModal(`Pembayaran ${requestId} telah ditolak.`);

        // Update table row status (in real app, this would refresh from server)
        updateRowStatus(requestId.replace('#', ''), 'rejected');
    }, 500);
}

function quickApprove(requestId) {
    if (confirm(`Apakah Anda yakin ingin menyetujui pembayaran #${requestId}?`)) {
        setTimeout(() => {
            showSuccessModal(`Pembayaran #${requestId} telah disetujui!`);
            updateRowStatus(requestId, 'approved');
        }, 500);
    }
}

function quickReject(requestId) {
    const reason = prompt('Masukkan alasan penolakan:');
    if (reason) {
        setTimeout(() => {
            showSuccessModal(`Pembayaran #${requestId} telah ditolak.`);
            updateRowStatus(requestId, 'rejected');
        }, 500);
    }
}

function updateRowStatus(requestId, status) {
    // Find the table row and update status
    const rows = document.querySelectorAll('tbody tr');
    rows.forEach(row => {
        const idCell = row.querySelector('td:nth-child(2)');
        if (idCell && idCell.textContent.includes(requestId)) {
            const statusCell = row.querySelector('td:nth-child(8) span');
            const actionCell = row.querySelector('td:nth-child(9) div');

            if (status === 'approved') {
                statusCell.className = 'px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800';
                statusCell.textContent = 'Approved';
                actionCell.innerHTML = `
                    <button class="text-blue-600 hover:text-blue-900" title="View Details">
                        <i class="fas fa-eye"></i>
                    </button>
                    <button class="text-gray-600 hover:text-gray-900" title="Print">
                        <i class="fas fa-print"></i>
                    </button>
                `;
            } else if (status === 'rejected') {
                statusCell.className = 'px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800';
                statusCell.textContent = 'Rejected';
                actionCell.innerHTML = `
                    <button class="text-blue-600 hover:text-blue-900" title="View Details">
                        <i class="fas fa-eye"></i>
                    </button>
                    <button class="text-orange-600 hover:text-orange-900" title="Resubmit">
                        <i class="fas fa-redo"></i>
                    </button>
                `;
            }
        }
    });
}

function showSuccessModal(message) {
    document.getElementById('successMessage').textContent = message;
    document.getElementById('successModal').classList.remove('hidden');

    setTimeout(() => {
        document.getElementById('successModal').classList.add('hidden');
    }, 3000);
}

function closeSuccessModal() {
    document.getElementById('successModal').classList.add('hidden');
}

// Close modals when clicking outside
document.addEventListener('click', function(event) {
    const reviewModal = document.getElementById('modalReviewDokumen');
    const imageModal = document.getElementById('imageModal');

    if (event.target === reviewModal) {
        closeReviewModal();
    }
    if (event.target === imageModal) {
        closeImageModal();
    }
});
</script>
@endsection
