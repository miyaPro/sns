<?php ob_end_clean() ?>
@extends('layouts.app')
@section('title') {{{ trans('menu.rival_list') }}} :: @parent @stop
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
                            <th>{{{ trans('field.name') }}}</th>
                            <th>{{{ trans('field.site_service') }}}</th>
                            <th>{{{ trans('field.analytics') }}}</th>
                            <th class="clientAction">{{{ trans('default.action') }}}</th>
                        </tr>
                        </thead>
                        <tbody>
                            @if(isset($data) && $data->count() > 0)
                                <?php
                                $userDetail = array(
                                        config('constants.service.facebook') => array(
                                                'first-city'        => 'friends_count',
                                                'second-city'       => 'posts_count',
                                        ),
                                        config('constants.service.twitter') => array(
                                                'first-city'        => 'friends_count',
                                                'third-city'        => 'followers_count',
                                                'second-city'       => 'posts_count'
                                        ),
                                        config('constants.service.instagram') => array(
                                                'first-city'        => 'friends_count',
                                                'third-city'        => 'followers_count',
                                                'second-city'       =>  'posts_count'
                                        )
                                );
                                ?>
                                @foreach($data as $i => $row)
                                    <tr>
                                        <td>{{{ ($data->currentPage() - 1) * $data->perPage() + $i + 1 }}}</td>
                                        <td>{{$row->name}}</td>
                                        <td>{{array_search($row->service_code, config('constants.service'))}}</td>
                                        <td>
                                            <ul class="clearfix account-detail location-earning-stats">
                                                @if(isset( $userDetail[$row->service_code]))
                                                    @foreach( $userDetail[$row->service_code] as $key => $value)
                                                        <li class="stat-divider service-type">
                                                            {{trans('field.'.array_search($row->service_code, config('constants.service')).'_'.$value)}}
                                                            <span class="statistics {{$key}}">{{ @number_format($row->$value)}}</span>
                                                        </li>
                                                    @endforeach
                                                @endif
                                            </ul>
                                        </td>
                                        <td>
                                            <a class="btn btn-danger btn-sm btn-delete" data-button="{{{$row->id}}}"  data-from = "{{ URL::route("account.destroy",":id") }}" href="javascript:void(0)">
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