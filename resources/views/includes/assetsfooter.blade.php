<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/autonumeric/4.10.5/autoNumeric.min.js"></script>
<script src="{{ asset('v1/assets/libs/sweetalert2/sweetalert2.min.js')}}"></script>
<script src="{{ asset('v1/assets/libs/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<script src="{{ asset('v1/assets/libs/simplebar/simplebar.min.js')}}"></script>
<script src="{{ asset('v1/assets/libs/node-waves/waves.min.js')}}"></script>
<script src="{{ asset('v1/assets/libs/feather-icons/feather.min.js')}}"></script>
<script src="{{ asset('v1/assets/js/pages/plugins/lord-icon-2.1.0.js')}}"></script>
<script src="{{ asset('v1/assets/js/plugins.js')}}"></script>
<script src="{{ asset('v1/assets/js/util/globalfn.js')}}"></script>
@if($tipe_halaman === "admin")
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.30.1/moment.min.js"></script>
<script src="{{ asset('v1/assets/js/app.js')}}"></script>
@endif
<script>
    var baseurlapi = "{{ url('/api/v1') }}";
    var baseurl = "{{ url('') }}";
    var id_user_login = "{{ isset($data['userInfo']->id) ? $data['userInfo']->id : '0' }}";
</script>