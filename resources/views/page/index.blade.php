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
                        <div class="form-group input-group input-large">
                            {!! Form::label('inputDateFrom', trans('field.from'), ['class' => 'col-sm-1 control-label']) !!}
                            <div class="col-sm-4 input-box">
                                {!! Form::text('from', isset($dates['from'])?$dates['from']:date('Y/m/d',strtotime('-2 weeks')), ['id' => 'inputDateFrom','class' => 'form-control default-date-picker dpd1']) !!}
                            </div>
                            {!! Form::label('inputDateTo', trans('field.to'), ['class' => 'col-sm-1 control-label']) !!}
                            <div class="col-sm-4 input-box">
                                {!! Form::text('to', isset($dates['to'])?$dates['to']:date('Y/m/d',time()), ['id' => 'inputDateTo','class' => 'form-control default-date-picker dpd2']) !!}
                            </div>
                            <button class="btn btn-primary btn-submit" type="submit"><i class="fa fa-search"></i></button>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </header>
                <div class="panel-body">
                    @if(isset($pageInfo))
                        <?php  $conditionPost = config('constants.condition_filter_post')?>
                        <?php  $conditionPage = config('constants.condition_filter_page')?>
                        <?php  $condition     = array_map(function($val) { return trans('field.condition_filter_'.$val); }, config('constants.condition_filter'));?>
                        @unset($conditionPage[array_search('posts', $conditionPage)])

                        @if($serviceCode == config('constants.service.instagram'))
                            @unset($conditionPage[array_search('favourites', $conditionPage)])

                            @unset($conditionPost[array_search('shares', $conditionPost)])
                            @unset($conditionPost[array_search('retweets', $conditionPost)])
                            @unset($conditionPost[array_search('favorites', $conditionPost)])
                        @elseif($serviceCode == config('constants.service.facebook'))
                            @unset($conditionPage[array_search('favourites', $conditionPage)])
                            @unset($conditionPage[array_search('followers', $conditionPage)])

                            @unset($conditionPost[array_search('favorite', $conditionPost)])
                            @unset($conditionPost[array_search('retweets', $conditionPost)])
                        @elseif($serviceCode == config('constants.service.twitter'))
                            @unset($conditionPage[array_search('favourites', $conditionPage)])

                            @unset($conditionPost[array_search('likes', $conditionPost)])
                            @unset($conditionPost[array_search('comments', $conditionPost)])
                            @unset($conditionPost[array_search('shares', $conditionPost)])
                        @endif
                        @foreach ($conditionPage as $i => $value)
                            <?php $conditionPage[$i] = trans('field.'.$nameService.'_'.$value.'_count'); ?>
                        @endforeach
                        @foreach ($conditionPost as $i => $value)
                            <?php $conditionPost[$i] = trans('field.'.$nameService.'_'.'post_'.$value.'_count'); ?>
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
                                            @if($serviceCode == config('constants.service.instagram'))
                                                <li class="stat-divider service-type">
                                                    {{trans('field.instagram_friends_count')}}
                                                    <span class="statistics first-city">{{ @number_format($pageInfo->friends_count)}}</span>
                                                </li>
                                                <li class="stat-divider service-type">
                                                    {{trans('field.instagram_followers_count')}}
                                                    <span class="statistics third-city">{{ @number_format($pageInfo->followers_count)}}</span>
                                                </li>
                                                <li class="stat-divider service-type">
                                                    {{trans('field.instagram_posts_count')}}
                                                    <span class="statistics second-city">{{ @number_format($pageInfo->posts_count)}}</span>
                                                </li>
                                            @elseif($serviceCode == config('constants.service.twitter'))
                                                <li class="stat-divider  service-type">
                                                    {{trans('field.twitter_friends_count')}}
                                                    <span class="statistics first-city">{{ @number_format($pageInfo->friends_count)}}</span>
                                                </li>
                                                <li class="stat-divider service-type">
                                                    {{trans('field.twitter_followers_count')}}
                                                    <span class="statistics third-city">{{ @number_format($pageInfo->followers_count)}}</span>
                                                </li>
                                                <li class="stat-divider service-type">
                                                    {{trans('field.twitter_posts_count')}}
                                                    <span class="statistics second-city">{{ @number_format($pageInfo->posts_count)}}</span>
                                                </li>
                                            @else
                                                <li class="stat-divider service-type">
                                                    {{trans('field.facebook_friends_count')}}
                                                    <span class="statistics first-city">{{ @number_format($pageInfo->friends_count)}}</span>
                                                </li>
                                                <li class="stat-divider service-type">
                                                    {{trans('field.facebook_posts_count')}}
                                                    <span class="statistics second-city">{{ @number_format($pageInfo->posts_count)}}</span>
                                                </li>
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
                            <div class="panel-heading">{{ trans('field.post_detail') }}</div>
                            <div class="panel-body service-first">
                                <div class="row">
                                    <div class="condition col-md-2">
                                        {!! Form::select('typeDrawPost',  $conditionPost, null, ['class' => 'typeDrawPost form-control','id' => 'typeDrawPost']) !!}
                                    </div>
                                    <div class="condition col-md-2">
                                        {!! Form::select('typeDrawSubPost',  $condition, null, ['class' => 'typeDrawSubPost form-control','id' => 'typeDrawSubPost']) !!}
                                    </div>
                                </div>
                                <div id="chartContainer2" class="row"></div>
                                @if(@$serviceCode == config('constants.service.facebook'))
                                    @foreach(@$listPosts as $post)
                                        <div class="alert alert alert-info clearfix">
                                            <div class="notification-info post-info">
                                                <ul class="clearfix notification-meta">
                                                    <li class="pull-left notification-sender">
                                                        <p class="post-content text-center">{{@str_limit(@$post->content, 100)}}</p>
                                                        <p class="post-icon"><i class="fa fa-comment first-city" aria-hidden="true"></i>&nbsp;
                                                            {{@$post->comment_count}}&nbsp;&nbsp;&nbsp;
                                                            <i class="fa fa-thumbs-o-up second-city" aria-hidden="true"></i>&nbsp;
                                                            {{@$post->like_count}}&nbsp;&nbsp;&nbsp;
                                                            <i class="fa fa-share third-city" aria-hidden="true"></i>&nbsp;
                                                            {{@$post->share_count}}
                                                        </p>
                                                    </li>
                                                    <li class="clearfix pull-right">
                                                        <p>{{ trans('field.post_created_time') }} :
                                                            {{(@$post->created_time)}}
                                                        </p>
                                                        <p>{{ trans('field.post_updated_time') }} :
                                                            {{(@$post->updated_at)}}
                                                        </p>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    @endforeach
                                @elseif(@$serviceCode == config('constants.service.twitter'))
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
                                                        <div class="subject">{{@str_limit(@$post->content, 100)}}</div>
                                                        <div class="message">
                                                            <p class="post-icon"><i class="fa fa-retweet first-city" aria-hidden="true"></i>&nbsp;
                                                                {{@$post->retweet_count}}&nbsp;&nbsp;&nbsp;
                                                                <i class="fa fa-heart second-city" aria-hidden="true"></i>&nbsp;
                                                                {{@$post->favorite_count}}
                                                            </p>
                                                        </div>
                                                    </li>
                                                    <li class="clearfix pull-right">
                                                        <p>{{ trans('field.post_created_time') }} :
                                                            {{(@$post->created_time)}}
                                                        </p>
                                                        <p>{{ trans('field.post_updated_time') }} :
                                                            {{(@$post->updated_at)}}
                                                        </p>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    @foreach(@$listPosts as $post)
                                        <div class="alert alert alert-info clearfix">
                                            <div class="notification-info post-info">
                                                <ul class="clearfix notification-meta">
                                                    <li class="pull-left notification-sender">
                                                        @if(isset($post->image_thumbnail))
                                                            <img src="{{$post->image_thumbnail}}">
                                                        @endif
                                                        <p class="post-content text-center">{{@str_limit(@$post->content, 100)}}</p>
                                                        <p class="post-icon"><i class="fa fa-comment first-city" aria-hidden="true"></i>&nbsp;
                                                            {{@$post->comment_count}}&nbsp;&nbsp;&nbsp;
                                                            <i class="fa fa-heart second-city" aria-hidden="true"></i>&nbsp;
                                                            {{@$post->like_count}}
                                                        </p>
                                                    </li>
                                                    <li class="clearfix pull-right">
                                                        <p>{{ trans('field.post_created_time') }} :
                                                            {{(@$post->created_time)}}
                                                        </p>
                                                        <p>{{ trans('field.post_updated_time') }} :
                                                            {{(@$post->updated_at)}}
                                                        </p>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
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
                var conditionPost = <?php echo json_encode ($conditionPost); ?>;
                $('#typeDrawPage, #typeDrawPost').css('width','200px');
                var graphDraw = function (typeDraw) {
                    var typeDrawPage= parseInt($('#typeDrawPage').val());
                    var typeDrawPost = parseInt($('#typeDrawPost').val());
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
                    chartPost.options.labels = [$('#typeDrawSubPost option:selected').html()];
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
                    if(Date.parse(dateFrom.val())<=Date.parse(dateTo.val())){
                        graphDraw(3);
                    }
                    return false;
                })

            })
        @endif
    </script>
@endsection