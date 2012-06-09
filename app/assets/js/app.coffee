Boat = 
	init: ->
		$('.itemsWrap ul li.del').bind 'click', Boat.del
		$('form .save').bind 'click', Boat.save

	# Deletions
	del: -> 
		ulElm = $(arguments[0].target).parent().parent()
		Ajax.post 'service/del', 
							{id: ulElm.attr('itemId'), tab: ulElm.attr('itemType')},
							Boat.deleted

	deleted: ( data, status ) ->
		eval 'status=' + status
		$('.itemsWrap.' + status.tab).find('ul[itemId$="' + status.id + '"]').detach()

	# Additions
	save: () -> 
		ulElm = $(arguments[0].target).parent().parent()

		data = { tab: ulElm.attr 'itemType' }
		$.each ulElm.find( ':input' ) , -> data[$(this).attr 'name'] = $(this).val()
		Ajax.post 'service/add',
							data, 
							Boat.saved
		return false

	saved: (data, status) ->
		eval 'status=' + status

		delete data.tab
		delete data.undefined

		liS = ''
		$.each data, (item) -> liS += '<li>' + data[item] + '</li>'
		$('.itemsWrap.' + status.tab + ' .head').after '<ul itemId="' + status.newId + '" itemType="' + status.tab + '">' + liS + '<li class="del"><a>Delete</a></li></ul>'
		$('form input[type$="text"]').val ''
		$('.itemsWrap.' + status.tab + ' ul li a').bind 'click', Boat.del

# = Boat END

# Utility objects: Ajax, Notify
Ajax = 
	post: ( url, data, cb ) ->
		console.log typeof cb 
		retData = ''
		$.ajax(
			{ url: document.location.origin + '/' + url, type:'post', data: data, async: true}
			).done ( status ) ->  
				if typeof cb is 'function' 
					cb(data, status) 
		return retData;

Notify =
	timeout: 4000
	now:null

	show: (msg, timeout) ->
		Notify.now = new Date().getTime()
		timeout = if timeout then timeout else Notify.timeout

		$('#notify').show().html msg
		Notify.hide timeout

	hide: (timeout) ->
		rightNow = new Date().getTime()
		if rightNow-Notify.now > timeout
			$('#notify').fadeOut 'slow'
		else 
		  setTimeout (-> Notify.hide timeout ), rightNow - Notify.now
		
window.Ajax = Ajax
window.Notify = Notify

setTimeout Boat.init, 200
