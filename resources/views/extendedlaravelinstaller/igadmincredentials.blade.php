@extends('extendedlaravelinstaller.layouts.master')

@section('template_title')
    {{ trans('extendedlaravelinstaller.igadmincredentials.templateTitle') }}
@endsection

@section('title')
    <i class="fa fa-magic fa-fw" aria-hidden="true"></i>
    {!! trans('extendedlaravelinstaller.igadmincredentials.title') !!}
@endsection

@section('container')
    <div class="tabs tabs-full">

        <input id="tab1" type="radio" name="tabs" class="tab-input" checked />


        <form method="post" action="{{ route('LaravelInstaller::igadminCredentialsSaveCredentials') }}" class="tabs-wrap" autocomplete="off">
            <div class="tab" id="tab1content">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">

                <div class="form-group {{ $errors->has('igadmin_email') ? ' has-error ' : '' }}">
                    <label for="igadmin_email">
                        {{ trans('extendedlaravelinstaller.igadmincredentials.form.igadmin_email_label') }}
                    </label>
                    <input type="text" name="igadmin_email" id="igadmin_email" value="{{ old('igadmin_email') }}" placeholder="{{ trans('extendedlaravelinstaller.igadmincredentials.form.igadmin_email_placeholder') }}" required="" />
                    @if ($errors->has('igadmin_email'))
                        <span class="error-block">
                            <i class="fa fa-fw fa-exclamation-triangle" aria-hidden="true"></i>
                            {{ $errors->first('igadmin_email') }}
                        </span>
                    @endif
                </div>

                <div class="form-group {{ $errors->has('igadmin_password') ? ' has-error ' : '' }}">
                    <label for="igadmin_password">
                        {{ trans('extendedlaravelinstaller.igadmincredentials.form.igadmin_password_label') }}
                    </label>
                    <input type="password" name="igadmin_password" id="igadmin_password" value="" placeholder="{{ trans('extendedlaravelinstaller.igadmincredentials.form.igadmin_password_placeholder') }}" required="" />
                    @if ($errors->has('igadmin_password'))
                        <span class="error-block">
                            <i class="fa fa-fw fa-exclamation-triangle" aria-hidden="true"></i>
                            {{ $errors->first('igadmin_password') }}
                        </span>
                    @endif
                </div>

                <div class="form-group {{ $errors->has('igadmin_password') ? ' has-error ' : '' }}">
                    <label for="igadmin_password_confirmation">
                        {{ trans('extendedlaravelinstaller.igadmincredentials.form.igadmin_password_confirmation_label') }}
                    </label>
                    <input type="password" name="igadmin_password_confirmation" id="igadmin_password_confirmation" value="" placeholder="{{ trans('extendedlaravelinstaller.igadmincredentials.form.igadmin_password_confirmation_placeholder') }}" required="" />
                    @if ($errors->has('igadmin_password_confirmation'))
                        <span class="error-block">
                            <i class="fa fa-fw fa-exclamation-triangle" aria-hidden="true"></i>
                            {{ $errors->first('igadmin_password_confirmation') }}
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
