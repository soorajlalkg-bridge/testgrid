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
              <div class="col-sm-6 col-md-6">
                <div class="card">
                  <div class="card-body">
                    <div class="h1 text-muted text-right mb-4">
                      <!-- <i class="icon-people"></i> -->
                      <i class="fa fa-users"></i>
                    </div>
                    <div class="text-value">{{ $activeUsers }}</div>
                    <small class="text-muted text-uppercase font-weight-bold">Total number of current users</small>
                  </div>
                </div>
              </div>
              <!-- /.col-->
              <div class="col-sm-6 col-md-6">
                <div class="card">
                  <div class="card-body">
                    <div class="h1 text-muted text-right mb-4">
                      <!-- <i class="icon-user-follow"></i> -->
                      <i class="fa fa-laptop"></i>
                    </div>
                    <div class="text-value">{{ $activeMachines }}</div>
                    <small class="text-muted text-uppercase font-weight-bold">Total number of active machines</small>
                  </div>
                </div>
              </div>
              <!-- /.col-->
            </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                  <div class="card-header">
                    Active User Sessions List</div>
                      <div class="card-body">

                        <table id="status-list-data-table" class="table table-responsive-sm table-bordered data-table" style="width:100%">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>IP</th>
                                <th>Last login</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
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
                <div class="panel-heading">Active Users List</div>

                <div class="panel-body">
                    <div class="col-md-6">
                        <div class="list-group">
                          <span class="list-group-item">#Total number of current users</span>
                          <span class="list-group-item">{{ $activeUsers }}</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="list-group">
                          <span class="list-group-item">#Total number of active machines</span>
                          <span class="list-group-item">{{ $activeMachines }}</span>
                        </div>
                    </div>
                    <table id="status-list-data-table" class="table table-striped table-bordered data-table" style="width:100%">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Machine</th>
                                <th>Last login</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Machine</th>
                                <th>Last login</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div> -->
@endsection
