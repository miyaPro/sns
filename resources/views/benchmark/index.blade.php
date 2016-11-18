<?php ob_end_clean(); ?>
@extends('layouts.app')
@section('title') {{{ trans('menu.rival_list') }}} :: @parent @stop
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="clearfix add-btn">
                <div class="btn-group">
                    <a class="btn btn-primary" href="{{ url('/benchmark/create') }}">{{{ trans('button.add') }}}</a>
                </div>
            </div><br />
            <section class="panel">
                <header class="panel-heading">
                    {{{ trans('menu.rival_list') }}}
                        <span style="text-transform:none;"></span>
                </header>
                <div class="panel-body">
                    @include('flash')
                    {!! Form::open(['class' => 'form-horizontal', 'method' => 'GET']) !!}
                    <div class="form-group">
                        <div class="col-md-4">
                            <div class="input-group">
                                <span class="input-group-addon btn-default">{{{ trans('field.check_acc') }}}</span>
                                {!! Form::text('keyword',  Request::get('keyword',null),['id' => 'keyword','class' => 'form-control' ]) !!}
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
                            <th>{{{ trans('field.site_service') }}}</th>
                            <th>{{{ trans('field.check_acc') }}}</th>
                            <th class="clientAction">{{{ trans('default.action') }}}</th>
                        </tr>
                        </thead>
                        <tbody>
                            @if(isset($data) && $data->count() > 0)
                                @foreach($data as $i => $row)
                                    <tr>
                                        <td>{{{ ($data->currentPage() - 1) * $data->perPage() + $i + 1 }}}</td>
                                        <td>{{ucfirst(array_search($row->service_code, config('constants.service')))}}</td>
                                        <td>{{$row->account_name}}</td>
                                        <td>
                                            <a class="btn btn-danger btn-sm btn-delete" data-button="{{{$row->auth_id}}}"  data-from = "{{ URL::route("benchmark.destroy",":id") }}" href="javascript:void(0)">
                                                {{{ trans('button.delete')}}}
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="6"><h5> {{{ trans('message.common_no_result')}}}</h5></td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                    @if($data && $data->count() > 0)
                        {{{  $data->render() }}}
                    @endif
                </div>
            </section>
        </div>
    </div>
    @include('modals.delete')
@endsection