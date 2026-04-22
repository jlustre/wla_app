<?php

namespace App\Helpers;

use App\Models\Prospect;

class PipelineHelper 
{
    /**
     * Get the total number of prospects for a user.
     */
    public static function getTotalProspects($userId)
    {
        return Prospect::where('user_id', $userId)->count();
    }

    public static function getAllProspectsByUser($userId)
    {
        return Prospect::where('user_id', $userId)
            ->with(['timelines' => function($q) {
                $q->orderByDesc('action_at');
            }])
            ->get()
            ->map(function($prospect) {
                $prospect->latest_stage = optional($prospect->timelines->sortByDesc('action_at')->first())->action;
                return $prospect;
            });
    }
    public static function getStagePercentage($userId, $stage)
    {
        $total = Prospect::where('user_id', $userId)->count();
        if ($total === 0) {
            return 0;
        }
        $stageCount = self::getStageProspects($userId, $stage)->count();
        return round(($stageCount / $total) * 100, 1);
    }

    public static function getStageProspects($userId, $stage)
    {
        return Prospect::where('user_id', $userId)
            ->with(['timelines' => function($q) {
                $q->orderByDesc('action_at');
            }])
            ->get()
            ->filter(function($prospect) use ($stage) {
                $latest = $prospect->timelines->sortByDesc('action_at')->first();
                return $latest && $latest->action === $stage;
            })
            ->sortByDesc(function($prospect) {
                return optional($prospect->timelines->sortByDesc('action_at')->first())->action_at;
            });
    }
}
