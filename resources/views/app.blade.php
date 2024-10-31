<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
    <!-- fabicon -->
    <link rel="apple-touch-icon" sizes="76x76" href="/admin_assets/logo/favicon.ico">
    {{-- <link rel="icon" type="image/png" sizes="32x32" href="/frontend_assets/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/frontend_assets/favicon/favicon-16x16.png">
    <link rel="manifest" href="/frontend_assets/favicon/site.webmanifest">
    <link rel="mask-icon" href="/frontend_assets/favicon/safari-pinned-tab.svg" color="#5bbad5"> --}}
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">
    {{-- <link rel="stylesheet" href="{{ asset('admin_assets/vendors/general/bootstrap/dist/css/bootstrap.min.css') }}" >
    <link href="{{ asset('admin_assets/vendors/custom/vendors/fontawesome5/css/all.min.css') }}" rel="stylesheet"
        type="text/css" /> --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @routes
    @vite('resources/js/app.js')
    {{-- @vite('resources/css/app.css') --}}
    @inertiaHead
  </head>
  <body>
    @inertia
    <div id="modal"></div>
  </body>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js" integrity="sha512-bLT0Qm9VnAYZDflyKcBaQ2gg0hSYNQrJ8RilYldYQ1FxQYoCLtUjuuRuZo+fjqhx/qtq/1itJ0C2ejDxltZVFg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<!-- <script src="{{ asset('admin_assets/vendors/general/jquery/dist/jquery.slim.min.js') }}"></script> -->
<script src="{{ asset('admin_assets/vendors/general/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('admin_assets/vendors/general/bootstrap/dist/js/bootstrap.min.js') }}"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery.nicescroll/3.5.1/jquery.nicescroll.min.js"></script>
</html>
