$(document).ready(function() {
    // Toggle Sidebar
    $('#sidebar-toggle').on('click', function() {
        $('.sidebar').toggleClass('active');
        $('.main-content').toggleClass('active');
    });

    // Close sidebar on mobile when clicking outside
    $(document).on('click', function(e) {
        if ($(window).width() <= 768) {
            if (!$(e.target).closest('.sidebar').length && !$(e.target).closest('#sidebar-toggle').length) {
                $('.sidebar').removeClass('active');
                $('.main-content').removeClass('active');
            }
        }
    });

    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Initialize popovers
    var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
    var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
        return new bootstrap.Popover(popoverTriggerEl);
    });

    // Auto-hide alerts after 5 seconds
    $('.alert').not('.alert-permanent').delay(5000).fadeOut(350);

    // Confirm delete actions
    $('.delete-confirm').on('click', function(e) {
        if (!confirm('Bạn có chắc chắn muốn xóa mục này?')) {
            e.preventDefault();
        }
    });

    // File input preview
    $('input[type="file"]').on('change', function() {
        var file = this.files[0];
        var reader = new FileReader();
        var preview = $(this).siblings('.image-preview');
        
        if (preview.length) {
            reader.onload = function(e) {
                preview.attr('src', e.target.result);
            }
            reader.readAsDataURL(file);
        }
    });

    // Form validation
    $('form.needs-validation').on('submit', function(event) {
        if (!this.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();
        }
        $(this).addClass('was-validated');
    });

    // Dynamic table search
    $('.table-search').on('keyup', function() {
        var value = $(this).val().toLowerCase();
        $('.table tbody tr').filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
        });
    });

    // Toggle password visibility
    $('.toggle-password').on('click', function() {
        var input = $(this).siblings('input');
        var type = input.attr('type') === 'password' ? 'text' : 'password';
        input.attr('type', type);
        $(this).find('i').toggleClass('fa-eye fa-eye-slash');
    });
}); 