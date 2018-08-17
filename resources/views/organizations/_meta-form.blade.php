<div class="alert alert-danger" id="meta-form-errors" style="display:none">
    <ul></ul>
</div>
<form id="meta-form" class="" method="POST" action="{{ route('organization-meta-new',$organization)}}">
<input type="hidden" name="_token" value="{{ csrf_token() }}" />
@if($meta)
<input type="hidden" name="id" value="{{ $meta->id }}"/>
@endif
    <div class="form-group">
            <label>Name</label>
            <input name="name" type="name" class="form-control" value="{{ $meta ? $meta->name : ''}}"/>
</div>
<div class="form-group">
        <label>Type</label>
        <select onchange="changeMetaType(this)" name="type" class="form-control">
        @foreach(App\Meta::$optionsTypes as $key=>$label)
        <option value="{{ $key }}" {{ $meta && $meta->type ==$key ? 'selected': ''}}>{{$label}}</option>
        @endforeach
        
    </select>
</div>
<div id="options-values" class="form-group" style="display:{{ $meta && $meta->type == 'option' ? 'block' : 'none' }}">
    <label>Options</label>
    <input name="options" type="text" class="form-control" value="{{ $meta ? $meta->options : ''}}" placeholder="Type comma separated options"/>
</div>
<div class="form-group">
    <label>Status</label>
    <select name="status" class="form-control">
        <option value="1" {{ $meta && $meta->statusID ==1 ? 'selected': ''}}>Show</option>
        <option value="2" {{ $meta && $meta->statusID ==2 ? 'selected': ''}}>Hide</option>
    </select>
</div>
</form>