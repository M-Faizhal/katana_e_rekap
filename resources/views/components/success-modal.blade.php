<!-- Success Modal -->
<div id="successModal" class="fixed inset-0 bg-black/20 hidden items-center justify-center z-50 " >
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
