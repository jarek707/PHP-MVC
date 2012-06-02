<div class='selectWrap [%% selectClass %%]' >
	<div style='position:relative; float:left; margin-right:12px;'>
	<label id='[%% domId %%]_lab' class='select_lab' for='[%% domId %%]' onclick='toggleSelect( this ,"[%% label %%]", "[%% keepLabel %%]", "[%% lab_pfix %%]");'>
	[%% lab_pfix %%][%% IF keepLabel = '' %%][%% label %%][%% ELSE %%][%% field_content %%][%% ENDIF %%]
	</label>
	</div>
	<select name='[%% domId %%]' id='[%% domId %%]' class='zchzn-select' style='display:none;' onchange='[%% jsClass %%].saveSelect( this, [%% recipe_id %%],"[%% label %%]", "[%% method %%]", "[%% auxId %%]" ,"[%% lab_pfix %%]" );'>
		[%% options %%]
	</select>
	<div id='[%% domId %%]_view' class='view' style='position:relative; float:left; '>[%% IF keepLabel %%] [%% field_content %%] [%% ENDIF %%]</div>
</div>
