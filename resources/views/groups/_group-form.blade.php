<div class="alert alert-danger" id="group-form-errors" style="display:none">
    <ul></ul>
</div>
<form id="group-form" class="" method="POST" action="{{ route('organization-meta-new',$organization)}}">
<input type="hidden" name="_token" value="{{ csrf_token() }}" />
@if(isset($group))
<input type="hidden" name="id" value="{{ $group->id }}"/>
@endif
    <div class="form-group">
            <label>Name</label>
            <input name="name" type="name" class="form-control" value="{{ isset($group) ? $group->name : '' }}"/>
</div>
</form>