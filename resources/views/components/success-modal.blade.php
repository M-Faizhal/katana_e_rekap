<!-- Success Modal -->
<div id="successModal" class="fixed inset-0 backdrop-blur-xs bg-black/30 modal-backdrop hidden items-center justify-center z-50 p-2 sm:p-4">
    <div class="bg-white rounded-xl sm:rounded-2xl shadow-2xl w-full max-w-sm sm:max-w-md mx-auto transform transition-all duration-300 scale-95" id="successModalContent">
        <!-- Header -->
        <div class="bg-green-600 text-white p-4 sm:p-6 flex items-center justify-between flex-shrink-0 rounded-t-xl sm:rounded-t-2xl">
            <div class="flex items-center space-x-2 sm:space-x-3">
                <div class="w-8 h-8 sm:w-10 sm:h-10 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                    <i class="fas fa-check text-white text-sm sm:text-lg"></i>
                </div>
                <div>
                    <h3 class="text-base sm:text-lg font-bold">Berhasil!</h3>
                    <p class="text-green-100 text-xs sm:text-sm">Operasi berhasil dilakukan</p>
                </div>
            </div>
        </div>

        
        <!-- Body -->
        <div class="p-4 sm:p-6 text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 sm:h-16 sm:w-16 rounded-full bg-green-100 mb-3 sm:mb-4">
                <i class="fas fa-check text-green-600 text-xl sm:text-2xl"></i>
            </div>
            
            <p class="text-gray-600 text-center mb-4 sm:mb-6 text-sm sm:text-base" id="successMessage">
                Data berhasil diperbarui!
            </p>
        </div>
        
        <!-- Footer -->
        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:py-4 flex items-center justify-center border-t border-gray-200 rounded-b-xl sm:rounded-b-2xl">
            <button type="button" onclick="closeSuccessModal()" class="px-4 py-2 sm:px-6 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors text-sm sm:text-base min-h-[44px] sm:min-h-[40px]">
                <i class="fas fa-check mr-1 sm:mr-2"></i>OK
            </button>
        </div>
    </div>
</div>

<script>
// Function to show success modal
function showSuccessModal(message = 'Data berhasil diperbarui!') {
    document.getElementById('successMessage').textContent = message;
    const modal = document.getElementById('successModal');
    const content = document.getElementById('successModalContent');
    
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    
    // Animation
    setTimeout(() => {
        content.classList.remove('scale-95');
        content.classList.add('scale-100');
    }, 10);
}

// Function to close success modal
function closeSuccessModal() {
    const modal = document.getElementById('successModal');
    const content = document.getElementById('successModalContent');
    
    content.classList.remove('scale-100');
    content.classList.add('scale-95');
    
    setTimeout(() => {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }, 200);
}

// Auto close after 3 seconds
function showSuccessModalWithAutoClose(message = 'Data berhasil diperbarui!', autoCloseDelay = 3000) {
    showSuccessModal(message);
    
    if (autoCloseDelay > 0) {
        setTimeout(() => {
            closeSuccessModal();
        }, autoCloseDelay);
    }
}

// Close modal when clicking outside
document.addEventListener('DOMContentLoaded', function() {
    const successModal = document.getElementById('successModal');
    if (successModal) {
        successModal.addEventListener('click', function(e) {
            if (e.target === successModal) {
                closeSuccessModal();
            }
        });
    }
});
</script>
