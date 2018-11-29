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
                    @php
                        var_dump($output);
                    @endphp    
                </div>
            </div>

            <div class="row">
              <div class="col-sm-12">
                <div class="card">
                    <form method="post" action="{{url('/admin/saveProfile')}}">
                        <input type="hidden" value="{{csrf_token()}}" name="_token" />
                  <div class="card-header">
                    <strong>Edit Profile</strong>
                  </div>
                  <div class="card-body">
                    <div class="row">
                      <div class="col-sm-12">
                        <div class="form-group">
                          <label for="name">Name</label>
                          <input type="text" class="form-control" id="name" name="name" value="{{$user->name}}" required="" placeholder="Name">
                        </div>
                      </div>
                    </div>
                    <!-- /.row-->
                    <div class="row">
                      <div class="col-sm-12">
                        <div class="form-group">
                          <label for="email">Email</label>
                          <input type="email" class="form-control" id="email" name="email" value="{{$user->email}}" required="" placeholder="email">
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


<!-- <div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Edit Profile</div>

                <div class="panel-body">
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
                    <form method="post" action="{{url('/admin/saveProfile')}}">
                        <input type="hidden" value="{{csrf_token()}}" name="_token" />
                      <div class="form-group">
                        <label for="name">Name:</label>
                        <input type="text" class="form-control" id="name" name="name" value="{{$user->name}}" required="">
                      </div>
                      <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" class="form-control" id="email" name="email" value="{{$user->email}}" required="">
                      </div>
                      <button type="submit" class="btn btn-default">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div> -->
@endsection
