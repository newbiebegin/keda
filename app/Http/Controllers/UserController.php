<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

use App\Http\Resources\User as UserResource;
use App\Http\Resources\APIPaginateCollection;

use Validator;
use Illuminate\Validation\Rule;
use Exception;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $perPage = $request->per_page ? $request->per_page : 10;
        $currentPage = $request->current_page ? $request->current_page : 1;

        $users = User::query();
		
		if($request->has('id'))
		{
			$users->where('id', $request->id);
		}
		
		if($request->has('email'))
		{
			$users->where('email', $request->email);
		}

		if($request->has('user_type_id'))
		{
			$users->where('user_type_id', $request->user_type_id);
		}

        $users =  $users->with(['userType'])
            ->whereRelation('userType', 'name', 'like', 'Customer')
            // ->whereNull('deleted_at')
            ->withTrashed()
            ->paginate($perPage, ["*"], "page", $currentPage);
        
        $response = new APIPaginateCollection($users, UserResource::class);

        return response()->json([
            'success' => true,
            'message' => 'User Found',
            'data' => $response,
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        \DB::beginTransaction();
		
		try{
			$user = User::FindOrFail($id);
            $user->delete();
			\DB::commit();
			
		} catch(Exception $ex){
			
			\DB::rollback();
			return response()->json([
                'message' => 'Failed to delete data',
                'errors' => $ex->getMessage()
            ], 409);
        }

        return response()->json([
            'success' => true,
            'message' => 'Data deleted successfully',
            'data' => $user,
        ], 200);
    }
}
