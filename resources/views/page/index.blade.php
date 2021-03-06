<?php ob_end_clean(); ?>
@extends('layouts.app')
@section('title') {{{ trans('title.info_detail').' '.$nameService }}} :: @parent @stop
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
            <div class="box_message"></div>
            <section class="panel">
                <header class="panel-heading">
                    <h4>{{{ trans('title.info_detail').' '.$nameService }}}</h4>
                    <div style="float: right; margin-top: -40px;">
                        {!! Form::open(['class' => 'form-horizontal frm-search', 'method' => 'POST']) !!}
                        <div class="form-group input-group input-large row">

                            {!! Form::label('inputDateFrom', trans('field.date_range'), ['class' => 'control-label col-md-2 pull-left']) !!}
                            <div class="input-group input-large col-md-8 pull-left" data-date="" data-date-format="yyyy/mm/dd">
                                {!! Form::text('from', !empty(request()->cookie('date_search')['from'])? str_replace('-', '/', request()->cookie('date_search')['from']): date('Y/m/d'), ['id' => 'inputDateFrom','class' => 'form-control default-date-picker dpd1']) !!}
                                {!! Form::label('inputDateTo', 'To', ['class' => 'input-group-addon page-label']) !!}
                                {!! Form::text('to', !empty(request()->cookie('date_search')['to'])? str_replace('-', '/', request()->cookie('date_search')['to']): date('Y/m/d', strtotime(@$date['to']." -2 weeks")), ['id' => 'inputDateTo','class' => 'form-control default-date-picker dpd2']) !!}
                            </div>
                            <div class="col-md-2 pull-right">
                                <button class="btn btn-primary btn-submit"><i class="fa fa-search"></i></button>
                            </div>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </header>
                <div class="panel-body">
                    @if(isset($pageInfo))
                        <?php  $conditionPage = config('constants.condition_filter_page')?>
                        <?php  $condition     = array_map(function($val) { return trans('field.condition_filter_'.$val); }, config('constants.condition_filter'));?>
                        <?php $postDetailService = array(
                                config('constants.service.facebook') => array(
                                        'fa-thumbs-o-up post-like-facebook'   => 'like_count',
                                        'fa-comment first-city'        => 'comment_count',
                                        'fa-share third-city'          => 'share_count'
                                ),
                                config('constants.service.twitter') => array(
                                        'fa-retweet first-city'        => 'retweet_count',
                                        'fa-heart second-city'         => 'favorite_count'
                                ),
                                config('constants.service.instagram') => array(
                                        'fa-heart second-city'         => 'like_count',
                                        'fa-comment first-city'        => 'comment_count'
                                )
                        );
                        ?>
                        @unset($conditionPage[array_search('posts', $conditionPage)])
                        @if($serviceCode == config('constants.service.instagram'))
                            @unset($conditionPage[array_search('favourites', $conditionPage)])
                        @elseif($serviceCode == config('constants.service.facebook'))
                            @unset($conditionPage[array_search('favourites', $conditionPage)])
                            @unset($conditionPage[array_search('followers', $conditionPage)])
                        @elseif($serviceCode == config('constants.service.twitter'))
                            @unset($conditionPage[array_search('favourites', $conditionPage)])
                        @endif
                        @foreach ($conditionPage as $i => $value)
                            <?php $conditionPage[$i] = trans('field.'.$nameService.'_'.$value.'_count'); ?>
                        @endforeach

                        <section class="panel">
                            <div class="panel-body profile-information">
                                <div class="col-md-2">
                                    <div class="profile-pic">
                                        <img src="{{ $pageInfo->avatar_url or elixir('images/profile.png') }}" alt="">
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="profile-desk">
                                        <h1>{{ @$pageInfo->name }}</h1>
                                        <p>
                                            {{@$pageInfo->description}}
                                        </p>
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="profile-statistics">
                                        <ul class="clearfix location-earning-stats">
                                            <li class="stat-divider service-type">
                                                {{trans('field.'.$nameService.'_friends_count')}}
                                                <span class="statistics first-city">{{ @number_format($pageInfo->friends_count)}}</span>
                                            </li>
                                            @if($serviceCode == '002' || $serviceCode == '003')
                                                <li class="stat-divider service-type">
                                                    {{trans('field.'.$nameService.'_followers_count')}}
                                                    <span class="statistics third-city">{{ @number_format($pageInfo->followers_count)}}</span>
                                                </li>
                                            @endif
                                            <li class="stat-divider service-type">
                                                {{trans('field.'.$nameService.'_posts_count')}}
                                                <span class="statistics second-city">{{ @number_format($pageInfo->posts_count)}}</span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </section>

                        <section class="panel">
                            <div class="panel-body service-first">
                                <div class="row">
                                    <div class="condition col-md-2">
                                        {!! Form::select('typeDrawPage',  $conditionPage, null, ['class' => 'typeDrawPage form-control','id' => 'typeDrawPage']) !!}
                                    </div>
                                    <div class="condition col-md-2">
                                        {!! Form::select('typeDrawSubPage',  $condition, null, ['class' => 'typeDrawSubPage form-control','id' => 'typeDrawSubPage']) !!}
                                    </div>
                                </div>
                                <div id="chartContainer1" class="row"></div>
                            </div>
                        </section>
                        <section class="panel">
                            <div class="panel-heading">{{ trans('field.post_engagement') }}</div>
                            <div class="panel-body service-first">
                                <div class="row">
                                    <div class="condition col-md-2">
                                        {!! Form::select('typeDrawSubPost',  $condition, null, ['class' => 'typeDrawSubPost form-control','id' => 'typeDrawSubPost']) !!}
                                    </div>
                                </div>
                                <div id="chartContainer2" class="row"></div>
                            </div>
                            <div class="panel-heading">{{ trans('field.post_list') }}</div>
                            <div class="panel-body service-first">
                                @foreach(@$listPosts as $post)
                                    <div class="alert alert alert-info clearfix">
                                        <div class="notification-info post-info">
                                            <ul class="clearfix notification-meta">
                                                <li class="pull-left notification-sender">
                                                    <div class="photo">
                                                        @if(isset($post->image_thumbnail))
                                                            <img src="{{$post->image_thumbnail}}">
                                                        @endif
                                                    </div>
                                                    <p class="post-content">{{@str_limit(@$post->content, 100)}}</p>
                                                    <p class="post-icon">
                                                        @if(isset($postDetailService[$serviceCode]))
                                                            @foreach($postDetailService[$serviceCode] as $key => $value)
                                                                <i class="fa {{$key}}" aria-hidden="true"></i>&nbsp;
                                                                {!! @$post->$value !!}&nbsp;&nbsp;&nbsp;
                                                            @endforeach
                                                        @endif
                                                    </p>
                                                </li>
                                                <li class="clearfix pull-right">
                                                    <p>{{ trans('field.post_created_at') }}
                                                        {{(@$post->created_time)}}
                                                    </p>
                                                    <p>{{ trans('field.data_get_updated_at') }}
                                                        {{(@$post->updated_at)}}
                                                    </p>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </section>
                    @endif
                </div>
            </section>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        @if(isset($pageInfo))
           $(function() {
                var urlGraphPage = '{{ URL::route('site.analytic.graph',  ["$pageInfo->page_id"]) }}';
                var urlGraphPost = '{{ URL::route('site.analytic.graphPost',  ["$pageInfo->page_id"]) }}';
                var chartPage,chartPost;
                chartPage = generateGraph('chartContainer1',[]);
                chartPost = generateGraph('chartContainer2',[]);
                graphDraw(3);

                $('.btn-submit').on('click',function () {
                    var dateFrom = $('#inputDateFrom'), dateTo = $('#inputDateTo');
                    if(Date.parse(dateFrom.val()) <= Date.parse(dateTo.val())){
                        graphDraw(3);
                    }else{
                        setErrorMesssage('{{trans('message.error_date_ranger')}}')
                    }
                    return false;
                });

                $('#typeDrawPage, #typeDrawSubPost, #typeDrawSubPage').on('change', function (e) {
                    e.preventDefault;
                    var _id = $(this).attr('id');
                    if(_id == 'typeDrawPage' || _id == 'typeDrawSubPage'){
                        graphDraw(1);
                    }else{
                        graphDraw(2);
                    }
                });

                function graphDraw(typeDraw) {
                    var typeDrawPage= parseInt($('#typeDrawPage').val());
                    var typeDrawSubPost = parseInt($('#typeDrawSubPost').val());
                    var typeDrawSubPage = parseInt($('#typeDrawSubPage').val());
                    var dataGraph = [];
                    var dateFrom = $('#inputDateFrom');
                    var dateTo = $('#inputDateTo');
                    if(typeDraw == 1 || typeDraw == 2){
                        $.ajax({
                            url: typeDraw ==1 ? urlGraphPage : urlGraphPost,
                            data: {
                                "_token"    : "{{ csrf_token() }}",
                                'dateFrom'  : dateFrom.val(),
                                'dateTo'    : dateTo.val(),
                                "typeDraw"  : typeDrawPage
                            },
                            type: 'POST',
                            context: this,
                            dataType: 'Json',
                            success: function (data) {
                                var dataResponse = data.contentCount;
                                var maxGraph = data.maxValueData;
                                dataGraph = [];
                                if(data.success){
                                    if(dataResponse != void 0 && Object.keys(dataResponse).length > 0 &&
                                       maxGraph     != void 0 && Object.keys(maxGraph).length > 0){
                                        if(typeDraw == 1){
                                            loadGraphPage(dataResponse, dataGraph, typeDrawSubPage, maxGraph);
                                        }else{
                                            loadGraphPost(dataResponse, dataGraph, typeDrawSubPost, maxGraph);
                                        }
                                    }
                                    setErrorMesssage(null);
                                }else{
                                    setErrorMesssage(data.message)
                                }
                            }
                        })
                    }else{
                        $.ajax({
                            url: urlGraphPage,
                            data: {
                                "_token"    : "{{ csrf_token() }}",
                                'dateFrom'  : dateFrom.val(),
                                'dateTo'    : dateTo.val(),
                                "typeDraw"  : typeDrawPage
                            },
                            type: 'POST',
                            context: this,
                            dataType: 'Json',
                            success: function (data) {
                                dataGraph = [];
                                if(data.success){
                                    var dataResponse = data.contentCount;
                                    var maxGraph = data.maxValueData;
                                    if(dataResponse != void 0 && Object.keys(dataResponse).length > 0 &&
                                       maxGraph     != void 0 && Object.keys(maxGraph).length > 0) {
                                        loadGraphPage(dataResponse, dataGraph, typeDrawSubPage, maxGraph);
                                    }
                                    setErrorMesssage(null);
                                }else{
                                    setErrorMesssage(data.message)
                                }
                            }
                        })
                        $.ajax({
                            url: urlGraphPost,
                            data: {
                                "_token"    : "{{ csrf_token() }}",
                                'dateFrom'  : dateFrom.val(),
                                'dateTo'    : dateTo.val()
                            },
                            type: 'POST',
                            context: this,
                            dataType: 'Json',
                            success: function (data) {
                                dataGraph = [];
                                var dataResponse = data.contentCount;
                                var maxGraph = data.maxValueData;
                                if(data.success){
                                    if(dataResponse != void 0 && Object.keys(dataResponse).length > 0 &&
                                      maxGraph      != void 0 && Object.keys(maxGraph).length > 0) {
                                        loadGraphPost(dataResponse, dataGraph, typeDrawSubPost, maxGraph);
                                    }
                                    setErrorMesssage(null);
                                }else{
                                    setErrorMesssage(data.message)
                                }
                            }
                        })
                    }
                }

                function generateGraph(element_id, data){
                    return Morris.Area({
                        element: element_id,
                        data: data,
                        xkey: 'date',
                        ykeys: ['count'],
                        labels: ['count'],
                        lineColors: ['#058DC7'],
                        hideHover: 'auto',
                        resize: true,
                        parseTime:false,
                        lineWidth: 5,
                        pointSize: 5,
                        smooth: false,
                        behaveLikeLine: true,
                        numLines: 6,
                        fillOpacity: '0.2',
                        xLabelFormat: function(d) {d = new Date(d.label); return (d.getMonth()+1)+'/'+d.getDate();},
                        yLabelFormat: function(y){return y != Math.round(y)?'':parseInt(y).toLocaleString();},
                    });
                }

                function loadGraphPage(dataResponse, dataGraph, typeDrawSubPage, maxGraph){
                    chartPage.options.labels = [$('#typeDrawPage option:selected').html()];
                    var maxPage;
                    if(typeDrawSubPage == '0'){
                        for (var item in dataResponse) {
                            dataGraph.push({
                                date: item,
                                count: dataResponse[item]['count']
                            })
                        }
                        maxPage = maxGraph['count']
                    }else if(typeDrawSubPage == '1'){
                        for (var item in dataResponse) {
                            dataGraph.push({
                                date : item,
                                count : dataResponse[item]['count_compare']
                            })
                        }
                        maxPage = maxGraph['count_compare']
                    }else{
                        for (var item in dataResponse) {
                            dataGraph.push({
                                date : item,
                                count : dataResponse[item]['count_change']
                            })
                        }
                        maxPage = maxGraph['count_change']
                    }
                    chartPage.options.ymax = maxPage;
                    chartPage.setData(dataGraph);
                }

                function loadGraphPost(dataResponse, dataGraph, typeDrawSubPost, maxGraph){
                    chartPost.options.labels = ["{{trans('field.post_engagement')}}"];
                    var maxPost;
                    if(typeDrawSubPost == '0'){
                        for (var item in dataResponse) {
                            dataGraph.push({
                                date: item,
                                count: dataResponse[item]['total']
                            })
                        }
                        maxPost = maxGraph['total']
                    }else if(typeDrawSubPost == '1'){
                        for (var item in dataResponse) {
                            dataGraph.push({
                                date : item,
                                count : dataResponse[item]['compare']
                            })
                        }
                        maxPost = maxGraph['compare']
                    }else{
                        for (var item in dataResponse) {
                            dataGraph.push({
                                date : item,
                                count : dataResponse[item]['change']
                            })
                        }
                        maxPost = maxGraph['change']
                    }
                    chartPost.options.ymax = maxPost;
                    chartPost.setData(dataGraph)
                }

                function setErrorMesssage(message) {
                    if(message != '' && message != null) {
                        $('.box_message').html('<p class="alert alert-danger">' + message + ' <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></p>');
                    } else {
                        $('.box_message').html('');
                    }
                }
            });
        @endif
    </script>
@endsection