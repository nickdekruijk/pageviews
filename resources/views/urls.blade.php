<table class="ellipsis">
@foreach(Pageviews::urls() as $url)
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
