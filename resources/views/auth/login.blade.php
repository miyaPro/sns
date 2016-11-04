@extends('login')
@section('title') {{{ trans('title.title_login') }}} :: @parent @stop
@section('content')
    {!! Form::open(['url' => 'login', 'class' => 'form-signin', 'role' => 'form']) !!}
        <h2 class="form-signin-heading">{{{ trans('default.site_title') }}}</h2>
        <div class="login-wrap">
            <div class="user-login-info">
                @include('errors.list')
                {!! Form::text('email', null, ['id' => 'inputEmail', 'class' => "form-control", 'placeholder' => trans('field.email')]) !!}
                {!! Form::password('password', ['id' => 'inputPassword', 'class' => "form-control", 'placeholder' => trans('field.password')]) !!}
            </div>
            <label class="checkbox">
                 {!! Form::checkbox('remember', null, false) !!}{{{ trans('button.remember_me') }}}
                 &nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp;&nbsp;
                 {{ link_to('/reset-password/email', $title = trans('button.reset'), $attributes = ['id'=>'reset1', 'class'=>'btn-reset1'], $secure = null)}}
            </label>

            <button class="btn btn-lg btn-login btn-block" type="submit">{{{ trans('button.login') }}}</button>
        </div>
    {!! Form::close() !!}

@endsection