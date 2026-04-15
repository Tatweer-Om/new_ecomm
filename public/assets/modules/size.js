$(document).ready(function() {
    
    $('.add_size').off().on('submit', function (e) {
        e.preventDefault();

        var formdatas = new FormData($('.add_size')[0]);
        formdatas.append('_token', window.crsftoken.crsfToken);
        var size_name = $('.size_name').val();
        var size_name_ar = $('.size_name_ar').val();
        var id = $('.size_id').val();
        if (id) {
            formdatas.append('_method', 'PUT'); // <-- important for Laravel
        }
        // Validation
        if (size_name == "") {
            show_notification('error', window.translations.validate_size_name);
            return false;
        }
        if (size_name_ar == "") {
            show_notification('error', window.translations.validate_size_name_ar);
            return false;
        }

        showPreloader();
        before_submit();

        $.ajax({
            type: "POST",
            url: id ? window.translations.update.replace(':id', id) : window.translations.store,
            data: formdatas,
            contentType: false,
            processData: false,
            success: function (data) {
                hidePreloader();
                after_submit();

                // Always show message from Laravel
                show_notification(data.status ? 'success' : 'error', data.message);

                if (!id) $(".add_size")[0].reset(); // reset only if add was successful
                // Refresh table after successful AJAX form submission
                fetchdata(currentPaginationUrl); // reload the current page
                
            },
            error: function (xhr) {
                hidePreloader();
                after_submit();

                let msg = "Something went wrong";
                if(xhr.responseJSON && xhr.responseJSON.message) {
                    msg = xhr.responseJSON.message;
                }
                show_notification('error', msg);

                console.log(xhr);
            }
        });
    });

});
document.addEventListener('alpine:init', () => {
    Alpine.store('modals', {
        size: false, // modal name is "size"
    });
});

function resetSizeForm() {
    const form = document.querySelector('.add_size');
    if (form) {
        form.reset(); // reset all normal inputs
        $('.size_id').val(''); // clear hidden ID manually
    }
}

function edit(id){
    showPreloader(); 

    let url = window.translations.edit.replace(':id', id);

    $.ajax({
        dataType: 'JSON',
        url: url,
        method: 'GET',
        success: function(fetch) {
            hidePreloader(); 

            if (fetch.status) {
                const size = fetch.data;
                $(".size_name").val(size.size_name);
                $(".size_name_ar").val(size.size_name_ar);
                $(".size_code").val(size.size_code);
                $(".description").val(size.description);
                $(".size_id").val(size.id);
                $(".modal-title").html(window.translations.size_update_success_lang);

                // 🔥 Open Alpine modal here
                Alpine.store('modals').size = true;

            } else {
                show_notification('error', window.translations.size_not_found_lang);
            }
        },
        error: function() {
            hidePreloader();
            after_submit();
            show_notification('error', window.translations.size_not_found_lang);
        }
    });
}

// delete
function del(id) {
    Swal.fire({
        title: window.translations.sure_lang,
        text: window.translations.delete_text_lang,
        icon: "warning",
        showCancelButton: !0,
        confirmButtonsize: "#3085d6",
        cancelButtonsize: "#d33",
        confirmButtonText: window.translations.delete_it_lang,
        confirmButtonClass: "items-center justify-center gap-2 px-5 py-2.5 text-sm font-bold text-white bg-[var(--primary-color)] rounded-full shadow-lg hover:bg-[var(--primary-darker)] hover:text-dark transition-transform hover:scale-105",
        cancelButtonClass: "items-center justify-center gap-2 px-5 py-2.5 text-sm font-bold text-white bg-[var(--primary-color)] rounded-full shadow-lg hover:bg-[var(--primary-darker)] hover:text-dark transition-transform hover:scale-105",
        buttonsStyling: !1
    }).then(function (result) {
        if (result.value) {
            showPreloader();

            let url = window.translations.delete.replace(':id', id);

            $.ajax({
                url: url,
                type: 'POST', // ✅ always POST and spoof method
                data: {
                    _method: 'DELETE', // Laravel expects this
                    _token: window.crsftoken.crsfToken
                },
                success: function (data) {
                    hidePreloader();
                    if (data.status) {
                        show_notification('success', data.message || window.translations.delete_success_lang);
                        fetchdata(currentPaginationUrl); // reload the page
                    } else {
                        show_notification('error', data.message || window.translations.delete_failed_lang);
                    }
                },
                error: function (xhr) {
                    hidePreloader();
                    show_notification('error', window.translations.delete_failed_lang);
                    console.error(xhr);
                }
            });
        } else if (result.dismiss === Swal.DismissReason.cancel) {
            show_notification('info', window.translations.safe_lang);
        }
    });
}

