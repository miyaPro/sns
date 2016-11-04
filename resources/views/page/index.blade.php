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
                                    @if(isset($pageInfo))
                                        <div class="condition col-md-1">
                                            @unset($condition[array_search('posts', $condition)])
                                            @if($serviceCode == config('constants.service.instagram'))
                                                @unset($condition[array_search('favourites', $condition)])
                                            @elseif($serviceCode == config('constants.service.facebook'))
                                                @unset($condition[array_search('favourites', $condition)])
                                                @unset($condition[array_search('followers', $condition)])
                                            @elseif($serviceCode == config('constants.service.twitter'))
                                                @unset($condition[array_search('favourites', $condition)])
                                            @endif
                                            @foreach ($condition as $i => $value)
                                                <?php $condition[$i] = trans('field.'.$nameService.'_'.$value.'_count'); ?>
                                            @endforeach
                                            {!! Form::select('typeDraw',  $condition, null, ['class' => 'typeDraw form-control','id' => 'typeDraw']) !!}
                                        </div>
                                    @endif
                                </div>
                                <div id="chartContainer1" class="row"></div>
                            </div>
                        </section>
                        <section class="panel">
                            <div class="panel-heading">{{ trans('field.post_detail') }}</div>
                            <div class="panel-body service-first">
                                <div id="chartContainer2" class="row" style="height: 350px"></div>
                            </div>
                            <div class="panel-body service-first">
                                @if(@$serviceCode == config('constants.service.facebook'))
                                    @foreach(@$listPosts as $post)
                                        <div class="alert alert alert-info clearfix">
                                            <div class="notification-info post-info">
                                                <ul class="clearfix notification-meta">
                                                    <li class="pull-left notification-sender">
                                                        <p class="post-content text-center">{{@str_limit(@$post->content, 100)}}</p>
                                                        <p><i class="fa fa-comment" aria-hidden="true"></i>&nbsp;
                                                            {{@$post->comment_count}}&nbsp;&nbsp;&nbsp;
                                                            <i class="fa fa-heart third-city" aria-hidden="true"></i>&nbsp;
                                                            {{@$post->like_count}}&nbsp;&nbsp;&nbsp;
                                                            <i class="fa fa-share" aria-hidden="true"></i>&nbsp;
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
                                                        @if(isset($post->image_thumbnail))
                                                            <img src="{{$post->image_thumbnail}}" style="width: 150px; height: 150px;">
                                                        @endif
                                                        <p class="post-content text-center">{{@str_limit(@$post->content, 100)}}</p>
                                                        <p><i class="fa fa-retweet" aria-hidden="true"></i>&nbsp;
                                                            {{@$post->retweet_count}}&nbsp;&nbsp;&nbsp;
                                                            <i class="fa fa-heart third-city" aria-hidden="true"></i>&nbsp;
                                                            {{@$post->favorite_count}}
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
                                @else
                                    @foreach(@$listPosts as $post)
                                        <div class="alert alert alert-info clearfix">
                                            <div class="notification-info post-info">
                                                <ul class="clearfix notification-meta">
                                                    <li class="pull-left notification-sender">
                                                        @if(isset($post->image_thumbnail))
                                                            <img src="{{$post->image_thumbnail}}" style="width: 150px; height: 150px;">
                                                        @endif
                                                        <p class="post-content text-center">{{@str_limit(@$post->content, 100)}}</p>
                                                        <p><i class="fa fa-comment" aria-hidden="true"></i>&nbsp;
                                                            {{@$post->comment_count}}&nbsp;&nbsp;&nbsp;
                                                            <i class="fa fa-heart third-city" aria-hidden="true"></i>&nbsp;
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

    <script>
        $(function() {
            var urlGraph = '{{ URL::route('site.analytic.graph',  ["$pageInfo->page_id"]) }}';
            var chart,chartPost;
            $('#typeDraw').css('width','200px');
            var graphDraw = function () {
                var typeDraw = parseInt($('#typeDraw').val());
                var dataResponse = [];
                var dateFrom = $('#inputDateFrom');
                var dateTo = $('#inputDateTo');
                if(typeDraw>=0){
                    $.ajax({
                        url: urlGraph,
                        data: {
                            "_token"    : "{{ csrf_token() }}",
                            'dateFrom'  : dateFrom.val(),
                            'dateTo'    : dateTo.val(),
                            "typeDraw"  : typeDraw

                        },
                        type: 'POST',
                        context: this,
                        dataType: 'Json',
                        success: function (data) {
                            if(data.success){
                                var dataResponse = data.contentCount;
                                chart.options.labels = [$('#typeDraw option:selected').html()];
                                if(dataResponse.length >= 0) {
                                    for (var i = 0; i < dataResponse.length; i++) {
                                        if (typeof dataResponse[i]['count'] == 'undefined' || dataResponse[i]['count'] == '') {
                                            dataResponse[i]['count'] = 0;
                                        }
                                    }
                                }
                                chart.setData(dataResponse);
                            }
                        }
                    })
                }else{
                    chart.setData([]);
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

            $('#typeDraw').on('change', function (e) {
                e.preventDefault;
                graphDraw();
            });
            chart=generateGraph('chartContainer1',[]);
            chartPost = generateGraph('chartContainer2',[
                {'date' : '2016-10-21', 'count' : 20}
            ])
            graphDraw();
            $('.btn-submit').on('click',function () {
                var dateFrom = $('#inputDateFrom'), dateTo = $('#inputDateTo');
                if(Date.parse(dateFrom.val())<=Date.parse(dateTo.val())){
                    graphDraw();
                }
                return false;
            })
        })
    </script>
@endsection