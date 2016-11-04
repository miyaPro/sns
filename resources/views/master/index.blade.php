<?php ob_end_clean() ?>
@extends('layouts.app')
@section('title') {{{ trans('title.master') }}} :: @parent @stop
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="clearfix add-btn">
                <div class="btn-group">
                    <a class="btn btn-primary" href="{{ url('master/create') }}">{{{ trans('button.add') }}}</a>
                </div>
            </div>
            <section class="panel">
                <header class="panel-heading">{{{ trans('title.master') }}}</header>
                <div class="panel-body">
                    @include('flash')
                    {!! Form::open(['class' => 'form-horizontal', 'method' => 'GET']) !!}
                    <div class="form-group">
                        <div class="col-md-4">
                            <div class="input-group">
                                <span class="input-group-addon btn-default">{{{ trans('field.group') }}}</span>
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
                            <th class="clientName">{{{ trans('field.group') }}}</th>
                            <th class="clientEmail">{{{ trans('field.code') }}}</th>
                            <th class="clientCompany">{{{ trans('field.name') }}}</th>
                            <th class="clientCompany">{{{ trans('field.status') }}}</th>
                            <th class="clientCompany">{{{ trans('field.attribute_label') }}}</th>
                            <th class="clientAction">{{{ trans('field.action') }}}</th>
                        </tr>
                        </thead>
                        @if($masters && $masters->count() > 0)
                            <tbody>
                            @foreach($masters as $i => $master)
                                <tr>
                                    <td>{{{ ($masters->currentPage() - 1) * $masters->perPage() + $i + 1 }}}</td>
                                    <td>{{{ $master->group }}}</td>
                                    <td>{{{ $master->code }}}</td>
                                    <td>
                                        <?php $field_name = 'name_'.Lang::locale() ?>
                                        <p>{{{ $master->$field_name }}}</p>
                                    </td>
                                    <td class="center">
                                        @if($master->active_flg == config('constants.active.enable'))
                                            {{{ trans('field.enable') }}}
                                        @else
                                            {{{ trans('field.disable') }}}
                                        @endif
                                    </td>
                                    <td>{{{ $master->attr1 }}}</td>
                                    <td class="center">
                                        <a href="{{ URL::route("master.edit","$master->id") }}" class="btn btn-info btn-sm edit">{{{ trans('button.update') }}}</a>
                                        <a class="btn btn-danger btn-sm btn-delete" data-button="{{{$master->id}}}"  data-from = "{{ URL::route("master.destroy",":id") }}" href="javascript:void(0)">
                                            {{{ trans('button.delete')}}}
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        @else
                            <tr>
                                <td colspan="6"><h5> {{{ trans('message.common_no_result')}}}</h5></td>
                            </tr>
                        @endif
                    </table>
                    @if($masters && $masters->count() > 0)
                        {{{  $masters->render() }}}
                    @endif
                </div>
            </section>
        </div>
    </div>
    @include('modals.delete')
@endsection