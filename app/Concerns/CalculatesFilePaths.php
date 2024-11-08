<?php

namespace App\Concerns;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\File;

trait CalculatesFilePaths
{
    /**
     * Generate a file path based on User, model and file name.
     */
    protected function calculatePath(User $user, Model $model, string $fileName): string
    {
        $name = File::name($fileName);
        $extension = File::extension($fileName);
        $date = Carbon::now()->format('Y-m-d');

        return implode('/', [
            $user->id,
            $model->getTable(),
            $model->id,
            $date,
            $name.'.'.$extension,
        ]);
    }
}
