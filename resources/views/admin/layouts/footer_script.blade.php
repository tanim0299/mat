<!-- ===============================================-->
    <!--    JavaScripts-->
    <!-- ===============================================-->
    <script src="{{ asset('backend') }}/vendors/popper/popper.min.js"></script>
    <script src="{{ asset('backend') }}/vendors/bootstrap/bootstrap.min.js"></script>
    <script src="{{ asset('backend') }}/vendors/anchorjs/anchor.min.js"></script>
    <script src="{{ asset('backend') }}/vendors/is/is.min.js"></script>
    <script src="{{ asset('backend') }}/vendors/fontawesome/all.min.js"></script>
    <script src="{{ asset('backend') }}/vendors/lodash/lodash.min.js"></script>
    <script src="../../../polyfill.io/v3/polyfill.min58be.js?features=window.scroll"></script>
    <script src="{{ asset('backend') }}/vendors/list.js/list.min.js"></script>
    <script src="{{ asset('backend') }}/vendors/feather-icons/feather.min.js"></script>
    <script src="{{ asset('backend') }}/vendors/dayjs/dayjs.min.js"></script>
    <script src="{{ asset('backend') }}/assets/js/phoenix.js"></script>
    <script src="{{ asset('backend') }}/vendors/echarts/echarts.min.js"></script>
    <script src="{{ asset('backend') }}/vendors/chart/chart.min.js"></script>
    <script src="{{ asset('backend') }}/vendors/list.js/list.min.js"></script>
    <script src="{{ asset('backend') }}/vendors/leaflet/leaflet.js"></script>
    <script src="{{ asset('backend') }}/vendors/leaflet.markercluster/leaflet.markercluster.js"></script>
    <script src="{{ asset('backend') }}/vendors/leaflet.tilelayer.colorfilter/leaflet-tilelayer-colorfilter.min.js"></script>
    <script src="{{ asset('backend') }}/assets/js/ecommerce-dashboard.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    {{-- <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script> --}}
    {{-- <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script> --}}
    @stack('footer_script')
    <script>
        $(document).ready(function() {
            $('.select2').select2();
        });
    </script>
    <script>
        function Sure()
        {
            if(confirm("Are Your Sure To Delete?"))
        {
            return ture;
        }
        else
        {
            return false;
        }
        }
   </script>
