<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;
use App\Http\Resources\Message as MessageResource;
use App\Http\Resources\APIPaginateCollection;

use Validator;
use Illuminate\Validation\Rule;
use Exception;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class MessageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $userType = Auth::user()->userType->name;
        
        $perPage = $request->per_page ? $request->per_page : 10;
        $currentPage = $request->current_page ? $request->current_page : 1;

        $messages = Message::query();
		
		if($request->has('sender_id'))
		{
			$messages->where('sender_id', $request->sender_id);
		}
		
		if($request->has('recipient_id'))
		{
			$messages->where('recipient_id', $request->recipient_id);
		}

        if($userType != 'staff')
        {
        	$messages->where(function($query) {
                $query->where('sender_id', Auth::id())
                ->orWhere('recipient_id', Auth::id());
            });
		}

        $messages =  $messages->paginate($perPage, ["*"], "page", $currentPage);
        $response = new APIPaginateCollection($messages, MessageResource::class);

        return response()->json([
            'success' => true,
            'message' => 'Message Found',
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
        $userType = Auth::user()->userType->name;
        $recipient_id = $request->recipient_id;

        $rules = [
            'recipient_id' => ['required',
            Rule::exists('users', 'id')->where(function ($query) use( $recipient_id) {
                return $query->where('id',  $recipient_id);
            })],
            'message' => ['required'],
        ];
        
        $validator = Validator::make($request->all(), $rules);

        if($validator->fails())
        {
            return response()->json([
                'message' => 'The given data was invalid.',
                'errors' => $validator->errors()
            ], 422);
        }

        if($userType != 'Staff') {
            $recipient = User::find($request->recipient_id);

            if($recipient)
            {
                if($recipient->userType->name=='Staff'){
                    return response()->json([
                        'message' => 'The given data was invalid.',
                        'errors' => [
                            "recipient_id"=> [
                                "The recipient id field does not have authorization."
                                ]
                            ],
                    ], 422);
                }
            }
        }

        \DB::beginTransaction();
		
		try{
			$input = $request->all();
			
			$input['sender_id'] = Auth::id();
            $input['sent_date'] =  date("Y-m-d H:i:s");
			$message = Message::create($input);
			\DB::commit();
			
		} catch(Exception $ex){
			
			\DB::rollback();
			return response()->json([
                'message' => 'Failed to save data',
                'errors' => $ex->getMessage()
            ], 409);
        }

        return response()->json([
            'success' => true,
            'message' => 'Data saved successfully',
            'data' => $message,
        ], 200);
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
    }
}
