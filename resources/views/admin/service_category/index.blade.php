@extends('layouts.app')
@section('content')
@if(Session::has('flash_message'))
<div class="alert alert-success">
 <button type="button" class="close"data-dismiss="alert">×</button>
 {{ Session::get('flash_message') }}
</div>
@endif
 <div class="container">
  
    @include('admin.service_category.partials.navigation')
    <h1 class="title">All Categories</h1>
    <div>
     {!! Form::open(array('method' => 'GET', 'route' => array('service_category_search'))) !!}
     {!! Form::text('search', null, ['class' => 'form-control ', 'placeholder'=> 'Search by Name']) !!}
     <button class="btn btn-default">Search</button>
     {!! Form::close() !!}
    </div>

  <table class="table">
    <thead>
      <tr>
        <th>id</th>
        <th>Name</th>
        <th>Description</th>
        <th>statusID</th>
        <th>uID</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>


      @foreach ($ServiceCategoryList as $item)
                    <tr>
                        <td>{{ $item->id }}</td>
                        <td><strong>{{ $item->name }}</strong></td>
                        <td>{{ $item->description }}</td>
                        <td>{{ $item->statusID }}</td>
                        <td>{{ $item->user->email  }}</td>
          <td>
          <a class="btn btn-success btn-sm" href="{{ URL::to('/admin/service_category/' . $item->id .'/edit') }}">Edit</a>
          {!! Form::open(array('method' => 'DELETE', 'url' => 'admin/service_category/'.$item->id,'style'=>'display:inline')) !!}
          {!! Form::submit('Delete', ['class' => 'btn btn-danger btn-sm','data-toggle'=>'confirmation']) !!}
          {!! Form::close() !!}
          <a class="btn btn-default btn-sm" href="{{ URL::to('/admin/service_category/' . $item->id ) }}">View</a>
          </td>
                        @foreach ($item->children as $children)
                            <tr>
                                <td>{{ $children->id }}</td>
                                <td>- {{ $children->name }}</td>
                                <td>{{ $children->description }}</td>
                                <td>{{ $children->statusID }}</td>
                                <td>{{ $children->user->email  }}</td>
          <td>
          <a class="btn btn-success btn-sm" href="{{ URL::to('/admin/service_category/' . $children->id .'/edit') }}">Edit</a>
          {!! Form::open(array('method' => 'DELETE', 'url' => 'admin/service_category/'.$children->id,'style'=>'display:inline')) !!}
          {!! Form::submit('Delete', ['class' => 'btn btn-danger btn-sm','data-toggle'=>'confirmation']) !!}
          {!! Form::close() !!}
          <a class="btn btn-default btn-sm" href="{{ URL::to('/admin/service_category/' . $item->id ) }}">View</a>
          </td>
                            </tr>
                        @endforeach
                    </tr>
                </tbody>
                @endforeach

    </tbody>
  </table>
  <?php echo $ServiceCategoryList->render(); ?>
 </div>

  <script type="text/javascript">
    jQuery(document).ready(function ($) {
        $('[data-toggle=confirmation]').confirmation({
            rootSelector: '[data-toggle=confirmation]',
            onConfirm: function (event, element) {
                element.closest('form').submit();
            }
        });   
    });
</script>
@stop
