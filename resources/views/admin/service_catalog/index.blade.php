@extends('layouts.app')
@section('content')
@if(Session::has('flash_message'))
<div class="alert alert-success">
 <button type="button" class="close"data-dismiss="alert">×</button>
 {{ Session::get('flash_message') }}
</div>
@endif
 <div class="container">
    @include('admin.service_catalog.partials.navigation')
    <h1>All Services</h1>

    <div>
     {!! Form::open(array('method' => 'GET', 'route' => array('service_catalog_search'))) !!}
     {!! Form::text('search', null, ['class' => 'form-control ', 'placeholder'=> 'Search by Name']) !!}
     <button class="btn btn-default">Search</button>
     {!! Form::close() !!}
    </div>

  <table class="table">
    <thead>
      <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Key</th>
        <th>Description</th>
        <th>statusID</th>
        <th>Custom</th>
        <th>uID</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($catalogList as $item)
                    <tr>
                        <td>{{ $item->id }}</td>
                        <td>
                            <span style="color:#fff;padding:10px;background-color:{{ $item->hex }};">{{ str_limit($item->name , $limit = 1, $end = '') }}</span>
                            <strong>{{ $item->name }}</strong>
                            <!--<br><a href="https://s3.us-east-2.amazonaws.com/stackrapp/assets/logos/{{ $item->service_key }}.svg" target="_blank">S3 logo</a>-->
                        </td>
                        <td>{{ $item->service_key }}</td>
                        <td>{{ $item->description }}</td>
                        <td>{{ $item->statusID }}</td>
                        <td>{{ $item->is_custom }}</td>
                        <td>{{ $item->user->email }}</td>
                        <td>
                        <a class="btn btn-success btn-sm" href="{{ URL::to('/admin/service_catalog/' . $item->id .'/edit') }}">Edit</a>
                        {!! Form::open(array('method' => 'DELETE', 'url' => 'admin/service_catalog/'.$item->id,'style'=>'display:inline')) !!}
                        {!! Form::submit('Delete', ['class' => 'btn btn-danger btn-sm','data-toggle'=>'confirmation','onclick'=>"return confirm('Are you sure ?')"]) !!}
                        {!! Form::close() !!}
                        <a class="btn btn-default btn-sm" href="{{ URL::to('/admin/service_catalog/' . $item->id ) }}">View</a>
                        </td>
                                      @foreach ($item->children as $children)
                                          <tr>
                                              <td>{{ $children->id }}</td>
                                              <td>{{ $children->custom }}</td>
                                              <td>
                                                <span style="color:#fff;padding:10px;background-color:{{ $children->hex }};">{{ str_limit($children->name , $limit = 1, $end = '') }}</span>
                                                - {{ $children->name }}
                                              </td>
                                              <td>{{ $children->description }}</td>
                                              <td>{{ $children->statusID }}</td>
                                              <td>{{ $children->is_custom }}</td>
                                              <td>{{ $children->user->email }}</td>
                        <td>
                        <a class="btn btn-success btn-sm" href="{{ URL::to('/admin/service_catalog/' . $children->id .'/edit') }}">Edit</a>
                        {!! Form::open(array('method' => 'DELETE', 'url' => 'admin/service_catalog/'.$children->id,'style'=>'display:inline')) !!}
                        {!! Form::submit('Delete', ['class' => 'btn btn-danger btn-sm','data-toggle'=>'confirmation','onclick'=>"return confirm('Are you sure ?')"]) !!}
                        {!! Form::close() !!}
                        <a class="btn btn-default btn-sm" href="{{ URL::to('/admin/service_catalog/' . $children->id ) }}">View</a>
                        </td>
                            </tr>
                        @endforeach
                    </tr>
                </tbody>
                @endforeach

    </tbody>
  </table>
  <?php echo $catalogList->render(); ?>
 </div>
@stop
