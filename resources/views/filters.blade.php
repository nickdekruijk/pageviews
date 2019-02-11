        <form method="get" style="text-align:center">
            <select name="density" onchange="this.form.submit()">
                @foreach([
                    'Per minute' => 60,
                    'Hourly' => 3600,
                    'Daily' => 3600*24,
                    'Weekly' => 3600*24*7,
                ] as $key => $value)
                <option value="{{ $value }}"{{ (request()->input('density') ?: 24*3600) == $value ? ' selected' : '' }}>{{ $key }}</option>
                @endforeach
            </select>
        </form>
