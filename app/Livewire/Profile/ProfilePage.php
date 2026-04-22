<?php

namespace App\Livewire\Profile;

use Livewire\Component;
use Illuminate\Support\Str;

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
        $this->profile = [
            'name' => 'Joey Lustre',
            'email' => 'joey@wealthlegacyalliance.com',
            'status' => 'Active Member',
            'bio' => 'Building a unified network marketing ecosystem where members recruit once, grow everywhere, and never miss an opportunity.',
            'avatar' => 'https://images.unsplash.com/photo-1560250097-0b93528c311a?auto=format&fit=crop&w=400&q=80',
            'join_date' => 'January 26, 2026',
            'sponsor_name' => 'Maria Santos',
            'referral_code' => 'JOEYLUSTRE128',
        ];

        $this->stats = [
            ['label' => 'Total Referrals', 'value' => '128', 'subtext' => '+14 this month'],
            ['label' => 'MLMs Joined', 'value' => '4', 'subtext' => '1 pending decision'],
            ['label' => 'Team Size', 'value' => '642', 'subtext' => 'Across all levels'],
        ];

        $this->referral = [
            'link' => url('/register?ref=JOEYLUSTRE128'),
            'sponsor_name' => 'Maria Santos',
            'join_date' => 'January 26, 2026',
            'referral_code' => 'JOEYLUSTRE128',
        ];

        $this->mlmCompanies = [
            [
                'name' => 'Alliance Trade Pro',
                'tagline' => 'Forex & digital assets',
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
            ['label' => 'Follow up with interested prospects', 'value' => '5 pending'],
            ['label' => 'Review missed spillovers', 'value' => '2 alerts'],
            ['label' => 'Join Prosperity Mobile', 'value' => 'Recommended'],
        ];
    }

    public function copyReferralLink(): void
    {
        $this->dispatch('copy-referral-link', url: $this->referral['link']);
        session()->flash('copied', 'Referral link copied.');
    }

    public function editProfile(): void
    {
        $this->dispatch('open-edit-profile');
    }

    public function manageCompanies(): void
    {
        $this->dispatch('open-manage-companies');
    }

    public function openSponsorTree(): void
    {
        $this->dispatch('open-sponsor-tree');
    }

    public function companyAction(string $companyName): void
    {
        $this->dispatch('company-action', company: $companyName);
    }

    public function getStatusPillClass(string $status): string
    {
        return match ($status) {
            'Joined' => 'bg-emerald-100 text-emerald-700',
            'Interested' => 'bg-amber-100 text-amber-700',
            'Contacted' => 'bg-cyan-100 text-cyan-700',
            default => 'bg-slate-100 text-slate-700',
        };
    }

    public function render()
    {
        return view('livewire.profile.profile-page');
    }
}