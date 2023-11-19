<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\MedicineAddRequest;
use App\Http\Requests\MedicineViewRequest;
use App\Services\MedicineService;

class MedicineController extends Controller
{
    public function add(MedicineAddRequest $request){
        $data=$request->only('name','parentId','price');
        if(!(new MedicineService())->save($data)){
            throw new \Exception('Failed to save');
        }
        return response()->json([
            'error'=>0,
            'msg'=>'Save Successfully'
        ]);
    }
    public function edit(MedicineAddRequest $request,$id){
        $data=$request->only('id','name','parentId','price');
        if(!(new MedicineService())->update($data,['id'=>$id])){
            throw new \Exception('Failed to update');
        }
        return response()->json([
            'error'=>0,
            'msg'=>'update Successfully'
        ]);
    }
    public function view(MedicineViewRequest $request,$id=null){
        $data=$request->only('name','parentId','price');
        $medicines=(new MedicineService())->getList();
        if($id != null){
            $medicines=(new MedicineService())->getOne($id);
        }
        if($request->has('search')){
            $medicines = (new MedicineService())->getList(['keyword'=>$request->get('search')]);
        }
        foreach ($data as $index=>$item){
            $medicines = (new MedicineService())->getList([$index=>$item]);
        }
        return response()->json([
            'error'=>0,
            'medicines'=>$medicines
        ]);
    }
    public function delete($id){
        if(!(new MedicineService())->delete(['id'=>$id])){
            throw new \Exception('failed to delete');
        }
        return response()->json([
            'error'=>0,
            'msg'=>'Delete Successfully'
        ]);
    }
}
