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
            <section class="panel">
                <header class="panel-heading">
                    <h4>{{{ trans('title.info_detail').' '.$nameService }}}</h4>
                    <div style="float: right; margin-top: -40px;">
                        {!! Form::open(['class' => 'form-horizontal frm-search', 'method' => 'POST']) !!}
                        <div class="form-group input-group input-large row">

                            {!! Form::label('inputDateFrom', trans('field.date_range'), ['class' => 'control-label col-md-2 pull-left']) !!}
                            <div class="input-group input-large col-md-8 pull-left" data-date="" data-date-format="yyyy/mm/dd">
                                {!! Form::text('from', !empty(request()->cookie('date_search')[0])? request()->cookie('date_search')[0]: date('Y/m/d'), ['id' => 'inputDateFrom','class' => 'form-control default-date-picker dpd1']) !!}
                                {!! Form::label('inputDateTo', trans('field.to'), ['class' => 'input-group-addon']) !!}
                                {!! Form::text('to', !empty(request()->cookie('date_search')[1])? request()->cookie('date_search')[1]: date('Y/m/d', strtotime(@$date['to']." -2 weeks")), ['id' => 'inputDateTo','class' => 'form-control default-date-picker dpd2']) !!}
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
                                        'fa-comment first-city'        => 'comment_count',
                                        'fa-thumbs-o-up second-city'   => 'like_count',
                                        'fa-share third-city'          => 'share_count'
                                ),
                                config('constants.service.twitter') => array(
                                        'fa-retweet first-city'        => 'retweet_count',
                                        'fa-heart second-city'         => 'favorite_count'
                                ),
                                config('constants.service.instagram') => array(
                                        'fa-comment first-city'        => 'comment_count',
                                        'fa-heart second-city'         => 'like_count'
                                )
                        );
                        $pageDetailService = array(
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
                                            @if(isset( $pageDetailService[$serviceCode]))
                                                @foreach( $pageDetailService[$serviceCode] as $key => $value)
                                                    <?php $field = 'instagram_'.$value;?>
                                                    <li class="stat-divider service-type">
                                                        {{trans('field.instagram_'.$value)}}
                                                        <span class="statistics {{$key}}">{{ @number_format($pageInfo->$value)}}</span>
                                                    </li>
                                                @endforeach
                                            @endif
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
                                                    <p>{{ trans('field.created_time') }} :
                                                        {{(@$post->created_time)}}
                                                    </p>
                                                    <p>{{ trans('field.updated_time') }} :
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
                $('#typeDrawPage').css('width','200px');
                var graphDraw = function (typeDraw) {
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
                                "typeDraw"  : typeDrawPage,
                            },
                            type: 'POST',
                            context: this,
                            dataType: 'Json',
                            success: function (data) {
                                var dataResponse = data.contentCount;
                                dataGraph = [];
                                if(data.success){
                                    if(typeDraw == 1){
                                        loadGraphPage(dataResponse, dataGraph, typeDrawSubPage);
                                    }else{
                                        loadGraphPost(dataResponse, dataGraph, typeDrawSubPost);
                                    }
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
                                "typeDraw"  : typeDrawPage,
                            },
                            type: 'POST',
                            context: this,
                            dataType: 'Json',
                            success: function (data) {
                                dataGraph = [];
                                if(data.success){
                                    var dataResponse = data.contentCount;
                                    loadGraphPage(dataResponse,dataGraph, typeDrawSubPage);
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
                                if(data.success){
                                    var dataResponse = data.contentCount;
                                    loadGraphPost(dataResponse, dataGraph, typeDrawSubPost);
                                }
                            }
                        })
                    }
                }
                var generateGraph = function(element_id, data){
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
                        fillOpacity: '0.2',
                        xLabelFormat: function(d) {d = new Date(d.label); return (d.getMonth()+1)+'/'+d.getDate();},
                        yLabelFormat: function(y){return y != Math.round(y)?'':y;},
                    });
                }
                var loadGraphPage = function(dataResponse, dataGraph, typeDrawSubPage){
                    chartPage.options.labels = [$('#typeDrawPage option:selected').html()];
                    if(typeDrawSubPage == '0'){
                        for (var item in dataResponse) {
                            dataGraph.push({
                                date: item,
                                count: dataResponse[item]['count']
                            })
                        }
                    }else{
                        for (var item in dataResponse) {
                            dataGraph.push({
                                date : item,
                                count : dataResponse[item]['count_compare']
                            })
                        }
                    }
                    chartPage.setData(dataGraph);
                }
                var loadGraphPost = function(dataResponse, dataGraph, typeDrawSubPost){
                    chartPost.options.labels = ["{{trans('field.post_engagement')}}"];
                    if(typeDrawSubPost == '0'){
                        for (var item in dataResponse) {
                            dataGraph.push({
                                date: item,
                                count: dataResponse[item]['total']
                            })
                        }
                    }else{
                        for (var item in dataResponse) {
                            dataGraph.push({
                                date : item,
                                count : dataResponse[item]['compare']
                            })
                        }

                    }
                    chartPost.setData(dataGraph)
                }
                $('#typeDrawPage, #typeDrawSubPost, #typeDrawSubPage').on('change', function (e) {
                    e.preventDefault;
                    var _id = $(this).attr('id');
                    if(_id == 'typeDrawPage' || _id == 'typeDrawSubPage'){
                        graphDraw(1);
                    }else{
                        graphDraw(2);
                    }
                });
                chartPage = generateGraph('chartContainer1',[]);
                chartPost = generateGraph('chartContainer2',[])
                graphDraw(3);
                $('.btn-submit').on('click',function () {
                    var dateFrom = $('#inputDateFrom'), dateTo = $('#inputDateTo');
                    if(Date.parse(dateFrom.val()) <= Date.parse(dateTo.val())){
                        graphDraw(3);
                    }
                    return false;
                })

            })
        @endif
    </script>
@endsection