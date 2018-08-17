function inviteEmail(form){

    if($('#email').val() == ''){
        $('#message').text("Please enter email");
        return false;
    }
    if($('#role').val() == ''){
        $('#message').text("Please select role");
        return false;
    }
        

    $.ajax({
        url:"/organization/add-user",
        type:"POST",
        data:$(form).serialize(),
        beforeSend:function(){
            $('#message').text("Please wait ...");
        },
        success:function(response){
            $('#message').text(response);
            pendingInvitions();
        }
    });
    return false;
}