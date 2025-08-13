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

// Format currency function
function formatCurrency(amount) {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0
    }).format(amount);
}

// Parse currency function
function parseCurrency(currencyString) {
    return parseInt(currencyString.replace(/[^\d]/g, '')) || 0;
}

// Confirm dialog function
function confirmAction(message, callback) {
    if (confirm(message)) {
        callback();
    }
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
