<table class="ellipsis">
@foreach(Pageviews::referers() as $referer)
    @if (!in_array($referer->host, config('pageviews.hide_referers')) && $referer->count)
    <tr>
        <td>
            @if ($referer->referer)
            <a href="{{ ($referer->referer) }}" target="_blank" class="">{{ $referer->referer }}</a>
            @else
            None
            @endif
        </td>
        <td>{{ $referer->count }} </td>
    </tr>
    @endif
@endforeach
</table>
