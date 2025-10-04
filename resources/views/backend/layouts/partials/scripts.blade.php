<!-- ================= Core JS ================= -->
<!-- jQuery (only once, must be first) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Bootstrap 4 JS -->
<script src="{{ asset('admin/assets/js/popper.min.js') }}"></script>
<script src="{{ asset('admin/assets/js/bootstrap.min.js') }}"></script>

<!-- ================= DataTables ================= -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

<!-- ================= Other Plugins ================= -->
<script src="{{ asset('admin/assets/js/owl.carousel.min.js') }}"></script>
<script src="{{ asset('admin/assets/js/metisMenu.min.js') }}"></script>
<script src="{{ asset('admin/assets/js/jquery.slimscroll.min.js') }}"></script>
<script src="{{ asset('admin/assets/js/jquery.slicknav.min.js') }}"></script>

<!-- ================= Charts ================= -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.min.js"></script>

<!-- Highcharts (with required modules) -->
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>
<script src="https://code.highcharts.com/modules/accessibility.js"></script>

<!-- ZingChart -->
<script src="https://cdn.zingchart.com/zingchart.min.js"></script>
<script>
    zingchart.MODULESDIR = "https://cdn.zingchart.com/modules/";
    ZC.LICENSE = ["569d52cefae586f634c54f86dc99e6a9", "ee6b7db5b51705a13dc2339db3edaf6d"];
</script>

<!-- Custom chart scripts -->
<script src="{{ asset('admin/assets/js/line-chart.js') }}"></script>
<script src="{{ asset('admin/assets/js/pie-chart.js') }}"></script>

<!-- ================= Summernote ================= -->
<link href="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote.min.js"></script>

<!-- ================= Custom Plugins & Scripts ================= -->
<script src="{{ asset('admin/assets/js/plugins.js') }}"></script>
<script src="{{ asset('admin/assets/js/scripts.js') }}"></script>

<!-- ================= Init Scripts ================= -->
<script>
    $(document).ready(function() {
        // DataTable initialize safely
        if ($.fn.DataTable.isDataTable('#dataTable')) {
            $('#dataTable').DataTable().destroy();
        }
        $('#dataTable').DataTable();
    });
</script>
