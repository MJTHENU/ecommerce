<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\District;

class DistrictController extends Controller
{
	//District page load
    public function getDistrictPageLoad() {

		$statuslist = DB::table('tp_status')->orderBy('id', 'asc')->get();
		
		$datalist = DB::table('district')
			->join('tp_status', 'district.is_publish', '=', 'tp_status.id')
			->select('district.*', 'tp_status.status')
			->orderBy('district.id','desc')
			->paginate(20);

        return view('backend.district', compact('statuslist', 'datalist'));
    }
	
	//Get data for district Pagination
	public function getDistrictTableData(Request $request){

		$search = $request->search;
		
		if($request->ajax()){

			if($search != ''){
				
				$datalist = DB::table('district')
					->join('tp_status', 'district.is_publish', '=', 'tp_status.id')
					->select('district.*', 'tp_status.status')
					->where(function ($query) use ($search){
						$query->where('district_name', 'like', '%'.$search.'%');
					})
					->orderBy('district.id','desc')
					->paginate(20);
			}else{
				
				$datalist = DB::table('district')
					->join('tp_status', 'district.is_publish', '=', 'tp_status.id')
					->select('district.*', 'tp_status.status')
					->orderBy('district.id','desc')
					->paginate(20);
			}

			return view('backend.partials.district_table', compact('datalist'))->render();
		}
	}
	
	//Save data for district
    public function saveDistrictData(Request $request){
		$res = array();
		
		$id = $request->input('RecordId');
		$district_name = $request->input('district_name');
		$is_publish = $request->input('is_publish');
		
		$validator_array = array(
			'district_name' => $request->input('district_name')
		);
		
		$validator = Validator::make($validator_array, [
			'district_name' => 'required|max:191'
		]);

		$errors = $validator->errors();

		if($errors->has('district_name')){
			$res['msgType'] = 'error';
			$res['msg'] = $errors->first('district_name');
			return response()->json($res);
		}

		$data = array(
			'district_name' => $district_name,
			'is_publish' => $is_publish
		);

		if($id ==''){
			$response = District::create($data);
			if($response){
				$res['msgType'] = 'success';
				$res['msg'] = __('New Data Added Successfully');
			}else{
				$res['msgType'] = 'error';
				$res['msg'] = __('Data insert failed');
			}
		}else{
			$response = District::where('id', $id)->update($data);
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
	
	//Get data for district by id
    public function getDistrictById(Request $request){

		$id = $request->id;
		
		$data = District::where('id', $id)->first();
		
		return response()->json($data);
	}
	
	//Delete data for district
	public function deleteDistrict(Request $request){
		
		$res = array();

		$id = $request->id;

		if($id != ''){
			$response = District::where('id', $id)->delete();
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
	
	//Bulk Action for District
	public function bulkActionDistrict(Request $request){
		
		$res = array();

		$idsStr = $request->ids;
		$idsArray = explode(',', $idsStr);
		
		$BulkAction = $request->BulkAction;

		if($BulkAction == 'publish'){
			$response = District::whereIn('id', $idsArray)->update(['is_publish' => 1]);
			if($response){
				$res['msgType'] = 'success';
				$res['msg'] = __('Data Updated Successfully');
			}else{
				$res['msgType'] = 'error';
				$res['msg'] = __('Data update failed');
			}
			
		}elseif($BulkAction == 'draft'){
			
			$response = District::whereIn('id', $idsArray)->update(['is_publish' => 2]);
			if($response){
				$res['msgType'] = 'success';
				$res['msg'] = __('Data Updated Successfully');
			}else{
				$res['msgType'] = 'error';
				$res['msg'] = __('Data update failed');
			}
			
		}elseif($BulkAction == 'delete'){
			$response = District::whereIn('id', $idsArray)->delete();
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
