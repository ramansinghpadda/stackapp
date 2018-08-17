<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

use App\service_catalog; //Modal for the service catalog 
use App\service_category; //Modal for the category
use Validator;
use Session;
use Redirect;
use Auth;
use App\Scripts\RandomColor;
use DB;

use Illuminate\Support\Facades\Input;

class ServiceCatalogController extends Controller
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
            $catalogList = service_catalog::where('name', 'LIKE','%'.$request->get('search').'%')
                           ->paginate();
        }else{
            $catalogList = service_catalog::where(['parentID' => 0])
                            ->orderBy('name', 'asc')
                            ->paginate();
        }
        return view('admin.service_catalog.index', compact('catalogList'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {   
        $items = service_category::orderBy('name', 'asc')->pluck('name', 'id');
        $dataKey = service_catalog::where(['parentID' => 0])->orderBy('name', 'asc')->get()->pluck('name', 'id');
        return view('admin.service_catalog.create', compact('items','dataKey'));
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
                'name' => 'required',
                'domain' => 'required|unique:service_catalogs',
                'service_key' => 'unique:service_catalogs'
                
            );
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return Redirect::back()->withErrors($validator->errors())->withInput();
            }
            else{  
               $servicecatalog = new service_catalog();
               $servicecatalog->name = $request->input('name');
               $servicecatalog->company = $request->input('company');
               $servicecatalog->catID = ($request->input('catID') == null) ? 0 : $request->input('catID');
               $servicecatalog->description = $request->input('description');
               $servicecatalog->service_key = $request->input('service_key');
               $servicecatalog->domain = $request->input('domain');
               $servicecatalog->url = $request->input('url');
               $servicecatalog->hex = RandomColor::one(array('format'=>'hex','luminosity'=>'light','hue'=>array('blue', 'green', 'red')));
               $servicecatalog->statusID = ($request->input('statusID') == null) ? '1' : $request->input('statusID');
               $servicecatalog->uID = (Auth::id() == null) ? 1 : Auth::id();
               $servicecatalog->parentID = ($request->input('parentID') == null) ? 0 : $request->input('parentID');
               $servicecatalog->is_custom = $request->input('is_custom');

               $isSaved = $servicecatalog->save();
               if($isSaved){
                 return redirect('/admin/service_catalog');
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
        $catalogList = service_catalog::with('category','parent','user')
                   ->where('id',$id)
                   ->orderBy('id', 'desc')
                   ->first();
        return view('admin.service_catalog.show' , compact('catalogList'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $dataSet = service_catalog::find($id);
        $items = service_category::pluck('name', 'id');
        $dataKey = service_catalog::where(['parentID' => 0])->orderBy('name', 'asc')->get()->pluck('name', 'id');

        return view('admin.service_catalog.edit' , compact('dataSet','items','dataKey'));
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
            'name' => 'required',
            'domain' => 'required|unique:service_catalogs,domain,'.$id,
            'service_key' => 'unique:service_catalogs,service_key,'.$id
            
        );
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator->errors())->withInput();
        }
        else{   
           $servicecatalog = service_catalog::find($id);
           $servicecatalog->name = $request->input('name');
           $servicecatalog->company = $request->input('company');
           $servicecatalog->catID = ($request->input('catID') == null) ? 0 : $request->input('catID');
           $servicecatalog->description = $request->input('description');
           $servicecatalog->service_key = $request->input('service_key');
           $servicecatalog->domain = $request->input('domain');
           $servicecatalog->url = $request->input('url');
           $servicecatalog->hex = $request->input('hex');
           $servicecatalog->is_custom = $request->input('is_custom');
           $servicecatalog->statusID = $request->input('statusID');
           $servicecatalog->parentID = ($request->input('parentID') == null) ? 0 : $request->input('parentID');
           $isSaved = $servicecatalog->save();
           if($isSaved){
             Session::flash('flash_message', "Updated");
             return redirect('/admin/service_catalog');
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
        $dataSet = service_catalog::find($id);
        $dataSet->statusID = 0;
        $isSaved = $dataSet->save();
        if($isSaved){
            Session::flash('flash_message', "Inactive");
            return redirect('/admin/service_catalog');
        }
    }

    /**
     * fetchfromfiles Fetch Url from file 
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function fetchbyURL(Request $request){
        $fileUrl = 'https://s3.us-east-2.amazonaws.com/stackrapp/files/services.txt';
        $handle = fopen($fileUrl, "r");
        
        $file_headers = @get_headers($fileUrl);
        if (!$file_headers[0] == 'HTTP/1.1 403 Forbidden'){
          Session::flash('flash_message', "File not exists");
          return redirect()->back(); 
        }

        if(!$handle){
          Session::flash('flash_message', "File is empty");
          return redirect()->back(); 
        }

        ob_start();
        $fileContent = file_get_contents($fileUrl,true);
        $fileLines = explode("\n", $fileContent);

        foreach($fileLines as $item){
          $itemStore = explode(',', $item);
          $fileData = array();
          if(!empty($itemStore)){
            //don't load again if it already exists
            $app= service_catalog::where('domain',$itemStore[0])->get();
            if (!$app) {
              continue;
            } else {
                $fileData['url'] = $itemStore[0];
                $fileData['catID'] = $itemStore[1];
                $fileData['act'] = 'url';
                $uploadState = $this->getservicebyURL($request->merge($fileData));
                if($uploadState){
                  echo "<p>Updated=> ". $itemStore[0]."</p>";

                }else{
                  echo "<p>Not Recorded=> ". $itemStore[0]."</p>";
                }
                ob_flush();
                sleep(10);
            }
            
          }
        }
        ob_end_flush(); 

    }

    /**
     * getservicebyURL Fetch Meta data from URL 
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getservicebyURL(Request $request){

      ini_set('max_execution_time', 1800); //30 minutes

        $scrapUrl =  $request->input('url');
        $url = "http://pgrabber.com/url2price_api.php?url=".$scrapUrl;
        $data1 = [
            'pretty_print',
            'scope' => 'full_meta',
        ];
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 100,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode($data1),
            CURLOPT_HTTPHEADER => array(
                // Set here requred headers
                "accept: */*",
                "accept-language: en-US,en;q=0.8",
                "content-type: application/json",
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        $urlresult = json_decode($response);
        if(@$urlresult->jobs[0]->pg_id == null || @$urlresult->jobs[0]->unexpected_error){
          Session::flash('flash_message', "Could not resolve host !");
          return redirect()->back(); 

        }else{
          $action = $request->input('act');
          $id = $request->input('id');
          return $this->storeSericesByUrl($request, $action , $urlresult, $id);
        }
        
    }

   /**
     * storeSericesByUrl function perform create and edit opration on Url data
     * @param  int  $request, $action , $urlresult, $id
     * @return \Illuminate\Http\Response
     */
      public function storeSericesByUrl(Request $request, $action , $urlresult, $id){
        $domain = @$urlresult->jobs[0]->domain;
        $open_graph = @$urlresult->jobs[0]->open_graph;
        $domainSegment = trim($this->clean($domain));
        $strKeyName = strtolower(str_replace(' ', '_', $domainSegment));
        $errormessage = '';
        $Successmessage = '';
        switch($action){
          case 'add':
              if (service_catalog::where('domain', '=', $domain)->count() > 0) {
                  Session::flash('flash_message', "domain Allready exists ! Please try another");
                  return redirect()->back(); 
              }
              $servicecatalog = new service_catalog();
              $errormessage = 'Opps! could not be saved! Try again';
              $Successmessage = 'Created';
          break;
          case 'edit':
              $servicecatalog = service_catalog::find($id);
              $Successmessage = 'Updated';
              $errormessage = 'Opps! could not be updated! Try again';
          break;
          case 'url':
              if (service_catalog::where('domain', '=', $domain)->count() > 0)
                return false;
              $servicecatalog = new service_catalog();
          break;
        }
        $servicecatalog->name = ( @$open_graph !== null && array_key_exists("site_name",@$open_graph) &&  @$open_graph->site_name !==null) ? @$open_graph->site_name : ucfirst($domainSegment);
        $servicecatalog->company = ( @$open_graph !== null && array_key_exists("site_name",@$open_graph) && @$open_graph->site_name !==null) ? @$open_graph->site_name : $domainSegment;
        $servicecatalog->catID = ($request->input('catID') == null) ? 0 : $request->input('catID');
        $servicecatalog->description = ( @$open_graph !== null && array_key_exists("description",@$open_graph) &&  @$open_graph->description !==null) ? @$open_graph->description : @$urlresult->jobs[0]->meta_description;
        $servicecatalog->service_key = $strKeyName;
        $servicecatalog->domain = @$domain;
        $servicecatalog->hex = RandomColor::one(array('format'=>'hex','luminosity'=>'light', 'hue'=>array('blue', 'green', 'red')));
        $servicecatalog->url = ( @$open_graph !== null && array_key_exists("url",@$open_graph) && @$open_graph->url !==null) ? @$open_graph->url : @$urlresult->jobs[0]->final_url;
        $servicecatalog->statusID = 1;
        $servicecatalog->uID = (Auth::id() == null) ? 1 : Auth::id();
        $servicecatalog->parentID = ($request->input('parentID') == null) ? 0 : $request->input('parentID');
        $isSaved = $servicecatalog->save();
         
         //Run only if action is from URL
         if($action=="url"){
            if($isSaved){
              return true;
            }else{
              return false;
            }  
         }

        //Run only if action from add/edit          
        if($isSaved){
           $id = $servicecatalog->id;
           Session::flash('flash_message', $Successmessage); 
           return redirect('/admin/service_catalog/'.$id.'/edit');
        }else{
           Session::flash('flash_message', $errormessage);
           return redirect()->back();
        }
        
      }

    /**
     * clean function strip out the name, company and servicey_key from domain
     * @param  $domain
     * @return \Illuminate\Http\Response
     */
      public function clean($domain) {
          $domainStore = explode('.', $domain); 
          if(count($domainStore) > 2) { 
              $subDomain = "";
              for($i=0; $i<count($domainStore)-1; $i++){
                $subDomain.= $domainStore[$i]." ";
              } 
              return $subDomain;
          } else{ 
            return $domainStore[0];
          }
      }  

      public function find(Request $request){
          $query = $request->input('q');
          $oID = $request->input('oID');
          if($query){
            $catalogList = service_catalog::where('name', 'LIKE','%'.$query.'%')->whereNull('is_custom')->get();
            
            //DA 2018-02-06: do not need this, i removed the history, wasn't built as asked
            $archiveHistory = DB::select("select sc.domain,sc.id,sc.name, app.id as appId from service_catalogs sc inner join applications app on app.scID = sc.id where app.oID = '$oID'  and sc.name like '%$query%' and app.statusID = 0");

            return view('service_catalog.ajax-search',compact('catalogList','archiveHistory'));
          }
      }
  
}
