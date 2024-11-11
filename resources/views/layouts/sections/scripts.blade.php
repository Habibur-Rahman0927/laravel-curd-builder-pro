<script src="{{ asset('assets/vendor/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/vendor/chartsjs/Chart.min.js') }}"></script>
<script src="{{ asset('assets/js/dashboard-charts.js') }}"></script>
<script src="{{ asset('assets/js/script.js') }}"></script>

<script src="{{ asset('assets/datatables/js/jquery-3.7.0.min.js') }}"></script>
<script src="{{ asset('assets/datatables/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/datatables/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('assets/datatables/js/jszip.min.js') }}"></script>
<script src="{{ asset('assets/datatables/js/vfs_fonts.js') }}"></script>
<script src="{{ asset('assets/datatables/js/pdfmake.min.js') }}"></script>
<script src="{{ asset('assets/datatables/js/buttons.html5.min.js') }}"></script>
<script src="{{ asset('assets/datatables/js/buttons.print.min.js') }}"></script>
<script src="{{ asset('assets/datatables/js/sweetalert2@11.js') }}"></script>

<!-- BEGIN: Page JS-->
@yield('page-script')
<!-- END: Page JS-->

@stack('scripts')
