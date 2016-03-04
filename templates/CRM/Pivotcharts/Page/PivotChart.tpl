
<h3>Demo using <a href="https://github.com/nicolaskruchten/pivottable" target="_blank">nicolaskruchten/pivottable</a> library</h3>


 <div id="output" style="margin: 30px;"></div>
{literal}
<script>
	jQuery(document).ready(function ($) {
	    jQuery("#output").pivotUI(
            [ 
            {/literal}
            	
				{foreach name=outer item=contact from=$data}
				  {literal}{ {/literal}
				  {foreach key=key item=item from=$contact}
				    "{$key}": "{$item}",
				  {/foreach}
				  {literal}} ,{/literal}
				{/foreach}
            {literal}
                
            ], 
            { 
                rows: ["Activity Type"], 
                cols: ["Record Type"] 
            }
        );

	});
</script>
{/literal}