@extends('layouts.app')

@section('content')
<!-- Header Section -->
<div class="bg-red-800 rounded-xl sm:rounded-2xl p-4 sm:p-6 lg:p-8 mb-6 sm:mb-8 text-white shadow-lg mt-4">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold mb-1 sm:mb-2">Penagihan Dinas</h1>
            <p class="text-red-100 text-sm sm:text-base lg:text-lg">Kelola penagihan dan invoice untuk client</p>
        </div>
        <div class="hidden sm:block lg:block">
            <i class="fas fa-file-invoice-dollar text-3xl sm:text-4xl lg:text-6xl"></i>
        </div>
    </div>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 lg:gap-6 mb-6 sm:mb-8">
    <div class="bg-white rounded-xl sm:rounded-2xl shadow-lg p-3 sm:p-4 lg:p-6 border border-gray-100">
        <div class="flex flex-col sm:flex-row sm:items-center">
            <div class="p-2 sm:p-3 rounded-lg sm:rounded-xl bg-yellow-100 mb-2 sm:mb-0 sm:mr-4 w-fit">
                <i class="fas fa-file-invoice text-yellow-600 text-sm sm:text-lg lg:text-xl"></i>
            </div>
            <div class="min-w-0">
                <h3 class="text-xs sm:text-sm lg:text-lg font-semibold text-gray-800 truncate">Outstanding Invoice</h3>
                <p class="text-lg sm:text-xl lg:text-2xl font-bold text-yellow-600">23</p>
                <p class="text-xs sm:text-sm text-yellow-500">
                    <i class="fas fa-file-invoice"></i> Belum bayar
                </p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl sm:rounded-2xl shadow-lg p-3 sm:p-4 lg:p-6 border border-gray-100">
        <div class="flex flex-col sm:flex-row sm:items-center">
            <div class="p-2 sm:p-3 rounded-lg sm:rounded-xl bg-green-100 mb-2 sm:mb-0 sm:mr-4 w-fit">
                <i class="fas fa-check-circle text-green-600 text-sm sm:text-lg lg:text-xl"></i>
            </div>
            <div class="min-w-0">
                <h3 class="text-xs sm:text-sm lg:text-lg font-semibold text-gray-800 truncate">Paid This Month</h3>
                <p class="text-lg sm:text-xl lg:text-2xl font-bold text-green-600">45</p>
                <p class="text-xs sm:text-sm text-green-500">
                    <i class="fas fa-arrow-up"></i> +12 dari bulan lalu
                </p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl sm:rounded-2xl shadow-lg p-3 sm:p-4 lg:p-6 border border-gray-100">
        <div class="flex flex-col sm:flex-row sm:items-center">
            <div class="p-2 sm:p-3 rounded-lg sm:rounded-xl bg-red-100 mb-2 sm:mb-0 sm:mr-4 w-fit">
                <i class="fas fa-exclamation-triangle text-red-600 text-sm sm:text-lg lg:text-xl"></i>
            </div>
            <div class="min-w-0">
                <h3 class="text-xs sm:text-sm lg:text-lg font-semibold text-gray-800 truncate">Overdue</h3>
                <p class="text-lg sm:text-xl lg:text-2xl font-bold text-red-600">7</p>
                <p class="text-xs sm:text-sm text-red-500">
                    <i class="fas fa-exclamation-triangle"></i> Terlambat
                </p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl sm:rounded-2xl shadow-lg p-3 sm:p-4 lg:p-6 border border-gray-100">
        <div class="flex flex-col sm:flex-row sm:items-center">
            <div class="p-2 sm:p-3 rounded-lg sm:rounded-xl bg-blue-100 mb-2 sm:mb-0 sm:mr-4 w-fit">
                <i class="fas fa-money-bill-wave text-blue-600 text-sm sm:text-lg lg:text-xl"></i>
            </div>
            <div class="min-w-0">
                <h3 class="text-xs sm:text-sm lg:text-lg font-semibold text-gray-800 truncate">Total Outstanding</h3>
                <p class="text-lg sm:text-xl lg:text-2xl font-bold text-blue-600">Rp 285M</p>
                <p class="text-xs sm:text-sm text-blue-500">
                    <i class="fas fa-money-bill-wave"></i> Amount
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Form Input Penagihan Dinas -->
<div class="bg-white rounded-xl sm:rounded-2xl shadow-lg border border-gray-100 mb-6 sm:mb-8">
    <div class="p-4 sm:p-6 border-b border-gray-200">
        <div class="flex items-center space-x-3">
            <div class="w-10 h-10 bg-red-100 rounded-xl flex items-center justify-center">
                <i class="fas fa-plus text-red-600 text-lg"></i>
            </div>
            <div>
                <h2 class="text-lg sm:text-xl font-bold text-gray-800">Upload Dokumen Penagihan</h2>
                <p class="text-sm sm:text-base text-gray-600 mt-1">Upload file dokumen penagihan dalam format PDF, gambar, atau Excel</p>
            </div>
        </div>
    </div>
    <form class="p-4 sm:p-6 space-y-6">
        <!-- Invoice/Kwitansi Section -->
        <div class="bg-red-50 rounded-xl p-4 sm:p-6 border-l-4 border-red-500">
            <h4 class="text-md font-semibold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-file-invoice text-red-500 mr-2"></i>Upload Invoice/Kwitansi
            </h4>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Upload File Invoice/Kwitansi</label>
                    <div class="border-2 border-dashed border-red-300 rounded-lg p-6 text-center hover:border-red-400 transition-colors">
                        <div class="space-y-2">
                            <i class="fas fa-cloud-upload-alt text-red-500 text-3xl"></i>
                            <div>
                                <p class="text-sm font-medium text-gray-700">Drag & drop file atau klik untuk upload</p>
                                <p class="text-xs text-gray-500">Support: PDF, JPG, PNG, Excel (XLS, XLSX)</p>
                            </div>
                        </div>
                        <input type="file" class="hidden" id="invoice-file" accept=".pdf,.jpg,.jpeg,.png,.xls,.xlsx" multiple>
                    </div>
                </div>
            </div>
        </div>

        <!-- SPJ Section -->
        <div class="bg-blue-50 rounded-xl p-4 sm:p-6 border-l-4 border-blue-500">
            <h4 class="text-md font-semibold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-file-alt text-blue-500 mr-2"></i>Upload Surat Pertanggung Jawaban (SPJ)
            </h4>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Upload File SPJ</label>
                    <div class="border-2 border-dashed border-blue-300 rounded-lg p-6 text-center hover:border-blue-400 transition-colors">
                        <div class="space-y-2">
                            <i class="fas fa-cloud-upload-alt text-blue-500 text-3xl"></i>
                            <div>
                                <p class="text-sm font-medium text-gray-700">Drag & drop file atau klik untuk upload</p>
                                <p class="text-xs text-gray-500">Support: PDF, DOC, DOCX, Excel (XLS, XLSX), JPG, PNG</p>
                            </div>
                        </div>
                        <input type="file" class="hidden" id="spj-file" accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png" multiple>
                    </div>
                </div>
            </div>
        </div>

        <!-- Faktur Pajak Section -->
        <div class="bg-green-50 rounded-xl p-4 sm:p-6 border-l-4 border-green-500">
            <h4 class="text-md font-semibold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-receipt text-green-500 mr-2"></i>Upload Faktur Pajak
            </h4>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Upload File Faktur Pajak</label>
                    <div class="border-2 border-dashed border-green-300 rounded-lg p-6 text-center hover:border-green-400 transition-colors">
                        <div class="space-y-2">
                            <i class="fas fa-cloud-upload-alt text-green-500 text-3xl"></i>
                            <div>
                                <p class="text-sm font-medium text-gray-700">Drag & drop file atau klik untuk upload</p>
                                <p class="text-xs text-gray-500">Support: PDF, JPG, PNG, Excel (XLS, XLSX)</p>
                            </div>
                        </div>
                        <input type="file" class="hidden" id="faktur-file" accept=".pdf,.jpg,.jpeg,.png,.xls,.xlsx" multiple>
                    </div>
                </div>
            </div>
        </div>

        <!-- Uang Masuk Section -->
        <div class="bg-purple-50 rounded-xl p-4 sm:p-6 border-l-4 border-purple-500">
            <h4 class="text-md font-semibold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-money-bill-wave text-purple-500 mr-2"></i>Upload Bukti Uang Masuk
            </h4>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Upload Bukti Transfer/Pembayaran</label>
                    <div class="border-2 border-dashed border-purple-300 rounded-lg p-6 text-center hover:border-purple-400 transition-colors">
                        <div class="space-y-2">
                            <i class="fas fa-cloud-upload-alt text-purple-500 text-3xl"></i>
                            <div>
                                <p class="text-sm font-medium text-gray-700">Drag & drop file atau klik untuk upload</p>
                                <p class="text-xs text-gray-500">Support: PDF, JPG, PNG, Excel (XLS, XLSX)</p>
                            </div>
                        </div>
                        <input type="file" class="hidden" id="bukti-file" accept=".pdf,.jpg,.jpeg,.png,.xls,.xlsx" multiple>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="flex flex-col sm:flex-row justify-end space-y-3 sm:space-y-0 sm:space-x-4 pt-6 border-t border-gray-200">
            <button type="button" class="w-full sm:w-auto px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500 transition-all duration-200">
                <i class="fas fa-times mr-2"></i>Batal
            </button>
            <button type="button" class="w-full sm:w-auto px-6 py-3 bg-gray-600 text-white rounded-lg hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 transition-all duration-200">
                <i class="fas fa-save mr-2"></i>Simpan Draft
            </button>
            <button type="submit" class="w-full sm:w-auto px-6 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 transition-all duration-200">
                <i class="fas fa-paper-plane mr-2"></i>Simpan & Submit
            </button>
        </div>
    </form>
</div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Setup drag and drop for all file upload areas
    const fileAreas = [
        { id: 'invoice-file', container: 'invoice-file' },
        { id: 'spj-file', container: 'spj-file' },
        { id: 'faktur-file', container: 'faktur-file' },
        { id: 'bukti-file', container: 'bukti-file' }
    ];

    fileAreas.forEach(area => {
        const fileInput = document.getElementById(area.id);
        const container = fileInput.closest('.border-dashed');

        if (!fileInput || !container) return;

        // Click to upload
        container.addEventListener('click', () => {
            fileInput.click();
        });

        // Drag and drop events
        container.addEventListener('dragover', (e) => {
            e.preventDefault();
            container.classList.add('bg-gray-50', 'border-solid');
        });

        container.addEventListener('dragleave', (e) => {
            e.preventDefault();
            container.classList.remove('bg-gray-50', 'border-solid');
        });

        container.addEventListener('drop', (e) => {
            e.preventDefault();
            container.classList.remove('bg-gray-50', 'border-solid');

            const files = e.dataTransfer.files;
            if (files.length > 0) {
                fileInput.files = files;
                handleFileSelection(fileInput, container);
            }
        });

        // File input change event
        fileInput.addEventListener('change', () => {
            handleFileSelection(fileInput, container);
        });
    });

    function handleFileSelection(input, container) {
        const files = input.files;
        if (files.length === 0) return;

        // Create preview area
        let previewArea = container.nextElementSibling;
        if (!previewArea || !previewArea.classList.contains('file-preview')) {
            previewArea = document.createElement('div');
            previewArea.className = 'file-preview mt-4 space-y-2';
            container.parentNode.insertBefore(previewArea, container.nextSibling);
        } else {
            previewArea.innerHTML = '';
        }

        // Display file previews
        Array.from(files).forEach((file, index) => {
            const filePreview = createFilePreview(file, index);
            previewArea.appendChild(filePreview);
        });

        // Update container content
        const icon = container.querySelector('i');
        const text = container.querySelector('p');
        if (icon && text) {
            icon.className = 'fas fa-check-circle text-green-500 text-3xl';
            text.innerHTML = `<span class="text-green-600 font-medium">${files.length} file(s) dipilih</span><br><span class="text-xs text-gray-500">Klik untuk mengubah file</span>`;
        }
    }

    function createFilePreview(file, index) {
        const div = document.createElement('div');
        div.className = 'flex items-center justify-between p-3 bg-gray-50 rounded-lg border';

        const fileName = file.name.length > 30 ? file.name.substring(0, 30) + '...' : file.name;
        const fileSize = (file.size / 1024 / 1024).toFixed(2);
        const fileType = file.type || 'Unknown';

        let iconClass = 'fas fa-file text-gray-500';
        if (file.type.includes('pdf')) iconClass = 'fas fa-file-pdf text-red-500';
        else if (file.type.includes('image')) iconClass = 'fas fa-file-image text-blue-500';
        else if (file.type.includes('sheet') || file.name.includes('.xls')) iconClass = 'fas fa-file-excel text-green-500';
        else if (file.type.includes('document')) iconClass = 'fas fa-file-word text-blue-600';

        div.innerHTML = `
            <div class="flex items-center space-x-3">
                <i class="${iconClass} text-lg"></i>
                <div>
                    <p class="text-sm font-medium text-gray-800">${fileName}</p>
                    <p class="text-xs text-gray-500">${fileSize} MB</p>
                </div>
            </div>
            <button type="button" class="text-red-500 hover:text-red-700 text-sm" onclick="removeFile(this, ${index})">
                <i class="fas fa-times"></i>
            </button>
        `;

        return div;
    }

    // Make removeFile function global
    window.removeFile = function(button, index) {
        const previewArea = button.closest('.file-preview');
        const container = previewArea.previousElementSibling;
        const fileInput = container.querySelector('input[type="file"]');

        // Remove preview
        button.closest('.flex').remove();

        // Reset if no more files
        if (previewArea.children.length === 0) {
            previewArea.remove();

            // Reset container
            const icon = container.querySelector('i');
            const text = container.querySelector('p');
            if (icon && text) {
                icon.className = 'fas fa-cloud-upload-alt text-3xl';
                text.innerHTML = 'Drag & drop file atau klik untuk upload<br><span class="text-xs text-gray-500">Support: PDF, JPG, PNG, Excel (XLS, XLSX)</span>';

                // Determine color based on container
                if (container.classList.contains('border-red-300')) {
                    icon.classList.add('text-red-500');
                } else if (container.classList.contains('border-blue-300')) {
                    icon.classList.add('text-blue-500');
                } else if (container.classList.contains('border-green-300')) {
                    icon.classList.add('text-green-500');
                } else if (container.classList.contains('border-purple-300')) {
                    icon.classList.add('text-purple-500');
                }
            }

            // Clear file input
            fileInput.value = '';
        }
    };
});
</script>
@endpush
