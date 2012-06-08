Arrange =
	selected_boat: null
	bind: () ->
		$('.boat.assigned div.name').bind 'click', Arrange.open_student_list
		$('.boat.unassigned').hover null, Arrange.close_student_list
		$('.boat ul li').bind 'click', Arrange.add_student

	attach_list: (elm) ->
		if $('.boat.unassigned').find('li').size()
			left	 = elm.offset().left
			top    = elm.offset().top
			height = elm.height()
			$('.boat.unassigned').css({top:top+height+4, left:left - 3}).fadeIn('slow');
		else 
			console.log ' empty '

	open_student_list: ( e ) ->
		Arrange.check_full()
		$('.selected').removeClass('selected')
		Arrange.selected_boat = $(e.target).parent().addClass('selected')
		Arrange.attach_list Arrange.selected_boat
		console.log [ e.target, Arrange.selected_boat ]

	close_student_list: (e) ->
		$('.boat.unassigned').fadeOut('fast');

	add_student: (e) ->
		toMove = $(this).parent()
		hasSki = toMove.hasClass('hasIt')
		boatSkis = Arrange.selected_boat.find('ul.hasIt').size()
		boatLoad = Arrange.check_full()

		if ( hasSki && (parseInt boatSkis) >3 )
			console.log [ 'too many skis', hasSki, boatSkis ]
		else 
			if ( parseInt(boatLoad) > 7 )
				console.log [ 'too many stuents', boatLoad ]
			else
				last_boat = toMove.parent().removeClass('full').attr('id_boat')
				Arrange.selected_boat.append( toMove.detach() )
				Arrange.attach_list Arrange.selected_boat
				Ajax.post 'service/reassign', 
						{	id_student: toMove.attr('id_student'), id_boat: Arrange.selected_boat.attr('id_boat'), last_boat: last_boat }
    Arrange.check_full()

	check_full: () ->
		if Arrange.selected_boat 
			boatLoad = Arrange.selected_boat.find('ul').size()
			if  (parseInt(boatLoad) > 7)
				Arrange.selected_boat.addClass('full')
			else
				Arrange.selected_boat.removeClass('full')
			boatLoad

setTimeout Arrange.bind, 200
