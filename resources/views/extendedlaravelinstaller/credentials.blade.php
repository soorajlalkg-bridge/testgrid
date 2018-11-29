@extends('extendedlaravelinstaller.layouts.master')

@section('template_title')
    {{ trans('extendedlaravelinstaller.credentials.templateTitle') }}
@endsection

@section('title')
    <i class="fa fa-magic fa-fw" aria-hidden="true"></i>
    {!! trans('extendedlaravelinstaller.credentials.title') !!}
@endsection

@section('container')
    <div class="tabs tabs-full">

        <input id="tab1" type="radio" name="tabs" class="tab-input" checked />


        <form method="post" action="{{ route('LaravelInstaller::credentialsSaveCredentials') }}" class="tabs-wrap" autocomplete="off">
            <div class="tab" id="tab1content">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">

                <div class="form-group {{ $errors->has('admin_name') ? ' has-error ' : '' }}">
                    <label for="admin_name">
                        {{ trans('extendedlaravelinstaller.credentials.form.admin_name_label') }}
                    </label>
                    <input type="text" name="admin_name" id="admin_name" value="{{ old('admin_name') }}" placeholder="{{ trans('extendedlaravelinstaller.credentials.form.admin_name_placeholder') }}" required="" />
                    @if ($errors->has('admin_name'))
                        <span class="error-block">
                            <i class="fa fa-fw fa-exclamation-triangle" aria-hidden="true"></i>
                            {{ $errors->first('admin_name') }}
                        </span>
                    @endif
                </div>

                <div class="form-group {{ $errors->has('admin_email') ? ' has-error ' : '' }}">
                    <label for="admin_email">
                        {{ trans('extendedlaravelinstaller.credentials.form.admin_email_label') }}
                    </label>
                    <input type="text" name="admin_email" id="admin_email" value="{{ old('admin_email') }}" placeholder="{{ trans('extendedlaravelinstaller.credentials.form.admin_email_placeholder') }}" required="" />
                    @if ($errors->has('admin_email'))
                        <span class="error-block">
                            <i class="fa fa-fw fa-exclamation-triangle" aria-hidden="true"></i>
                            {{ $errors->first('admin_email') }}
                        </span>
                    @endif
                </div>

                <div class="form-group {{ $errors->has('admin_password') ? ' has-error ' : '' }}">
                    <label for="admin_password">
                        {{ trans('extendedlaravelinstaller.credentials.form.admin_password_label') }}
                    </label>
                    <input type="password" name="admin_password" id="admin_password" value="" placeholder="{{ trans('extendedlaravelinstaller.credentials.form.admin_password_placeholder') }}" required="" />
                    @if ($errors->has('admin_password'))
                        <span class="error-block">
                            <i class="fa fa-fw fa-exclamation-triangle" aria-hidden="true"></i>
                            {{ $errors->first('admin_password') }}
                        </span>
                    @endif
                </div>

                <div class="buttons">
                    <button class="button" >
                        {{ trans('extendedlaravelinstaller.credentials.form.buttons.setup_credentials') }}
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
