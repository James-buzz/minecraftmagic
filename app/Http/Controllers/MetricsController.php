<?php

namespace App\Http\Controllers;

use App\Facades\Prometheus;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Prometheus\RenderTextFormat;
use Throwable;

class MetricsController extends Controller
{
    /**
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
