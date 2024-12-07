$(document).ready(function() {
    $('.category-checkbox').change(function() {
        var categoryId = $(this).val();
        var action = $(this).prop('checked') ? 'follow' : 'unfollow';
        
        $.ajax({
            url: 'follow_category.php',
            type: 'POST',
            data: {
                category_id: categoryId,
                action: action
            },
            success: function(response) {
                var data = JSON.parse(response);
                if (data.status === 'success') {
                    // Tampilkan notifikasi sukses
                    toastr.success(action === 'follow' ? 
                        'Berhasil mengikuti kategori' : 
                        'Berhenti mengikuti kategori'
                    );
                } else {
                    // Tampilkan pesan error
                    toastr.error(data.message);
                }
            }
        });
    });
}); 