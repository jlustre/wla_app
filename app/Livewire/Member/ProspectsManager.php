<?php

namespace App\Livewire\Member;

use App\Models\Prospect;
use App\Models\Timeline;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class ProspectsManager extends Component
{
    use WithFileUploads;
    use WithPagination;

    #[Url(as: 'q', history: true)]
    public string $search = '';

    #[Url(history: true)]
    public string $sourceFilter = '';

    #[Url(history: true)]
    public string $hotnessFilter = '';

    #[Url(history: true)]
    public string $followUpFrom = '';

    #[Url(history: true)]
    public string $followUpTo = '';

    public string $sortBy = 'created_at';

    public string $sortDir = 'desc';

    public int $perPage = 10;

    // Create / edit modal
    public bool $showFormModal = false;

    public ?int $editingId = null;

    public string $first_name = '';

    public string $last_name = '';

    public string $email = '';

    public string $phone = '';

    public string $source = '';

    public string $hotness = '';

    public string $next_follow_up = '';

    public string $notes = '';

    // Delete confirm
    public ?int $deletingId = null;

    // Import modal
    public bool $showImportModal = false;

    public $importFile = null;

    public ?array $importSummary = null;

    public array $sourceOptions = [
        'Facebook', 'Instagram', 'TikTok', 'YouTube', 'Twitter / X',
        'LinkedIn', 'Referral', 'Friend', 'Family', 'Website', 'Event', 'Other',
    ];

    public array $hotnessOptions = ['Hot', 'Warm', 'Cold'];

    // Activity log modal
    public bool $showActivityModal = false;

    public ?int $activityProspectId = null;

    public ?int $editingActivityId = null;

    public string $activityAction = 'Phone Call';

    public string $activityNotes = '';

    public string $activityDate = '';

    public string $activityNextFollowUp = '';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingSourceFilter(): void
    {
        $this->resetPage();
    }

    public function updatingHotnessFilter(): void
    {
        $this->resetPage();
    }

    public function updatingFollowUpFrom(): void
    {
        $this->resetPage();
    }

    public function updatingFollowUpTo(): void
    {
        $this->resetPage();
    }

    public function updatingPerPage(): void
    {
        $this->resetPage();
    }

    public function clearFilters(): void
    {
        $this->reset(['search', 'sourceFilter', 'hotnessFilter', 'followUpFrom', 'followUpTo']);
        $this->resetPage();
    }

    public function toggleSort(string $field): void
    {
        if ($this->sortBy === $field) {
            $this->sortDir = $this->sortDir === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $field;
            $this->sortDir = 'asc';
        }
    }

    public function openCreate(): void
    {
        $this->resetForm();
        $this->editingId = null;
        $this->showFormModal = true;
    }

    public function openEdit(int $id): void
    {
        $prospect = Prospect::where('user_id', Auth::id())->findOrFail($id);

        $this->editingId = $prospect->id;
        $this->first_name = $prospect->first_name ?? '';
        $this->last_name = $prospect->last_name ?? '';
        $this->email = $prospect->email ?? '';
        $this->phone = $prospect->phone ?? '';
        $this->source = $prospect->source ?? '';
        $this->hotness = $prospect->hotness ?? '';
        $this->next_follow_up = optional($prospect->next_follow_up)->format('Y-m-d\TH:i') ?? '';
        $this->notes = $prospect->notes ?? '';
        $this->resetErrorBag();
        $this->showFormModal = true;
    }

    public function closeModal(): void
    {
        $this->showFormModal = false;
        $this->resetForm();
    }

    protected function resetForm(): void
    {
        $this->reset([
            'first_name', 'last_name', 'email', 'phone',
            'source', 'hotness', 'next_follow_up', 'notes', 'editingId',
        ]);
        $this->resetErrorBag();
    }

    protected function rules(): array
    {
        return [
            'first_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'source' => 'nullable|string|max:255',
            'hotness' => 'nullable|string|max:50',
            'next_follow_up' => 'nullable|date',
            'notes' => 'nullable|string|max:5000',
        ];
    }

    public function save(): void
    {
        $this->validate();

        if (trim($this->first_name) === '' && trim($this->last_name) === '') {
            $this->addError('first_name', 'Please provide at least a first name or a last name.');

            return;
        }

        if (trim($this->email) === '' && trim($this->phone) === '') {
            $this->addError('email', 'Please provide at least an email address or a phone number.');

            return;
        }

        $userId = Auth::id();

        $duplicate = Prospect::where('user_id', $userId)
            ->when($this->editingId, fn ($q) => $q->where('id', '!=', $this->editingId))
            ->where(function ($q) {
                $q->when(trim($this->email) !== '', fn ($qq) => $qq->orWhere('email', $this->email));
                $q->when(trim($this->phone) !== '', fn ($qq) => $qq->orWhere('phone', $this->phone));
            })
            ->exists();

        if ($duplicate) {
            $this->addError('email', 'You already have a prospect with this email or phone number.');

            return;
        }

        $data = [
            'user_id' => $userId,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email ?: null,
            'phone' => $this->phone ?: null,
            'source' => $this->source ?: null,
            'hotness' => $this->hotness ?: null,
            'next_follow_up' => $this->next_follow_up ?: null,
            'notes' => $this->notes ?: null,
        ];

        if ($this->editingId) {
            Prospect::where('user_id', $userId)->findOrFail($this->editingId)->update($data);
            session()->flash('success', 'Prospect updated successfully.');
        } else {
            $prospect = Prospect::create($data);
            Timeline::create(['prospect_id' => $prospect->id, 'action' => 'Added', 'action_at' => now()]);
            session()->flash('success', 'Prospect added successfully.');
        }

        $this->showFormModal = false;
        $this->resetForm();
    }

    public function confirmDelete(int $id): void
    {
        $this->deletingId = $id;
    }

    public function cancelDelete(): void
    {
        $this->deletingId = null;
    }

    public function delete(): void
    {
        if (! $this->deletingId) {
            return;
        }

        Prospect::where('user_id', Auth::id())->where('id', $this->deletingId)->delete();

        session()->flash('success', 'Prospect deleted.');
        $this->deletingId = null;
        $this->resetPage();
    }

    public function openActivityLog(int $prospectId): void
    {
        $prospect = Prospect::where('user_id', Auth::id())->findOrFail($prospectId);

        $this->activityProspectId = $prospect->id;
        $this->resetActivityForm();
        $this->showActivityModal = true;
    }

    public function closeActivityLog(): void
    {
        $this->showActivityModal = false;
        $this->activityProspectId = null;
        $this->resetActivityForm();
    }

    protected function resetActivityForm(): void
    {
        $this->reset(['editingActivityId', 'activityNotes', 'activityNextFollowUp']);
        $this->activityAction = 'Phone Call';
        $this->activityDate = now()->format('Y-m-d\TH:i');
        $this->resetErrorBag();
    }

    protected function activityRules(): array
    {
        return [
            'activityAction' => 'required|string|max:50',
            'activityNotes' => 'nullable|string|max:2000',
            'activityDate' => 'required|date',
            'activityNextFollowUp' => 'nullable|date',
        ];
    }

    public function editActivity(int $id): void
    {
        $activity = Timeline::whereHas('prospect', fn ($q) => $q->where('user_id', Auth::id()))
            ->where('prospect_id', $this->activityProspectId)
            ->findOrFail($id);

        $this->editingActivityId = $activity->id;
        $this->activityAction = $activity->action;
        $this->activityNotes = $activity->notes ?? '';
        $this->activityDate = optional($activity->action_at)->format('Y-m-d\TH:i') ?? now()->format('Y-m-d\TH:i');
        $this->activityNextFollowUp = optional($activity->next_follow_up_dt)->format('Y-m-d\TH:i') ?? '';
        $this->resetErrorBag();
    }

    public function cancelEditActivity(): void
    {
        $this->resetActivityForm();
    }

    public function saveActivity(): void
    {
        $this->validate($this->activityRules());

        $prospect = Prospect::where('user_id', Auth::id())->findOrFail($this->activityProspectId);

        $data = [
            'prospect_id' => $prospect->id,
            'action' => $this->activityAction,
            'notes' => $this->activityNotes !== '' ? $this->activityNotes : null,
            'action_at' => $this->activityDate,
            'next_follow_up_dt' => $this->activityNextFollowUp !== '' ? $this->activityNextFollowUp : null,
        ];

        if ($this->editingActivityId) {
            Timeline::where('prospect_id', $prospect->id)->findOrFail($this->editingActivityId)->update($data);
        } else {
            Timeline::create($data);
        }

        if ($this->activityNextFollowUp !== '') {
            $prospect->update(['next_follow_up' => $this->activityNextFollowUp]);
        }

        $this->resetActivityForm();
    }

    public function deleteActivity(int $id): void
    {
        Timeline::whereHas('prospect', fn ($q) => $q->where('user_id', Auth::id()))
            ->where('prospect_id', $this->activityProspectId)
            ->where('id', $id)
            ->delete();

        if ($this->editingActivityId === $id) {
            $this->resetActivityForm();
        }
    }

    public function openImport(): void
    {
        $this->importFile = null;
        $this->importSummary = null;
        $this->resetErrorBag();
        $this->showImportModal = true;
    }

    public function closeImport(): void
    {
        $this->showImportModal = false;
        $this->importFile = null;
        $this->importSummary = null;
    }

    public function import(): void
    {
        $this->validate([
            'importFile' => 'required|file|mimes:csv,txt|max:2048',
        ]);

        $userId = Auth::id();
        $path = $this->importFile->getRealPath();

        $handle = fopen($path, 'r');

        if (! $handle) {
            $this->addError('importFile', 'Could not read the uploaded file.');

            return;
        }

        $header = fgetcsv($handle);

        if (! $header) {
            fclose($handle);
            $this->addError('importFile', 'The file appears to be empty.');

            return;
        }

        $header = array_map(fn ($h) => Str::of($h)->trim()->lower()->snake()->toString(), $header);

        $expected = ['first_name', 'last_name', 'email', 'phone', 'source', 'hotness', 'notes'];
        $existingEmails = Prospect::where('user_id', $userId)->whereNotNull('email')->pluck('email')->map(fn ($e) => strtolower($e))->flip();
        $existingPhones = Prospect::where('user_id', $userId)->whereNotNull('phone')->pluck('phone')->flip();

        $seenInFile = [];
        $imported = 0;
        $skipped = [];
        $rowNumber = 1;

        while (($row = fgetcsv($handle)) !== false) {
            $rowNumber++;

            if (count(array_filter($row, fn ($v) => trim((string) $v) !== '')) === 0) {
                continue;
            }

            $assoc = [];
            foreach ($header as $i => $key) {
                if (in_array($key, $expected, true)) {
                    $assoc[$key] = trim($row[$i] ?? '');
                }
            }

            $firstName = $assoc['first_name'] ?? '';
            $lastName = $assoc['last_name'] ?? '';
            $email = $assoc['email'] ?? '';
            $phone = $assoc['phone'] ?? '';

            if ($firstName === '' && $lastName === '') {
                $skipped[] = "Row {$rowNumber}: missing first and last name.";

                continue;
            }

            if ($email === '' && $phone === '') {
                $skipped[] = "Row {$rowNumber}: missing email and phone.";

                continue;
            }

            if ($email !== '' && ! filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $skipped[] = "Row {$rowNumber}: invalid email address.";

                continue;
            }

            $emailKey = $email !== '' ? strtolower($email) : null;
            $dupeKey = $emailKey ?: $phone;

            if ($emailKey && isset($existingEmails[$emailKey])) {
                $skipped[] = "Row {$rowNumber}: a prospect with this email already exists.";

                continue;
            }

            if ($phone !== '' && isset($existingPhones[$phone])) {
                $skipped[] = "Row {$rowNumber}: a prospect with this phone already exists.";

                continue;
            }

            if ($dupeKey && isset($seenInFile[$dupeKey])) {
                $skipped[] = "Row {$rowNumber}: duplicate of another row in this file.";

                continue;
            }

            $importedProspect = Prospect::create([
                'user_id' => $userId,
                'first_name' => $firstName ?: null,
                'last_name' => $lastName ?: null,
                'email' => $email ?: null,
                'phone' => $phone ?: null,
                'source' => $assoc['source'] ?: null,
                'hotness' => $assoc['hotness'] ?: null,
                'notes' => $assoc['notes'] ?: null,
            ]);

            Timeline::create(['prospect_id' => $importedProspect->id, 'action' => 'Added', 'action_at' => now()]);

            $imported++;

            if ($dupeKey) {
                $seenInFile[$dupeKey] = true;
            }
            if ($emailKey) {
                $existingEmails[$emailKey] = true;
            }
            if ($phone !== '') {
                $existingPhones[$phone] = true;
            }
        }

        fclose($handle);

        $this->importSummary = [
            'imported' => $imported,
            'skipped' => count($skipped),
            'errors' => array_slice($skipped, 0, 10),
            'more_errors' => max(0, count($skipped) - 10),
        ];

        $this->importFile = null;

        if ($imported > 0) {
            $this->resetPage();
        }
    }

    protected function filterOptions(int $userId): array
    {
        return [
            'sources' => Prospect::where('user_id', $userId)->whereNotNull('source')->where('source', '!=', '')->distinct()->orderBy('source')->pluck('source')->all(),
            'hotness' => Prospect::where('user_id', $userId)->whereNotNull('hotness')->where('hotness', '!=', '')->distinct()->orderBy('hotness')->pluck('hotness')->all(),
        ];
    }

    protected function stats(int $userId): array
    {
        $base = Prospect::where('user_id', $userId);

        return [
            'total' => (clone $base)->count(),
            'hot' => (clone $base)->where('hotness', 'Hot')->count(),
            'due_this_week' => (clone $base)->whereBetween('next_follow_up', [now(), now()->addDays(7)])->count(),
            'added_this_week' => (clone $base)->where('created_at', '>=', now()->subDays(7))->count(),
        ];
    }

    protected function rows(int $userId)
    {
        $query = Prospect::query()->where('user_id', $userId);

        if (trim($this->search) !== '') {
            $term = '%'.trim($this->search).'%';
            $query->where(function ($q) use ($term) {
                $q->where('first_name', 'like', $term)
                    ->orWhere('last_name', 'like', $term)
                    ->orWhere('email', 'like', $term)
                    ->orWhere('phone', 'like', $term)
                    ->orWhere('source', 'like', $term);
            });
        }

        if ($this->sourceFilter !== '') {
            $query->where('source', $this->sourceFilter);
        }

        if ($this->hotnessFilter !== '') {
            $query->where('hotness', $this->hotnessFilter);
        }

        if ($this->followUpFrom !== '') {
            $query->whereDate('next_follow_up', '>=', $this->followUpFrom);
        }

        if ($this->followUpTo !== '') {
            $query->whereDate('next_follow_up', '<=', $this->followUpTo);
        }

        $sortable = ['first_name', 'created_at', 'next_follow_up', 'hotness'];
        $sortBy = in_array($this->sortBy, $sortable, true) ? $this->sortBy : 'created_at';

        return $query->orderBy($sortBy, $this->sortDir)->paginate($this->perPage);
    }

    public function render()
    {
        $userId = Auth::id();

        return view('livewire.member.prospects-manager', [
            'rows' => $this->rows($userId),
            'stats' => $this->stats($userId),
            'filterOptions' => $this->filterOptions($userId),
            'activityProspect' => $this->activityProspectId
                ? Prospect::where('user_id', $userId)->find($this->activityProspectId)
                : null,
            'activities' => $this->activityProspectId
                ? Timeline::where('prospect_id', $this->activityProspectId)->orderByDesc('action_at')->orderByDesc('id')->get()
                : collect(),
            'activityOptions' => Timeline::actionOptions(),
        ]);
    }
}
