    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <script>
        // Common jQuery functions
        $(document).ready(function() {
            // Confirm before delete
            $('.delete-btn').click(function(e) {
                if(!confirm('Are you sure you want to delete this record?')) {
                    e.preventDefault();
                }
            });
            
            // Form validation
            $('form').submit(function() {
                const required = $(this).find('[required]');
                let valid = true;
                
                required.each(function() {
                    if($(this).val().trim() === '') {
                        valid = false;
                        $(this).addClass('is-invalid');
                    } else {
                        $(this).removeClass('is-invalid');
                    }
                });
                
                return valid;
            });
        });
    </script>
</body>
</html>