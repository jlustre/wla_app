<?php

namespace App\Livewire\Profile;

use App\Models\Activity;
use App\Models\Company;
use App\Models\InviteCode;
use App\Models\Prospect;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;

class ProfilePage extends Component
{
    use WithFileUploads;

    public $newAvatar = null;

    public ?string $statusMessage = null;

    public array $profile = [];
    public array $stats = [];
    public array $referral = [];
    public array $inviteCodeHistory = [];
    public array $mlmCompanies = [];
    public array $recentMembers = [];
    public array $activities = [];
    public array $prospects = [];
    public array $quickActions = [];
    public int $directReferralsCount = 0;
    public int $teamSize = 0;

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
            'avatar' => $this->avatarUrl($memberProfile?->avatar, $user?->username ?? 'Member'),
            'has_avatar' => (bool) $memberProfile?->avatar,
            'join_date' => optional($memberProfile?->membership_started_at ?? $user?->created_at)->format('F j, Y') ?? '—',
            'sponsor_name' => $user?->sponsor?->username ?? 'Unassigned',
            'city' => $memberProfile?->city ?: 'Not set',
            'phone' => $memberProfile?->phone_number ?: 'Not set',
            'role' => Str::headline((string) ($user?->getRoleNames()->first() ?? 'member')),
            'completion' => (int) ($memberProfile?->completion_percentage ?? 0),
        ];

        $this->directReferralsCount = $user ? $user->directMembers()->count() : 0;
        $this->teamSize = $user?->downlineCount() ?? 0;

        $companyAffiliations = $user
            ? $user->companyAffiliations()->get()
            : collect();
        $affiliationByCompany = $companyAffiliations->keyBy('company_id');
        $joinedCount = $companyAffiliations->where('status', 'active')->count();
        $pendingCount = $companyAffiliations->where('status', 'pending')->count();
        $totalCompanies = Company::count();

        $prospectsQuery = $user ? Prospect::where('user_id', $user->id) : null;
        $activeProspectsCount = $prospectsQuery
            ? (clone $prospectsQuery)->whereIn('hotness', ['Hot', 'Warm'])->count()
            : 0;

        $this->stats = [
            ['title' => 'Direct Referrals', 'value' => (string) $this->directReferralsCount, 'change' => 'People you personally invited', 'icon' => 'users', 'accent' => 'blue'],
            ['title' => 'Companies Joined', 'value' => $joinedCount.' of '.$totalCompanies, 'change' => $pendingCount.' pending decision', 'icon' => 'folder', 'accent' => 'gold'],
            ['title' => 'Team Size', 'value' => (string) $this->teamSize, 'change' => 'Across all levels', 'icon' => 'network', 'accent' => 'emerald'],
            ['title' => 'Active Prospects', 'value' => (string) $activeProspectsCount, 'change' => 'Hot or warm right now', 'icon' => 'clock', 'accent' => 'slate'],
        ];

        $activeInviteCode = $user ? InviteCode::currentFor($user) : null;

        $this->referral = [
            'link' => $activeInviteCode ? url('/register?ref='.$activeInviteCode->code) : url('/register'),
            'sponsor_name' => $this->profile['sponsor_name'],
            'join_date' => $this->profile['join_date'],
            'referral_code' => $activeInviteCode?->code,
            'expires_at' => $activeInviteCode?->expires_at,
        ];

        $this->inviteCodeHistory = $user ? $this->loadInviteCodeHistory($user) : [];

        $badgeColors = ['emerald', 'cyan', 'violet', 'amber', 'rose'];

        $this->mlmCompanies = Company::query()
            ->orderBy('name')
            ->get()
            ->values()
            ->map(function (Company $company, int $index) use ($affiliationByCompany, $badgeColors) {
                $affiliation = $affiliationByCompany->get($company->id);
                $isJoined = $affiliation?->status === 'active';
                $isPending = $affiliation?->status === 'pending';

                return [
                    'name' => $company->name,
                    'tagline' => $company->tagline ?: $company->category,
                    'status' => $isJoined ? 'Joined' : ($isPending ? 'Pending' : 'Not Joined'),
                    'joined_at' => $isJoined && $affiliation->joined_at ? 'Joined '.$affiliation->joined_at->format('M d, Y') : null,
                    'description' => $company->short_description ?: ($company->full_description ?: 'No description provided yet.'),
                    'badge_color' => $badgeColors[$index % count($badgeColors)],
                    'logo_letter' => Str::upper(Str::substr($company->name, 0, 1)),
                    'action_label' => $isJoined ? 'View Details' : ($isPending ? 'View Application' : 'Join Now'),
                    'action_style' => $isJoined ? 'light' : ($isPending ? 'outline' : 'gradient'),
                    'footer_note' => $isPending ? 'Pending approval' : null,
                ];
            })
            ->all();

        $this->recentMembers = $user
            ? $user->directMembers()
                ->latest()
                ->take(4)
                ->get()
                ->map(fn (User $member) => [
                    'name' => $member->username,
                    'email' => $member->email,
                    'date' => optional($member->created_at)->diffForHumans() ?? 'Recently',
                    'image' => $member->profile?->avatar
                        ? asset('storage/'.$member->profile->avatar)
                        : 'https://ui-avatars.com/api/?name='.urlencode($member->username).'&background=0b1730&color=ffffff&size=100',
                ])
                ->all()
            : [];

        $this->activities = $user
            ? $user->activities()
                ->latest('created_at')
                ->take(4)
                ->get()
                ->map(fn (Activity $activity) => [
                    'type' => $activity->type,
                    'title' => $activity->title,
                    'message' => $activity->description ?: '',
                    'time' => optional($activity->created_at)->diffForHumans() ?? 'Recently',
                ])
                ->all()
            : [];

        $this->prospects = $prospectsQuery
            ? (clone $prospectsQuery)
                ->latest('updated_at')
                ->take(5)
                ->get()
                ->map(fn (Prospect $prospect) => [
                    'name' => trim($prospect->first_name.' '.$prospect->last_name),
                    'status' => $prospect->hotness ?: 'N/A',
                    'last_contact' => optional($prospect->updated_at)->diffForHumans() ?? 'Unknown',
                ])
                ->all()
            : [];

        $this->quickActions = [
            ['label' => 'Follow up with warm prospects', 'value' => $activeProspectsCount.' pending', 'route' => 'member.sponsored-members'],
            ['label' => 'Review your downline', 'value' => $this->teamSize.' members', 'route' => 'member.genealogy'],
            ['label' => 'Explore open companies', 'value' => max($totalCompanies - $joinedCount, 0).' available', 'route' => 'member.resources'],
            ['label' => 'Update profile settings', 'value' => $this->profile['completion'].'% complete', 'route' => 'member.settings.profile'],
        ];
    }

    public function updatedNewAvatar(): void
    {
        $this->validateOnly('newAvatar', [
            'newAvatar' => ['image', 'max:2048'],
        ]);

        $user = auth()->user();

        if (! $user || ! $this->newAvatar) {
            return;
        }

        $oldAvatar = $user->profile?->avatar;
        $path = $this->newAvatar->store('avatars', 'public');

        $user->profile()->updateOrCreate(
            ['user_id' => $user->id],
            ['avatar' => $path]
        );

        if ($oldAvatar) {
            Storage::disk('public')->delete($oldAvatar);
        }

        $this->profile['avatar'] = $this->avatarUrl($path, $user->username);
        $this->profile['has_avatar'] = true;
        $this->newAvatar = null;

        $this->statusMessage = 'Profile photo updated.';
        $this->dispatch('avatar-updated');
    }

    public function removeAvatar(): void
    {
        $user = auth()->user();

        if (! $user) {
            return;
        }

        $oldAvatar = $user->profile?->avatar;

        if ($oldAvatar) {
            Storage::disk('public')->delete($oldAvatar);
        }

        $user->profile()->updateOrCreate(
            ['user_id' => $user->id],
            ['avatar' => null]
        );

        $this->profile['avatar'] = $this->avatarUrl(null, $user->username);
        $this->profile['has_avatar'] = false;

        $this->statusMessage = 'Profile photo removed.';
        $this->dispatch('avatar-updated');
    }

    protected function avatarUrl(?string $path, string $username): string
    {
        return $path
            ? asset('storage/'.$path)
            : 'https://ui-avatars.com/api/?name='.urlencode($username).'&background=0b1730&color=ffffff&size=256';
    }

    public function copyReferralLink(): void
    {
        $this->dispatch('copy-to-clipboard', value: $this->referral['link']);
        session()->flash('copied', 'Referral link copied.');
    }

    public function regenerateInviteLink(): void
    {
        $user = auth()->user();

        if (! $user) {
            return;
        }

        $code = InviteCode::regenerateFor($user);

        $this->referral['link'] = url('/register?ref='.$code->code);
        $this->referral['referral_code'] = $code->code;
        $this->referral['expires_at'] = $code->expires_at;
        $this->inviteCodeHistory = $this->loadInviteCodeHistory($user);

        $this->statusMessage = 'A new single-use invite link was generated.';
    }

    protected function loadInviteCodeHistory(User $user): array
    {
        return InviteCode::where('user_id', $user->id)
            ->with('usedBy')
            ->latest()
            ->take(8)
            ->get()
            ->map(fn (InviteCode $code) => [
                'code' => $code->code,
                'status' => $code->effectiveStatus(),
                'created_at' => $code->created_at->format('M j, Y'),
                'expires_at' => $code->expires_at->format('M j, Y g:ia'),
                'used_at' => $code->used_at?->format('M j, Y g:ia'),
                'used_by' => $code->usedBy?->username,
            ])
            ->all();
    }

    public function companyAction(string $companyName): void
    {
        $this->dispatch('company-action', company: $companyName);
    }

    public function inviteStatusPillClass(string $status): string
    {
        return match ($status) {
            'active' => 'bg-emerald-50 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-300',
            'used' => 'bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-300',
            'expired' => 'bg-amber-50 text-amber-700 dark:bg-amber-500/10 dark:text-amber-300',
            'revoked' => 'bg-rose-50 text-rose-700 dark:bg-rose-500/10 dark:text-rose-300',
            default => 'bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-300',
        };
    }

    public function getStatusPillClass(string $status): string
    {
        return match ($status) {
            'Hot' => 'bg-rose-100 text-rose-700 dark:bg-rose-500/10 dark:text-rose-300',
            'Warm' => 'bg-amber-100 text-amber-700 dark:bg-amber-500/10 dark:text-amber-300',
            'Cold' => 'bg-cyan-100 text-cyan-700 dark:bg-cyan-500/10 dark:text-cyan-300',
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
