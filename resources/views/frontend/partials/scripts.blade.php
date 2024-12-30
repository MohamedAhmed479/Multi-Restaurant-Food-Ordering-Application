    <!-- jQuery -->
    <script src="{{ asset('frontend') }}/vendor/jquery/jquery-3.3.1.slim.min.js"></script>
    <!-- Bootstrap core JavaScript-->
    <script src="{{ asset('frontend') }}/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- Select2 JavaScript-->
    <script src="{{ asset('frontend') }}/vendor/select2/js/select2.min.js"></script>
    <!-- Owl Carousel -->
    <script src="{{ asset('frontend') }}/vendor/owl-carousel/owl.carousel.js"></script>
    <!-- Custom scripts for all pages-->
    <script src="{{ asset('frontend') }}/js/custom.js"></script>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    {{-- ------------ Wishlist Add Start ----------- --}}
    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        function addWishList(id, element) {
            // إضافة تأثير عند الضغط
            $(element).find('i').addClass('text-success'); // تغيير اللون
            $(element).find('i').css({
                'transform': 'scale(1.5)',
                'transition': 'transform 0.3s ease, color 0.3s ease'
            });

            // إعادة الشكل بعد التأثير
            setTimeout(() => {
                $(element).find('i').css('transform', 'scale(1)');
            }, 300);

            //alert(id)
            $.ajax({
                type: "POST",
                dataType: "json",
                url: "/add-wish-list/" + id,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(data) {
                    // Start Message 
                    const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',

                        showConfirmButton: false,
                        timer: 3000
                    })
                    if ($.isEmptyObject(data.error)) {

                        Toast.fire({
                            type: 'success',
                            icon: 'success',
                            title: data.success,
                        })
                    } else {

                        Toast.fire({
                            type: 'error',
                            icon: 'error',
                            title: data.error,
                        })
                    }
                    // End Message  
                }
            })
        }
    </script>
