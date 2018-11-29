@extends('user.layout.app')

@section('content')
<img src="{{ URL::asset('/images/ajax-loader.gif') }}" id="loading-image">
<form id="configForm" method="POST" action="{{url('/user/actionConfigure')}}" enctype="multipart/form-data" autocomplete=off>
  <input type="hidden" value="{{csrf_token()}}" name="_token" />
  <div class="padding-15">
    <?php /*@if(Session::has('success') && !empty(Session::get('success')))
        <div class="alert alert-success alert-dismissible col-sm-12">
          {{Session::get('success')}}
        </div>
    @endif
    @if(Session::has('error') && !empty(Session::get('error')))
        <div class="alert alert-danger alert-dismissible col-sm-12">
          {{Session::get('error')}}
        </div>
    @endif*/?>
    @php ($hideAlertClass = ( (Session::has('success') && !empty(Session::get('success'))) || (Session::has('error') && !empty(Session::get('error'))) ) ? '' : 'd-none' )
    @php ($successAlertClass = (Session::has('success') && !empty(Session::get('success'))) ? 'alert-success' : '' )
    @php ($failureAlertClass = (Session::has('error') && !empty(Session::get('error'))) ? 'alert-danger' : '' )
    <div class="alert alert-dismissible {{$successAlertClass}} {{$failureAlertClass}} alert-message col-sm-12 {{$hideAlertClass}} ">
      <span>
        @if(Session::has('success') && !empty(Session::get('success')))
          {{Session::get('success')}}
        @endif
        @if(Session::has('error') && !empty(Session::get('error')))
          {{Session::get('error')}}
        @endif
      </span>
    </div>
  </div>
  <div class="row padding-15">
    <div class="col-sm-4">
      
        <div class="form-group margin-0">

          <label class="label-con">Configuration : </label>
          <select class="form-control con-select" id="config-files" name="configid">
            @if($cfgFilesCount > 0)
              @foreach($cfgFiles as $cfgFile)
                <option value="{{ $cfgFile->id }}" {{ $cfgFile->id == $latestCfgFile->id ? 'selected="selected"' : '' }}>{{ $cfgFile->name }}</option>
              @endforeach
            @else
              <option value="">{{ $defaultCfgFile->name }}</option>
            @endif
          </select>

        </div>
      
    </div>
    <div class="col-sm-8 text-right buttons-wrapp">
      <button class="btn secondary-btn" id="create-config">New</button>
      <button class="btn secondary-btn" id="update-config" disabled="">Save</button>
      <button class="btn secondary-btn" id="copy-config">Save As</button>
      <button class="btn secondary-btn" id="delete-config">Delete</button>
      <button class="btn secondary-btn d-none" id="delete-config-submit" type="submit" name="action" value="delete">Delete</button>
      <button class="btn secondary-btn" id="export-config" type="submit" name="action" value="export">Export</button>
      <button class="btn secondary-btn d-none" id="trigger-import" name="action" value="import">Import</button>
      <label class="btn secondary-btn" >
        Import<input type="file" name="importcfg" id="import-cfg" class="d-none">
      </label>
    </div>
  </div>

  <div class="row padding-15">
    <div class="col-sm-12">
      <div class="group-wrapp">
        <div class="text-value">
          #Groups
        </div>
        <input type="tel" value="{{ ($cfgFilesCount > 0)?$latestCfgFile->configgroup->count():$defaultCfgFile->numgroups }}" id="num-groups">
      </div>

      <div class="show-group">
        <label class="label-con">show group : </label>
        <select class="form-control con-select" id="config-groups" name="configgroupid">
        @if($cfgFilesCount > 0)
          @foreach($latestCfgFile->configgroup as $configGroup)
            <option value="{{ $configGroup->id }}" @if($latestCfgFile->selectedCfgGroupId == $configGroup->id) selected="selected" @endif >Group{{ $configGroup->groupindex }}</option>
          @endforeach
        @else
          <option value="">Group{{ $defaultCfgFile->numgroups }}</option>
        @endif
        </select>
        <a href="javascript:void(0);" data-toggle="tooltip" title="Group Up" class="btn button-cntrl" id="next-group-btn">
          <i class="material-icons dp48">arrow_drop_up</i>
        </a>
        <a href="javascript:void(0);" data-toggle="tooltip" title="Group Down" class="btn button-cntrl" id="prev-group-btn">
          <i class="material-icons dp48">arrow_drop_down</i>
        </a>
        <a href="javascript:void(0);" data-toggle="tooltip" title="Add Group" class="btn button-cntrl" id="create-group-btn">
          <i class="material-icons dp48">library_add</i>
        </a>
        <a href="javascript:void(0);" data-toggle="tooltip" title="Delete Group" class="btn button-cntrl" id="delete-group-btn">
          <i class="material-icons dp48">delete_forever</i>
        </a>
      </div>

    </div>
  </div>

  <div class="row padding-15">
    <div class="col-sm-12">
      <div class="group-wrapp">
        <div class="text-value">
          #Rows
        </div>
        <input type="tel" class="group-props" value="{{ ($cfgFilesCount > 0)? ($latestCfgFile->firstconfiggroup ? $latestCfgFile->firstconfiggroup->numrows:0) :$defaultCfgFile->numrows }}" id="num-rows" data-val="{{ ($cfgFilesCount > 0)? ($latestCfgFile->firstconfiggroup ? $latestCfgFile->firstconfiggroup->numrows:0) :$defaultCfgFile->numrows }}" name="numrows">
      </div>
      <div class="group-wrapp">
        <div class="text-value">
          #Columns
        </div>
        <input type="tel" class="group-props" value="{{ ($cfgFilesCount > 0)? ($latestCfgFile->firstconfiggroup ? $latestCfgFile->firstconfiggroup->numcols:0) :$defaultCfgFile->numcols }}" id="num-cols" data-val="{{ ($cfgFilesCount > 0)? ($latestCfgFile->firstconfiggroup ? $latestCfgFile->firstconfiggroup->numcols:0) :$defaultCfgFile->numcols }}" name="numcols">
      </div>
      <div class="group-wrapp show-group">
        <a href="javascript:void(0);" data-toggle="tooltip" title="Swap Rows & Columns" class="btn button-cntrl" id="swap-rows-cols">
          <i class="material-icons dp48">swap_horiz</i>
        </a>
      </div>

    </div>
  </div>

  <div class="row padding-15">
    <div class="col-sm-12">
      <table id="configure-table1" class="table table-striped table-bordered text-center configure" style="width:100%">
        <thead class="table-head">
            <tr>
                <th>Row</th>
                <th>Column</th>
                <th>Channel Label</th>
                <th>Channel</th>
                <th>IP</th>
                <th>Channel URL</th>
                <th>Channel ID</th>
            </tr>
        </thead>
        <tbody>
          @if($cfgFilesCount > 0)
            @php ($cfgChannelCol = 1)
            @if($latestCfgFile->channels)
            @foreach($latestCfgFile->channels as $cfgChannel)
              <tr id="row_{{ $cfgChannel->position }}_{{$cfgChannelCol}}" data-row="{{ $cfgChannel->position }}" data-col="{{$cfgChannelCol}}">
                <td>{{ $cfgChannel->position }}</td>
                <td>{{ $cfgChannelCol }}</td>
                <td>
                  <input type="text" class="channellabel" name="channels[{{ $cfgChannel->position }}][{{$cfgChannelCol}}][channellabel]" placeholder="Enter Label" value="{{ $cfgChannel->label }}" >
                </td>
                <td>
                  <select class="form-control con-select channel" name="channels[{{ $cfgChannel->position }}][{{$cfgChannelCol}}][channel]">
                    <option value="">Select</option>
                    @if($channels)
                    @foreach($channels as $channel)
                      <option value="{{ $channel->id }}" data-ip="{{ $channel->node->ip }}" {{ $channel->id == $cfgChannel->channelid ? 'selected="selected"' : '' }} >channel_{{ $channel->index }}</option>
                    @endforeach
                    @endif
                  </select>
                </td>
                <td>
                @if($cfgChannel->channel)
                {{ $cfgChannel->channel->node->ip }}
                @endif
                </td>
                <td>
                  <input type="url" class="channelurl" name="channels[{{ $cfgChannel->position }}][{{$cfgChannelCol}}][channelurl]" placeholder="Enter Url" value="{{ $cfgChannel->url }}">
                </td>
                <td>
                  {{ $cfgChannel->channelid }}
                </td>
              </tr>
              @php ($cfgChannelCol++)
              @if($cfgChannelCol > $latestCfgFile->firstconfiggroup->numcols)
                @php ($cfgChannelCol = 1)
              @endif
            @endforeach
            @endif
          @else
          <tr id="row_1_1" data-row="1" data-col="1">
            <td>1</td>
            <td>1</td>
            <td>
              <input type="text" class="channellabel" name="channels[1][1][channellabel]" placeholder="Enter Label">
            </td>
            <td>
              <select class="form-control con-select channel" name="channels[1][1][channel]">
                <option value="">Select</option>
                @if($channels)
                @foreach($channels as $channel)
                  <option value="{{ $channel->id }}" data-ip="{{ $channel->node->ip }}">channel_{{ $channel->index }}</option>
                @endforeach
                @endif
              </select>
            </td>
            <td>
                
            </td>
            <td>
              <input type="url" class="channelurl" name="channels[1][1][channelurl]" placeholder="Enter Url">
            </td>
            <td>
              
            </td>
          </tr>
          @endif
        </tbody>
      </table>

      <?php /*<table class="table table-striped table-bordered text-center configure configure-table" id="configure-table">
        <thead class="table-head">
          <tr>
            <th scope="col">Row</th>
            <th scope="col">Column</th>
            <th scope="col">Channel Label</th>
            <th scope="col">Channel</th>
            <th scope="col">IP</th>
            <th scope="col">Channel URL</th>
          </tr>
        </thead>
        <tbody>
          @if($cfgFilesCount > 0)
            @php ($cfgChannelCol = 1)
            @if($latestCfgFile->channels)
            @foreach($latestCfgFile->channels as $cfgChannel)
              <tr id="row_{{ $cfgChannel->position }}_{{$cfgChannelCol}}" data-row="{{ $cfgChannel->position }}" data-col="{{$cfgChannelCol}}">
                <td>{{ $cfgChannel->position }}</td>
                <td>{{ $cfgChannelCol }}</td>
                <td>
                  <input type="text" class="channellabel" name="channels[{{ $cfgChannel->position }}][{{$cfgChannelCol}}][channellabel]" placeholder="Enter Label" value="{{ $cfgChannel->label }}" >
                </td>
                <td>
                  <select class="form-control con-select channel" name="channels[{{ $cfgChannel->position }}][{{$cfgChannelCol}}][channel]">
                    <option value="">Select</option>
                    @if($channels)
                    @foreach($channels as $channel)
                      <option value="{{ $channel->id }}" data-ip="{{ $channel->node->ip }}" {{ $channel->id == $cfgChannel->channelid ? 'selected="selected"' : '' }} >channel_{{ $channel->index }}</option>
                    @endforeach
                    @endif
                  </select>
                </td>
                <td>
                @if($cfgChannel->channel)
                {{ $cfgChannel->channel->node->ip }}
                @endif
                </td>
                <td>
                  <input type="url" class="channelurl" name="channels[{{ $cfgChannel->position }}][{{$cfgChannelCol}}][channelurl]" placeholder="Enter Url" value="{{ $cfgChannel->url }}">
                </td>
              </tr>
              @php ($cfgChannelCol++)
              @if($cfgChannelCol > $latestCfgFile->firstconfiggroup->numcols)
                @php ($cfgChannelCol = 1)
              @endif
            @endforeach
            @endif
          @else
          <tr id="row_1_1" data-row="1" data-col="1">
            <td>1</td>
            <td>1</td>
            <td>
              <input type="text" class="channellabel" name="channels[1][1][channellabel]" placeholder="Enter Label">
            </td>
            <td>
              <select class="form-control con-select channel" name="channels[1][1][channel]">
                <option value="">Select</option>
                @if($channels)
                @foreach($channels as $channel)
                  <option value="{{ $channel->id }}" data-ip="{{ $channel->node->ip }}">channel_{{ $channel->index }}</option>
                @endforeach
                @endif
              </select>
            </td>
            <td>
                
            </td>
            <td>
              <input type="url" class="channelurl" name="channels[1][1][channelurl]" placeholder="Enter Url">
            </td>
          </tr>
          @endif
        </tbody>
      </table>*/?>

    </div>
  </div>

</form>

<div class="d-none">
  <select id="channels-list">
    <option value="">Select</option>
    @if($channels)
    @foreach($channels as $channel)
      <option value="{{ $channel->id }}" data-ip="{{ $channel->node->ip }}">channel_{{ $channel->index }}</option>
    @endforeach
    @endif
  </select>
</div>

<!-- createConfigModal starts-->
<div class="modal fade" id="createConfigModal" tabindex="-1" role="dialog" aria-labelledby="createConfigModalLabel" aria-hidden="true" data-backdrop="static">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>

        <h3 class="modal-title text-center">Configuration Save As</h3>
        <form method="post" id="createConfigForm" autocomplete="off">
          <div class="row">
              <div class="form-errors col-sm-12 alert alert-danger d-none"></div>
              <div class="col-sm-12 alert alert-success d-none"></div>
              <div class="col-sm-12">
                  <input type="text" id="config-name" name="name" placeholder="Name" required="">
                  <span class="invalid-error-msg"></span>
                  <input type="hidden" id="config-action" name="config-action" value="">
              </div>
              <div class="col-sm-12 text-right">
                  <button type="button" class="btn primary-btn" id="ajax-config-submit" >Save</button>
              </div>
          </div>
        </form>

    </div>
  </div>
</div>
<!-- createConfigModal ends-->

<div class="modal fade" id="confirmDeleteModal" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteModal" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Confirm</h4>
        </div>
        <div class="modal-body">
          <p>Are you sure you want to delete?</p>
        </div>
        <div class="modal-footer">
          <button class="btn cancel-btn" data-dismiss="modal" id="cancel-delete">Cancel</button>
          <button type="button" class="btn primary-btn" id="confirm-delete">Delete</button>
        </div>
      </div>      
    </div>
</div>

@endsection
