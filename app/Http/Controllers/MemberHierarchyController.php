<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class MemberHierarchyController extends Controller
{
    public function sponsorshipTree(Request $request)
    {
        $user = Auth::user();
        \Log::debug('Genealogy API - Auth user', ['user' => $user]);
        $depth = (int) $request->query('depth', 3);

        // Recursive function to build the tree
        $buildTree = function ($user, $depth) use (&$buildTree) {
            if ($depth < 0 || !$user) return null;
            $children = $user->directMembers()->select('id', 'username', 'email', 'rank_name', 'status')->get();
            return [
                'id' => $user->id,
                'username' => $user->username,
                'email' => $user->email,
                'rank_name' => $user->rank_name,
                'status' => $user->status,
                'children' => $children->map(fn($child) => $buildTree($child, $depth - 1))->filter()->values(),
            ];
        };

        $tree = $buildTree($user, $depth);
        \Log::debug('Genealogy API - Tree', ['tree' => $tree]);
        return response()->json(['data' => $tree]);
    }
}
