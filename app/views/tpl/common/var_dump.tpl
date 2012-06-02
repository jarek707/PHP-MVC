<script type='text/javascript'>
	if ( typeof DMP == 'undefined' ) {
		DMP = {
			dumpDiv: "<div id='var_dump' style='overflow:auto; position:absolute; max-height:99%; width:50%; border:1px solid #ddd; top:0; left:50% !important; background:#eee; opacity:0.8; left:300px'> <div id='var_dump_ctrl' style='position:relative; float:right;' > <button onclick='DMP.toggle();'>Show/Hide Content</button> <button onclick='DMP.destroy();'>Destroy Dumper</button> </div> <ul> </ul> </div> ",

			toggle: function() {
				if ( $("#var_dump ul").is(":visible") ) 
					$("#var_dump ul").hide(); 
				else 
					$("#var_dump ul").show();
			},

			destroy: function() { $("#var_dump").hide(); },

			init: function() {
				if ( $('#var_dump').size() === 0 )  {
					$('body').append(this.dumpDiv);
				}
			}
		}
		DMP.init();
	}
	$('#var_dump ul').append( '<li style=\"list-style:none;\">[%% lab %%]<pre>[%% dumpS %%]</pre></li>');
</script>

