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
                .fullpage SELECT {display:inline-block;margin:0;padding:4px 6px;background-color:rgba(255,255,255,0.5);box-sizing:border-box;font:inherit;border-radius:3px;border:1px solid rgba(0,0,0,0.2)}
                .fullpage A {color:inherit}
                .fullpage A:hover {text-decoration:underline}
                .fullpage TABLE.ellipsis {width:100%;max-width:700px}
                .fullpage TABLE.ellipsis TD:first-child {text-overflow:ellipsis;white-space:nowrap;overflow:hidden;max-width:0;font-size:0.8em}
                .fullpage TABLE.ellipsis TD:last-child {width:1%;text-align:right}
            </style>
        </section>
@endsection
