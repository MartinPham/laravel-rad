@extends ('layouts.ACP.index')

@section ('body')


    <!-- Page content -->
    <div id="page-content">
        <!-- Dashboard Header -->
        <!-- For an image header add the class 'content-header-media' and an image as in the following example -->
        <div class="content-header content-header-media">
            <div class="header-section">
                <div class="row">
                    <!-- Main Title (hidden on small devices for the statistics to fit) -->
                    <div class="col-md-4 col-lg-6 hidden-xs hidden-sm">
                        <h1>Welcome <strong>{{ \Auth::user()->name }}</strong><br><small>Dashboard</small></h1>
                    </div>
                    <!-- END Main Title -->

                </div>
            </div>
            <!-- For best results use an image with a resolution of 2560x248 pixels (You can also use a blurred image with ratio 10:1 - eg: 1000x100 pixels - it will adjust and look great!) -->
            <img src="/assets/ACP/img/placeholders/headers/dashboard_header.jpg" alt="header image" class="animation-pulseSlow">
        </div>
        <!-- END Dashboard Header -->


    </div>
    <!-- END Page Content -->

@stop


@section ('script')
    <!-- Remember to include excanvas for IE8 chart support -->
    <!--[if IE 8]><script src="js/helpers/excanvas.min.js"></script><![endif]-->


@stop