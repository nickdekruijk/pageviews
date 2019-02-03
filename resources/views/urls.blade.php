<table>
@foreach(Pageviews::urls(60) as $url)
    <tr>
        <td>
            @if ($url->url)
            <a href="{{ ($url->url) }}" target="_blank">{{ $url->url }}</a>
            @else
            None
            @endif
        </td>
        <td>{{ $url->count }} </td>
    </tr>
@endforeach
</table>
