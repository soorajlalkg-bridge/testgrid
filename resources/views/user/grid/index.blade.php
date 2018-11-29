@extends('user.layout.app')

@section('content')
<img src="{{ URL::asset('/images/ajax-loader.gif') }}" id="loading-image">
<div class="padding-15">
    @if(Session::has('success') && !empty(Session::get('success')))
        <div class="alert alert-success alert-dismissible col-sm-12">
          {{Session::get('success')}}
        </div>
    @endif
    @if(Session::has('error') && !empty(Session::get('error')))
        <div class="alert alert-danger alert-dismissible col-sm-12">
          {{Session::get('error')}}
        </div>
    @endif
    <div class="alert alert-dismissible alert-message col-sm-12 d-none">
      <span></span>
    </div>
</div>
<form id="gridForm" method="POST" action="{{url('/user/grid')}}" autocomplete=off>
  <input type="hidden" value="{{csrf_token()}}" name="_token" />
<div class="row padding-15">
  <div class="col-sm-12">
  	@if($cfgFile)
    <?php /*<div class="show-group">
      <label class="label-con">Player Mode : </label>
      <select class="form-control con-select" id="grid-player-mode" name="grid-player-mode">
            <option value="fixed" @if($gridPlayerMode == 'fixed') selected="selected" @endif >Fixed Size</option>
            <option value="auto" @if($gridPlayerMode == 'auto') selected="selected" @endif >Auto Fit (Horizontal)</option>
            <option value="autofitvertical" @if($gridPlayerMode == 'autofitvertical') selected="selected" @endif >Auto Fit (Vertical)</option>
      </select>
    </div>*/?>
    <div class="show-group">
      <label class="label-con">Player Mode : </label>
      <select class="form-control con-select" id="grid-player-mode-dynamic" name="grid-player-mode">
            <option value="fixed" @if($gridPlayerMode == 'fixed') selected="selected" @endif >Fixed Size</option>
            <option value="auto" @if($gridPlayerMode == 'auto') selected="selected" @endif >Auto Fit (Horizontal)</option>
            <option value="autofitvertical" @if($gridPlayerMode == 'autofitvertical') selected="selected" @endif >Auto Fit (Vertical)</option>
      </select>
    </div>
    <div class="show-group">
      <label class="label-con">show group : </label>
      <select class="form-control con-select" id="grid-config-groups" data-cfg="{{ $cfgFile->id }}" name="grid-config-groups">
          @foreach($cfgFile->configgroup as $configGroup)
            <option value="{{ $configGroup->id }}" @if($gridConfigGroup == $configGroup->id || $cfgFile->selectedCfgGroupId == $configGroup->id) selected="selected" @endif >Group{{ $configGroup->groupindex }}</option>
          @endforeach
      </select>
      	<a href="javascript:void(0);" data-toggle="tooltip" title="Group Up" class="btn button-cntrl" id="grid-next-group-btn">
	      <i class="material-icons dp48">arrow_drop_up</i>
	    </a>
	    <a href="javascript:void(0);" data-toggle="tooltip" title="Group Down" class="btn button-cntrl" id="grid-prev-group-btn">
	      <i class="material-icons dp48">arrow_drop_down</i>
	    </a>
    </div>
    <div class="show-group">
    	<button class="btn secondary-btn" id="toggle-full-screen">Full Screen</button>
    </div>
    @endif
  </div>
</div>
</form>

<div class="row padding-all-15 video-scroll" id="video-scroll">
<div class="col-sm-12 video-outer">
@if($cfgFile)
	@if($cfgFile->firstconfiggroup)
		@if($cfgFile->firstconfiggroup->configchannel)
			@php ($cfgChannelIndex = 1)
			@php ($countChannels = count($cfgFile->firstconfiggroup->configchannel))
			
			<div class="video-controller"> 
			@foreach($cfgFile->firstconfiggroup->configchannel as $cfgChannel)
				@php ($videoUrl = $cfgChannel->url)
				@php ($videoExt = pathinfo($videoUrl, PATHINFO_EXTENSION))
				@php ($rowStart = ($cfgChannelIndex%$cfgFile->firstconfiggroup->numcols))
				@if($videoExt == 'm3u8' || $videoExt == 'mp4')
					  <div class="video-wrapp" data-col="{{$cfgChannel->position.'-'.$cfgChannel->column_position}}">
					  	<div class="video-container">
					    <!-- <video id="video-{{$cfgChannel->id}}" class="video-js vjs-default-skin vjs-16-9" controls preload="auto" width="640" height="264" playsinline crossorigin="anonymous" data-id="{{$cfgChannel->id}}"> -->
					    <video id="video-{{$cfgChannel->id}}" class="video-js vjs-default-skin vjs-16-9" controls preload="auto" playsinline crossorigin="anonymous" data-id="{{$cfgChannel->id}}">
						    <source src="{{$cfgChannel->url}}" type="{{ ($videoExt=='m3u8')? 'application/x-mpegURL':'video/mp4' }}">
						</video>
						</div>
						<!-- <div id="my-peak-meter-{{$cfgChannel->id}}" class="my-peak-meter"></div> -->
						<div class="my-vu-meter">
							<canvas id="canvas-{{$cfgChannel->id}}" width="130" height="15" style="display: block;"></canvas>
						</div>
					    <?php /*<div class="video-equalizer">

					      <div class="video-equa">
					        <div class="video-show"></div>
					        <div class="green-bar"></div>
					        <div class="red-bar"></div>
					      </div>
					      <div class="video-equa margin-t-5">
					        <div class="audio-show"></div>
					        <div class="green-bar"></div>
					        <div class="red-bar"></div>
					      </div>

					      <div class="video-eqau-opacity">
					        <div class="video-equa">
					          <div class="green-bar"></div>
					          <div class="red-bar"></div>
					        </div>
					        <div class="video-equa margin-t-5">
					          <div class="green-bar"></div>
					          <div class="red-bar"></div>
					        </div>
					      </div>

					    </div> */?>
					    <div class="video-bar green">
					      <span>{{$cfgChannel->label}}</span>
					    </div>

					  </div>
					
  				@endif
  				@php ($cfgChannelIndex++)
  				@if($rowStart == '0' && $cfgChannelIndex <= $countChannels)
				</div><div class="clear"></div><div class="video-controller"> 
				@endif
  			@endforeach
  			</div>
  		@endif
	@endif
@endif
</div>
</div>

<div class="row padding-all-15 fullscreen-mode">

</div>

<div class="d-none">
	<div id="video-list">
		<div class="video-wrapp">
			<div class="video-container">
			<?php /*  <video id="video-" class="video-js vjs-default-skin vjs-16-9" controls preload="auto" width="640" height="264" playsinline  crossorigin="anonymous" data-id=""> */?>
			<video id="video-" class="video-js vjs-default-skin vjs-16-9" controls preload="auto" playsinline  crossorigin="anonymous" data-id="">
			    <source src="" type="">
			</video>
			</div>
			<!-- <div id="my-peak-meter-" class="my-peak-meter"></div> -->
			<div class="my-vu-meter">
				<canvas id="canvas-" width="130" height="15" style="display: block;"></canvas>
			</div>
			<?php /* <div class="video-equalizer">
			  	<div class="video-equa">
			    	<div class="video-show"></div>
			    	<div class="green-bar"></div>
			    	<div class="red-bar"></div>
			  	</div>
			  	<div class="video-equa margin-t-5">
			    	<div class="audio-show"></div>
			    	<div class="green-bar"></div>
			    	<div class="red-bar"></div>
			  	</div>
			  	<div class="video-eqau-opacity">
			    	<div class="video-equa">
			      		<div class="green-bar"></div>
			      		<div class="red-bar"></div>
			    	</div>
			    	<div class="video-equa margin-t-5">
			      		<div class="green-bar"></div>
			      		<div class="red-bar"></div>
			    	</div>
			  	</div>
			</div> */?>
			<div class="video-bar green">
				<span></span>
			</div>
		</div>
	</div>
</div>


@endsection
