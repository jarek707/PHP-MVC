Arrange =
	selected_boat: null

	init: () ->
		$('.boat.assigned div.name').bind 'click', Arrange.open_student_list
		$('.boat.unassigned').hover null, Arrange.close_student_list
		$('.boat ul li').bind 'click', Arrange.add_student
		$('.toggle').bind 'click', Arrange.toggle
		$('#inline_edit input#last').bind 'blur', Arrange.update_student_name
		$('#inline_edit input#first').bind 'blur', Arrange.skip_to_last
		$('#instructions').show().bind 'click',Arrange.show_instructions
		Arrange.check_full_all()
		Arrange.selected_boat = null
		Notify.show 'Click on the info icon in the menu to see instructions', 10000

	show_instructions: ->
		console.log  'here '
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

	attach_list: (elm) ->
		if $('.boat.unassigned').find('li').size()
			left	 = elm.offset().left
			top    = elm.offset().top
			height = elm.height()
			$('.boat.unassigned').css({top:top+height+4, left:left - 3}).fadeIn 'slow'

	open_student_list: ( e ) ->
		Arrange.check_full()
		$('.selected').removeClass 'selected'
		Arrange.selected_boat = $(e.target).parent().addClass 'selected'
		Arrange.attach_list Arrange.selected_boat
		$(e.target).parent().find('ul').show 'fast'
		Notify.show 'Selected boat ' + $(e.target).parent().find('.name').html()

	close_student_list: (e) ->
		$('.boat.unassigned').fadeOut 'fast'

	edit_student: (toMove) ->
		first = toMove.find('li first').html()
		last  = toMove.find('li last').html()
		$('#inline_edit input#first').val first
		$('#inline_edit input#last').val last
		toMove.find('li').hide()
		editLi = $('#inline_edit')
		$(toMove).append  editLi
		$('#inline_edit').show()
		$('#inline_edit input#first').focus().select()

	update_student_name:(e) ->
		ulWrap = $(e.target).parent().parent()
		console.log [ $(e.target).attr 'id' ]
		ulWrap.find('li').show()
		ulWrap.find('li first').html $('#inline_edit input#first').val()
		ulWrap.find('li last').html $('#inline_edit input#last').val()
		Ajax.post 'service/update_student',{ id_student: ulWrap.attr('id_student'), first:$('#inline_edit input#first').val(), last:$('#inline_edit input#last').val() }

		$('#inline_edit').hide()

	add_student: (e) ->
		toMove = $(this).parent()
		if not Arrange.selected_boat
			Arrange.selected_boat=toMove.parent()

		if toMove.parent().attr('id_boat') is Arrange.selected_boat.attr('id_boat')
			Arrange.edit_student( toMove )
		else 
			hasSki = toMove.hasClass 'hasIt'
			boatSkis = Arrange.selected_boat.find('ul.hasIt').size()
			boatLoad = Arrange.check_full()

			if (parseInt boatLoad) > 7
				Notify.show 'Limit for students reached for this boat'
			else if (Arrange.selected_boat.hasClass 'library') and  boatLoad > 3 
				Notify.show 'The library boat is already full'
			else if hasSki && (parseInt boatSkis) > 3
				Notify.show 'Limit of students with skis reached for this boat'
			else
				Notify.show 'Moving student <b>' + toMove.find('last').html() + '</b> to boat <b>' + Arrange.selected_boat.find('.name').html() + '</b>'
				last_boat = toMove.parent().removeClass('full').attr 'id_boat'
				Arrange.selected_boat.append toMove.detach() 
				Arrange.attach_list Arrange.selected_boat
				Ajax.post 'service/reassign', 
					{	id_student: toMove.attr('id_student'), id_boat: Arrange.selected_boat.attr('id_boat'), last_boat: last_boat }

			Arrange.check_full()

	check_full_all: () ->
		$.each $('.boatList div.assigned'), -> Arrange.check_full $ this
		
	check_full: ( selected_boat ) ->
		Arrange.selected_boat = selected_boat if selected_boat 

		if Arrange.selected_boat 
			boatLoad = Arrange.selected_boat.find('ul').size()
			if  parseInt(boatLoad) > 7
				Arrange.selected_boat.addClass 'full'
			else
				Arrange.selected_boat.removeClass 'full'
			boatLoad

$('.boat.assigned').draggable { containment: '.boatLine' }
setTimeout Arrange.init, 200
