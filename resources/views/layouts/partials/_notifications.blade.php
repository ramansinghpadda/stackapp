@if (session('success'))
	<div class="container">
		<div class="row">
			<div class="col-md-8 col-md-offset-2">
				<div class="alert alert-success">
				<button type="button" class="close" data-dismiss="alert">×</button> 
					{!! session('success') !!}
				</div>
			</div>
		</div>
	</div>
@endif

@if (session('error'))
	<div class="container">
		<div class="row">
			<div class="col-md-8 col-md-offset-2">
				<div class="alert alert-danger">
				<button type="button" class="close" data-dismiss="alert">×</button> 
					{!! session('error') !!}
				</div>
			</div>
		</div>
	</div>
@endif