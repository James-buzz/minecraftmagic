<?php

namespace App\Http\Controllers\Prometheus;

use App\Facades\Prometheus;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Prometheus\RenderTextFormat;
use Throwable;

class MetricsController extends Controller
{
    /**
     * Display the Prometheus metrics.
     *
     * @throws Throwable
     */
    public function __invoke(Request $request): Response
    {
        $formatter = new RenderTextFormat;

        return response(
            $formatter->render(Prometheus::getMetricFamilySamples()),
            200,
            [
                'Content-Type' => RenderTextFormat::MIME_TYPE,
            ]
        );
    }
}
