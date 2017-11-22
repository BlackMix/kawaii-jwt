<?php

namespace App\Api\V1\Controllers\Admin\Roles;

use App\Permission,
    Illuminate\Http\Request,
    App\Api\V1\Controllers\Controller,
    Auth;

class PermissionsList extends Controller
{
    /**
     * instance.
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth:api']);
    }

     /**
     * @return array list roles 
     */
    public function list(Request $request){

        if (!Auth::User()->ability(['admin.permissions'], ['read'])):
            return response()->json(['body' => ['message' => __('lang.admin.permissions.read')]]);
        endif;

        $limit = 20;

        if($request->input('limit')):
            $limit = $request->input('limit');
        endif;

        $permissions = Permission::withCount('roles')->paginate($limit);

        return response()->json([
            'body' => $permissions->items(),
            'meta' => [
                'limit'   => $permissions->perPage(),
                'page'    => $permissions->currentPage(),
                'total'   => $permissions->total(),
                'last'    => $permissions->lastPage()
            ],
                'status'  => [
                'code' => 200
            ]
        ], 200);
    }
}