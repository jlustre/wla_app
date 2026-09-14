<?php

namespace App\Livewire\Profile;

use App\Models\User;
use Illuminate\Support\Str;
use Livewire\Component;

class ProfilePage extends Component
{
    public array $profile = [];
    public array $stats = [];
    public array $referral = [];
    public array $mlmCompanies = [];
    public array $recentMembers = [];
    public array $activities = [];
    public array $prospects = [];
    public array $quickActions = [];

    public function mount(): void
    {
        /** @var User|null $user */
        $user = auth()->user();
        $memberProfile = $user?->profile;
        $status = $user?->status;
        $statusLabel = $status instanceof \BackedEnum
            ? Str::headline($status->value)
            : Str::headline((string) ($status ?? 'active'));

        $this->profile = [
            'name' => $user?->username ?? 'Member',
            'email' => $user?->email ?? '',
            'status' => $statusLabel.' Member',
            'bio' => $memberProfile?->bio ?: 'Building a unified network marketing ecosystem where members recruit once, grow everywhere, and never miss an opportunity.',
            'avatar' => $memberProfile?->avatar
                ? asset('storage/'.$memberProfile->avatar)
                : 'https://ui-avatars.com/api/?name='.urlencode($user?->username ?? 'Member').'&background=0b1730&color=ffffff&size=256',
            'join_date' => optional($memberProfile?->membership_started_at ?? $user?->created_at)->format('F j, Y') ?? '—',
            'sponsor_name' => $user?->sponsor?->username ?? 'Unassigned',
            'city' => $memberProfile?->city ?: 'Not set',
            'phone' => $memberProfile?->phone_number ?: 'Not set',
            'role' => Str::headline((string) ($user?->getRoleNames()->first() ?? 'member')),
            'completion' => (int) ($memberProfile?->completion_percentage ?? 64),
        ];

        $this->stats = [
            ['title' => 'Total Referrals', 'value' => '128', 'change' => '+14 this month', 'icon' => 'users', 'accent' => 'blue'],
            ['title' => 'Companies Joined', 'value' => '4', 'change' => '1 pending decision', 'icon' => 'folder', 'accent' => 'gold'],
            ['title' => 'Team Size', 'value' => '642', 'change' => 'Across all levels', 'icon' => 'network', 'accent' => 'emerald'],
            ['title' => 'Active Prospects', 'value' => '18', 'change' => '5 ready for follow-up', 'icon' => 'clock', 'accent' => 'slate'],
        ];

        $inviteCode = $memberProfile?->invite_code ?: Str::upper(Str::slug((string) ($user?->username ?? 'member'), '')).'128';

        $this->referral = [
            'link' => url('/register?ref='.$inviteCode),
            'sponsor_name' => $this->profile['sponsor_name'],
            'join_date' => $this->profile['join_date'],
            'referral_code' => $inviteCode,
        ];

        $this->mlmCompanies = [
            [
                'name' => 'Alliance Home Services',
                'tagline' => 'Home services',
                'status' => 'Joined',
                'joined_at' => 'Joined Feb 03, 2026',
                'description' => 'Active participation with 43 members already connected under your qualified line.',
                'badge_color' => 'emerald',
                'logo_letter' => 'A',
                'action_label' => 'View Details',
                'action_style' => 'light',
                'footer_note' => null,
            ],
            [
                'name' => 'Legacy Health Connect',
                'tagline' => 'Health and wellness',
                'status' => 'Joined',
                'joined_at' => 'Joined Feb 15, 2026',
                'description' => 'Strong growth potential with new team activity this week and increasing referral momentum.',
                'badge_color' => 'cyan',
                'logo_letter' => 'B',
                'action_label' => 'View Details',
                'action_style' => 'dark',
                'footer_note' => null,
            ],
            [
                'name' => 'Prosperity Mobile',
                'tagline' => 'Telecom & services',
                'status' => 'Not Joined',
                'joined_at' => null,
                'description' => 'Three direct referrals already joined this company. Join now to avoid more upward reassignment.',
                'badge_color' => 'violet',
                'logo_letter' => 'C',
                'action_label' => 'Join Now',
                'action_style' => 'gradient',
                'footer_note' => 'Potential spillover risk',
            ],
            [
                'name' => 'Future Rewards Club',
                'tagline' => 'Loyalty & shopping',
                'status' => 'Joined',
                'joined_at' => 'Joined Mar 01, 2026',
                'description' => 'Consistent member activity and strong residual opportunity in this vertical.',
                'badge_color' => 'amber',
                'logo_letter' => 'D',
                'action_label' => 'View Details',
                'action_style' => 'outline',
                'footer_note' => null,
            ],
            [
                'name' => 'Wealth Shield Benefits',
                'tagline' => 'Insurance & protection',
                'status' => 'Not Joined',
                'joined_at' => null,
                'description' => 'Ideal for your audience, with a strong match to your current recruitment strengths.',
                'badge_color' => 'rose',
                'logo_letter' => 'E',
                'action_label' => 'Join Now',
                'action_style' => 'dark',
                'footer_note' => 'Recommended for you',
            ],
        ];

        $this->recentMembers = [
            ['name' => 'Kevin Marquez', 'email' => 'kevin@example.com', 'date' => '2 hours ago', 'image' => 'https://i.pravatar.cc/100?img=12'],
            ['name' => 'Angela Rivera', 'email' => 'angela@example.com', 'date' => 'Yesterday', 'image' => 'https://i.pravatar.cc/100?img=32'],
            ['name' => 'Daniel Cruz', 'email' => 'daniel@example.com', 'date' => '2 days ago', 'image' => 'https://i.pravatar.cc/100?img=15'],
            ['name' => 'Sarah Lopez', 'email' => 'sarah@example.com', 'date' => '3 days ago', 'image' => 'https://i.pravatar.cc/100?img=45'],
        ];

        $this->activities = [
            [
                'type' => 'success',
                'title' => 'New referral joined under your link',
                'message' => 'Angela Rivera completed registration and is now part of your alliance team.',
                'time' => '12 minutes ago',
            ],
            [
                'type' => 'info',
                'title' => 'Joined a new MLM company',
                'message' => 'You successfully joined Future Rewards Club and unlocked additional team coverage.',
                'time' => '3 hours ago',
            ],
            [
                'type' => 'danger',
                'title' => 'Missed spillover alert',
                'message' => 'Two direct referrals joined Prosperity Mobile. You are currently not qualified in that company.',
                'time' => 'Yesterday',
            ],
            [
                'type' => 'warning',
                'title' => 'Follow-up reminder',
                'message' => 'You have 5 prospects tagged as interested and ready for a second contact.',
                'time' => '2 days ago',
            ],
        ];

        $this->prospects = [
            ['name' => 'Michael Reyes', 'status' => 'Interested', 'last_contact' => 'Today'],
            ['name' => 'Patricia Gomez', 'status' => 'Contacted', 'last_contact' => 'Yesterday'],
            ['name' => 'Joshua Lim', 'status' => 'Joined', 'last_contact' => '2 days ago'],
            ['name' => 'Emily Torres', 'status' => 'New', 'last_contact' => '3 days ago'],
        ];

        $this->quickActions = [
            ['label' => 'Follow up with interested prospects', 'value' => '5 pending', 'route' => 'member.sponsored-members'],
            ['label' => 'Review missed spillovers', 'value' => '2 alerts', 'route' => 'member.genealogy'],
            ['label' => 'Join Prosperity Mobile', 'value' => 'Recommended', 'route' => 'member.resources'],
            ['label' => 'Update profile settings', 'value' => $this->profile['completion'].'% complete', 'route' => 'member.settings.profile'],
        ];
    }

    public function copyReferralLink(): void
    {
        $this->dispatch('copy-to-clipboard', value: $this->referral['link']);
        session()->flash('copied', 'Referral link copied.');
    }

    public function companyAction(string $companyName): void
    {
        $this->dispatch('company-action', company: $companyName);
    }

    public function getStatusPillClass(string $status): string
    {
        return match ($status) {
            'Joined' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-300',
            'Interested' => 'bg-amber-100 text-amber-700 dark:bg-amber-500/10 dark:text-amber-300',
            'Contacted' => 'bg-cyan-100 text-cyan-700 dark:bg-cyan-500/10 dark:text-cyan-300',
            default => 'bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-300',
        };
    }

    public function companyBadgeClass(string $color): string
    {
        return match ($color) {
            'emerald' => 'bg-emerald-50 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-300',
            'cyan' => 'bg-cyan-50 text-cyan-700 dark:bg-cyan-500/10 dark:text-cyan-300',
            'violet' => 'bg-violet-50 text-violet-700 dark:bg-violet-500/10 dark:text-violet-300',
            'amber' => 'bg-amber-50 text-amber-700 dark:bg-amber-500/10 dark:text-amber-300',
            'rose' => 'bg-rose-50 text-rose-700 dark:bg-rose-500/10 dark:text-rose-300',
            default => 'bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-300',
        };
    }

    public function companyActionClass(string $style): string
    {
        return match ($style) {
            'dark' => 'bg-slate-950 text-white hover:bg-slate-800 dark:bg-white dark:text-slate-950',
            'gradient' => 'bg-gradient-to-r from-blue-600 to-cyan-500 text-white hover:from-blue-500 hover:to-cyan-400',
            'outline' => 'border border-slate-300 text-slate-700 hover:bg-slate-50 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800',
            default => 'bg-slate-100 text-slate-800 hover:bg-slate-200 dark:bg-slate-800 dark:text-slate-100',
        };
    }

    public function activityToneClass(string $type): string
    {
        return match ($type) {
            'success' => 'bg-emerald-500',
            'info' => 'bg-blue-500',
            'danger' => 'bg-rose-500',
            'warning' => 'bg-amber-400',
            default => 'bg-slate-400',
        };
    }

    public function render()
    {
        return view('livewire.profile.profile-page');
    }
}
