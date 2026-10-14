<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CalendarEvent extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'calendar_id',
        'type',
        'title',
        'description',
        'location',
        'start_at',
        'end_at',
        'all_day',
        'completed_at',
        'color',
        'series_id',
        'recurrence_frequency',
        'recurrence_until',
        'reminder_minutes',
        'prospect_id',
        'phone_number',
        'priority',
        'meeting_link',
    ];

    protected $casts = [
        'start_at' => 'datetime',
        'end_at' => 'datetime',
        'all_day' => 'boolean',
        'completed_at' => 'datetime',
        'recurrence_until' => 'date',
    ];

    /**
     * @return array<string, array{label: string, icon: string}>
     */
    public static function typeOptions(): array
    {
        return [
            'event' => ['label' => 'Event', 'icon' => 'calendar'],
            'call' => ['label' => 'Phone Call', 'icon' => 'phone'],
            'follow_up' => ['label' => 'Follow-up', 'icon' => 'arrow-path'],
            'task' => ['label' => 'Task', 'icon' => 'check-circle'],
        ];
    }

    /**
     * Which of the type-specific fields apply to a given type — drives the
     * create/edit form's conditional fields and each listing table's extra
     * columns, so "call", "follow_up", "task" and "event" aren't identical
     * forms/tables wearing a different label.
     *
     * @return array{prospect: bool, phone: bool, priority: bool, meetingLink: bool}
     */
    public static function fieldsForType(string $type): array
    {
        return [
            'prospect' => in_array($type, ['call', 'follow_up'], true),
            'phone' => $type === 'call',
            'priority' => in_array($type, ['follow_up', 'task'], true),
            'meetingLink' => $type === 'event',
        ];
    }

    /**
     * @return array<string, string>
     */
    public static function priorityOptions(): array
    {
        return [
            '' => 'No priority',
            'low' => 'Low',
            'medium' => 'Medium',
            'high' => 'High',
        ];
    }

    public static function priorityBadgeClass(?string $priority): string
    {
        return match ($priority) {
            'high' => 'bg-rose-100 text-rose-700 dark:bg-rose-500/15 dark:text-rose-300',
            'medium' => 'bg-amber-100 text-amber-700 dark:bg-amber-500/15 dark:text-amber-300',
            'low' => 'bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-300',
            default => 'bg-slate-100 text-slate-400 dark:bg-slate-800 dark:text-slate-500',
        };
    }

    public function isCompleted(): bool
    {
        return $this->completed_at !== null;
    }

    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }

    public function prospect()
    {
        return $this->belongsTo(Prospect::class);
    }

    /**
     * Palette keyed the way "My Calendars" color swatches are offered in
     * the UI — loosely mirrors Google Calendar's named event colors.
     *
     * @return array<string, array{label: string, hex: string}>
     */
    public static function colorPalette(): array
    {
        return [
            'tomato' => ['label' => 'Tomato', 'hex' => '#e67c73'],
            'tangerine' => ['label' => 'Tangerine', 'hex' => '#f4511e'],
            'banana' => ['label' => 'Banana', 'hex' => '#f6bf26'],
            'sage' => ['label' => 'Sage', 'hex' => '#33b679'],
            'basil' => ['label' => 'Basil', 'hex' => '#0b8043'],
            'peacock' => ['label' => 'Peacock', 'hex' => '#039be5'],
            'blueberry' => ['label' => 'Blueberry', 'hex' => '#3f51b5'],
            'lavender' => ['label' => 'Lavender', 'hex' => '#7986cb'],
            'grape' => ['label' => 'Grape', 'hex' => '#8e24aa'],
            'graphite' => ['label' => 'Graphite', 'hex' => '#616161'],
        ];
    }

    public static function colorHex(?string $key): string
    {
        return static::colorPalette()[$key]['hex'] ?? static::colorPalette()['peacock']['hex'];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function calendar()
    {
        return $this->belongsTo(Calendar::class);
    }

    /**
     * The effective display color: the event's own override if set,
     * otherwise the calendar it belongs to.
     */
    public function displayColor(): string
    {
        return $this->color ?? $this->calendar?->color ?? 'peacock';
    }
}
