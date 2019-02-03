@extends('admin::base')

@section('view')
        <section class="fullpage">
            @include('pageviews::visitors')
            <h2>Top referers</h2>
            @include('pageviews::referers')
            <h2>Top urls</h2>
            @include('pageviews::urls')
        </section>
@endsection
