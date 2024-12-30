    <!-- jQuery -->
    <script src="{{ asset('frontend/vendor/jquery/jquery-3.3.1.slim.min.js') }}"></script>
    <!-- Bootstrap core JavaScript-->
    <script src="{{ asset('frontend/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- Select2 JavaScript-->
    <script src="{{ asset('frontend/vendor/select2/js/select2.min.js') }}"></script>
    <script src="{{ asset('frontend/vendor/owl-carousel/owl.carousel.js') }}"></script>
    <!-- Custom scripts for all pages-->
    <script src="{{ asset('frontend/js/custom.js') }}"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>


    <script>
        @if (Session::has('message'))
            var type = "{{ Session::get('alert-type', 'info') }}"
            switch (type) {
                case 'info':
                    toastr.info(" {{ Session::get('message') }} ");
                    break;

                case 'success':
                    toastr.success(" {{ Session::get('message') }} ");
                    break;

                case 'warning':
                    toastr.warning(" {{ Session::get('message') }} ");
                    break;

                case 'error':
                    toastr.error(" {{ Session::get('message') }} ");
                    break;
            }
        @endif
    </script>


    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>

    <script>
        function applyCoupon() {
            var coupon_name = $('#coupon_name').val(); // الحصول على اسم الكوبون
            $.ajax({
                type: "POST",
                dataType: "json",
                data: {
                    _token: '{{ csrf_token() }}', // تضمين CSRF Token
                    coupon_name: coupon_name
                },
                url: "/apply-coupon", // المسار إلى الدالة الخلفية
                success: function(data) {
                    // رسالة النجاح أو الخطأ باستخدام Swal
                    const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000
                    });

                    if (data.success) {
                        Toast.fire({
                            type: 'success',
                            icon: 'success',
                            title: data.message // عرض رسالة النجاح من الخادم
                        }).then(() => {
                            location.reload(); // إعادة تحميل الصفحة لتحديث السلة
                        });
                    } else if (data.error) {
                        Toast.fire({
                            type: 'error',
                            icon: 'error',
                            title: data.error // عرض رسالة الخطأ من الخادم
                        });
                    }
                },
                error: function(xhr, status, error) {
                    // التعامل مع أخطاء HTTP (مثل 500 أو 400)
                    const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000
                    });
                    Toast.fire({
                        type: 'error',
                        icon: 'error',
                        title: 'An error occurred. Please try again later.'
                    });
                    console.error('Error:', error); // طباعة الخطأ في وحدة التحكم للتصحيح
                }
            });
        }
    </script>


    <script>
        function couponRemove() {
            $.ajax({
                type: "GET",
                dataType: "json",
                url: "/remove-coupon",
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
                        });
                        location.reload();

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

    <script>
        $(document).ready(function() {
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 1500,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer);
                    toast.addEventListener('mouseleave', Swal.resumeTimer);
                }
            });

            $('.inc').on('click', function() {
                var id = $(this).data('id');
                var input = $(this).closest('span').find('input');
                var newQuantity = parseInt(input.val()) + 1;
                updateQuantity(id, newQuantity);
            });

            $('.dec').on('click', function() {
                var id = $(this).data('id');
                var input = $(this).closest('span').find('input');
                var newQuantity = parseInt(input.val()) - 1;
                if (newQuantity >= 1) {
                    updateQuantity(id, newQuantity);
                }
            });

            $('.remove').on('click', function() {
                var id = $(this).data('id');
                removeFromCart(id);
            });

            function updateQuantity(id, quantity) {
                $.ajax({
                    url: '{{ route('cart.updateQuantity') }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        id: id,
                        quantity: quantity
                    },
                    success: function(response) {
                        if (response.success) {
                            Toast.fire({
                                icon: 'success',
                                title: 'Quantity Updated'
                            }).then(() => {
                                location.reload();
                            });
                        } else if (response.error) {
                            Toast.fire({
                                icon: 'error',
                                title: response.error
                            });
                        }
                    },
                    error: function(xhr) {
                        Toast.fire({
                            icon: 'error',
                            title: 'An error occurred'
                        });
                    }
                })
            }

            function removeFromCart(id) {
                $.ajax({
                    url: '{{ route('cart.remove') }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        id: id
                    },
                    success: function(response) {

                        Toast.fire({
                            icon: 'success',
                            title: 'Cart Remove Successfully'
                        }).then(() => {
                            location.reload();
                        });

                    }
                });
            }
        })
    </script>
