@extends('layouts.app') @section('content')
<style>
    .app-list{
        list-style: none;
        clear: both;
    }
    .app-list li{
        padding:5px;
    }
    .label-tag{
        padding: 10px;
        border: 1px solid #3a88e3;
        color: #3a88e3;
        margin:2px;
    }
    .label-tag:hover{
        color:#fff;
        background:#3a88e3;
    }
    
    .loader {
       border: 5px solid #f3f3f3;
        border-top: 5px solid #3498db;
        border-radius: 50%;
        width: 70px;
        height: 70px;
        margin:0px auto;
        animation: spin 2s linear infinite;
    }
    
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    
    .checkbox-container {
        display: inline-block;
        position: relative;
        padding: 2px 10px 0px 35px;
        cursor: pointer;
        margin: 2px;
        font-size: 14px;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
        border: 1px solid lightgray;
    }
    
    /* Hide the browser's default checkbox */
    .checkbox-container input {
        position: absolute;
        opacity: 0;
        cursor: pointer;
    }
    
    /* Create a custom checkbox */
    .checkmark {
        position: absolute;
        top: 2px;
        left: 2px;
        height: 20px;
        width: 22px;
        background-color: #eee;
    }
    
    /* On mouse-over, add a grey background color */
    .checkbox-container:hover input ~ .checkmark {
        background-color: #ccc;
    }
    
    /* When the checkbox is checked, add a blue background */
    .checkbox-container input:checked ~ .checkmark {
        background-color: #2196F3;
    }
    
    /* Create the checkmark/indicator (hidden when not checked) */
    .checkmark:after {
        content: "";
        position: absolute;
        display: none;
    }
    
    /* Show the checkmark when checked */
    .checkbox-container input:checked ~ .checkmark:after {
        display: block;
    }
    
    /* Style the checkmark/indicator */
    .checkbox-container .checkmark:after {
        left: 9px;
        top: 5px;
        width: 5px;
        height: 10px;
        border: solid white;
        border-width: 0 3px 3px 0;
        -webkit-transform: rotate(45deg);
        -ms-transform: rotate(45deg);
        transform: rotate(45deg);
    }
</style>
<div class="container">
    <div class="row">
        <form onsubmit="return scanUrl(this)" class="form-row col-md-10 col-md-offset-1" action="{{ url('/analyze') }}">
            <div class="form-group col-md-11">
                <input class="form-control" type="url" id="url" name="q" value="{{ $url }}" />
            </div>
            <div class="form-group col-md-1">
                <button class="btn btn-primary">Re-scan</button>
            </div>
        </form>
        <div class="app-list container">
            <div id="loader" class="loader-animation">
                <div class="loader"></div>
                <p class="loader-stage text-center">Loading Please</p>
            </div>

                        <div class="alert alert-danger" id="form-errors" style="display:none">
                            <ul></ul>
                        </div>
            <div id="registration-form" style="display:none;">

                    <form onsubmit="return registerUser(this)" method="post">

                        <div class="col-md-6">
                            <div id="app-list"></div>
                            @if(Auth::user())
                                 <div class="form-group">
                                <button type="submit" id="submit-btn" class="btn btn-success">
                                        Update
                                </button>
                                <button type="reset" id="reset-button" style="display:none;"></button>
                            </div>
                            @endif

                        </div>
                        <div class="col-md-6">
                            {{ csrf_field() }}
                            @if(!Auth::user())
                           
                            <h3>Register</h3>
                            <p>We'll add the applications we found to your inventory</p>
                            <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                                <input placeholder="Name" id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" required autofocus> @if ($errors->has('name'))
                                <span class="help-block">
                        <strong>{{ $errors->first('name') }}</strong>
                    </span> @endif

                            </div>
                            <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                <input placeholder="Email" id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required> @if ($errors->has('email'))
                                <span class="help-block">
                        <strong>{{ $errors->first('email') }}</strong>
                    </span> @endif

                            </div>
                            <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">

                                <input placeholder="Password" id="password" type="password" class="form-control" name="password" required> @if ($errors->has('password'))
                                <span class="help-block">
                        <strong>{{ $errors->first('password') }}</strong>
                    </span> @endif
                            </div>
                            <div class="form-group">
                                <input placeholder="Confirm Password" id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                            </div>
                            <div class="form-group">
                                <button type="submit" id="submit-btn" class="btn btn-success">
                         Register
                </button>
                                <button type="reset" id="reset-button" style="display:none;"></button>

                            </div>
                            @endif
                        </div>
                        <input type="hidden" id="domain" name="domain" value="{{ $url }}" />
                    </form>
            </div>
        </div>
    </div>
</div>
@endsection @section('scripts')
<script>
    function scanUrl(formElement){
        $('#domain').val($('#url').val());
        $('#reset-button').trigger('click');
        $.ajax({
            type:'POST',
            url:"{{ route('analyze-stack') }}",
            data:{
                _token:"{{ csrf_token() }}",
                url : $('#url').val()
            },
            beforeSend:function(){
                $('#app-list').hide();
                $('#loader').show();
                $('#registration-form').hide();
                $('#loader .loader-stage').text("Retrieving Data...");
                console.log('retrieving data');
                 $('#app-list').html("<h3>Technologies identified:</h3>");
                 $("#form-errors").find("ul").html('');
                 $("#form-errors").css('display','none');
            },success:function(response){
                if(!$.isEmptyObject(response.error)){
                     printErrorMsg(response.error);
                     $('#loader').hide();
                  }else{
                   
                  
                $('#loader .loader-stage').text("Building inventory...please wait");
                
               
                console.log('building data data');
                $.each(response,function(i,v){
                    var html='<label class="checkbox-container">'+v;
                        html+='<input name="applications[]" value="'+v+'" type="checkbox" checked="checked">';
                        html+='<span class="checkmark"></span></label>';
                   
                    $('#app-list').append(html);
                });
                $('#registration-form').show();
                }
            },
            error:function(){
                 $('#registration-form').hide();
            },
            complete:function(response, textStatus){
                $('#loader .loader-stage').text("Almost done");
                if(textStatus == "error"){
                    $('#registration-form').hide();
                    $('#app-list').html('<p class="text-center">'+response.responseJSON.message+'</p>');
                }else{
                    
                }
                $('#loader').hide();
                $('#app-list').show();
            }
        });
        return false;
    }

    function printErrorMsg (msg) {
      $("#form-errors").find("ul").html('');
      $("#form-errors").css('display','block');
      $.each( msg, function( key, value ) {
        $("#form-errors").find("ul").append('<li>'+value+'</li>');
      });
    
    }
    function registerUser(form){
        $('#submit-btn').attr('disabled','disabled');
        $.ajax({
            type:'POST',
            url:"{{ route('create-organization-with-domain') }}",
            data:$(form).serialize(),
            beforeSend:function(){},
            success:function(data){
                if(data.success){
                     window.location.href="/organization/"+data.organization.id;
                 }
                 else if($.isEmptyObject(data.error)){
                    //alert(data.success);
                  }else{
                    printErrorMsg(data.error);
                  }
                  $('#submit-btn').removeAttr('disabled');
            },
        });
        return false;
    }
    scanUrl();
</script>
@endsection