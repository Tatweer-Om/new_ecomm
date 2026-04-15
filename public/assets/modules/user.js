$(document).ready(function() {
    
    $('.add_user').off().on('submit', function (e) {
        e.preventDefault();

        var formdatas = new FormData($('.add_user')[0]);
        formdatas.append('_token', window.crsftoken.crsfToken);
        var name = $('.name').val();
        var contact = $('.contact').val();
        var password = $('.password').val();
        var id = $('.user_id').val();
        
        // Validation
        if (name == "") {
            show_notification('error', window.translations.validate_user_name_lang);
            return false;
        }
        if (contact == "") {
            show_notification('error', window.translations.validate_user_contact_lang);
            return false;
        }
        if (id) {
            formdatas.append('_method', 'PUT'); // <-- important for Laravel
        }
        
        if (!id) {
            if (password == "") {
                show_notification('error', window.translations.validate_user_password_lang);
                return false;
            }
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

                if (!id) $(".add_user")[0].reset(); // reset only if add was successful
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
        user: false, // modal name is "user"
    });
});

function resetuserForm() {
    const form = document.querySelector('.add_user');
    if (form) {
        form.reset(); // reset all normal inputs
        $('.user_id').val(''); // clear hidden ID manually
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
                const user = fetch.data;
                $(".name").val(user.name);
                $(".contact").val(user.contact);
                $(".email").val(user.email); 
                // permission
                $(".dashboard").prop("checked", user.dashboard == 1);
                $(".user").prop("checked", user.user == 1);
                $(".booking").prop("checked", user.booking == 1);
                $(".customer").prop("checked", user.customer == 1);
                $(".expense").prop("checked", user.expense == 1);
                $(".dress").prop("checked", user.dress == 1);
                $(".laundry").prop("checked", user.laundry == 1);
                $(".setting").prop("checked", user.setting == 1);
                $(".add_dress").prop("checked", user.add_dress == 1);
                $(".delete_booking").prop("checked", user.delete_booking == 1);
                $(".report").prop("checked", user.report == 1);
                // 
                $(".user_id").val(user.id);
                $(".modal-title").html(window.translations.user_update_success_lang);

                // 🔥 Open Alpine modal here
                Alpine.store('modals').user = true;

            } else {
                show_notification('error', window.translations.user_not_found_lang);
            }
        },
        error: function() {
            hidePreloader();
            after_submit();
            show_notification('error', window.translations.user_not_found_lang);
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

