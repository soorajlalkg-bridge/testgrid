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
                    Nodes List</div>
                      <div class="card-body">
                        <a class="btn btn-primary button" href="{{ url('/admin/nodes/create') }}">
                        <i class="fa fa-user-plus"></i>&nbsp;Add Node</a>
                        <br/><br/>

                        <table id="nodes-list-data-table" class="table table-responsive-sm table-bordered data-table" style="width:100%">
                        <thead>
                            <tr>
                                <th>IP</th>
                                <th>Actions</th>
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

<div class="modal fade" id="nodeConfirmModal" tabindex="-1" role="dialog" aria-labelledby="nodeConfirmModalLabel" aria-hidden="true">
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
        <button class="btn btn-danger" type="button" id="delete-node">Delete</button>
      </div>
    </div>
    <!-- /.modal-content-->
  </div>
  <!-- /.modal-dialog-->
</div>

@endsection
