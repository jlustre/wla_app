<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Timeline extends Model
{
    protected $fillable = [
        'prospect_id',
        'action',
        'notes',
        'action_at',
        'next_follow_up_dt',
    ];

    protected $casts = [
        'action_at' => 'datetime',
        'next_follow_up_dt' => 'datetime',
    ];

    /**
     * The kinds of activity a member can log against a prospect — drives
     * the "Activity Log" popup on the My Prospects page (icon + color per
     * type, so a phone call reads differently from a presentation).
     *
     * @return array<string, array{label: string, icon: string, color: string}>
     */
    public static function actionOptions(): array
    {
        return [
            'Added' => ['label' => 'Added to Pipeline', 'icon' => 'user-plus', 'color' => 'slate'],
            'Phone Call' => ['label' => 'Phone Call', 'icon' => 'phone', 'color' => 'sky'],
            'Text / Email' => ['label' => 'Text / Email', 'icon' => 'chat-bubble-left-right', 'color' => 'violet'],
            'Met In Person' => ['label' => 'Met In Person', 'icon' => 'users', 'color' => 'emerald'],
            'Presentation' => ['label' => 'Presentation', 'icon' => 'presentation-chart', 'color' => 'amber'],
            'Invited' => ['label' => 'Invited', 'icon' => 'link', 'color' => 'indigo'],
            'Followed Up' => ['label' => 'Followed Up', 'icon' => 'arrow-path', 'color' => 'teal'],
            'Joined' => ['label' => 'Joined', 'icon' => 'check-circle', 'color' => 'green'],
            'Closed' => ['label' => 'Closed', 'icon' => 'shield', 'color' => 'rose'],
        ];
    }

    /**
     * The funnel/kanban stages a prospect moves through, in forward order.
     * Each stage buckets one or more activity types from actionOptions()
     * (e.g. a "Phone Call" and a "Text / Email" both count as "Contacted"),
     * so the board stays simple while the activity log keeps the detail.
     * "Closed" is last and represents an exit, not forward progress.
     *
     * @return array<string, array{label: string, color: string, actions: array<int, string>}>
     */
    public static function funnelStageOptions(): array
    {
        return [
            'Added' => ['label' => 'Added', 'color' => 'slate', 'actions' => ['Added']],
            'Contacted' => ['label' => 'Contacted', 'color' => 'sky', 'actions' => ['Phone Call', 'Text / Email']],
            'Met In Person' => ['label' => 'Met In Person', 'color' => 'emerald', 'actions' => ['Met In Person']],
            'Presentation' => ['label' => 'Presentation', 'color' => 'amber', 'actions' => ['Presentation']],
            'Invited' => ['label' => 'Invited', 'color' => 'indigo', 'actions' => ['Invited']],
            'Followed Up' => ['label' => 'Followed Up', 'color' => 'teal', 'actions' => ['Followed Up']],
            'Joined' => ['label' => 'Joined', 'color' => 'green', 'actions' => ['Joined']],
            'Closed' => ['label' => 'Closed', 'color' => 'rose', 'actions' => ['Closed']],
        ];
    }

    /**
     * Which funnel stage a raw activity action belongs to. Falls back to
     * "Added" for an unrecognized or missing action, so a prospect with no
     * logged activity still has a well-defined board column.
     */
    public static function funnelStageForAction(?string $action): string
    {
        foreach (static::funnelStageOptions() as $stage => $meta) {
            if (in_array($action, $meta['actions'], true)) {
                return $stage;
            }
        }

        return 'Added';
    }

    public static function actionIcon(?string $action): string
    {
        return static::actionOptions()[$action]['icon'] ?? 'clock';
    }

    public static function actionBadgeClass(?string $action): string
    {
        return match (static::actionOptions()[$action]['color'] ?? 'slate') {
            'sky' => 'bg-sky-100 text-sky-700 dark:bg-sky-500/15 dark:text-sky-300',
            'violet' => 'bg-violet-100 text-violet-700 dark:bg-violet-500/15 dark:text-violet-300',
            'emerald' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/15 dark:text-emerald-300',
            'amber' => 'bg-amber-100 text-amber-700 dark:bg-amber-500/15 dark:text-amber-300',
            'indigo' => 'bg-indigo-100 text-indigo-700 dark:bg-indigo-500/15 dark:text-indigo-300',
            'teal' => 'bg-teal-100 text-teal-700 dark:bg-teal-500/15 dark:text-teal-300',
            'green' => 'bg-green-100 text-green-700 dark:bg-green-500/15 dark:text-green-300',
            'rose' => 'bg-rose-100 text-rose-700 dark:bg-rose-500/15 dark:text-rose-300',
            default => 'bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-300',
        };
    }

    public function prospect()
    {
        return $this->belongsTo(Prospect::class);
    }
}
