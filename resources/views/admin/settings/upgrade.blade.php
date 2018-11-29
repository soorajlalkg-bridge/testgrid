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
                <div class="col-sm-12">
                        
                </div>
            </div>

            <div class="row">
              <div class="col-sm-12">
                <div class="card">
                    <form method="post" action="{{url('/admin/upgradeVersion')}}">
                        <input type="hidden" value="{{csrf_token()}}" name="_token" />
                  <div class="card-header">
                    <strong>Upgrade Application</strong>
                  </div>
                  <div class="card-body">
                    <div class="row">
                      <div class="col-sm-12">
                        <div class="form-group">
                          <label for="name">Current Version</label>
                            @php
                                var_dump($output);
                            @endphp
                        </div>
                      </div>
                    </div>
                    <!-- /.row-->
                  </div>
                  <div class="card-footer">
                        <button class="btn btn-sm btn-primary button" type="submit">
                          <i class="fa fa-dot-circle-o"></i> Upgrade</button>
                    </div>
                    </form>
                </div>
              </div>
          </div>
      </div>
  </div>

@endsection
