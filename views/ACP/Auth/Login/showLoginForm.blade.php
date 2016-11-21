@extends ('layouts.ACP.auth')

@section ('body')
        <!-- Login Alternative Row -->
<div class="container">
    <div class="row">
        <div class="col-md-5 col-md-offset-1">
            <div id="login-alt-container">
                <!-- Title -->
                <h1 class="push-top-bottom">
                    <i class="gi gi-flash"></i> <strong>{{ @$template['name'] }} ACP</strong><br>
                    <small>Welcome to {{ @$template['name'] }} Administrator Control Panel</small>
                </h1>
                <!-- END Title -->

                <!-- Key Features -->
                <ul class="fa-ul text-muted">
                    <li><i class="fa fa-info-circle fa-li text-success"></i> Please login using provided credentials.</li>
                    <li><i class="fa fa-info-circle fa-li text-success"></i> Only Administrators can access this area.</li>
                </ul>
                <!-- END Key Features -->

                <!-- Footer -->
                <footer class="text-muted push-top-bottom">
                    <small>&copy 2016 - {{ @$template['name'] }}</small>
                </footer>
                <!-- END Footer -->
            </div>
        </div>
        <div class="col-md-5">
            <!-- Login Container -->
            <div id="login-container">
                <!-- Login Title -->
                <div class="login-title text-center">
                    <h1><strong>Access ACP</strong></strong></h1>
                </div>
                <!-- END Login Title -->
                <!-- Login Block -->
                <div class="block push-bit">
                    <!-- Login Form -->

                    {!! Form::open(['files' => true, 'class' => 'form-horizontal', 'id' => 'form-login']) !!}
                        @if (session('action') == 'login')
                            @if (@$errors && count($errors) > 0)
                                <div class="alert alert-danger">
                                    <strong>Whoops!</strong> There were some problems with your input.<br><br>
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        @endif
                        {!! csrf_field() !!}
                        <div class="form-group">
                            <div class="col-xs-12">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="gi gi-envelope"></i></span>
                                    {!! Form::text('email', old('email'), ['class' => 'form-control input-lg', 'id' => 'login-email', 'required' => 'true']) !!}
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-xs-12">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="gi gi-asterisk"></i></span>
                                    {!! Form::password('password', ['class' => 'form-control input-lg', 'id' => 'login-password', 'required' => 'true']) !!}
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-xs-12">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-android"></i></span>
                                    {!! Recaptcha::render() !!}
                                </div>
                            </div>
                        </div>
                        <div class="form-group form-actions">
                            <div class="col-xs-4">
                            </div>
                            <div class="col-xs-8 text-right">
                                <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-angle-right"></i>&nbsp; <b> &nbsp; Login to ACP &nbsp; &nbsp;</b></button><br/>
                            </div>
                        </div>
                    {!! Form::close() !!}
                    <!-- END Login Form -->



                </div>
                <!-- END Login Block -->
            </div>
            <!-- END Login Container -->
        </div>
    </div>
</div>
<!-- END Login Alternative Row -->

@stop


@section ('script')
        <!-- Load and execute javascript code used only in this page -->
<script src="/assets/ACP/js/pages/login.js"></script>
<script>$(function(){ Login.init(); });</script>
@stop