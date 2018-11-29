@extends('admin.layout.app')

@section('content')
<div class="container-fluid">
          <div class="animated fadeIn">
            @if(Session::has('success'))
                <div class="alert alert-success">
                  {{Session::get('success')}}
                </div>
            @endif
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <div class="row">
              <div class="col-sm-8">
                <div class="card">
                    <form method="post" action="{{url('/admin/nodes/update/'.$id)}}">
                        <input type="hidden" name="_method" value="PUT">
                        <input type="hidden" value="{{csrf_token()}}" name="_token" />
                  <div class="card-header">
                    <strong>Edit Node</strong>
                  </div>
                  <div class="card-body">
                    <div class="row">
                      <div class="col-sm-12">
                        <div class="form-group">
                          <label for="ip">IP</label>
                          <input type="text" class="form-control" id="ip" name="ip" value="{{$node->ip}}" required="">
                        </div>
                      </div>
                    </div>
                    <!-- /.row-->
                  </div>
                  <div class="card-footer">
                        <button class="btn btn-sm btn-primary button" type="submit">
                          <i class="fa fa-dot-circle-o"></i> Submit</button>
                    </div>
                    </form>
                </div>
              </div>
          </div>
      </div>
  </div>

@endsection
