<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\Locality;

class LocalityController extends Controller
{
	//Locality page load
    public function getLocalityPageLoad() {

		$statuslist = DB::table('tp_status')->orderBy('id', 'asc')->get();
		
		$datalist = DB::table('locality')
			->join('tp_status', 'locality.is_publish', '=', 'tp_status.id')
			->select('locality.*', 'tp_status.status')
			->orderBy('locality.id','desc')
			->paginate(20);

        return view('backend.locality', compact('statuslist', 'datalist'));
    }
	
	//Get data for Locality Pagination
	public function getLocalityTableData(Request $request){

		$search = $request->search;
		
		if($request->ajax()){

			if($search != ''){
				
				$datalist = DB::table('locality')
					->join('tp_status', 'locality.is_publish', '=', 'tp_status.id')
					->select('locality.*', 'tp_status.status')
					->where(function ($query) use ($search){
						$query->where('locality_name', 'like', '%'.$search.'%');
					})
					->orderBy('locality.id','desc')
					->paginate(20);
			}else{
				
				$datalist = DB::table('locality')
					->join('tp_status', 'locality.is_publish', '=', 'tp_status.id')
					->select('locality.*', 'tp_status.status')
					->orderBy('locality.id','desc')
					->paginate(20);
			}

			return view('backend.partials.locality_table', compact('datalist'))->render();
		}
	}
	
	//Save data for Locality
    public function saveLocalityData(Request $request){
		$res = array();
		
		$id = $request->input('RecordId');
		$locality_name = $request->input('locality_name');
		$is_publish = $request->input('is_publish');
		
		$validator_array = array(
			'locality_name' => $request->input('locality_name')
		);
		
		$validator = Validator::make($validator_array, [
			'locality_name' => 'required|max:191'
		]);

		$errors = $validator->errors();

		if($errors->has('locality_name')){
			$res['msgType'] = 'error';
			$res['msg'] = $errors->first('locality_name');
			return response()->json($res);
		}

		$data = array(
			'locality_name' => $locality_name,
			'is_publish' => $is_publish
		);

		if($id ==''){
			$response = Locality::create($data);
			if($response){
				$res['msgType'] = 'success';
				$res['msg'] = __('New Data Added Successfully');
			}else{
				$res['msgType'] = 'error';
				$res['msg'] = __('Data insert failed');
			}
		}else{
			$response = Locality::where('id', $id)->update($data);
			if($response){
				$res['msgType'] = 'success';
				$res['msg'] = __('Data Updated Successfully');
			}else{
				$res['msgType'] = 'error';
				$res['msg'] = __('Data update failed');
			}
		}
		
		return response()->json($res);
    }
	
	//Get data for locality by id
    public function getLocalityById(Request $request){

		$id = $request->id;
		
		$data = Locality::where('id', $id)->first();
		
		return response()->json($data);
	}
	
	//Delete data for Locality
	public function deleteLocality(Request $request){
		
		$res = array();

		$id = $request->id;

		if($id != ''){
			$response = Locality::where('id', $id)->delete();
			if($response){
				$res['msgType'] = 'success';
				$res['msg'] = __('Data Removed Successfully');
			}else{
				$res['msgType'] = 'error';
				$res['msg'] = __('Data remove failed');
			}
		}
		
		return response()->json($res);
	}
	
	//Bulk Action for Locality
	public function bulkActionLocality(Request $request){
		
		$res = array();

		$idsStr = $request->ids;
		$idsArray = explode(',', $idsStr);
		
		$BulkAction = $request->BulkAction;

		if($BulkAction == 'publish'){
			$response = Locality::whereIn('id', $idsArray)->update(['is_publish' => 1]);
			if($response){
				$res['msgType'] = 'success';
				$res['msg'] = __('Data Updated Successfully');
			}else{
				$res['msgType'] = 'error';
				$res['msg'] = __('Data update failed');
			}
			
		}elseif($BulkAction == 'draft'){
			
			$response = Locality::whereIn('id', $idsArray)->update(['is_publish' => 2]);
			if($response){
				$res['msgType'] = 'success';
				$res['msg'] = __('Data Updated Successfully');
			}else{
				$res['msgType'] = 'error';
				$res['msg'] = __('Data update failed');
			}
			
		}elseif($BulkAction == 'delete'){
			$response = Locality::whereIn('id', $idsArray)->delete();
			if($response){
				$res['msgType'] = 'success';
				$res['msg'] = __('Data Removed Successfully');
			}else{
				$res['msgType'] = 'error';
				$res['msg'] = __('Data remove failed');
			}
		}
		
		return response()->json($res);
	}
}
