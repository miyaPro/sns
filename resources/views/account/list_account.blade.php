<?php ob_end_clean() ?>
@extends('layouts.app')
@section('title') {{{ trans('menu.site_list') }}} :: @parent @stop
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="clearfix add-btn">
                <div class="btn-group">
                    <a class="btn btn-primary" href="{{ url('/account/search') }}">{{{ trans('button.add') }}}</a>
                </div>
            </div><br />
            <section class="panel">
                <header class="panel-heading">
                    {{{ trans('menu.site_list') }}}
                        <span style="text-transform:none;"></span>
                </header>
                <div class="panel-body">
                    @include('flash')
                    {!! Form::open(['class' => 'form-horizontal', 'method' => 'GET']) !!}
                    <div class="form-group">
                        <div class="col-md-4">
                            <div class="input-group">
                                <span class="input-group-addon btn-default">{{{ trans('field.check_acc') }}}</span>
                                {!! Form::text('keyword',  Request::get('keyword',null),
                             ['id' => 'keyword','class' => 'form-control' ]) !!}
                                <span class="input-group-btn">
                                            <button class="btn btn-primary" type="submit"><i class="fa fa-search"></i></button>
                                        </span>
                            </div>
                        </div>
                    </div>
                    {!! Form::close() !!}
                    <table class="table table-striped table-hover table-bordered">
                        <thead>
                        <tr role="row">
                            <th class="clientNo">No.</th>
                            <th>{{{ trans('field.site_url') }}}</th>
                            <th>{{{ trans('field.site_service') }}}</th>
                            <th class="clientCtaSetting">{{{ trans('field.action_cta') }}}</th>
                            <th>{{{ trans('field.analytics') }}}</th>
                            <th class="clientAction">{{{ trans('default.action') }}}</th>
                        </tr>
                        </thead>
                        <tbody>
                            @if(isset($data) && $data->count() > 0)
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td class="center">
                                       {{-- <a href="{{ URL::route("site.creative.index") }}" class="btn btn-success btn-sm">{{{ trans('button.creative') }}}</a>
                                        <a href="{{{ URL::route("site.condition.index") }}}" class="btn btn-info btn-sm edit">{{{ trans('button.condition') }}}</a>--}}
                                    </td>
                                    <td class="center">
                                        {{--<a href="{{{ URL::route("site.analytic.index") }}}" class="btn btn-success btn-sm">{{{ trans('button.show') }}}</a>--}}
                                    </td>
                                    <td class="center">
                                        {{--<a href="{{ URL::route("site.edit") }}" class="btn btn-info btn-sm edit">{{{ trans('button.update') }}}</a>
                                        <a class="btn btn-danger btn-sm btn-delete" data-button=""  data-from = "{{ URL::route("site.destroy",":id") }}" href="javascript:void(0)">
                                            {{{ trans('button.delete')}}}
                                        </a>--}}
                                    </td>
                                </tr>
                            @else
                                <tr>
                                    <td colspan="6"><h5> {{{ trans('message.common_no_result')}}}</h5></td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </section>
        </div>
    </div>
    @include('modals.delete')
@endsection