<?php

namespace App\Common\Traits;

use App\ApplicationAttachment;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Facades\Storage;
use Auth;

trait AttachmentUploadable
{
	
    public function attachments(){
        return $this->hasMany('App\ApplicationAttachment','appID','id');
    }

    public function upload($file){
        $response = ['status'=>false,'message'=>'No file to upload'];

        if($file){
            $fileId = time() . '.' . $file->getClientOriginalExtension();
            try{
                echo $file->storeAs('organizations/'.$this->oID.'/'.$this->id, $fileId);
                $attachment = new ApplicationAttachment;
                $attachment->file_id = $fileId;
                $attachment->appID = $this->id;
                $attachment->oID = $this->oID;
                $attachment->file_name = $file->getClientOriginalName();
                $attachment->file_type = $file->getMimeType();
                $attachment->uID = Auth::user()->id;
                $attachment->save();
                $response['status'] = true;
                $response['message'] = 'File uploaded successfully !';
            }catch(\Exception $e){
                $response['status'] = false;
                $response['message'] = $e->getMessage();
            }
            
        }
        return $response;
    }


    /*
        $url = Storage::temporaryUrl(
            'file1.jpg', now()->addMinutes(5)
        );
    */
    

}