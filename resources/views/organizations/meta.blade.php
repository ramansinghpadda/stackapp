@extends('layouts.app') @section('content')
<?php $userRoleInOrganization  = Auth::user()->userrole($organization); 
$updateAppPermission = $userRoleInOrganization ?  $userRoleInOrganization->canAccess('update-application') : false;
    $addAppPermission =  $userRoleInOrganization ? $userRoleInOrganization->canAccess('add-application') : false;
    $manageOrgPermission = $userRoleInOrganization ? $userRoleInOrganization->canAccess('manage-organization') : false;
    

?>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    @include('organizations.navigation')
                </div>
                <div class="panel-body">
                    <div class="m-b-20"><button class="btn btn-success" onclick="loadMetaModal()"><i class="glyphicon glyphicon-plus"></i> Add column</button></div>
                    <div class="table-responsive">
                        <table class="table table-condensed">
                            <thead>
                                <tr>
                                    <th>Sort</th>
                                    <th>Name</th>
                                    <th>Type</th>
                                    <th>Options</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="meta-table">
                                @if($metaMapping->count() > 0 ) @foreach($metaMapping as $option)
                                <tr id="positions_{{$option->id}}">
                                    <td>
                                        <span class="glyphicon glyphicon-menu-hamburger"></span>
                                    </td>
                                    <td>{{ $option->meta->name }}</td>
                                    <td>{{ $option->meta->type }}</td>
                                    <td>{{ $option->meta->options ? $option->meta->options : 'N/A' }}</td>
                                    <td>
                                        <label class="label @if ($option->meta->statusID ==1) label-success @else label-default @endif">
                                @if ($option->meta->statusID ==1) Shown @else Hidden @endif
                              </label>
                                    </td>
                                    <td>

                                        <div class="row">
                                            @if($option->meta->is_custom)
                                            <div class="col-12 col-md-6 text-right">
                                                <button onclick="loadMetaModal({{ $option->meta->id }})" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-edit"></i> &nbsp;Edit</button>
                                            </div>

                                            <div class="col-12 col-md-6 text-left">
                                                <form method="post" action="{{ route('organization-meta-delete',[$organization,$option->meta]) }}" onsubmit="return validateDeleteMeta()">
                                                    <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                                                    <button class="btn btn-xs btn-danger"><i class="glyphicon glyphicon-trash"></i> &nbsp;Delete</button>
                                                </form>
                                            </div>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @endforeach @else
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="6">No record found!</td>
                                </tr>
                            </tfoot>
                            @endif
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(function(){
        $("[name=toggler]").click(function(){
                $('.toHide').hide();
                $("#blk-"+$(this).val()).show('slow');
        });
     });
     

    function loadMetaModal(id){
        $('#metaModal').modal('show');
        $.ajax({
            url:"{{ route('organization-meta-new-ajax',$organization)}}"+"?id="+id,
            beforeSend:function(){
                $('#meta-progress').show();
            },
            success:function(response){
                $('#meta-modal-body').html(response);
                $('#meta-progress').hide();
            }
        });
         
    }

    function printErrorMsg (msg) {
      $("#meta-form-errors").find("ul").html('');
      $("#meta-form-errors").css('display','block');
      $.each( msg, function( key, value ) {
        $("#meta-form-errors").find("ul").append('<li>'+value+'</li>');
      });
  }

    function saveAttribute(element){
        $(element).attr('disabled','disabled');
         $.ajax({
             type:"POST",
             url:"{{ route('organization-meta-new',$organization)}}",
             data:$('#meta-form').serialize(),
             beforeSend:function(){
                 $('#meta-progress').show();
             },
             
             success:function(data){
                 if(data.success){
                     window.location.reload();
                 }
                else if($.isEmptyObject(data.error)){
                    //alert(data.success);
                  }else{
                    printErrorMsg(data.error);
                  }
                $('#meta-progress').hide();
                $(element).removeAttr('disabled');
             }
         });
    }

    function changeMetaType(element){
        if($(element).val() == 'option'){
            $('#options-values').show();
             $('#options-values').val("");
        }else{
            $('#options-values').hide();
        }
    }

    function validateDeleteMeta(){
         return confirm("Are you sure you want to delete meta attribute ?");
    }
</script>

<div class="modal fade" data-backdrop="static" id="metaModal" tabindex="-1" role="dialog" aria-labelledby="metaModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="metaModalLabel">Manage column</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
            </div>
            <div class="modal-body" id="meta-modal-body">

            </div>
            <div class="modal-footer">
                <div class="small-progress" id="meta-progress" display:none;>
                    <div class="indeterminate"></div>
                </div>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="saveAttribute(this)">Save</button>
            </div>
        </div>
    </div>
</div>
@endsection @section('scripts')
<script>
    $("#meta-table").sortable({
        items: "tr",
        'containment': 'parent',
        cursor: 'move',
        opacity: 0.6,
        update: function(event, ui) {
            $.post('{{ route('meta-reposition',$organization) }}', $(this).sortable('serialize')+"&_token={{ csrf_token() }}", function(data) {
                if(!data.success) {
                    alert('Whoops, something went wrong :/');
                }
        }, 'json'); 
      }
    });
</script>
@endsection