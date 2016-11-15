<?php ob_end_clean(); ?>
@extends('layouts.app')
@section('title') {{{ trans('title.dashboard') }}} :: @parent @stop
@section('content')
    @if(Auth::user()->authority == $authority['admin'])
        <div class="row">
            <div class="col-md-12">
                <!--breadcrumbs start -->
                <ul class="breadcrumb">
                    <li><a href="{!! URL::to('user') !!}"><i class="fa fa-bars"></i> {{{ trans('menu.user_list') }}}</a></li>
                    <li class="active">
                        {{{ trans('title.info_detail') }}}
                    </li>
                </ul>
                <!--breadcrumbs end -->
            </div>
        </div>
    @endif
    <div class="row">
        <div class="col-md-12">
            <section class="panel">
                <div class="panel-body service-first">
                    <div class="form-group">
                        {!! Form::open(['class' => 'form-horizontal frm-search', 'method' => 'POST']) !!}
                        <div class="search-box col-md-6">
                            {!! Form::label('inputDateFrom', trans('field.date_range'), ['class' => 'control-label col-md-2']) !!}
                            <div class="input-group input-large col-md-8" data-date="" data-date-format="yyyy/mm/dd">
                                {!! Form::text('from', @$dates['from'], ['id' => 'inputDateFrom','class' => 'form-control default-date-picker dpd1']) !!}
                                {!! Form::label('inputDateTo', 'To', ['class' => 'input-group-addon']) !!}
                                {!! Form::text('to', @$dates['to'], ['id' => 'inputDateTo','class' => 'form-control default-date-picker dpd2']) !!}
                            </div>
                            <div class="col-md-2 button-box">
                                <button class="btn btn-primary" type="submit"><i class="fa fa-search"></i></button>
                            </div>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </section>
            @include('flash')

            <section class="panel service">
                <header class="panel-heading tab-bg-dark-navy-blue">
                    <ul class="nav nav-tabs nav-justified ">
                        @foreach(config('constants.service') as $service_name => $service_code)
                            <li class="{{{ ($service_name == 'facebook')  ? 'active' : '' }}}">
                                <a data-toggle="tab" href="#{{{ $service_name }}}" aria-expanded="{{{ ($service_name == 'facebook')  ? 'true' : 'false' }}}">
                                    {{{ trans('default.'.$service_name) }}}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </header>
                <div class="panel-body service tab-content tasi-tab">
                    @foreach(config('constants.service')  as $service_name => $service_code)
                        <div id="{{{ $service_name }}}" class="tab-pane {{{ ($service_name == 'facebook')  ? 'active' : '' }}}">
                            <div class="service-content">
                                @if(in_array($service_code, $services))
                                    <div class="clearfix add-other">
                                        @if($service_code == config('constants.service.facebook'))
                                            <div class="condition col-md-2">
                                                {!! Form::select('sel_account',  $authAccount[$service_code], null, ['class' => 'form-control','id' => 'sel_account_'.$service_name]) !!}
                                            </div>
                                        @endif
                                        <div class="btn-group">
                                            @if(isset($user) && $user->authority == config('constants.authority.client'))
                                                <a class="btn btn-primary" href="{{ url('social/handle'.ucfirst($service_name)).($service_code == config('constants.service.twitter') ? '' : '/1') }}">{{{ trans('button.add_more_account') }}}</a>
                                            @endif
                                        </div>
                                    </div>
                                    @if(isset($pageList[$service_code]))
                                        @foreach($pageList[$service_code] as $page)
                                            <section class="panel page-box" data-account="{{{ @$page['auth_id'] }}}">
                                                <div class="panel-body page_name_box">
                                                    <div class="wk-progress tm-membr col-md-8">
                                                        <div class="tm-avatar">
                                                            <img src="{{{ @$page['avatar_url'] }}}" alt="">
                                                        </div>
                                                        <div class="col-md-7 col-xs-7">
                                                            <span class="tm">{{{ @$page['name'] }}}</span>
                                                        </div>
                                                        <div class="col-md-3 col-xs-3 btn-view">
                                                            <a href="{!! URL::to('page/'.$page['id']) !!}" class="btn btn-white">{{{ trans('button.view') }}}</a>
                                                        </div>
                                                    </div>
                                                    @if(isset($totalPage[$service_code]) && isset($totalPage[$service_code][$page['id']]))
                                                        <?php $thisToday = $totalPage[$service_code][$page['id']]; ?>
                                                    @endif
                                                    <div class="page-detail">
                                                        <ul class="clearfix location-earning-stats">
                                                            <li class="stat-divider change_graph" data-show="friends" data-service-name="{{{ $service_name }}}" data-service-code="{{{ $service_code }}}" data-page="{{{ $page['page_id'] }}}">
                                                                {{{ trans('field.'.$service_name.'_friends_count') }}}
                                                                <span class="first-item">{{{ @$thisToday['friends_count'] ? number_format($thisToday['friends_count'], 0, '.', '.') : 0 }}}</span>
                                                            </li>
                                                            @if(config('constants.service.facebook') != $service_code)
                                                                <li class="stat-divider change_graph" data-show="followers" data-service-name="{{{ $service_name }}}" data-service-code="{{{ $service_code }}}" data-page="{{{ $page['page_id'] }}}">
                                                                    {{{ trans('field.'.$service_name.'_followers_count') }}}
                                                                    <span class="third-item">{{{ @$thisToday['followers_count'] ? number_format($thisToday['followers_count'], 0, '.', '.') : 0 }}}</span>
                                                                </li>
                                                                {{--@if(config('constants.service.instagram') != $service_code)--}}
                                                                {{--<li class="change_graph" data-show="favourites" data-service-name="{{{ $service_name }}}" data-service-code="{{{ $service_code }}}" data-page="{{{ $page['page_id'] }}}">--}}
                                                                {{--{{{ trans('field.'.$service_name.'_favourites_count') }}}--}}
                                                                {{--<span class="fourth-item">{{{ @$thisToday['favourites_count'] ? number_format($thisToday['favourites_count'], 0, '.', '.') : 0 }}}</span>--}}
                                                                {{--</li>--}}
                                                                {{--@endif--}}
                                                            @endif
                                                            <li class="stat-divider change_graph" data-show="posts" data-service-name="{{{ $service_name }}}" data-service-code="{{{ $service_code }}}" data-page="{{{ $page['page_id'] }}}">
                                                                {{{ trans('field.'.$service_name.'_posts_count') }}}
                                                                <span class="second-item">{{{ @$thisToday['posts_count'] ? number_format($thisToday['posts_count'], 0, '.', '.') : 0 }}}</span>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                    <div class="col-md-12 graph-box">
                                                        <div class="graph" id="graph_line_{{ $service_name.'_'.$page['id'] }}"></div>
                                                    </div>
                                                </div>
                                            </section>
                                        @endforeach
                                    @else
                                        <section class="panel col-md-12">
                                            <div class="panel-body clearfix add-btn">
                                                <div class="btn-group">
                                                    <h5> {{{ trans('message.page_not_found')}}}</h5>
                                                </div>
                                            </div>
                                        </section>
                                    @endif
                                @else
                                    <section class="panel col-md-12">
                                        <div class="panel-body clearfix add-btn">
                                            <div class="btn-group">
                                                @if(isset($user) && $user->authority == config('constants.authority.client'))
                                                    <a class="btn btn-primary" href="{{ url('social/handle'.ucfirst($service_name)) }}">{{{ trans('button.grant_access') }}}</a>
                                                @endif
                                            </div>
                                        </div>
                                    </section>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>
        </div>
        @endsection
        @section('scripts')
            <script>
                //create graph for post detail data
                $(function(){
                            <?php $i = 0; ?>
                            @foreach(config('constants.service') as $service_name => $service_code)
                            @if(isset($pageList[$service_code]))
                            @foreach($pageList[$service_code] as $page_id => $page)
                    var data        = [],
                            label       = ['{{{ trans('field.post_engagement') }}}'],
                            element_id  = 'graph_line_{{ $service_name.'_'.$page_id }}';
                    @if(isset($postByDay[$service_code]) && isset($postByDay[$service_code][$page_id]))
                    if($('#' + element_id).length > 0) {
                        @if($i > 0)
                            $('#{{{ $service_name }}}').css('display', 'block');
                        @endif
                        @foreach($postByDay[$service_code][$page_id] as $date => $post)
                            data.push({
                            date: '{{{ $date }}}',
                            value: '{{{ $post['compare'] }}}'
                        });
                        @endforeach
                        generate_graph(element_id, label, data);
                        @if($i > 0)
                             $('#{{{ $service_name }}}').css('display', 'none');
                        @endif
                    }
                    @endif
                    @endforeach
                    @endif
                    <?php $i++; ?>

                    //show hide page by auth account
                    if($('#sel_account_{{{ $service_name }}}').length > 0) {
                        //init this page
                        var auth_id = $('#sel_account_{{{ $service_name }}}').val();
                        select_page('{{{ $service_name }}}', auth_id);

                        //select a account
                        $('#sel_account_{{{ $service_name }}}').on('change', function () {
                            var auth_id = $(this).val();
                            select_page('{{{ $service_name }}}', auth_id);
                        });
                    }
                    @endforeach
                });

                function select_page(service_name, auth_id) {
                    console.log(service_name + ' - ' + auth_id);
                    $('#' + service_name + ' .page-box').each(function (e) {
                        if($(this).data('account') == auth_id) {
                            $(this).show();
                        } else {
                            $(this).hide();
                        }
                    });
                }
                function generate_graph(element_id, label, data) {
                    new Morris.Area({
                        element: element_id,
                        xkey: 'date',
                        ykeys: ['value'],
                        labels: label,
                        lineColors: ['#058DC7'],
                        parseTime: false,
                        hideHover: 'auto',
                        resize: true,
                        lineWidth: 5,
                        pointSize: 5,
                        smooth: false,
                        behaveLikeLine: true,
                        fillOpacity: '0.2',
                        yLabelFormat: function(d) {if(d != 0 && !parseInt(d)) {return '';} return parseInt(d);},
                        xLabelFormat: function(d) {d = new Date(d.label); return (d.getMonth()+1)+'/'+d.getDate();},
                        data: data
                    });
                }

            </script>
@endsection