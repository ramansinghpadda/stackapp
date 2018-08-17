@extends('layouts.app') @section('content')
<?php  
    $grouplist = [];
    $groupArray = [];

    foreach($groups as $group){
        $grouplist[]= ['value'=>$group->id,'text'=>$group->name];
        $groupArray[$group->id] = $group->name;
    }

    $updateAppPermission = $userRoleInOrganization ?  $userRoleInOrganization->canAccess('update-application') : false;
    $addAppPermission =  $userRoleInOrganization ? $userRoleInOrganization->canAccess('add-application') : false;
    $manageOrgPermission = $userRoleInOrganization ? $userRoleInOrganization->canAccess('manage-organization') : false;
    $columns = $organization->getMetaColumns();
?>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    @include('organizations.navigation')
                </div>

            </div>
        </div>
    </div>
</div>
<div class="m-l-10 m-r-10">
    <div class="clear clearfix">
        @if($updateAppPermission) 
            @if($addAppPermission)
            <button class="btn btn-xs btn-success pull-left m-r-10" onclick="addApplication()" title="Add an application"><i class="glyphicon glyphicon-plus"></i> Add application</button> 
            @endif 
        @endif 
        @if($manageOrgPermission)
            <button class="btn btn-xs btn-primary pull-left" onclick="loadMetaModal()" title="Add a column to the table"><i class="glyphicon glyphicon-plus"></i> Add column</button> 
        @endif
        <button class="btn btn-xs btn-warning pull-right" onclick="$('#columnModal').modal('show')" title="Personalize your view settings">Customize your view</button>
    </div>

    <div class="table-responsive">
        <table id="applications" class="table applications-table table-bordered table-hover order-column">
            <thead class="applications-table__thead">
                <tr>
                    <?php $freezedColumn = 0; $defaultOrder=0; ?>
                    @if($updateAppPermission)
                    <?php $freezedColumn++; $defaultOrder=1; ?>
                <!--<th class="applications-table__th th__fixed">
                    @if($userRoleInOrganization && $userRoleInOrganization->canAccess('add-application'))
                        <button class="btn btn-xs btn-primary" onclick="loadMetaModal()">Add Col</button> 
                    @endif
                </th>-->
                @endif
                    <th class="applications-table__th th__fixed">Application</th>
                    @if($groups->count() > 0)
                    <?php $freezedColumn++; ?>
                    <th class="applications-table__th th__fixed">Group(s)</th>
                    @endif 
                    @foreach($columns as $column) 
                        @if(!$columnPreferences || ($columnPreferences && !in_array($column->id,$columnPreferences->columns)))
                            <th class="applications-table__th">
                                @if($manageOrgPermission)
                                <span class="text-red" onclick="loadMetaModal({{$column->id}})"><i class="glyphicon glyphicon-edit"></i></span>&nbsp;&nbsp; 
                                @endif 
                                {{$column->name}}
                            </th>
                        @endif 
                    @endforeach
                </tr>
                <tfoot class="applications-table__tfoot">
                <tr>
                    <th class="applications-table__tfoot__th">Application</th>
                   @if($groups->count() > 0)
                   <th class="applications-table__tfoot__th">Group(s)</th>
                    @endif 
                    @foreach($columns as $column) 
                        @if(!$columnPreferences || ($columnPreferences && !in_array($column->id,$columnPreferences->columns)))
                            
                    <th class="applications-table__tfoot__th" data-id="{{ $column->id }}">{{$column->name}}</th>
                    @endif @endforeach
                </tr>

            </tfoot>
            </thead>
            <tbody id="ajax-response">
                @include('applications._application-list')
            </tbody>
        </table>
    </div>
</div>
<!-- Modal -->
<div id="addApplicationModal" class="modal fade modal__application-add" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h3 class="modal-title">
                    Add an application
                </h3>
            </div>
            <div class="modal-body">
                @if(!$authUser->planAppLimitValid($organization))
                <div class="alert alert-warning">
                    You cannot add more applications. @if($userRoleInOrganization && $userRoleInOrganization->name == "owner") Please <a href="{{route('user-subscription')}}" class="alert__inline-link">upgrade</a> your plan. @endif
                </div>
                @else
                <p class="modal__application-add__p">Search for an application, or add your custom application:</p>
                <input class="form-control" placeholder="Type the name of the application..." onkeyup="searchServiceCatelog(this);" type="text" id="application" />
                <div id="search-response"></div>
                @endif
            </div>
        </div>
    </div>
</div>
<script>
    function addApplication(){
        $('#addApplicationModal').modal({
            backdrop: 'static',
            keyboard: false
        });
    }
    var ajax = null;
    function searchServiceCatelog(element){ 
        if($(element).val().length >= 3){
            if(ajax){
                ajax.abort();
            }

            ajax = $.ajax({
                url:"{{ url('/servicecatalog/search') }}",
                data:{ q: $(element).val(),oID:<?=$organization->id?>},
                beforeSend:function(){
                     $('#search-response').html('Searching ... ');
                },
                success:function(response){
                        $('#search-response').html(response);
                }
            });
        }else{
            $('#search-response').html("");
        }
    }

    function addNewApplication(element,id){
        $(element).attr('disabled','disabled');
        $.ajax({
            url:"{{ route('organization-application-new',$organization->id) }}",
            type:'POST',
            data:{ serviceId:id,
                name : $('#application').val(),
                "_token": "{{ csrf_token() }}"
            } ,
            beforeSend:function(){
                    $('#search-response').html('Adding application, please wait ... ');
            },
            success:function(response){
                $(element).removeAttr('disabled');
                window.location.reload();
            },
            error: function(ts) { 
                var errorMessage= '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">×</button>'+ts.responseJSON.error+'</div>';
                $('#search-response').html(errorMessage);
            }
        });
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

<div class="modal fade" data-backdrop="static" id="columnModal" tabindex="-1" role="dialog" aria-labelledby="columnModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="columnModalLabel">Customize</h4>
            </div>
            <div class="modal-body" id="columns-modal-body">
                <form id="columns-form">
                    @foreach($columns as $attribute)

                    <div style="
                            clear: both;
                            display: block;
                            padding: 10px;
                            margin: 5px;
                            width: 100%;
                        ">{{$attribute->name}}
                        <span class="radio-select pull-right">
                            <input name="metaColumns[{{$attribute->id}}]"  id="column-{{$attribute->id}}-1" type="radio" value="1" 
                            @if(!$columnPreferences || ($columnPreferences && !in_array($attribute->id,$columnPreferences->columns))))
                                    checked
                                @endif />
                            <label for="column-{{$attribute->id}}-1">Show</label>
                            <input name="metaColumns[{{$attribute->id}}]"   id="column-{{$attribute->id}}-2" type="radio"  value="0" @if($columnPreferences && in_array($attribute->id,$columnPreferences->columns)))
                                    checked
                                @endif>
                            <label for="column-{{$attribute->id}}-2" >Hide</label>
                        </span>
                    </div>

                    @endforeach

                </form>
            </div>
            <div class="modal-footer">
                <div class="small-progress" id="columns-progress" style="display:none;">
                    <div class="indeterminate"></div>
                </div>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="saveColumns(this)">Save</button>
            </div>
        </div>
    </div>
</div>



@endsection @section('styles')
<link href="{{ asset('/DataTables/css/dataTables.bootstrap.min.css') }}" rel="stylesheet" />
<link href="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/css/bootstrap-editable.css" rel="stylesheet" />
<style>
</style>
@endsection @section('scripts')
<script src="{{ asset('/js/datatables.min.js') }}"></script>
<script src="//cdn.datatables.net/colreorder/1.4.1/js/dataTables.colReorder.min.js"></script>
<script src="{{ asset('/DataTables/js/dataTables.bootstrap.min.js') }}"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.20.1/moment.js"></script>
<script type="text/javascript" src="{{ asset('/js/select2.min.js')}}"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/js/bootstrap-editable.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('#applications tfoot th').each( function (i) {
            var defaultOrder = <?=$defaultOrder?>;
            var title = $(this).text();
                if($(this).attr('data-id')){
                    $(this).html( '<input class="applications-table__input__filter metaAttributes" name="DBTablePositions['+$(this).attr('data-id')+']" type="text" placeholder="Filter '+title+'" />' );
                }else{
                    $(this).html( '<input class="applications-table__input__filter" type="text" placeholder="Filter '+title+'" />' );
                }
            
        });
    
        var table= $('#applications').DataTable({
            orderCellsTop: true,
            paging:false,
            pageLength: 5000,
            drawCallback: function(){
                var api = this.api(); 
                $('.editable', api.table().body())
                    .editable()
                    .off('hidden')
                    .on('hidden', function(e, reason) {
                        if(reason === 'save') {
                        $(this).closest('td').attr('data-order', $(this).text());
                        table.row($(this).closest('tr')).invalidate().draw(false);
                        }
                    });
            },
            <?php if($manageOrgPermission) { ?>
            colReorder: {
                fixedColumnsLeft:<?=$freezedColumn?>
            },
            <?php } ?>
            
        });
    
        table.on( 'column-reorder', function ( e, settings, details ) {
            console.log($('.metaAttributes').serialize());
            <?php if($manageOrgPermission) { ?>
            $.post('{{ route('meta-reposition',$organization) }}', $('.metaAttributes').serialize()+"&_token={{ csrf_token() }}", function(data) {
                if(!data.success) {
                    alert('Whoops, something went wrong :/');
                }
            }, 'json'); 
        <?php } ?>
        });
    
        table.columns().every( function () {
            var that = this;
            $('input', this.footer() ).on( 'keyup change', function (){
                if ( that.search() !== this.value ) {
                    that.search( this.value ).draw();
                }
            });
        });
    
        <?php  if($updateAppPermission){ ?>
            var groupSources = <?=json_encode($grouplist)?>;
            $('.inlineEditable').editable({
                 success: function(response, newValue) {
                    console.log(response);
                    if(response.status == 'error') 
                    return response.msg; //msg will be shown in editable for
                }
            });
            $('.groups').editable({
                source :groupSources,
                success: function(response, newValue) {
                    console.log(response);
                    if(response.status == 'error') 
                    return response.msg; //msg will be shown in editable form
                }
            });
        <?php }else{?>
            var groupSources = <?=json_encode($grouplist)?>;
            $('.inlineEditable').editable({ 
                disabled: true, 
                success: function(response, newValue) {
                    console.log(response);
                    if(response.status == 'error') 
                    return response.msg; //msg will be shown in editable form
                } 
            });
    
            $('.groups').editable({
            source :groupSources,
            disabled: true,
            success: function(response, newValue) {
                console.log(response);
                if(response.status == 'error') 
                return response.msg; //msg will be shown in editable form
            }
            });
        <?php }?>
    
       
    
    } );
</script>
<script>
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

    function saveColumns(element){
        $(element).attr('disabled','disabled');
         $.ajax({
             type:"POST",
             url:"{{ route('save-columns',$organization)}}",
             data:$('#columns-form').serialize()+"&_token={{ csrf_token() }}",
             beforeSend:function(){
                 $('#columns-progress').show();
             },
             
             success:function(data){
                $(element).removeAttr('disabled');
                window.location.reload();
             }
         });
    }
</script>

@endsection