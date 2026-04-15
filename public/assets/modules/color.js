$(document).ready(function() {
    
    $('.add_color').off().on('submit', function (e) {
        e.preventDefault();

        var formdatas = new FormData($('.add_color')[0]);
        formdatas.append('_token', window.crsftoken.crsfToken);
        var color_name = $('.color_name').val();
        var color_name_ar = $('.color_name_ar').val();
        var id = $('.color_id').val();
        if (id) {
            formdatas.append('_method', 'PUT'); // <-- important for Laravel
        }
        // Validation
        if (color_name == "") {
            show_notification('error', window.translations.validate_color_name);
            return false;
        }
        if (color_name_ar == "") {
            show_notification('error', window.translations.validate_color_name_ar);
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

                if (!id) $(".add_color")[0].reset(); // reset only if add was successful
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
        color: false, // modal name is "color"
    });
});

function resetColorForm() {
    const form = document.querySelector('.add_color');
    if (form) {
        form.reset(); // reset all normal inputs
        $('.color_id').val(''); // clear hidden ID manually
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
                const color = fetch.data;
                $(".color_name").val(color.color_name);
                $(".color_name_ar").val(color.color_name_ar);
                $(".color_code").val(color.color_code);
                $(".color_id").val(color.id);
                $(".modal-title").html(window.translations.color_update_success_lang);

                // 🔥 Open Alpine modal here
                Alpine.store('modals').color = true;

            } else {
                show_notification('error', window.translations.color_not_found_lang);
            }
        },
        error: function() {
            hidePreloader();
            after_submit();
            show_notification('error', window.translations.color_not_found_lang);
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
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
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

