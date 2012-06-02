Resize = {
  images: {},

  getProfileImages : function( typ ) {
  	$.ajax({
  	  url:'back.php?action=getProfileImages&format=ajax',
  	  method:'get',
  	  success: function( data ) {
  	    var imgDataS = 'Resize.images =' +  data;
  	    eval( imgDataS );
  	    console.debug ( Resize.images[typ] );
  	    console.debug ( $(Resize.images[typ]).size() );
  	    Resize.iterateResize( typ );
  	  }
  	});
  },

  iterateResize: function( imgTyp ) {
  	for ( var typ in Resize.images) {
  	  if ( (imgTyp == 'all' )|| (typ == imgTyp) ) {
				for ( var j in Resize.images[typ] ) {
					console.log ( typ + '  ' + j );
					$.ajax({
						url:'back.php?action=runResizeAjaxOne&format=ajax&typ=' + typ + '&file=' + Resize.images[typ][j],
						method:'get',
						success: function(data) {
							$('#out').prepend("<div>" + data + "</div>");
						}
					});
				}
			}
  	}
  }
}
