@extends('layouts.app')
@section('title') {{{ trans('default.info_acc') }}} :: @parent @stop
@section('content')
    <div class="row">
        <div class="col-md-12">
            <!--breadcrumbs start -->
            <ul class="breadcrumb">
                <li><a href="{!! URL::to('benchmark') !!}"><i class="fa fa-bars"></i> {{{ trans('menu.rival_list') }}}</a></li>
                <li class="active">
                        {{{ trans('title.add_new') }}}
                </li>
            </ul>
            <!--breadcrumbs end -->
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <section class="panel">
                <header class="panel-heading">
                        {{{ trans('title.add_new') }}}
                </header>
                <div class="panel-body">
                    @include('flash')
                    {!! Form::open(['url' => '/benchmark', 'class' => 'cmxform form-horizontal ad-form', 'role' => 'form']) !!}
                    <div class="form-group">
                        {!! Form::label('site_url', trans('field.select_social'), ['class' => 'col-md-2 control-label required']) !!}
                        <div class="col-md-6">
                            {!! Form::select('service_code',  $social, null, ['class' => 'form-control','id' => 'service_code']) !!}
                            @if ($errors->has('service_code'))
                                <label class="error">{{ $errors->first('service_code') }}</label>
                            @endif
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('site_service', trans('field.account_name'), ['class' => 'col-md-2 control-label required']) !!}
                        <div class="col-md-6">
                            {!! Form::text('account_name', null, ['id' => 'account_name', 'class' => "form-control"]) !!}
                            @if ($errors->has('account_name'))
                                <label class="error">{{ $errors->first('account_name') }}</label>
                            @endif
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-offset-2 col-md-6">
                                <button class="btn btn-primary" type="submit">{{{ trans('button.send') }}}</button>
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
            </section>
        </div>
    </div>
@endsection



