        <form method="get" style="text-align:center">
            Show
            <select name="density" onchange="this.form.submit()">
                @foreach([
                    'Per minute' => 60,
                    'Hourly' => 3600,
                    'Daily' => 3600*24,
                    'Weekly' => 3600*24*7,
                ] as $key => $value)
                <option value="{{ $value }}"{{ (request()->input('density') ?: config('pageviews.default_density')) == $value ? ' selected' : '' }}>{{ $key }}</option>
                @endforeach
            </select>
            &nbsp; Since
            <select name="from" onchange="this.form.submit()">
                @foreach([
                    'Start' => '1970-01-01',
                    'Today' => 'today',
                    'Yesterday' => 'yesterday',
                    '2 days ago',
                    '3 days ago',
                    '7 days ago',
                    '14 days ago',
                    '30 days ago',
                    '60 days ago',
                    '365 days ago',
                ] as $key => $value)
                <option value="{{ $value }}"{{ (request()->input('from') ?: config('pageviews.default_from')) == $value ? ' selected' : '' }}>{{ is_numeric($key) ? $value : $key }}</option>
                @endforeach
            </select>
        </form>
