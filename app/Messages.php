<?php

namespace App;

class Messages
{

    public static function  notAuthorized(){
        return [
            'code'=>403,
            'message'=>"Not authorized to access this resource !"
        ];
    }

    public static function  noRecordFound(){
        return [
            'code'=>404,
            'message'=>"No record found!"
        ];
    }

    public static function  noValidCode(){
        return [
            'code'=>404,
            'message'=>"Invitation link has been expired or invalid!"
        ];
    }
	
	
	
}