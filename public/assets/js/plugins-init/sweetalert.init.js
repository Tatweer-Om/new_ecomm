(function($) {
    "use strict";

    document.addEventListener('DOMContentLoaded', function() {
        const selectors = [
            { selector: '.sweet-wrong', handler: () => Swal.fire("Oops...", "Something went wrong !!", "error") },
            { selector: '.sweet-message', handler: () => Swal.fire("Hey, Here's a message !!") },
            { selector: '.sweet-text', handler: () => Swal.fire("Hey, Here's a message !!", "It's pretty, isn't it?") },
            { selector: '.sweet-success', handler: () => Swal.fire("Hey, Good job !!", "You clicked the button !!", "success") },
            { selector: '.sweet-confirm', handler: () => Swal.fire({
                title: "Are you sure to delete ?",
                text: "You will not be able to recover this imaginary file !!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, delete it !!"
            }).then((result) => {
                if(result.isConfirmed) {
                    Swal.fire("Deleted !!", "Hey, your imaginary file has been deleted !!", "success");
                }
            }) },
            { selector: '.sweet-success-cancel', handler: () => Swal.fire({
                title: "Are you sure to delete ?",
                text: "You will not be able to recover this imaginary file !!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, delete it !!",
                cancelButtonText: "No, cancel it !!"
            }).then((result) => {
                if(result.isConfirmed) {
                    Swal.fire("Deleted !!", "Hey, your imaginary file has been deleted !!", "success");
                } else {
                    Swal.fire("Cancelled !!", "Hey, your imaginary file is safe !!", "error");
                }
            }) },
            { selector: '.sweet-image-message', handler: () => Swal.fire({
                title: "Sweet !!",
                text: "Hey, Here's a custom image !!",
                imageUrl: "images/hand.png",
                imageWidth: "20%"
            }) },
            { selector: '.sweet-html', handler: () => Swal.fire({
                title: "Sweet !!",
                html: "<span style='color:#ff0000'>Hey, you are using HTML !!<span>"
            }) },
            { selector: '.sweet-auto', handler: () => Swal.fire({
                title: "Sweet auto close alert !!",
                text: "Hey, i will close in 2 seconds !!",
                timer: 2000,
                showConfirmButton: false
            }) }
        ];

        selectors.forEach(item => {
            const el = document.querySelector(item.selector);
            if(el) {
                el.onclick = item.handler;
            }
        });
    });

})(jQuery);
