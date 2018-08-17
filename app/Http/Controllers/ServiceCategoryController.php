<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

use App\service_category; //Modal for the category
use Validator;
use Session;
use Redirect;
use Auth;

use Illuminate\Support\Facades\Input;

class ServiceCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //Check if the search string is passed. If yes then seach according the string. Else get alll record
        if($request->get('search') && $request->get('search')!=""){
            $ServiceCategoryList = service_category::where('name', 'LIKE','%'.$request->get('search').'%')
                           ->paginate();
        }else{
            $ServiceCategoryList = service_category::where(['parentID' => 0])
                            ->orderBy('name', 'asc')
                            ->paginate();    
        }
        return view('admin.service_category.index', compact('ServiceCategoryList'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {   

        $items = service_category::pluck('name', 'id');
        $dataKey = service_category::where(['parentID' => 0])->get()->pluck('name', 'id');
        return view('admin.service_category.create', compact('items','dataKey'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

      //Check for vakidations rule
            $rules = array(
                'name' => 'required'
                
            );
            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return Redirect::back()->withErrors($validator->errors())->withInput();
            }
            else{  
               $service_category = new service_category();
               $service_category->name = $request->input('name');
               $service_category->description = ($request->input('description') == null) ? '' : $request->input('description');
               $service_category->statusID = ($request->input('statusID') == null) ? '1' : $request->input('statusID');
               $service_category->uID = Auth::id();
               $service_category->parentID = ($request->input('parentID') == null) ? 0 : $request->input('parentID');
               $service_category->key = $this->clean($request->input('name'));

               $isSaved = $service_category->save();
               if($isSaved){
                 return redirect('/admin/service_category');
               }else{
                 Session::flash('flash_message', "Error");
                 return redirect()->back();
               }
           }
    }       
   

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {   
        $ServiceCategoryList = service_category::with('parent','user')
                   ->where('id',$id)
                   ->first();
        return view('admin.service_category.show' , compact('ServiceCategoryList'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $dataSet = service_category::find($id);
        $dataKey = service_category::orderBy('name', 'asc')->get()->pluck('name', 'id');

        return view('admin.service_category.edit' , compact('dataSet','items','dataKey'));
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

        $rules = array(
            'name' => 'required'
            
        );
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator->errors())->withInput();
        }
        else{   
           $service_category = service_category::find($id);
           $service_category->name = $request->input('name');
           $service_category->description = $request->input('description');
           $service_category->statusID = $request->input('statusID');
           $service_category->parentID = ($request->input('parentID') == null) ? 0 : $request->input('parentID');
            $service_category->key = $request->input('key');
           $isSaved = $service_category->save();
           if($isSaved){
             Session::flash('flash_message', "Updated");
             return redirect('/admin/service_category');
           }
       }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $dataSet = service_category::find($id);
        $dataSet->statusID = 0;
        $isSaved = $dataSet->save();
        if($isSaved){
            Session::flash('flash_message', "Inactive");
            return redirect('/admin/service_category');
        }
    }





     function clean($string) {
   $string = strtolower(str_replace(' ', '-', $string)); // Replaces all spaces with hyphens.
   $string = preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.

   return preg_replace('/-+/', '-', $string); // Replaces multiple hyphens with single one.
    }
}
