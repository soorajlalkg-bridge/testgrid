@extends('admin.layout.app')

@section('content')
<!-- <div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Dashboard</div>

                <div class="panel-body">
                    You are logged in as Admin!
                </div>
            </div>
        </div>
    </div>
</div> -->
<div class="container-fluid">
  <div class="animated fadeIn">
    <div class="row">
      <div class="col-lg-12">
        <div class="card">
          <div class="card-header">
            Dashboard
          </div>
          <div class="card-body">
            <p>You are logged in as Admin!</p>
          </div>
        </div>
      </div>
    </div>
    <!-- /.row-->
  </div>
</div>
@endsection
