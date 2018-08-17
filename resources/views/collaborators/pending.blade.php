<h5><strong>Pending Invitations</strong></h5>
<table class="table table-striped">
<tr><th>Email </th><th>Invitation Date</th></tr>
@foreach($invitations as $invitation)
<tr><td>{{$invitation->email}}</td><td>{{ date('F j Y H:i',strtotime($invitation->created_at)) }}</td></tr>
@endforeach
</table>