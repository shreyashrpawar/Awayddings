<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class PreBookingSummaryStatus extends Model
{
    use HasFactory;

    /**
     * @param string $status
     * @return int
     */
    public static function getIdByStatus(string $status): int
    {
        $cacheKey = 'status_id_' . $status;

        return Cache::remember($cacheKey, now()->addDays(7), function () use ($status) {
            $statusModel = self::where('name', $status)->first();

            return $statusModel ? $statusModel->id : 0;
        });
    }

    public static function getIdsByStatus(array $statuses) : array
    {
        $cacheKey = 'status_ids_' . implode('_', $statuses);

        return Cache::remember($cacheKey, now()->addDays(7), function () use ($statuses) {
            $statusModels = self::whereIn('name', $statuses)->get();
            return $statusModels ? $statusModels->pluck('id')->toArray() : [];
        });
    }
}
