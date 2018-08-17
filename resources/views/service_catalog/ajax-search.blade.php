<ul class="list-unstyled service_catalog_ajax-results">
    @foreach($catalogList as $catalog)
    <li class="application-suggestion">
    	@if ($catalog->domain)<img src="https://www.google.com/s2/favicons?domain={{$catalog->domain}}" alt="{{$catalog->name}} logo"> @endif{{ $catalog->name }} <button class="btn btn-xs btn-primary pull-right" onclick="addNewApplication(this,{{ $catalog->id}})">Add</button>
    </li>
    @endforeach
    <!--
    @if(count($archiveHistory) > 0 )
    <br/><h5><strong>Archive History</strong></h5>
    @foreach($archiveHistory as $history)
        <li class="application-suggestion">
        @if ($history->domain)<img src="https://www.google.com/s2/favicons?domain={{$history->domain}}" alt="{{$history->name}} logo"> @endif{{ $history->name }} <button class="btn btn-xs btn-success pull-right" onclick="restoreApplication(this,{{ $history->appId}})">Restore</button>
        </li>
    @endforeach
    @endif
    -->
    <li class="m-t-10"><p><strong>Not finding it?</strong> Keep typing the name of your application, then click below:</p>
    	<button class="btn btn-success" onclick="addNewApplication(this)">Save as custom application</button>
    </li>
</ul>