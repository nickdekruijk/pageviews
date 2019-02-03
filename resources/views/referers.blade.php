<table>
@foreach(Pageviews::referers(60) as $referer)
    <tr>
        <td>
            @if ($referer->referer)
            <a href="{{ ($referer->referer) }}" target="_blank">{{ $referer->referer }}</a>
            @else
            None
            @endif
        </td>
        <td>{{ $referer->count }} </td>
    </tr>
@endforeach
</table>
