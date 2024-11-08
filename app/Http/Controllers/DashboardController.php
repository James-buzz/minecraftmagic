<?php

namespace App\Http\Controllers;

use App\Models\Generation;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\QueryBuilder\QueryBuilder;

class DashboardController extends Controller
{
    public function __construct(
    ) {}

    /**
     * Display the dashboard.
     */
    public function index(Request $request): Response
    {
        $pagination = QueryBuilder::for(Generation::class)
            ->defaultSort('-created_at')
            ->where('user_id', $request->user()->id)
            ->where('status', 'completed')
            ->whereNotNull('file_path')
            ->with(['style', 'style.type'])
            ->select([
                'id',
                'thumbnail_file_path',
                'art_style_id',
            ])
            ->paginate(9)
            ->through(fn ($generation) => [
                'id' => $generation->id,
                'thumbnail_url' => $generation->thumbnail_url,
                'art_style' => $generation->style->name,
                'art_type' => $generation->style->type->name,
            ]);

        return Inertia::render('Dashboard', [
            'pagination' => $pagination,
        ]);
    }
}
