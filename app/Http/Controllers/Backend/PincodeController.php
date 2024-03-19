<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\Pincode;

class PincodeController extends Controller
{
	//pincode page load
    public function getPincodePageLoad() {

		$statuslist = DB::table('tp_status')->orderBy('id', 'asc')->get();
		
		$datalist = DB::table('pincode')
			->join('tp_status', 'pincode.is_publish', '=', 'tp_status.id')
			->select('pincode.*', 'tp_status.status')
			->orderBy('pincode.id','desc')
			->paginate(20);

        return view('backend.pincode', compact('statuslist', 'datalist'));
    }
	
	//Get data for pincode Pagination
	public function getPincodeTableData(Request $request){

		$search = $request->search;
		
		if($request->ajax()){

			if($search != ''){
				
				$datalist = DB::table('pincode')
					->join('tp_status', 'pincode.is_publish', '=', 'tp_status.id')
					->select('pincode.*', 'tp_status.status')
					->where(function ($query) use ($search){
						$query->where('pincode_name', 'like', '%'.$search.'%');
					})
					->orderBy('pincode.id','desc')
					->paginate(20);
			}else{
				
				$datalist = DB::table('pincode')
					->join('tp_status', 'pincode.is_publish', '=', 'tp_status.id')
					->select('pincode.*', 'tp_status.status')
					->orderBy('pincode.id','desc')
					->paginate(20);
			}

			return view('backend.partials.pincode_table', compact('datalist'))->render();
		}
	}
	
	//Save data for pincode
    public function savePincodeData(Request $request){
		$res = array();
		
		$id = $request->input('RecordId');
		$pincode_name = $request->input('pincode_name');
		$is_publish = $request->input('is_publish');
		
		$validator_array = array(
			'pincode_name' => $request->input('pincode_name')
		);
		
		$validator = Validator::make($validator_array, [
			'pincode_name' => 'required|max:191'
		]);

		$errors = $validator->errors();

		if($errors->has('pincode_name')){
			$res['msgType'] = 'error';
			$res['msg'] = $errors->first('pincode_name');
			return response()->json($res);
		}

		$data = array(
			'pincode_name' => $pincode_name,
			'is_publish' => $is_publish
		);

		if($id ==''){
			$response = Pincode::create($data);
			if($response){
				$res['msgType'] = 'success';
				$res['msg'] = __('New Data Added Successfully');
			}else{
				$res['msgType'] = 'error';
				$res['msg'] = __('Data insert failed');
			}
		}else{
			$response = Pincode::where('id', $id)->update($data);
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
	
	//Get data for pincode by id
    public function getPincodeById(Request $request){

		$id = $request->id;
		
		$data = Pincode::where('id', $id)->first();
		
		return response()->json($data);
	}
	
	//Delete data for pincode
	public function deletePincode(Request $request){
		
		$res = array();

		$id = $request->id;

		if($id != ''){
			$response = Pincode::where('id', $id)->delete();
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
	
	//Bulk Action for pincode
	public function bulkActionPincode(Request $request){
		
		$res = array();

		$idsStr = $request->ids;
		$idsArray = explode(',', $idsStr);
		
		$BulkAction = $request->BulkAction;

		if($BulkAction == 'publish'){
			$response = Pincode::whereIn('id', $idsArray)->update(['is_publish' => 1]);
			if($response){
				$res['msgType'] = 'success';
				$res['msg'] = __('Data Updated Successfully');
			}else{
				$res['msgType'] = 'error';
				$res['msg'] = __('Data update failed');
			}
			
		}elseif($BulkAction == 'draft'){
			
			$response = Pincode::whereIn('id', $idsArray)->update(['is_publish' => 2]);
			if($response){
				$res['msgType'] = 'success';
				$res['msg'] = __('Data Updated Successfully');
			}else{
				$res['msgType'] = 'error';
				$res['msg'] = __('Data update failed');
			}
			
		}elseif($BulkAction == 'delete'){
			$response = Pincode::whereIn('id', $idsArray)->delete();
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
