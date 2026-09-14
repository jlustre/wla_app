<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class HierarchyController extends Controller
{
    public function show(Request $request, User $user)
    {
        $depth = (int) $request->query('depth', 3);

        return response()->json([
            'data' => $this->buildTree($user, $depth),
        ]);
    }

    public function children(User $user)
    {
        $children = $user->directMembers()
            ->select('id', 'username', 'email', 'rank_name', 'status', 'sponsor_id')
            ->withCount('directMembers')
            ->get();

        return response()->json([
            'data' => $children,
        ]);
    }

    public function upline(User $user)
    {
        $upline = [];

        while ($user->sponsor) {
            $user = $user->sponsor;
            $upline[] = [
                'id' => $user->id,
                'username' => $user->username,
                'rank_name' => $user->rank_name,
                'status' => $user->status,
            ];
        }

        return response()->json([
            'data' => array_reverse($upline),
        ]);
    }

    public function metrics(User $user)
    {
        return response()->json([
            'data' => [
                'direct_referrals' => $user->directMembers()->count(),
                'active_direct_referrals' => $user->directMembers()->where('status', 'active')->count(),
                'inactive_direct_referrals' => $user->directMembers()->where('status', 'inactive')->count(),
            ],
        ]);
    }

    private function buildTree(User $user, int $depth)
    {
        $node = [
            'id' => $user->id,
            'username' => $user->username,
            'email' => $user->email,
            'rank_name' => $user->rank_name ?? 'Member',
            'status' => $user->status,
            'children_count' => $user->directMembers()->count(),
            'children' => [],
        ];

        if ($depth > 0) {
            $children = $user->directMembers()
                ->select('id', 'username', 'email', 'rank_name', 'status', 'sponsor_id')
                ->get();

            foreach ($children as $child) {
                $node['children'][] = $this->buildTree($child, $depth - 1);
            }
        }

        return $node;
    }
}
