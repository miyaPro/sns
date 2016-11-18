@extends('layouts.app')
@section('title')
    @if(ends_with(Route::currentRouteAction(), 'MasterController@create'))
        {{{ trans('title.master_register') }}}
    @elseif(ends_with(Route::currentRouteAction(), 'MasterController@edit'))
        {{{ trans('title.master_edit') }}}
    @endif
    ::
@parent @stop
@section('content')
    <div class="row">
        <div class="col-md-12">
            <!--breadcrumbs start -->
            <ul class="breadcrumb">
                <li><a href="{!! URL::to('master') !!}"><i class="fa fa-bars"></i> {{{ trans('menu.master_list') }}}</a></li>
                <li class="active">
                    @if(!isset($user))
                        {{{ trans('title.master_register') }}}
                    @else
                        {{{ trans('title.master_edit') }}}
                    @endif
                </li>
            </ul>
            <!--breadcrumbs end -->
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <section class="panel">
                <header class="panel-heading">
                    @if(ends_with(Route::currentRouteAction(), 'MasterController@create'))
                        {{{ trans('title.master_register') }}}
                    @elseif(ends_with(Route::currentRouteAction(), 'MasterController@edit'))
                        {{{ trans('title.master_edit') }}}
                    @endif
                </header>
                <div class="panel-body">
                    @include('flash')
                    @if(ends_with(Route::currentRouteAction(), 'MasterController@create'))
                        {!! Form::open(['url' => 'master', 'class' => 'cmxform form-horizontal', 'role' => 'form']) !!}
                    @elseif(ends_with(Route::currentRouteAction(), 'MasterController@edit'))
                        {!! Form::model($master,[ 'route' => ['master.update', $master->id], 'method' => 'PUT', 'class' => 'cmxform form-horizontal', 'role' => 'form']) !!}
                    @endif
                    <div class="form-group">
                        {!! Form::label('group', trans('field.group'), ['class' => "col-md-2 control-label required"]) !!}
                        <div class="col-md-3">
                            {!! Form::hidden('id', null, ['class' => 'form-control']) !!}
                            {!! Form::text('group', null, ['id' => 'inputGroup','class' => 'form-control']) !!}
                            @if ($errors->has('group'))
                                <label for="inputGroup" class="error">{{ $errors->first('group') }}</label>
                            @endif
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('code', trans('field.code'), ['class' => 'col-md-2 control-label required']) !!}
                        <div class="col-md-3">
                            {!! Form::text('code', null, ['id' => 'inputCode', 'class' => "form-control"]) !!}
                            @if ($errors->has('code'))
                                <label for="inputCode" class="error">{{ $errors->first('code') }}</label>
                            @endif
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('name_ja', trans('field.name_ja'), ['class' => 'col-md-2 control-label']) !!}
                        <div class="col-md-6">
                            {!! Form::text('name_ja', null, ['id' => 'inputNameJa','class' => 'form-control']) !!}
                            @if ($errors->has('name_ja'))
                                <label for="inputNameJa" class="error">{{ $errors->first('name_ja') }}</label>
                            @endif
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('name_vn', trans('field.name_vn'), ['class' => 'col-md-2 control-label']) !!}
                        <div class="col-md-6">
                            {!! Form::text('name_vn', null, ['id' => 'inputNameVn','class' => 'form-control']) !!}
                            @if ($errors->has('name_vn'))
                                <label for="inputNameVn" class="error">{{ $errors->first('name_vn') }}</label>
                            @endif
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('name_en', trans('field.name_en'), ['class' => 'col-md-2 control-label']) !!}
                        <div class="col-md-6">
                            {!! Form::text('name_en', null, ['id' => 'inputNameEn','class' => 'form-control']) !!}
                            @if ($errors->has('name_en'))
                                <label for="inputNameEn" class="error">{{ $errors->first('name_en') }}</label>
                            @endif
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('attr1', trans('field.attribute_label'), ['class' => 'col-md-2 control-label']) !!}
                        <div class="col-md-3">
                            {!! Form::text('attr1', null, ['id' => 'inputAttr1', 'class' => "form-control"]) !!}
                            @if ($errors->has('attr1'))
                                <label for="inputAttr1" class="error">{{ $errors->first('attr1') }}</label>
                            @endif
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('active_flg', trans('field.status'), ['class' => 'col-md-2 control-label']) !!}
                        <div class="col-md-6 icheck">
                            <div class="minimal-blue single-row">
                                <label>
                                    <div class="radio ">
                                        {{ Form::radio('active_flg', config('constants.active.enable'), true) }}
                                        {{{ trans('field.enable') }}}
                                    </div>
                                </label>
                                <label class="active-last-radio">
                                    <div class="radio ">
                                        {{ Form::radio('active_flg', config('constants.active.disable')) }}
                                        {{{ trans('field.disable') }}}
                                    </div>
                                </label>
                            </div>
                            @if ($errors->has('active_flg'))
                                <label for="inputActive" class="error">{{ $errors->first('active_flg') }}</label>
                            @endif
                        </div>
                    </div>
                    <div class="columns separator"></div>
                    <div class="form-group">
                        <div class="col-md-offset-2 col-md-6">
                            @if(ends_with(Route::currentRouteAction(), 'MasterController@create'))
                                <button class="btn btn-primary" type="submit">{{{ trans('button.add') }}}</button>
                            @elseif(ends_with(Route::currentRouteAction(), 'MasterController@edit'))
                                <button class="btn btn-primary" type="submit">{{{ trans('button.update') }}}</button>
                            @endif
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
            </section>
        </div>
    </div>
@endsection