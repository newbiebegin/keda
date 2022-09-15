<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Feedback;

use App\Http\Resources\Feedback as FeedbackResource;
use App\Http\Resources\APIPaginateCollection;

use Validator;
use Illuminate\Validation\Rule;
use Exception;
use Illuminate\Support\Facades\Auth;

class FeedbackController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $perPage = $request->per_page ? $request->per_page : 10;
        $currentPage = $request->current_page ? $request->current_page : 1;

        $feedbacks = Feedback::query();
		
		if($request->has('informer_id'))
		{
			$feedbacks->where('informer_id', $request->informer_id);
		}
		
		if($request->has('customer_id'))
		{
			$feedbacks->where('customer_id', $request->customer_id);
		}

        $feedbacks =  $feedbacks->paginate($perPage, ["*"], "page", $currentPage);
        $response = new APIPaginateCollection($feedbacks, FeedbackResource::class);

        return response()->json([
            'success' => true,
            'message' => 'Feedback Found',
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
         //
        $rules = [
            'message' => 'required',
        ];
        
        $validator = Validator::make($request->all(), $rules);

        if($validator->fails())
        {
            return response()->json([
                'message' => 'The given data was invalid.',
                'errors' => $validator->errors()
            ], 422);
        }
        \DB::beginTransaction();
		
		try{
			$input = $request->all();
			
			$input['informer_id'] = Auth::id();
            $input['status'] = 'new';

            $input['type'] = 'feedback';

            if($request->has('customer_id') && $request->customer_id != null)
                $input['type'] = 'report';

			$feedback = Feedback::create($input);
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
            'data' => $feedback,
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
