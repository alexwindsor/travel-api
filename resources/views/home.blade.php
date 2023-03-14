<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @vite('resources/css/app.css')
    <title>Travel Api</title>
    <style>
        a: {
            color: white;
        }
    </style>
</head>
<body>

<div class="p-12">

    <h1 class="text-6xl mb-5">Product Search</h1>

    <form method="get" action="/">

        <input type="hidden" name="page" value="1">

        Search : <input type="text" name="title" value="{{ $params['title'] }}" class="border border-black rounded-sm p-1">

        <br><br>
        Locale :
        <select name="geo" class="border border-black rounded-sm p-1">
            <option value="en"{{ $params['geo'] === 'en' ? ' selected' : '' }}>UK</option>
            <option value="en-ie"{{ $params['geo'] === 'en-ie' ? ' selected' : '' }}>Ireland</option>
            <option value="de-de"{{ $params['geo'] === 'de-de' ? ' selected' : '' }}>Germany</option>
        </select>

        <br><br>

        <input type="number" min="1" max="50" name="limit" value="{{ $params['limit'] }}" class="border border-black rounded-sm p-1"> Results per page

        <br><br>

        <button type="submit" class="border border-black rounded-sm p-1">SEARCH</button>

    </form>
</div>

<br><br>
<hr class="h-2 bg-black">
<br>

<div align="center">
    @if($params['error'])
        {{ $params['error'] }}
    @else
        {{ $data['meta']['total_count'] }} results found:
        <br><br>
        <table>
            @foreach($data['data'] as $row)
            <tr class="bg-gray-200 border-2 border-black">
                <td colspan="2">
                    <h2 class="text-2xl">{{ $row['title'] }}</h2>
                    <span class="text-xs"><i>updated:</i> {{ $row['updated'] }}</span>
                </td>
            </tr>

            <tr class="bg-gray-200 border-2 border-black">
                <td>
                    <img src="{{ $row['img_sml'] }}" class="rounded m-2 border border-black">
                </td>

                <td>
                    Adult from: {{ $params['currency'] . $row['price_from_adult'] }} // Child from: {{ $params['currency'] .
                    $row['price_from_child'] }}
                   @if (isset($row['rrp_adult']) && floatval($row['rrp_adult']))
                        <br>
                        <span class="text-red-500 text-sm">
                            RRP Adult: {{ $params['currency'] . floatval($row['price_from_adult']) }} //
                            RRP Child:  {{ $params['currency'] . floatval($row['price_from_adult']) }}
                        </span>
                    @endif

                    @if (count($row['price_from_all']))
                        <div class="ml-10 mt-2">
                            @foreach ($row['price_from_all'] as $price_for_all)
                                {{ $price_for_all['desc'] }}: from {{ $params['currency'] . $price_for_all['price_from'] }}
                                (rrp: {{ $params['currency'] . $price_for_all['rrp'] }})
                                <br>
                                {{ $price_for_all['type_description'] }}
                                <br>
                            @endforeach
                        </div>
                    @endif

                    @if (! empty($row['seasons']))
                        <br><br>
                        Seasons: {{ $row['seasons'] }}
                    @endif
                </td>
            </tr>

            <tr>
                <td colspan="2">&nbsp</td>
            </tr>
            @endforeach
        </table>
        <br><br>
        {!! $params['pagination'] !!}
        <br><br>
    </div>

@endif

<div class="text-center my-10">{{ $params['url'] }}</div>

</body>
</html>
