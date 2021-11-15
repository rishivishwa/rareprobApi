<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\Image;
use App\Models\Category;

class ImageServices
{
    public function getImageList($request)
    {
        /*using request for query string................................ */
        $categories = request('dataType');
        $categories_id = request('categoryId');
        /*condition if request has both the condition  ..........................*/
        if ($request->has('dataType') && $request->has('categoryId')) {

            $data = DB::table('categories')
                ->join('images', 'categories.category_id', '=', 'images.category_id')
                ->where(array('categories.dataType' => $categories, 'categories.category_id' => $categories_id))
                ->get()->toArray();

            $dataTypes = DB::table('categories')
                ->get();
            $dataTypes =  $dataTypes->groupBy('dataType')->toArray();
        } 
        /*if request match this condition then ................................ */
        else if ($request->has('dataType')) {
            $data = DB::table('categories')
                ->join('images', 'categories.category_id', '=', 'images.category_id')
                ->where(array('categories.dataType' => $categories))
                ->get()->toArray();

            $dataTypes = DB::table('categories')
                ->get();
            $dataTypes =  $dataTypes->groupBy('dataType')->toArray();
        }
        /*if request match this  condition then....................................*/ 
        else if ($request->has('categoryId')) {
            $data = DB::table('categories')
                ->join('images', 'categories.category_id', '=', 'images.category_id')
                ->where(array('categories.category_id' => $categories_id))
                ->get()->toArray();

            $dataTypes = DB::table('categories')
                ->get();
            $dataTypes =  $dataTypes->groupBy('dataType')->toArray();
        } 
        else {
            $data = DB::table('categories')
                ->join('images', 'categories.category_id', '=', 'images.category_id')
                ->get()->toArray();

            $dataTypes = DB::table('categories')
                ->get();
            $dataTypes =  $dataTypes->groupBy('dataType')->toArray();
        }
        /*declearing variable  where  data will store*/ 
        $rishi = [];
        /*iterate each row of data */ 
        foreach ($data as $row1) {
            
            $data = (object) $data;
            $el    = $row1;
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
                "datatype" => $el->dataType,
                "categoryName" => $el->categoryName,
                "categoryId" => $el->category_id,
                "categoryImage" => $el->categoryImage,
                "isPremium" => $el->isPremium,
                "images" => $arr
            ];
            $rishi[$el->category_id] =     $newData;
        }
        
        $rishi == array_values($rishi);
        $santosh = [];
        
        foreach ($dataTypes as $key => $row) {
            $values =  [];
            foreach ($rishi as $r) {
                
                if ($r["datatype"] == $key) {
                    array_push($values, $r);
                }
            }
            $santosh[$key] = $values;
        }
        // return json_encode([ $santosh]);
        return ["message" => "Success", "status" => 200, "data" => $santosh];
    }
}
