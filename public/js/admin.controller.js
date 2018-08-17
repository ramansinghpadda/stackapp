 jQuery(document).ready(function ($) {
    $(".catalog-services-name").on('keyup',function(){
       var name = $('input[name=name]').val();
       console.log(name);
       var servicekey = name.replace(/\s+/g, '_');
       $('#service_key').val(servicekey.toLowerCase());
    });
     
});