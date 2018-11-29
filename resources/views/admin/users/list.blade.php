@extends('admin.layout.app')

@section('content')
<div class="container-fluid">
    <div class="animated fadeIn">
        @if(Session::has('success'))
            <div class="alert alert-success">
              {{Session::get('success')}}
            </div>
        @endif
        @if(Session::has('failure'))
            <div class="alert alert-danger">
              {{Session::get('failure')}}
            </div>
        @endif
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                  <div class="card-header">
                    Users List</div>
                      <div class="card-body">
                        <div class="float-left">
                          <a class="btn btn-primary button" href="{{ url('/admin/users/create') }}">
                          <i class="fa fa-user-plus"></i>&nbsp;Add User</a>
                        </div>
                        <div class="float-right">User License Limit: {{ $userLicenseLimit }}</div>
                        <br/><br/>

                        <!-- <table class="table table-responsive-sm table-bordered"> -->
                        <table id="users-list-data-table" class="table table-responsive-sm table-bordered data-table" style="width:100%">
                        <thead>
                            <tr>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Username</th>
                                <th>Email</th>
                                <th>Login Limit</th>
                                <th>Created</th>
                                <th>Action</th>
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

<div class="modal fade" id="userConfirmModal" tabindex="-1" role="dialog" aria-labelledby="userConfirmModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Delete Confirmation</h4>
        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <div class="modal-body">
        <p>Are you sure you want to delete?</p>
      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary" type="button" data-dismiss="modal">Close</button>
        <button class="btn btn-danger" type="button" id="delete-user">Delete</button>
      </div>
    </div>
    <!-- /.modal-content-->
  </div>
  <!-- /.modal-dialog-->
</div>

@endsection
