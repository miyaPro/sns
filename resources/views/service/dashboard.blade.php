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
                    <div class="search-box">
                        {!! Form::open(['class' => 'form-horizontal frm-search', 'method' => 'POST']) !!}
                        <div class="form-group input-group input-large">
                            {!! Form::label('inputDateFrom', trans('field.from'), ['class' => 'col-sm-1 control-label']) !!}
                            <div class="col-sm-4 input-box">
                                {!! Form::text('from', @$dates['from'], ['id' => 'inputDateFrom','class' => 'form-control default-date-picker dpd1']) !!}
                            </div>
                            {!! Form::label('inputDateTo', trans('field.to'), ['class' => 'col-sm-1 control-label']) !!}
                            <div class="col-sm-4 input-box">
                                {!! Form::text('to', @$dates['to'], ['id' => 'inputDateTo','class' => 'form-control default-date-picker dpd2']) !!}
                            </div>
                            <button class="btn btn-primary" type="submit"><i class="fa fa-search"></i></button>
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
                            @if(in_array($service_code, $services))
                                @if(isset($pageList[$service_code]))
                                    <div class="service-content">
                                        @foreach($pageList[$service_code] as $page)
                                            <section class="panel">
                                                <div class="panel-body page_name_box">
                                                    <div class="wk-progress tm-membr col-md-6">
                                                        <div class="tm-avatar">
                                                            <img src="{{{ @$page['avatar_url'] }}}" alt="">
                                                        </div>
                                                        <div class="col-md-7 col-xs-7">
                                                            <span class="tm">{{{ @$page['page_name'] }}}</span>
                                                        </div>
                                                        <div class="col-md-3 col-xs-3 btn-view">
                                                            <a href="{!! URL::to('page/'.$page['page_id']) !!}" class="btn btn-white">{{{ trans('button.view') }}}</a>
                                                        </div>
                                                    </div>
                                                    @if(isset($totalPage[$service_code]) && isset($totalPage[$service_code][$page['page_id']]))
                                                        <?php $thisToday = $totalPage[$service_code][$page['page_id']]; ?>
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
                                                        <div class="graph" id="graph_line_{{ $service_name.'_'.$page['page_id'] }}"></div>
                                                    </div>
                                                </div>
                                            </section>
                                        @endforeach
                                    </div>
                                @endif
                            @else
                                <div class="service-content">
                                    <section class="panel col-md-12">
                                        <div class="panel-body clearfix add-btn">
                                            <div class="btn-group">
                                                <a class="btn btn-primary" href="{{ url('social/handle'.ucfirst($service_name)) }}">{{{ trans('button.grant_access') }}}</a>
                                            </div>
                                        </div>
                                    </section>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </section>
    </div>
@endsection
@section('scripts')
    <script>
        var graph_label = [];
        //prepare label per analytics
        @foreach(config('constants.service') as $service_name => $service_code)
            @foreach(config('constants.condition_filter_page') as $item)
                graph_label['{{{ $service_name.'_'.$item }}}']    = ['{{{ trans('field.'.$service_name.'_'.$item.'_count') }}}'];
            @endforeach
        @endforeach

        $('.change_graph').on('click', function (e) {
            var data_show       = $(this).data('show'),
                page            = $(this).data('page'),
                service_name    = $(this).data('service-name'),
                service_code    = $(this).data('service-code');
            load_graph(data_show, page, service_name, service_code);
        });
        function load_graph(data_show, page, service_name, service_code) {
            var pagePerDay      = '<?php echo json_encode($pagePerDay) ?>',
                    data        = [],
                    element_id  = 'graph_line_' +service_name + '_' + page,
            pagePerDay          = JSON.parse(pagePerDay);
            if(pagePerDay[service_code] != void 0 && pagePerDay[service_code][page] != void 0) {
                var page_data = pagePerDay[service_code][page];
                for(var i = 0; i < page_data.length; i++) {
                    data.push({
                        date: page_data[i]['date'],
                        value: page_data[i][data_show + '_count']
                    });
                }
            }
            $('#' + element_id).html('');
            generate_graph(element_id, graph_label[service_name + '_' + data_show], data);
        }

        //init loader using friends
        $(function(){
            <?php $i = 0; ?>
            @foreach(config('constants.service') as $service_name => $service_code)
                @if(isset($pageList[$service_code]))
                    @foreach($pageList[$service_code] as $page_id => $page)
                        var data        = [],
                            label       = ['{{{ trans('field.'.$service_name.'_friends_count') }}}'],
                            element_id  = 'graph_line_{{ $service_name.'_'.$page_id }}';
                        @if($i > 0)
                            $('#{{{ $service_name }}}').css('display', 'block');
                        @endif
                        @if(isset($pagePerDay[$service_code]) && isset($pagePerDay[$service_code][$page_id]))
                            @foreach($pagePerDay[$service_code][$page_id] as $pageData)
                                data.push({
                                    date: '{{{ $pageData['date'] }}}',
                                    value: '{{{ $pageData['friends_count'] }}}'
                                });
                            @endforeach
                        @endif
                        generate_graph(element_id, label, data);
                        @if($i > 0)
                             $('#{{{ $service_name }}}').css('display', 'none');
                        @endif
                    @endforeach
                @endif
            <?php $i++; ?>
            @endforeach
        });

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