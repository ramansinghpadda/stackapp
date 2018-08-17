<?php namespace App\Helpers;
use Storage;
class Attachment{
    public static function _link($attachment){
        if($attachment){
            $ext = pathinfo($attachment->file_name, PATHINFO_EXTENSION);
            $iconImageUrl =  url('/filetypes/'.$ext).".png"; 
            
            return $html = '
                <div class="col-md-3">
                    <div class="attachment panel panel-default" style="border:1px solid #3a88e3">
                        <div class="panel-heading"><a target="_blank" href="'.route('document',[$attachment->oID,$attachment->appID,$attachment->id]).'">'.$attachment->file_name.'</a></div>
                        <div class="panel-body" style="min-height: 130px; text-align:center">
                        <img style="height:100%;width:100px" src="'.$iconImageUrl.'"/>
                        </div>
                        <div class="panel-footer text-center">
                        <a  href="'.route('download-document',[$attachment->oID,$attachment->appID,$attachment->id]).'"><i class="glyphicon glyphicon-download"></i>&nbsp;Download</a>
                        &nbsp;&nbsp;
                         <a href="'.route('delete-document',[$attachment->oID,$attachment->appID,$attachment->id]).'"><i class="glyphicon glyphicon-trash"></i>&nbsp;Delete</a>
                        </div>
                    </div>
                </div>
            ';
        }
    }
}