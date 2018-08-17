 @if($applications) @foreach($applications as $application)


<?php 

$totalColumns = 0;
?>
<tr>
    <td class="applicatoins-table__body-td applications-table__col-name" data-order="{{$application->name}}">
        @if ($application->domain)
        <img src="https://www.google.com/s2/favicons?domain={{$application->domain}}" alt="{{$application->name}} logo"> @else
        <span class="applications-table__letter-icon" style="background-color:{{ $application->hex }};" first-letter="{{ str_limit($application->name , $limit = 1, $end = '') }}">

        </span> @endif
        <a  
        data-mode="inline" 
            data-url="{{ route('application-update') }}" 
            data-params="{{ '{_token:\''.csrf_token().'\',appID:\''.$application->id.'\',oID:\''.$organization->id.'\'}' }}" 
            data-pk="{{ $application->id }}" 
            data-name="appID" 
            data-type="text" 
            data-value="{{$application->name}}" 
            class="applications-table__link-name" 
            title="View {{$application->name}}" 
            href="{{ route('organization-application-view',['id'=>$organization->id,'appId'=>$application->id]) }}">
        <strong>{{$application->name}}</strong>
        </a>
    </td>
    @if ($groups->count() > 0)
    <?php $groupsNames = []; 
        $applicationGroups = $application->getAssociatedGroups($groupArray);
      
    ?>
       
    <td data-order="{{ implode(' ',array_values($applicationGroups)) }}">
    <?php $selected = array_keys($applicationGroups); ?>
       
        <a  class="groups"  
            data-pk="{{ $application->id }}" 
            data-name="groups" 
            data-params="{{ '{_token:\''.csrf_token().'\',appID:\''.$application->id.'\',oID:\''.$organization->id.'\'}' }}" 
            data-url="{{ route('application-update') }}"  
            data-type="checklist"  
            data-value="{{ implode(',',$selected)}}">
        </a>
    </td>
    @endif
    <?php  ; $metaDataValues = $application->getMetaData();
            foreach($metaDataValues as $metadata){
                $mappedValue = $metadata->value;
                if(!$columnPreferences || ($columnPreferences && !in_array($metadata->id,$columnPreferences->columns))){
                    $totalColumns++;
                ?>
                        <td data-order="{{$mappedValue}}">    
                    @if(filter_var($mappedValue, FILTER_VALIDATE_URL))
                        <a  class="inlineEditable" 
                            data-mode="inline" 
                            data-url="{{ route('application-update') }}" 
                            data-pk="{{ $metadata->mmID }}" 
                            data-name="meta" 
                            data-type="textarea" 
                            data-inputclass="form-control" 
                            data-params="{{ '{_token:\''.csrf_token().'\',appID:\''.$application->id.'\',oID:\''.$organization->id.'\'}' }}" 
                            data-value="{{$mappedValue}}" href="{{$mappedValue}}" title="link to {{$mappedValue}}" target="_blank">{{$mappedValue}}</a>
                    @elseif($metadata->type == 'date') 
                        <a  class="inlineEditable" 
                            data-mode="inline" 
                            data-params="{{ '{_token:\''.csrf_token().'\',appID:\''.$application->id.'\',oID:\''.$organization->id.'\'}' }}" 
                            data-url="{{ route('application-update') }}" 
                            data-pk="{{ $metadata->mmID }}" 
                            data-name="meta" 
                            data-inputclass="date-control" 
                            data-type="combodate" 
                            data-value="{{$mappedValue}}">{{$mappedValue}}</a>
                    @elseif($metadata->type == 'long_text') 
                        <a  class="inlineEditable" 
                            data-mode="inline" 
                            data-params="{{ '{_token:\''.csrf_token().'\',appID:\''.$application->id.'\',oID:\''.$organization->id.'\'}' }}" 
                            data-url="{{ route('application-update') }}" 
                            data-pk="{{ $metadata->mmID }}" 
                            data-name="meta" 
                            data-type="textarea" 
                            data-value="{{$mappedValue}}">{{$mappedValue}}</a>
                    @elseif($metadata->type == 'option') 
                    <?php 
                            $options = explode(',',$metadata->options);
                            $optionSrc = [];
                            foreach($options as $val){
                                $optionSrc[]=['value'=>$val,'text'=>$val];
                            }
                    ?>
                        <a  data-source='<?=json_encode($optionSrc,JSON_HEX_APOS)?>' 
                            class="inlineEditable" 
                            data-mode="inline" 
                            data-params="{{ '{_token:\''.csrf_token().'\',appID:\''.$application->id.'\',oID:\''.$organization->id.'\'}' }}" 
                            data-url="{{ route('application-update') }}" 
                            data-pk="{{ $metadata->mmID }}" 
                            data-name="meta" 
                            data-type="select" data-value="{{$mappedValue}}">{{$mappedValue}} </a>
                    @else
                        <a  class="inlineEditable" 
                            data-mode="inline" 
                            data-params="{{ '{_token:\''.csrf_token().'\',appID:\''.$application->id.'\',oID:\''.$organization->id.'\'}' }}" 
                            data-url="{{ route('application-update') }}" 
                            data-pk="{{ $metadata->mmID }}" 
                            data-name="meta" 
                            data-type="text" 
                            data-value="{{$mappedValue}}">{{$mappedValue}} </a>
                    @endif
            </td>
        <?php  } } ?>
</tr>
@endforeach @else
<tr>
    <td colspan="{{ $totalColumns +2 }}">
        <div>
            <ol class="lead p-t-10">
                <li class="m-b-10">Start adding application by clicking the "+ Add application"</li>
                <li class="m-b-10"><a href="{{ route('organization-meta',$organization)}}">Customize the columns</a> you need to collect data for your inventory</li>
                <li class="m-b-10"><a href="{{ route('organization-groups',$organization)}}">Create groups</a> for your departments/divisions and assign it to applications</li>
                <li><a href="{{ route('organization-team',$organization)}}">Add team members</a> to help with managing the inventory, or simply to acccess the data.</li>
            </ol>
        </div>
    </td>
</tr>
@endif