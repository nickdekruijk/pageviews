@extends('admin::base')

@section('view')
        <section class="fullpage" style="overflow:auto">
            @include('pageviews::filters')
            @include('pageviews::visitors')
            <h2>Top referers</h2>
            @include('pageviews::referers')
            <h2>Top urls</h2>
            @include('pageviews::urls')
            <style>
                A {color:inherit}
                A:hover {text-decoration:underline}
                TABLE.ellipsis {width:100%;max-width:700px}
                TABLE.ellipsis TD:first-child {text-overflow:ellipsis;white-space:nowrap;overflow:hidden;max-width:0;font-size:0.8em}
                TABLE.ellipsis TD:last-child {width:1%;text-align:right}
                }
            </style>
        </section>
@endsection
