 <!-- ===============================================-->
    <!--    Favicons-->
    <!-- ===============================================-->
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('backend') }}/assets/img/favicons/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('backend') }}/assets/img/favicons/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('backend') }}/assets/img/favicons/favicon-16x16.png">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('backend') }}/assets/img/favicons/favicon.ico">
    <link rel="manifest" href="{{ asset('backend') }}/assets/img/favicons/manifest.json">
    <meta name="msapplication-TileImage" content="{{ asset('backend') }}/assets/img/favicons/mstile-150x150.png">
    <meta name="theme-color" content="#ffffff">
    <script src="vendors/imagesloaded/imagesloaded.pkgd.min.js"></script>

    <!-- ===============================================-->
    <!--    Stylesheets-->
    <!-- ===============================================-->
    <link rel="preconnect" href="https://fonts.googleapis.com/">
    <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin="">
    <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@300;400;600;700;800;900&amp;display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../../unicons.iconscout.com/release/v4.0.0/css/line.css">
    <link href="{{ asset('backend') }}/assets/css/theme.min.css" type="text/css" rel="stylesheet" id="style-default">
    <link href="{{ asset('backend') }}/assets/css/user.min.css" type="text/css" rel="stylesheet" id="user-style-default">
    <link href="{{ asset('backend') }}/vendors/leaflet/leaflet.css" rel="stylesheet">
    <link href="{{ asset('backend') }}/vendors/leaflet.markercluster/MarkerCluster.css" rel="stylesheet">
    <link href="{{ asset('backend') }}/vendors/leaflet.markercluster/MarkerCluster.Default.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.10.16/css/dataTables.bootstrap.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.10.16/css/dataTables.bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.10.16/css/dataTables.bootstrap4.css" rel="stylesheet">
    {{-- <link href="https://cdn.datatables.net/2.0.3/css/dataTables.bootstrap5.css" rel="stylesheet"> --}}
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>

    <link href="https://fonts.googleapis.com/css2?family=Noto+Serif+Bengali:wght@100..900&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    @if(config('app.locale') == 'bn')
<style>
    body{
        font-family: "Noto Serif Bengali", serif;
    }
</style>
@endif

<style>
    span.select2-selection.select2-selection--single {
    border-radius: 0px;
    height: 34px;
}

.profile-avatar {
    /* background: #ff8c11; */
    color: white;
    width: 34px;
    height: 34px;
    border-radius: 100%;
    align-items: center;
    justify-content: center;
    text-align: center;
    font-size: 24px;
}
</style>
