<?php

namespace App\Concerns;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\File;
use RuntimeException;

trait CalculatesFilePaths
{
    /**
     * Generate a file path based on User, model and file name.
     */
    protected function calculatePath(User $user, Model $model, string $fileName): string
    {
        $modelId = $model->getKey();

        if ($modelId === null) {
            throw new RuntimeException('Model must have a primary key to calculate file path');
        }

        $name = File::name($fileName);
        $extension = File::extension($fileName);
        $date = Carbon::now()->format('Y-m-d');

        return implode('/', [
            $user->id,
            $model->getTable(),
            $modelId,
            $date,
            $name.'.'.$extension,
        ]);
    }
}
