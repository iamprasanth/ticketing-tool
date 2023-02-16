<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta name="csrf-token" content="{!! csrf_token() !!}">
    <title>FAKTENHAUS</title>
    <!-- Favicon -->
    <!-- <link rel="shortcut icon" href="favicon.ico">
	<link rel="icon" href="favicon.ico" type="image/x-icon"> -->
    <!-- Custom CSS -->
    <!-- Select-2 -->
    @if (($title == 'project_list') || (($title == 'project_view')) || ($title == 'users_list') || ($title == 'ticket_list') || ($title == 'ticket_view'))
    <link href="{{ asset('css/bootstrap-select.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('/css/select2.min.css') }}" rel="stylesheet" type="text/css">
    @endif
    <!-- Datepicker -->
    @if (($title == 'project_list') || (($title == 'project_view')) || ($title == 'users_list'))
    <link href="{{ asset('css/bootstrap-datepicker.min.css') }}" rel="stylesheet" type="text/css">
    @endif
    <!-- Data-table -->
    @if (($title == 'project_list') || ($title == 'project_category') || ($title == 'project_labels') || ($title == 'project_taskLabel')
    || ($title == 'users_list') || ($title == 'ticket_category') || ($title == 'ticket_status') || ($title == 'ticket_list'))
    <link href="{{ asset('css/datatables.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('themes/dist/css/responsive.dataTables.min.css') }}" rel="stylesheet" type="text/css">
    @endif
    <!-- Sweet-alert -->
    @if (($title == 'project_list') || ($title == 'project_category') || ($title == 'project_labels') || ($title == 'project_taskLabel')
    || ($title == 'users_list') || ($title == 'ticket_category')|| ($title == 'ticket_status') || ($title == 'ticket_list')  || ($title == 'ticket_view'))
    <link href="{{asset('css/sweetalert.min.css')}}" rel="stylesheet" type="text/css">
    @endif
    <!-- Pretty check-box -->
    @if (($title == 'project_view'))
    <link href="{{asset('css/pretty-checkbox.min.css')}}" rel="stylesheet" type="text/css">
    @endif
    <link href="{{asset('themes/dist/css/style.css')}}" rel="stylesheet" type="text/css">
    <link href="{{asset('css/custom.css')}}" rel="stylesheet" type="text/css">
</head>

<body>
    <!-- Preloader -->
    <div class="preloader-it">
        <div class="la-anim-1"></div>
    </div>
    <!-- /Preloader -->
    <div class="wrapper  theme-1-active pimary-color-blue">
        @include('layout.header')
        @include('layout.sidebar')
        <div class="right-sidebar-backdrop"></div>
        <div class="page-wrapper">
            <div class="container-fluid">
                @yield('content')

                @include('layout.footer')
            </div>
        </div>
    </div>

    <script src="{{ asset('vendors/bower_components/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('vendors/bower_components/bootstrap/dist/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('themes/dist/js/jquery.slimscroll.min.js') }}"></script>
    <script src="{{ asset('themes/dist/js/init.min.js') }}"></script>
    <script src="{{ asset('/messages.js') }}"></script>
    <script src="{{ asset('/js/loadingoverlay.min.js') }}"></script>

    <!-- Custom Plugins -->
    @if (($title == 'project_list') || ($title == 'project_category') || ($title == 'project_labels') || ($title == 'project_taskLabel')
    || ($title == 'users_list') || ($title == 'ticket_category') || ($title == 'ticket_status') || ($title == 'ticket_list'))
    <script src="{{ asset('vendors/bower_components/datatables/media/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('themes/dist/js/dataTables.responsive.min.js') }}"></script>
    @endif
    <!-- Datepicker -->
    @if (($title == 'project_list') || (($title == 'project_view')) || ($title == 'users_list'))
    <script src="{{ asset('/js/bootstrap-datepicker.min.js') }}"></script>
    @endif
    <!-- Select-2 -->
    @if (($title == 'project_list') || (($title == 'project_view')) || ($title == 'users_list') || ($title == 'ticket_list') || ($title == 'ticket_view'))
    <script src="{{ asset('themes/dist/js/bootstrap-select.min.js') }}"></script>
    <script src="{{ asset('themes/dist/js/jquery.multi-select.min.js') }}"></script>
    <script src="{{ asset('/js/select2.full.min.js') }}"></script>
    @endif
    <!-- CK-Editor -->
    @if (($title == 'project_view') || ($title == 'ticket_view'))
    <script src="{{ asset('/ckeditor/ckeditor.js') }}"></script>
    @endif
    <!-- Sweet-alert -->
    @if (($title == 'project_list') || ($title == 'project_category') || ($title == 'project_labels') || ($title == 'project_taskLabel')
    || ($title == 'users_list') || ($title == 'ticket_category') || ($title == 'ticket_status') || ($title == 'ticket_list')  || ($title == 'ticket_view'))
    <script src="{{ asset('/js/sweetalert.min.js') }}"></script>
    @endif
    <!-- Moment -->
    @if (($title == 'project_view') || ($title == 'users_list') || ($title == 'ticket_list'))
    <script src="{{ asset('/js/moment.min.js') }}"></script>
    @endif
    <!-- InputMask -->
    @if (($title == 'project_list') || (($title == 'project_view')) || ($title == 'users_list'))
    <script src="{{ asset('/js/jquery.inputmask.bundle.min.js') }}"></script>
    @endif
    <!-- Match-Height -->
    @if (($title == 'project_list') || (($title == 'project_view')) || ($title == 'users_list'))
    <script src="{{ asset('/js/jquery.matchHeight.min.js') }}"></script>
    @endif
    <!-- Overlay -->
    @if (($title == 'project_list') || (($title == 'project_view')) || ($title == 'users_list'))
    <script src="{{ asset('/js/loadingoverlay.min.js') }}"></script>
    @endif
    <!-- Bootstrap-Switchery -->
    @if (($title == 'users_list') || ($title == 'project_category') || ($title == 'project_labels') || ($title == 'project_taskLabel') || ($title == 'ticket_category') || ($title == 'ticket_status') || ($title == 'ticket_list'))
    <script src="{{ asset('themes/dist/js/switchery.min.js') }}"></script>
    <script src="{{ asset('vendors/bower_components/bootstrap-switch/dist/js/bootstrap-switch.min.js') }}"></script>
    @endif
    <?php $baseUrl = env('APP_URL');
    $constants = Config::get('constants'); ?>
    <script>
        var constants = <?php echo json_encode($constants) ?>;
        var baseUrl = <?php echo json_encode($baseUrl) ?>;
    </script>
    <script src="/js/@yield('js_file', 'custom.js')"></script>
</body>

</html>
