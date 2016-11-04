<?php ob_end_clean() ?>
@extends('layouts.app')
@section('title') {{{ trans('title.master') }}} :: @parent @stop
@section('content')
    @include('modals.delete')
    <div class="row">
        <div class="col-md-12">
        <header class="panel-heading">facebook</header>
            <div class="row tile_count">
                <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
                    <span class="count_top"><i class="fa fa-user"></i> Like</span>
                        <div class="count">2500</div>
                            <span class="count_bottom"><i class="green">4% </i> From Last Moth</span>
                </div>
            </div>
        </div>
    </div>
@endsection