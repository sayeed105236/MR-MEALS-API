<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Crypt;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 10);
        $query = User::select('users.name', 'users.email', 'users.phone', 'users.balance')
        ->where('users.is_admin', 0)
        ->where('users.is_verified', 1);
        //->get();

       
        if ($request->has('search')) 
    {
        $query->where('name', 'like', '%' . $request->search . '%')
        ->orWhere('email','like', '%' . $request->search . '%')
        ->orWhere('phone','like', '%' . $request->search . '%')
        ->orWhere('referral_code','like', '%' . $request->search . '%')
        ->orWhere('sponsor','like', '%' . $request->search . '%')
        ;
    }
    //  foreach ($users as $user) {
    //     $user->password = Crypt::decryptString($user->password);
    //     $user->makeVisible('password');
    //     }

   

    // Fetch the paginated users
    $users = $query->paginate($perPage);

    // Return the paginated response
    return response()->json($users);

return response()->json(['users' => $users]);
        
    }
}
