@extends('admin.layout.app')

@section('content')
<div class="container-fluid">
    <div class="animated fadeIn">
        @if(Session::has('success'))
            <div class="alert alert-success">
              {{Session::get('success')}}
            </div>
        @endif
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                  <div class="card-header">
                    Logs List</div>
                      <div class="card-body">
                        <form method="post" action="{{url('/admin/logs/export')}}" id="logs-list-data-table-form">
                            <input type="hidden" value="{{csrf_token()}}" name="_token" />
                            <button class="btn btn-primary button" type="submit" value="csv" name="type">
                            <i class="fa fa-dot-circle-o"></i> Export CSV</button>
                            <button class="btn btn-primary button" type="submit" value="pdf" name="type">
                            <i class="fa fa-dot-circle-o"></i> Export PDF</button>
                            <input type="hidden" value="" name="searchvalue" id="searchvalue" />

                            <br/><br/>

                            <!-- <table class="table table-responsive-sm table-bordered"> -->
                            <table id="logs-list-data-table" class="table table-responsive-sm table-bordered data-table" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Action</th>
                                        <th>Date</th>
                                        <th>Time</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </form>
                      </div>
                    </div>
            </div>
        </div>
    </div>
</div>

<!-- <div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Logs List</div>

                <div class="panel-body">
                    <table id="logs-list-data-table" class="table table-striped table-bordered data-table" style="width:100%">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Action</th>
                                <th>Date</th>
                                <th>Time</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php /*@foreach ($logs as $log)
                            <tr>
                                <td>{{$log->name}}</td>
                                <td>{{$log->log}}</td>
                                <td>{{$log->created_at}}</td>
                            </tr>
                            @endforeach */?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>Name</th>
                                <th>Action</th>
                                <th>Date</th>
                                <th>Time</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div> -->
@endsection
