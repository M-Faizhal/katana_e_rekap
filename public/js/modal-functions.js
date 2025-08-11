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
                if (modalId === 'modalTambahPenawaran') {
                    resetTambahModal();
                } else if (modalId === 'modalEditPenawaran') {
                    resetEditModal();
                }
            }
            
            // Remove escape key listener
            document.removeEventListener('keydown', handleEscapeKey);
        }, 300);
    }
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
            firstItem.querySelector('.total-harga').value = 'Rp 0';
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
    // Reset edit modal content
    const container = document.getElementById('daftarBarangEdit');
    if (container) {
        container.innerHTML = '';
        if (typeof editItemCounter !== 'undefined') {
            editItemCounter = 0;
        }
    }
}

// Close modal when clicking outside
function setupModalClickOutside() {
    const modals = document.querySelectorAll('[id^="modal"]');
    modals.forEach(modal => {
        modal.addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal(this.id);
            }
        });
    });
}

// Format currency to Rupiah
function formatRupiah(angka) {
    return 'Rp ' + angka.toLocaleString('id-ID');
}

// Parse Rupiah string to number
function parseRupiah(rupiahString) {
    return parseInt(rupiahString.replace(/[^0-9]/g, '')) || 0;
}

// Initialize all modal functionality
document.addEventListener('DOMContentLoaded', function() {
    setupModalClickOutside();
    
    // Setup floating action button
    const fabButton = document.querySelector('.fixed.bottom-16.right-16');
    if (fabButton) {
        fabButton.addEventListener('click', function() {
            openModal('modalTambahPenawaran');
        });
    }
});

// Action functions for buttons
function viewDetail(id) {
    openDetailModal(id);
}

function editPenawaran(id) {
    openEditModal(id);
}

function deletePenawaran(id) {
    openHapusModal(id);
}
