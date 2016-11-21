@extends ('layouts.ACP.index')

@section ('body')

        <!-- Pimage content -->
<div id="page-content">
    <!-- Table Responsive Header -->
    <div class="content-header">
        <div class="header-section">
            <h1>
                <i class="fa fa-files-o"></i>Item
                <br>
                <small>List</small>
            </h1>
        </div>
    </div>
    <!-- <ul class="breadcrumb breadcrumb-top">
        <li>Image</li>
        <li>List</li>
    </ul> -->
    <!-- END Table Responsive Header -->


    <!-- Responsive Full Block -->
    <div class="block">
        <div class="form-group">
            <a href="{{ route($routeId . '.create') }}" data-toggle="tooltip"  class="btn btn-primary"><i class="fa fa-plus"> &nbsp; <b>New</b> &nbsp; </i></a>


        </div>
        <!-- END Responsive Full Title -->
        <div class="table-responsive">
            <table class="table table-vcenter table-striped">
                <thead>
                <tr>
                    <th style="width: 20%;">Photo</th>
                    <th style="width: 20%;">Name</th>
                    <th style="width: 50%;" class="text-center"></th>
                </tr>
                </thead>
                <tbody>
                @foreach ($items as $i)
                    <tr data-id="{{ $i->id }}">
                        <td><img src="{{ $i->photo }}" width="100" /></td>
                        <td>{{ $i->name }}</td>
                        <td class="text-right">
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route($routeId . '.update', [ 'id' => $i->id]) }}" data-toggle="tooltip" class="btn btn-info"><i class="fa fa-pencil"> &nbsp; <b>Edit</b> &nbsp; </i></a>

                                <a onclick="confirmDelete('{{ route($routeId . '.delete', [ 'id' => $i->id]) }}')" data-toggle="tooltip" class="btn btn-danger"><i class="fa fa-times"> &nbsp; <b>Delete</b> &nbsp; </i></a>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <!-- END Responsive Full Content -->
    </div>
    <!-- END Responsive Full Block -->

</div>
<!-- END Responsive Partial Block -->
</div>
<!-- END Pimage Content -->


@stop


@section ('script')


@stop