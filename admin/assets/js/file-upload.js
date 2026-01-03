/**
 * File Upload Drag and Drop Handler
 */

document.addEventListener('DOMContentLoaded', function() {
    const fileUploadArea = document.getElementById('fileUploadArea');
    const fileInput = document.getElementById('fileInput');
    const filePreview = document.getElementById('filePreview');
    const fileContent = fileUploadArea?.querySelector('.file-upload-content');
    const fileName = document.getElementById('fileName');
    const fileSize = document.getElementById('fileSize');
    const removeBtn = document.getElementById('removeFile');
    
    if (!fileUploadArea || !fileInput) return;
    
    // Click to select file
    fileUploadArea.addEventListener('click', function(e) {
        if (e.target !== removeBtn && !e.target.closest('.btn-remove-file')) {
            fileInput.click();
        }
    });
    
    // File selected via input
    fileInput.addEventListener('change', function(e) {
        if (this.files && this.files[0]) {
            handleFile(this.files[0]);
        }
    });
    
    // Drag and drop events
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        fileUploadArea.addEventListener(eventName, preventDefaults, false);
    });
    
    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }
    
    ['dragenter', 'dragover'].forEach(eventName => {
        fileUploadArea.addEventListener(eventName, highlight, false);
    });
    
    ['dragleave', 'drop'].forEach(eventName => {
        fileUploadArea.addEventListener(eventName, unhighlight, false);
    });
    
    function highlight(e) {
        fileUploadArea.classList.add('drag-over');
    }
    
    function unhighlight(e) {
        fileUploadArea.classList.remove('drag-over');
    }
    
    // Handle dropped files
    fileUploadArea.addEventListener('drop', function(e) {
        const dt = e.dataTransfer;
        const files = dt.files;
        
        if (files.length > 0) {
            fileInput.files = files;
            handleFile(files[0]);
        }
    }, false);
    
    // Handle file display
    function handleFile(file) {
        const maxSize = 10 * 1024 * 1024; // 10MB
        
        if (file.size > maxSize) {
            alert('File size must be less than 10MB');
            return;
        }
        
        // Display file info
        fileName.textContent = file.name;
        fileSize.textContent = formatFileSize(file.size);
        
        // Show preview, hide content
        fileContent.style.display = 'none';
        filePreview.style.display = 'flex';
    }
    
    // Remove file
    removeBtn?.addEventListener('click', function(e) {
        e.stopPropagation();
        fileInput.value = '';
        fileContent.style.display = 'block';
        filePreview.style.display = 'none';
    });
    
    // Format file size
    function formatFileSize(bytes) {
        if (bytes >= 1073741824) {
            return (bytes / 1073741824).toFixed(2) + ' GB';
        } else if (bytes >= 1048576) {
            return (bytes / 1048576).toFixed(2) + ' MB';
        } else if (bytes >= 1024) {
            return (bytes / 1024).toFixed(2) + ' KB';
        } else {
            return bytes + ' bytes';
        }
    }
});
