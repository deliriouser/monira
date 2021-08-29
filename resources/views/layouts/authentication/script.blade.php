<script src="{{asset('assets/js/jquery-3.5.1.min.js')}}"></script>
<!-- Bootstrap js-->
<script src="{{asset('assets/js/bootstrap/bootstrap.bundle.min.js')}}"></script>
<!-- feather icon js-->
<script src="{{asset('assets/js/icons/feather-icon/feather.min.js')}}"></script>
<script src="{{asset('assets/js/icons/feather-icon/feather-icon.js')}}"></script>
<!-- scrollbar js-->
<!-- Sidebar jquery-->
<script src="{{asset('assets/js/config.js')}}"></script>
<!-- Plugins JS start-->
@yield('script')
<!-- Plugins JS Ends-->
<!-- Theme js-->
<script src="{{asset('assets/js/script.js')}}"></script>
<script src="{{asset('assets/js/blockui/jquery.blockUI.min.js')}}"></script>
<script src="{{asset('assets/js/sweetalerts/sweetalert2.min.js')}}"></script>

<!-- Plugin used-->

<script>
    $(document).ready(function(){
        $('body').on("submit", "#myform", function(e){
                e.preventDefault();
                var form = $("#myform").closest("form");
                var formData = new FormData(form[0]);
                var url = form.attr('action');
                var curenturl = window.location.href;
                $.blockUI({
                message: 'Login....',
                fadeIn: 800,
                overlayCSS: {
                    backgroundColor: '#1b2024',
                    opacity: 0.8,
                    zIndex: 1200,
                    cursor: 'wait'
                },
                css: {
                    border: 0,
                    color: '#fff',
                    zIndex: 1201,
                    padding: 0,
                    backgroundColor: 'transparent'
                }
            });

                $.ajax({
                    processing: true,
                    serverSide: true,
                    processData: false,
                    contentType: false,
                    type: "POST",
                    url: url,
                    data: formData, // serializes the form's elements.
                    success: function(data) {
                        $.unblockUI();
                        if(data==1) {
                        window.location.href = curenturl;
                        } else {
                        Swal({title: "Gagal", text: "Username dan Password Salah", type:"error", timer: 5000});
                        }
                    }
                    });
            });
        });
    </script>
