Arrange = # __
	selected_boat: null
	max_skis: 4
	max_cap : 8
	max_lib : 4

	init: () ->
		$('.boat.assigned div.name' ).bind 'click', __.open_student_list
		$('.boat.unassigned'        ).hover null,   __.hide_unassigned
		$('.boat ul li'             ).bind 'click', __.add_student
		$('#inline_save img'        ).bind 'click', __.update_student_name
		$('#inline_edit            ').bind 'blur',  __.input_to_text
		$('#instructions'    ).show().bind 'click', __.show_instructions

		$('.toggle').bind 'click', __.toggle
		__.check_full_all()
		__.selected_boat = null
		Notify.show 'Click on the info icon in the menu to see instructions', 10000

	show_instructions: ->
		if $('#instructions_content').is(':visible')
			$('#instructions_content').hide()
		else
			$('#instructions_content').show()

	toggle: (e) ->
		if $(e.target).hasClass 'show'
			$(e.target).removeClass('show').addClass 'hide'
			$(e.target).next().find('ul').slideUp 'fast'
		else 
			$(e.target).removeClass('hide').addClass 'show'
			$(e.target).next().find('ul').show 'fast'

	hide_unassigned: ->
		$('.boat.unassigned').fadeOut 'fast'
		
	attach_unassigned: (elm) ->
		if $('.boat.unassigned').find('li').size()
			left	 = elm.offset().left
			top    = elm.offset().top
			height = elm.height()
			$('.boat.unassigned').css({top:top + height + 4, left:left - 3}).fadeIn 'slow'

	open_student_list: ( e ) ->
		__.check_full()
		$('.selected').removeClass 'selected'
		__.selected_boat = $(e.target).parent().addClass 'selected'
		__.attach_unassigned __.selected_boat
		$(e.target).parents('ul').show 'fast'
		Notify.show 'Selected boat ' + $(e.target).parent().find('.name').html()

	edit_student: (toMove) ->
		$('#inline_edit input#first').val toMove.find('li first').html()
		$('#inline_edit input#last' ).val toMove.find('li last' ).html()
		toMove.find('li').hide()
		toMove.addClass 'in_edit'
		$(toMove).append $('#inline_edit').show()
		$('#inline_edit input#first').focus().select()

	set_skipair: (elm, has_skipair) ->
		if ( $(elm).parents('.boat').find('ul.hasIt').size() is __.max_skis ) and has_skipair
			false and Notify.show 'Limit of students with skis reached for this boat'
		else
			if has_skipair 
				$(elm).addClass 'hasIt' 
			else 
				$(elm).removeClass 'hasIt'

	input_to_text: (e) ->
		ulWrap = $(e.target).parents('.in_edit').removeClass 'in_edit'
		ulWrap.find('li'      ).show()
		$('#inline_edit'      ).hide()
		
		has_skipair = $('#inline_edit input#has_skipair').is(':checked')
		return false if not __.set_skipair ulWrap, has_skipair

		first = $('#inline_edit input#first').val()
		last  = $('#inline_edit input#last' ).val()
		ulWrap.find('li first').html first
		ulWrap.find('li last' ).html last

		id_student:  ulWrap.attr('id_student') 
		first_name:  first
		last_name:   last
		has_skipair: if has_skipair then 1 else 0
	
	update_student_name:(e) ->
		ajaxData = __.input_to_text e
		Ajax.post 'service/update_student', ajaxData if ajaxData

	add_student: (e) ->
		toMove = $(this).parents('ul')
		__.selected_boat = toMove.parent() if not __.selected_boat

		if toMove.parent().attr('id_boat') is __.selected_boat.attr('id_boat')
			__.edit_student( toMove )
		else 
			hasSki   = toMove.hasClass 'hasIt'
			boatSkis = __.selected_boat.find('ul.hasIt').size()
			boatLoad = __.check_full()

			if (parseInt boatLoad) >= __.max_cap 
				Notify.show 'Limit of students reached for this boat'
			else if (__.selected_boat.hasClass 'library') and boatLoad >= __.max_lib
				Notify.show 'The library boat is already full'
			else if hasSki && (parseInt boatSkis) >= __.max_skis 
				Notify.show 'Limit of students with skis reached for this boat'
			else
				Notify.show 'Moving student <b>' + toMove.find('last').html() + '</b> to boat <b>' + __.selected_boat.find('.name').html() + '</b>'
				last_boat = toMove.parent().removeClass('full').attr 'id_boat'
				__.selected_boat.append toMove.detach() 
				__.attach_unassigned __.selected_boat
				Ajax.post 'service/reassign', 
									id_student: toMove.attr('id_student') 
									id_boat:    __.selected_boat.attr('id_boat')
									last_boat:  last_boat

			__.check_full()

	is_library: ->

	check_full_all: () ->
		$.each $('.boatList div.assigned'), -> __.check_full $ this
		
	check_full: ( selected_boat ) ->
		__.selected_boat = selected_boat if selected_boat 
		__.is_library()

		if __.selected_boat 
			boatLoad = __.selected_boat.find('ul').size()
			if  parseInt(boatLoad) >= __.max_cap 
				__.selected_boat.addClass 'full'
				__.hide_unassigned()
			else
				__.selected_boat.removeClass 'full'
			boatLoad

__ = Arrange
#$('.boat.assigned').draggable { containment: '.boatLine' }
setTimeout __.init, 200
