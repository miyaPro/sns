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
                            {!! Form::select('typeSociale',  $social, null, ['class' => 'form-control','id' => 'typeSociale']) !!}
                            @if ($errors->has('typeSociale'))
                                <label class="error">{{ $errors->first('typeSociale') }}</label>
                            @endif
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('site_service', trans('field.check_acc'), ['class' => 'col-md-2 control-label required']) !!}
                        <div class="col-md-6">
                            {!! Form::text('nickname', null, ['id' => 'nickname', 'class' => "form-control"]) !!}
                            @if ($errors->has('nickname'))
                                <label class="error">{{ $errors->first('nickname') }}</label>
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



