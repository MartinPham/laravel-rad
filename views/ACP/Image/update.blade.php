@extends ('layouts.ACP.index')

@section ('body')


        <!-- Page content -->
<div id="page-content">
    <!-- Components Header -->
    <div class="content-header">
        <div class="header-section">
            <h1>
                <i class="fa fa-files-o"></i>Image
                <br>
                <small>{{ (@$item)?"Edit":"Add" }} </small>
            </h1>
        </div>
    </div>
    <ul class="breadcrumb breadcrumb-top">
        <li>Image</li>
        <li>{{ (@$item)?"Edit":"Add" }} </li>
    </ul>

    <!-- END Components Header -->



    {!! Form::open(['files' => true, 'class' => 'form-horizontal xform-bordered', 'url' => Request::fullUrl()]) !!}

    {{--
            <!-- Actions block -->
    <div class="block action-block">
        <div class="form-group">
            {!! Form::button('<i class="fa fa-undo"></i> Annulla', ['class' => 'btn btn-sm btn-warning', 'type' => 'reset', 'onclick' => 'history.go(-1);']) !!}
            {!! Form::button('<i class="gi gi-ok_2"></i> Salva', ['class' => 'btn btn-sm btn-primary', 'type' => 'submit']) !!}
        </div>
    </div>
    <!-- END Actions block -->

    --}}
    <div class="block">
        <!-- Responsive Full Title -->
        <div class="block-title">
            <h2><i class="fa fa-files-o"></i> {{ (@$item)?"Edit":"Add" }}</h2>
        </div>
        <!-- END Responsive Full Title -->

        <!-- Form Components Row -->
        <div class="row">
            <div class="col-md-12">

                <div class="form-group">
                    <label class="col-md-2 control-label">Name</label>

                    <div class="col-md-10">
                        {!! Form::text('name', old('name', @$item->name), ['class' => 'form-control', 'required' => 'true']) !!}
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-2 control-label">Order (DESC)</label>

                    <div class="col-md-10">
                        {!! Form::text('idx', old('idx', @$item->idx), ['class' => 'form-control', 'required' => 'true']) !!}
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-2 control-label">Photo</label>

                    <div class="col-md-10">
                        {!! Form::text('photo', old('photo', @$item->photo),  ['class' => 'form-control xfile-picker', 'xrequired' => 'true', 'data-preview' => @$item->photo ? : '']) !!}
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-2 control-label">Lat</label>

                    <div class="col-md-10">
                        {!! Form::text('lat', old('lat', @$item->geometry['location']['lat']),  ['class' => 'form-control', 'required' => 'true']) !!}
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-2 control-label">Lng</label>

                    <div class="col-md-10">
                        {!! Form::text('lng', old('lng', @$item->geometry['location']['lng']),  ['class' => 'form-control', 'required' => 'true']) !!}
                    </div>
                </div>


                <div class="form-group">
                    <div class="text-center">
                        {!! Form::button('<i class="fa fa-repeat"></i> Cancel', ['class' => 'btn btn-sm btn-danger', 'type' => 'reset']) !!}
                        {!! Form::button('<i class="gi gi-ok_2"></i> Save', ['class' => 'btn btn-sm btn-primary', 'type' => 'submit']) !!}
                    </div>
                </div>



            </div>
            <!-- END Time and Date Pickers Block -->
        </div>
        <!-- END Row -->

    </div>
    <!-- end .block -->
    {!! Form::close() !!}

</div>
<!-- END Page Content -->


@stop

