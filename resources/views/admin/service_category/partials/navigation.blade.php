<nav class="navbar">
    <ul class="nav navbar-nav">
        <li><a href="{{ URL::to('/admin/') }}">&larr; Main admin</a></li>
        <li><a href="{{ URL::to('/admin/service_category') }}">All Categories</a></li>
        <li><a href="{{ URL::to('/admin/service_category/create')}}">+ New category</a>
    </ul>
</nav>