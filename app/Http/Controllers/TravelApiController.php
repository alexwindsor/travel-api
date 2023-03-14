<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;

class TravelApiController extends Controller
{


    public function index() {

        $params = [
            'title' => request('title') ?? null,
            'geo' => request('geo') ?? null,
            'limit' => intval(request('limit')) > 0 && intval(request('limit')) < 51 ? intval(request('limit')) : 10, // defaults to 10
            'page' => intval(request('page')) > 0 ? intval(request('page')) : 1, // defaults to 1
            'currency' => null,
            'error' => null,
            'pages' => null,
            'pagination' => null,
            'url' => null
        ];

        $data = null;
        $geo_array = ['en', 'en-ie', 'de-de'];
        if (! in_array($params['geo'], $geo_array)) $params['geo'] = 'en'; // default to 'en' if we don't have a valid value

        if (! $params['title']) $params['error'] = 'Please enter search criteria';
        else {
            $offset = $params['limit'] * ($params['page'] - 1); // work out the offset from the current page and the results-per-page

            $params['url'] = 'https://global.atdtravel.com/api/products?geo=' . $params['geo'] . '&title=' . $params['title'] . '&limit=' . $params['limit'] . '&offset=' . $offset;

            // make the curl request using built-in Laravel guzzlehttp/guzzle package
            $data = Http::get('https://global.atdtravel.com/api/products', [
                'geo' => $params['geo'],
                'title' => $params['title'],
                'limit' => $params['limit'],
                'offset' => $offset
            ]);

            $params['error'] = $data->json()['err_desc'] ?? null;

            if (! $params['error']) {
                $params['currency'] = $data->json()['meta'] === 'GBP' ? '£' : '€';

                // build the pagination if there are more than one pages of results
                if ($data['meta']['limit'] < $data['meta']['total_count']) {

                    $params['pages'] = intval($data['meta']['total_count'] / $data['meta']['limit']);

                    $params['pagination'] = '';

                    for ($i = 1; $i <= $params['pages']; $i++) {
                        $params['pagination'] .= '<a href="index.php?page=' . $i . '&title=' . $params['title'] . '&geo=' . $params['geo'] . '&limit=' . $params['limit'] . '" class="inline leading-10 p-6 m-2 border border-black';
                        $params['pagination'] .= $params['page'] === $i ? ' bg-black text-white' : '';
                        $params['pagination'] .= '"> ' . $i . ' </a>';
                    }
                }
            }

        }

        return view('home', compact('data', 'params'));
    }

}
