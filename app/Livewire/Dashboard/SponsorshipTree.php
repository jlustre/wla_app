<?php

namespace App\Livewire\Dashboard;

use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Livewire\Component;

class SponsorshipTree extends Component
{
    public array $tree = [];

    public array $nodesById = [];

    public array $descendantCountCache = [];

    public int $selectedNodeId = 0;

    public string $search = '';

    public int $maxDepth = 4;

    public bool $activeOnly = false;

    public function mount(): void
    {
        $user = Auth::user();

        if ($user instanceof User) {
            $this->tree = $this->makeNode($user, null, 0);
            $this->registerNode($this->tree);
            $this->loadChildrenFor($this->tree['id']);
            $this->selectedNodeId = $this->tree['id'];
        }
    }

    public function focusNode(int $nodeId): void
    {
        if ($this->ensureNodeAvailable($nodeId)) {
            $this->selectedNodeId = $nodeId;
            $this->loadChildrenFor($nodeId);
        }
    }

    public function focusParent(): void
    {
        $selectedNode = $this->selectedNode();

        if (($selectedNode['parent_id'] ?? null) !== null) {
            $this->selectedNodeId = $selectedNode['parent_id'];
        }
    }

    public function jumpToRoot(): void
    {
        $this->selectedNodeId = $this->tree['id'] ?? 0;
    }

    public function clearSearch(): void
    {
        $this->search = '';
    }

    public function updatedMaxDepth(): void
    {
        $selectedNode = $this->selectedNode();

        if ($selectedNode) {
            $this->loadChildrenFor($selectedNode['id']);
        }
    }

    public function render()
    {
        $selectedNode = $this->selectedNode();

        if ($selectedNode) {
            $selectedNode['descendant_count'] = $this->descendantCountFor($selectedNode['id']);
            $this->nodesById[$selectedNode['id']]['descendant_count'] = $selectedNode['descendant_count'];
        }

        $lineage = $selectedNode ? $this->lineageFor($selectedNode['id']) : [];
        $columns = $this->explorerColumns($lineage);
        $visibleNodes = $this->visibleNodes();
        $rootId = $this->tree['id'] ?? 0;

        return view('livewire.dashboard.sponsorship-tree', [
            'selectedNode' => $selectedNode,
            'lineage' => $lineage,
            'columns' => $columns,
            'searchResults' => $this->searchResults(),
            'miniMapNodes' => $this->miniMapNodes($columns),
            'stats' => [
                'visible_levels' => $visibleNodes->isEmpty() ? 0 : ((int) $visibleNodes->max('level') + 1),
                'visible_members' => $visibleNodes->count(),
                'active_members' => $visibleNodes->where('status', 'active')->count(),
                'focused_descendants' => (int) ($selectedNode['descendant_count'] ?? 0),
                'root_name' => $this->nodesById[$rootId]['name'] ?? 'Root',
            ],
        ]);
    }

    protected function makeNode(User $user, ?int $parentId, int $level): array
    {
        $user->loadMissing('profile');
        $directCount = (int) ($user->direct_members_count ?? $user->directMembers()->count());

        return [
            'id' => $user->id,
            'parent_id' => $parentId,
            'name' => $user->username,
            'email' => $user->email,
            'status' => (string) ($user->status?->value ?? $user->status ?? 'active'),
            'status_label' => Str::headline((string) ($user->status?->value ?? $user->status ?? 'active')),
            'level' => $level,
            'initials' => $this->initialsFor($user->username),
            'joined' => optional($user->created_at)->format('M Y') ?? 'Recently',
            'direct_count' => $directCount,
            'descendant_count' => $this->descendantCountCache[$user->id] ?? $directCount,
            'loaded_children' => false,
            'avatar' => $user->profile?->avatar,
            'city' => $user->profile?->city,
            'phone' => $user->profile?->phone_number,
            'bio' => $user->profile?->bio,
            'has_more_children' => false,
        ];
    }

    protected function registerNode(array $node): void
    {
        $merged = array_merge($this->nodesById[$node['id']] ?? [], $node);

        if (($this->tree['id'] ?? null) === $merged['id']) {
            $merged['parent_id'] = null;
            $merged['level'] = 0;
        }

        $this->nodesById[$merged['id']] = $merged;
    }

    protected function selectedNode(): ?array
    {
        if (isset($this->nodesById[$this->selectedNodeId])) {
            return $this->nodesById[$this->selectedNodeId];
        }

        return $this->tree ?: null;
    }

    protected function lineageFor(int $nodeId): array
    {
        $lineage = [];
        $currentId = $nodeId;
        $seen = [];

        while (isset($this->nodesById[$currentId]) && ! isset($seen[$currentId])) {
            $seen[$currentId] = true;
            $node = $this->nodesById[$currentId];
            array_unshift($lineage, $node);

            if (($node['parent_id'] ?? null) === null || $node['parent_id'] === $currentId) {
                break;
            }

            $currentId = $node['parent_id'];
        }

        return $lineage;
    }

    protected function explorerColumns(array $lineage): array
    {
        return collect($lineage)
            ->values()
            ->map(function (array $node, int $index) use ($lineage) {
                $this->loadChildrenFor($node['id']);
                $children = $this->visibleChildren($node);

                if ($children === [] && $index !== count($lineage) - 1) {
                    return null;
                }

                return [
                    'parent' => $node,
                    'selected_child_id' => $lineage[$index + 1]['id'] ?? null,
                    'nodes' => $children,
                ];
            })
            ->filter()
            ->values()
            ->all();
    }

    protected function searchResults(): array
    {
        if (trim($this->search) === '') {
            return [];
        }

        $term = Str::lower(trim($this->search));

        return User::query()
            ->with('profile')
            ->where(function ($query) use ($term) {
                $query->whereRaw('LOWER(username) like ?', ['%'.$term.'%'])
                    ->orWhereRaw('LOWER(email) like ?', ['%'.$term.'%']);
            })
            ->orderBy('username', 'asc')
            ->limit(20)
            ->get()
            ->map(function (User $user) {
                $path = $this->pathToRootCandidate($user);

                if ($path === []) {
                    return null;
                }

                return $this->makeNode($user, $user->sponsor_id, count($path) - 1);
            })
            ->filter()
            ->take(8)
            ->values()
            ->all();
    }

    protected function visibleNodes(): Collection
    {
        return collect($this->nodesById)
            ->filter(fn (array $node) => $node['level'] <= $this->maxDepth)
            ->filter(fn (array $node) => ! $this->activeOnly || $node['status'] === 'active')
            ->values();
    }

    protected function visibleChildren(array $node): array
    {
        return collect($this->nodesById)
            ->filter(fn (array $child) => ($child['parent_id'] ?? null) === $node['id'])
            ->filter(fn (array $child) => $child['level'] <= $this->maxDepth)
            ->filter(fn (array $child) => ! $this->activeOnly || $child['status'] === 'active')
            ->sortBy('name')
            ->values()
            ->all();
    }

    protected function miniMapNodes(array $columns): array
    {
        return collect($columns)
            ->map(fn (array $column) => [
                'parent_id' => $column['parent']['id'],
                'parent_name' => $column['parent']['name'],
                'selected_child_id' => $column['selected_child_id'],
                'count' => count($column['nodes']),
            ])
            ->all();
    }

    protected function loadChildrenFor(int $nodeId): void
    {
        $node = $this->nodesById[$nodeId] ?? null;

        if (! $node || ($node['loaded_children'] ?? false) || $node['level'] >= $this->maxDepth) {
            return;
        }

        $user = User::query()->with('profile')->find($nodeId);

        if (! $user instanceof User) {
            return;
        }

        $children = $user->directMembers()
            ->with('profile')
            ->withCount('directMembers')
            ->where('id', '!=', $user->id)
            ->orderBy('username', 'asc')
            ->take(8)
            ->get();

        foreach ($children as $child) {
            if ($child->id === $user->id) {
                continue;
            }

            $this->registerNode($this->makeNode($child, $user->id, $node['level'] + 1));
        }

        $this->nodesById[$nodeId]['loaded_children'] = true;
        $this->nodesById[$nodeId]['has_more_children'] = $node['direct_count'] > $children->count();
    }

    protected function ensureNodeAvailable(int $nodeId): bool
    {
        if (isset($this->nodesById[$nodeId])) {
            return true;
        }

        $user = User::query()->with('profile')->find($nodeId);

        if (! $user instanceof User) {
            return false;
        }

        $path = $this->pathToRootCandidate($user);

        if ($path === []) {
            return false;
        }

        foreach ($path as $level => $pathUser) {
            $parentId = $level === 0 ? null : $path[$level - 1]->id;
            $this->registerNode($this->makeNode($pathUser, $parentId, $level));
        }

        foreach ($path as $pathUser) {
            $this->loadChildrenFor($pathUser->id);
        }

        return isset($this->nodesById[$nodeId]);
    }

    protected function pathToRootCandidate(User $user): array
    {
        $rootId = $this->tree['id'] ?? 0;
        $path = [];
        $current = $user;
        $seen = [];

        while ($current instanceof User) {
            if (isset($seen[$current->id])) {
                return [];
            }

            $seen[$current->id] = true;
            array_unshift($path, $current);

            if ($current->id === $rootId) {
                return $path;
            }

            if (! $current->sponsor_id || $current->sponsor_id === $current->id) {
                return [];
            }

            $current = User::query()->with('profile')->find($current->sponsor_id);
        }

        return [];
    }

    protected function descendantCountFor(int $userId): int
    {
        if (isset($this->descendantCountCache[$userId])) {
            return $this->descendantCountCache[$userId];
        }

        $user = User::query()->find($userId);

        return $this->descendantCountCache[$userId] = $user instanceof User ? $user->downlineCount() : 0;
    }

    protected function initialsFor(string $name): string
    {
        return Str::of($name)
            ->replace(['.', '_', '-'], ' ')
            ->explode(' ')
            ->filter()
            ->take(2)
            ->map(fn (string $segment) => Str::upper(Str::substr($segment, 0, 1)))
            ->implode('');
    }
}