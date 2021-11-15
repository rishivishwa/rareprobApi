<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Services\ImageServices;
use Facade\Ignition\QueryRecorder\Query;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\QueryBuilder\QueryBuilder;

class GetDataController extends Controller
{
    private $key;
    private $imageServices = 'ImageServices';
    public function __construct(ImageServices $imageServices)
    {
        $this->key = "A^eb#&28r*^*#%^@";
        $this->imageServices = $imageServices;
    }

    public function newdata(Request $request)
    {
        $res = $this->checkAuthorization($request);
        if (!empty($res))
            return $res;
        $response = $this->imageServices->getImageList($request);
        return response(json_encode($response), $response["status"]);
    }
    private function checkAuthorization(Request $request)
    {
        $authKeySend = $request->header('Authorization');
        if ($authKeySend !== $this->key)
            return response()->json(["error" => "UnAuthorized"], 401);
    }
    
    /*........................................................................................*/

    public function show(Request $request)
    {
        // $key = $request->key;
        $categories = request('dataType');
        $categories_id = request('categoryId');
        // $categories=$categories->category_id;
        // $categories=request('categoriesId');
        // $categ = request('category_id');

        // print_r($_GET);die;

        if ($request->has('dataType') && $request->has('categoryId'))  {

            $data = DB::table('categories')
                ->join('images', 'categories.category_id', '=', 'images.category_id')
                ->where(array('categories.dataType' => $categories,'categories.category_id'=>$categories_id))
                // ->where(array('categories.category_id'=>$categories))
                ->get()->toArray();

            $dataTypes = DB::table('categories')
                ->get();
            $dataTypes =  $dataTypes->groupBy('dataType')->toArray();
        }

        
        else if($request->has('dataType'))
        {
            $data = DB::table('categories')
            ->join('images', 'categories.category_id', '=', 'images.category_id')
            ->where(array('categories.dataType'=>$categories))
            // ->where(array('categories.category_id'=>$categories))
            ->get()->toArray();

        $dataTypes = DB::table('categories')     
        ->get();
        $dataTypes =  $dataTypes->groupBy('dataType')->toArray();
        }
        else if($request->has('categoryId'))
        {
            $data = DB::table('categories')
            ->join('images', 'categories.category_id', '=', 'images.category_id')
            // ->where(array('categories.dataType'=>$categories))
            ->where(array('categories.category_id'=>$categories_id))

            // ->where(array('categories.category_id'=>$categories))
            ->get()->toArray();

        $dataTypes = DB::table('categories')     
        ->get();
        $dataTypes =  $dataTypes->groupBy('dataType')->toArray();
        }

        else {
            $data = DB::table('categories')
                ->join('images', 'categories.category_id', '=', 'images.category_id')
                // ->where(array('categories.dataType'=>$categories))
                ->get()->toArray();

            $dataTypes = DB::table('categories')
                ->get();
            $dataTypes =  $dataTypes->groupBy('dataType')->toArray();
        }

        $rishi = [];
        // $rishi = (object) $rishi;
        foreach ($data as $row1) {

            $data = (object) $data;
            $el    = $row1;
            $el = (object) $el;
            // print_r($el);
            $arr = [];
            foreach ($data as $row) {

                $row = (object) $row;
                if ($row->categoryName == $el->categoryName) {
                    array_push($arr, [
                        "url" => $row->url,
                        "name" => $row->name,
                        "thumbnail" => $row->thumbnail,
                    ]);
                }
            }

            $newData = [
                "datatype" => $el->dataType,
                "categoryName" => $el->categoryName,
                "categoryId" => $el->category_id,
                "categoryImage" => $el->categoryImage,
                "isPremium" => $el->isPremium,
                "images" => $arr
            ];

            $rishi[$el->category_id] =     $newData;
        }
        /**                  */

        // $rishi = (array) $rishi;
        // print_r($rishi);
        


        $rishi == array_values($rishi);
        $santosh = [];
        foreach ($dataTypes as $key => $row) {

            $values =  [];
            foreach ($rishi as $r) {

                if ($r["datatype"] == $key ){
                    array_push($values, $r);
                }
                // echo trim($key);
            }
            $santosh[$key] = $values;
            /* removing empty arry from data */
            

            // if ($request->has('dataType')) {
            //     // $santosh = (object) $santosh;
            //     $santosh[$key]->dataType = $request->input('dataType')
            //     ->where('categories.dataType','neons')->get();
            //     }
        }
        if($santosh[$key]== empty($key))
            {
               array_pop($santosh[$key]);
    
            }
        return json_encode([$santosh]);
    }

    /**................................................................................. */


    public function imageData(Request $request)
    {

        $data = DB::table('categories')
            ->join('images', 'categories.category_id', '=', 'images.category_id')
            ->get()->toArray();

        $new = array_map(function ($el) use ($data) {

            $data = (object) $data;
            $el = (object) $el;

            $arr = [];
            foreach ($data as $row) {
                $row = (object) $row;
                if ($row->categoryName == $el->categoryName) {
                    array_push($arr, [
                        "url"       => $row->url,
                        "name"      => $row->name,
                        "thumbnail" => $row->thumbnail,
                    ]);
                }
            }
            $newData = [
                "dataType"      =>     $el->dataType,
                "categoryName"  =>     $el->categoryName,
                "categoryId"    =>     $el->category_id,
                "categoryImage" =>     $el->categoryImage,
                "isPremium"     =>     $el->isPremium,
                "images"        =>     $arr
            ];
            return $newData;
        }, $data);
        return json_encode(["effects" => $new]);
    }

    public function next(Request $request, Category $category)
    {
        //    $data1 = $data-->groupBy('dataType');
        $data = DB::table('categories')
            ->join('images', 'categories.category_id', '=', 'images.category_id')
            // ->select('dataType',DB::raw('count (*) as total'))
            // ->groupBy('dataType')
            // ->orderBy('dataType')
            // ->where('categories.category_id','images.category_id')
            ->get()->toArray();
        $new = array_map(function ($el) use ($data) {
            $data = (object) $data;
            $el = (object) $el;

            $arr = [];
            foreach ($data as $row) {

                $row = (object) $row;
                if ($row->categoryName == $el->categoryName) {
                    array_push($arr, [
                        "url" => $row->url,
                        "name" => $row->name,
                        "thumbnail" => $row->thumbnail,
                    ]);
                }
            }

            $newData = [
                "dataType" => $el->dataType,
                "categoryName" => $el->categoryName,
                "categoryId" => $el->category_id,
                "categoryImage" => $el->categoryImage,
                "isPremium" => $el->isPremium,
                "images" => $arr
            ];
            return $newData;
        }, $data);
        // $new = (object) $new ;
        return json_encode(["effects" => $new]);
    }

    /**................................................................................. */

}
