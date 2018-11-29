@extends('extendedlaravelinstaller.layouts.master')

@section('template_title')
    {{ trans('extendedlaravelinstaller.licenses.templateTitle') }}
@endsection

@section('title')
    <i class="fa fa-magic fa-fw" aria-hidden="true"></i>
    {!! trans('extendedlaravelinstaller.licenses.title') !!}
@endsection

@section('container')
    <div class="tabs tabs-full">

        <input id="tab1" type="radio" name="tabs" class="tab-input" checked />


        <form method="post" action="{{ route('LaravelInstaller::licensesSaveLicenses') }}" class="tabs-wrap" autocomplete="off">
            <div class="tab" id="tab1content">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">

                <div class="form-group {{ $errors->has('user_licenses') ? ' has-error ' : '' }}">
                    <label for="user_licenses">
                        {{ trans('extendedlaravelinstaller.licenses.form.user_licenses_label') }}
                    </label>
                    <input type="number" name="user_licenses" id="user_licenses" value="{{ old('user_licenses') }}" placeholder="{{ trans('extendedlaravelinstaller.licenses.form.user_licenses_placeholder') }}" min="1" max="1000000" required="" />
                    @if ($errors->has('user_licenses'))
                        <span class="error-block">
                            <i class="fa fa-fw fa-exclamation-triangle" aria-hidden="true"></i>
                            {{ $errors->first('user_licenses') }}
                        </span>
                    @endif
                </div>

                <div class="form-group {{ $errors->has('encryption_key') ? ' has-error ' : '' }}">
                    <label for="encryption_key">
                        {{ trans('extendedlaravelinstaller.licenses.form.encryption_key_label') }}
                    </label>
                    <input type="password" name="encryption_key" id="encryption_key" value="{{ old('encryption_key') }}" placeholder="{{ trans('extendedlaravelinstaller.licenses.form.encryption_key_placeholder') }}" required="" min="4" max="30"/>
                    @if ($errors->has('encryption_key'))
                        <span class="error-block">
                            <i class="fa fa-fw fa-exclamation-triangle" aria-hidden="true"></i>
                            {{ $errors->first('encryption_key') }}
                        </span>
                    @endif
                </div>

                <div class="buttons">
                    <button class="button" >
                        {{ trans('extendedlaravelinstaller.licenses.form.buttons.setup_user_licenses') }}
                        <i class="fa fa-angle-right fa-fw" aria-hidden="true"></i>
                    </button>
                </div>
            </div>
        </form>

    </div>
@endsection

@section('scripts')
    <script type="text/javascript">
        
    </script>
@endsection
