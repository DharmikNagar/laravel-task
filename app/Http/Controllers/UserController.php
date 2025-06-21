<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $req)
    {
        $sort = 'asc';

        if ($req->has("sort_order")) {
            $sort = $req->get("sort_order");
        }
        if (!in_array($sort, ['asc', 'desc'])) {
            return response()->json(['message' => 'Invalid sort order'], 400);
        }
        $user = User::orderBy('id', $sort);
        if($req->has("search")){
            $user = $user->where(function($query) use ($req) {
                $search = $req->get("search");
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
            });
        }
        
        $user = $user->paginate(10);
        return response()->json(['message' => 'List of users', 'data' => $user]);
    }
}
