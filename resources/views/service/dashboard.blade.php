<?php ob_end_clean(); ?>
@php $service_code = $service_data['service_code']; @endphp
@php $service_name = $service_data['service_name']; @endphp
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
                        @if(Auth::user()->authority == config('constants.authority.admin'))
                            <div class="user-info pull-left">{{$user->company_name.' ('.$user->email.')'}}</div>
                        @endif
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
                        @foreach(config('constants.service') as $name => $code)
                            <li class="{{{ ($code == $service_code)  ? 'active' : '' }}}">
                                <a href="{!! URL::to('dashboard/'.$code.'/'.$user->id) !!}" aria-expanded="{{{ ($code == $service_code)  ? 'true' : 'false' }}}">
                                    {{{ trans('default.'.$name) }}}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </header>
                <div class="panel-body service">
                    <div class="service-content">
                        @if($service_data['service_exist'])
                            <div class="clearfix add-other">
                                @if($service_code == config('constants.service.facebook') && count($authAccount[$service_code]) > 1)
                                    <div class="condition col-md-4">
                                        {!! Form::select('sel_account',  $authAccount[$service_code], null, ['class' => 'form-control sel_account','id' => 'sel_account_'.$service_name]) !!}
                                    </div>
                                @endif
                                <div class="btn-group">
                                    @if(Auth::user()->authority == config('constants.authority.client'))
                                        <a class="btn btn-primary" href="{{ url('social/handle'.ucfirst($service_name)).($service_code == config('constants.service.twitter') ? '' : '/1') }}">{{{ trans('button.add_more_account') }}}</a>
                                    @endif
                                </div>
                            </div>
                            @if((isset($pageList) && count($pageList) > 0) || (isset($pageCompetitor) && count($pageCompetitor) > 0))
                                @foreach($pageList as $page)
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
                                            @if(isset($totalPage) && isset($totalPage[$page['id']]))
                                                <?php $thisToday = $totalPage[$page['id']]; ?>
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
                                                <section class="panel panel-chart">
                                                    <header class="panel-heading">{{ trans('title.post_engagement_daily') }}</header>
                                                    <div class="panel-body">
                                                        <div class="graph" id="graph_line_{{ $service_name.'_'.$page['id'] }}"></div>
                                                    </div>
                                                </section>
                                            </div>
                                        </div>
                                    </section>
                                @endforeach

                                @if(isset($pageCompetitor) && count($pageCompetitor) > 0)
                                    <div class="panel-heading">
                                        {{trans('menu.rival_list')}}
                                    </div>
                                    @foreach(config('constants.service') as $serviceName => $serviceCode )
                                        <?php  $conditionPage[$serviceName] = config('constants.condition_filter_page')?>
                                        @if($serviceCode == config('constants.service.instagram'))
                                            @unset($conditionPage[$serviceName][array_search('favourites', $conditionPage[$serviceName])])
                                        @elseif($serviceCode == config('constants.service.facebook'))
                                            @unset($conditionPage[$serviceName][array_search('favourites', $conditionPage[$serviceName])])
                                            @unset($conditionPage[$serviceName][array_search('followers', $conditionPage[$serviceName])])
                                        @elseif($serviceCode == config('constants.service.twitter'))
                                            @unset($conditionPage[$serviceName][array_search('favourites', $conditionPage[$serviceName])])
                                        @endif
                                    @endforeach
                                    @foreach($pageCompetitor as $page)
                                        <section class="panel page-box" data-account="{{{ @$page['auth_id'] }}}">
                                            <div class="panel-body page_name_box">
                                                <div class="wk-progress tm-membr col-md-8">
                                                    <div class="tm-avatar">
                                                        <img src="{{{ @$page['avatar_url'] }}}" alt="">
                                                    </div>
                                                    <div class="col-md-7 col-xs-7">
                                                        <span class="tm">{{{ @$page['name'] }}}</span>
                                                    </div>
                                                </div>
                                                @if(isset($totalPage) && isset($totalPage[$page['id']]))
                                                    <?php $thisToday = $totalPage[$page['id']]; ?>
                                                @endif
                                                <div class="page-detail">
                                                    <ul class="clearfix location-earning-stats">
                                                        <li class="stat-divider change_data_graph" data-show="friends" data-service-name="{{{ $service_name }}}" data-service-code="{{{ $service_code }}}" data-page="{{{ $page['id'] }}}">
                                                            {{{ trans('field.'.$service_name.'_friends_count') }}}
                                                            <span class="first-item">{{{ @$thisToday['friends_count'] ? number_format($thisToday['friends_count'], 0, '.', '.') : 0 }}}</span>
                                                        </li>
                                                        @if(config('constants.service.facebook') != $service_code)
                                                            <li class="stat-divider change_data_graph" data-show="followers" data-service-name="{{{ $service_name }}}" data-service-code="{{{ $service_code }}}" data-page="{{{ $page['id'] }}}">
                                                                {{{ trans('field.'.$service_name.'_followers_count') }}}
                                                                <span class="third-item">{{{ @$thisToday['followers_count'] ? number_format($thisToday['followers_count'], 0, '.', '.') : 0 }}}</span>
                                                            </li>
                                                        @endif
                                                        <li class="stat-divider change_data_graph" data-show="posts" data-service-name="{{{ $service_name }}}" data-service-code="{{{ $service_code }}}" data-page="{{{ $page['id'] }}}">
                                                            {{{ trans('field.'.$service_name.'_posts_count') }}}
                                                            <span class="second-item">{{{ @$thisToday['posts_count'] ? number_format($thisToday['posts_count'], 0, '.', '.') : 0 }}}</span>
                                                        </li>
                                                    </ul>
                                                </div>
                                                <div class="col-md-12 graph-box">
                                                    <section class="panel panel-chart">
                                                        <div class="row">
                                                            <div class="condition col-md-2">
                                                                {!! Form::select('typeDrawSubPage',  $conditionPage[$service_name], null, ['class' => 'typeDrawSubPage form-control','id' => 'typeDrawSubPage_'.$page['id'], 'data-page' => $page['id']]) !!}
                                                            </div>
                                                            <div class="condition col-md-2">
                                                                <?php  $condition = array_map(function($val) { return trans('field.condition_filter_'.$val); }, config('constants.condition_filter'));?>
                                                                {!! Form::select($service_name.'_'.$page['id'],  $condition, null, ['class' => 'typeDraw form-control','id' => 'typeDraw_'.$page['id'], 'data-service-name' => $service_name, 'data-page' => $page['id'], 'data-page' => $page['id']]) !!}
                                                            </div>
                                                        </div>
                                                        <header class="panel-heading">{{ trans('title.page_engagement_daily') }}</header>
                                                        <div class="panel-body">
                                                            <div class="graph" id="graph_line_Competitor_{{ $service_name.'_'.$page['id'] }}"></div>
                                                        </div>
                                                    </section>
                                                </div>
                                            </div>
                                        </section>
                                    @endforeach
                                @endif
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
                                        @if(Auth::user()->authority == config('constants.authority.client'))
                                            <a class="btn btn-primary" href="{{ url('social/handle'.ucfirst($service_name)) }}">{{{ trans('button.grant_access') }}}</a>
                                        @endif
                                    </div>
                                </div>
                            </section>
                        @endif
                    </div>
                </div>
            </section>
        </div>
    </div>
@endsection
@section('scripts')
    @if($service_data['service_exist'])
    <script>
        //create graph for post detail data
        $(function(){
            @if(isset($pageList))
                @foreach($pageList as $page_id => $page)
                    var data        = [],
                    label       = ['{{{ trans('field.post_engagement') }}}'],
                    element_id  = 'graph_line_{{ $service_name.'_'.$page_id }}';
                    @if(isset($postByDay) && isset($postByDay[$page_id]))
                        if($('#' + element_id).length > 0) {
                            @foreach($postByDay[$page_id] as $date => $post)
                                data.push({
                                date: '{{{ $date }}}',
                                value: '{{{ $post['compare'] }}}'
                            });
                            @endforeach
                            generate_graph(element_id, label, data);
                        }
                    @endif
                @endforeach
            @endif
            @if(isset($pageCompetitor))
                var urlGraphPage , typeDraw;
                @foreach($pageCompetitor as $page_id => $page)
                    urlGraphPage = '{{ URL::route('site.analytic.graph',  ["$page_id"]) }}';
                    typeDraw = $('#{{'typeDraw_'.$service_name.'_'.$page_id}}');
                    element_id = 'graph_line_Competitor_{{ $service_name.'_'.$page_id }}';
                    getDataAjax(element_id, urlGraphPage, '{{$page_id}}');
                @endforeach
            @endif

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

            $('.typeDraw, .typeDrawSubPage').on('change', function (e) {
                e.preventDefault;
                var page_id = $(this).data('page');
                var element_chart_id = 'graph_line_Competitor_' + $(this).data('service-name') + '_' + $(this).data('page'),
                        url = '{{url('page')}}/' + $(this).data('page') + '/social/graph';
                $('#'+element_chart_id).html(' ');
                getDataAjax(element_chart_id, url, page_id);
            });
        });

        function select_page(service_name, auth_id) {
            $('.service-content .page-box').each(function (e) {
                if($(this).data('account') == auth_id) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        }
        function generate_graph(element_id, label, data) {
            var maxGraph = Math.max.apply(Math,data.map(function(o){return o['value'];}));
            var ceilMax = Math.ceil(maxGraph/4);
            if(maxGraph >= 3*ceilMax){
                maxGraph = 4*ceilMax +  4*Math.ceil(ceilMax/4);
            }
            maxGraph = maxGraph < 4 ? 4 : maxGraph;
            console.log(maxGraph)
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
                ymax: maxGraph,
                pointSize: 5,
                smooth: false,
                behaveLikeLine: true,
                fillOpacity: '0.2',
                yLabelFormat: function(d) {if(d != 0 && !parseInt(d)) {return '';} return parseInt(d).toLocaleString();},
                xLabelFormat: function(d) {d = new Date(d.label); return (d.getMonth()+1)+'/'+d.getDate();},
                data: data
            });
        }

        function getDataAjax(element_id, urlGraphPage, page_id){
            var typeDrawSubPage= parseInt($('#typeDrawSubPage_' + page_id).val());
            var typeDraw = parseInt($('#typeDraw_' + page_id).val());
            $.ajax({
                url: urlGraphPage,
                data: {
                    "_token"    : "{{ csrf_token() }}",
                    'dateFrom'  : $('#inputDateFrom').val(),
                    'dateTo'    : $('#inputDateTo').val(),
                    "typeDraw"  : typeDrawSubPage,
                },
                type: 'POST',
                context: this,
                dataType: 'Json',
                success: function (data) {
                    var dataResponse = data.contentCount, dataGraph = [], label = [$('#typeDraw_' + page_id + ' option:selected').html()];
                    if(data.success){
                        if(typeDraw == '0'){
                            for (var item in dataResponse) {
                                dataGraph.push({
                                    date: item,
                                    value: dataResponse[item]['count']
                                })
                            }
                        }else{
                            for (var item in dataResponse) {
                                dataGraph.push({
                                    date : item,
                                    value : dataResponse[item]['count_compare']
                                })
                            }
                        }

                        var parent_graph = $('#' + element_id).parents('.panel.page-box'),
                            service_display = parent_graph.css('display');
                        if(service_display == 'none') {
                            parent_graph.css('display', 'block');
                        }
                        console.log(dataGraph);
                        generate_graph(element_id, label, dataGraph);
                        if(service_display == 'none') {
                            parent_graph.css('display', 'none');
                        }
                    }
                }
            })
        }
    </script>
    @endif
@endsection