$(document).ready(function() {
	initAjaxClass();
	initAjaxForm();
	initRemoveError();
	initiCheck();
	initiCheckboxToggle();
	initDataTable();

	$.srSmoothscroll({
		step: 100,
		speed: 100,
		ease: 'swing',
		target: $('body'),
		container: $(window)
	});

});

$.ajaxSetup({
    headers: {
        'X-XSRF-TOKEN': $('input[name="csrf-token"]').val()
    }
});



/*
* ajaxStart
*/
$(document).ajaxStart(function() {
	Pace.restart();
});



/*
* Ajax загрузка страницы по url
*/
function setPage(page, popstate){
	var page = page || '';
	var popstate = popstate || ''; 
	
	//Выход из функции
	if(page == ''){
		return false;
	}
	
	$('.sidebar-menu a').each(function(){
		var url = $(this).attr('href');
		if(page == url){
			$(this).closest('li').addClass('active');
			$(this).closest('li').parent().closest('li').addClass('active');
		}else{
			$(this).closest('li').removeClass('active');
		}
	});

	$.ajax({
		url: page,
		type: 'GET',
		data: {
			'_token' : $('meta[name="csrf-token"]').attr('content')
		},
		success: function(data){
			var title = document.title;
			var content = '';
			var content_header = '';

			if(data.hasOwnProperty('title')){title = data['title'];}
			if(data.hasOwnProperty('content')){content = data['content'];}
			if(data.hasOwnProperty('content-header')){content_header = data['content-header'];}


			document.title = title;
			$("#ajax-content").html(content);
			$('.content-header').html(content_header);
			scrollTo();

			initAjaxClass();
			initAjaxForm();
			initRemoveError();
			initiCheck();
			initiCheckboxToggle();
			initDataTable();

			if(popstate == ''){
				history.pushState({page: page, type: "page"}, title, page);
			}
		},
		error: function() {
			alert('Произошла ошибка!');
		}
	});
	return false;
}


if (history.pushState) {
	history.pushState({page: window.location.pathname, type: "page"}, document.title, window.location.pathname);

	window.addEventListener('popstate', function(e){
		setPage(e.state.page, 'popstate');
	}, false);

	$(document).on('click','a:not(.no-ajax):not([href="#"])',function(e){
		setPage($(this).attr('href'));
		return false;
	});
}



/*
* Отправка формы через ajax
* 	data {
* 		'status': 'success|warning|error|info|',
* 		'message': 'Краткий текст сообщения',
* 		'description': 'Подробный текст сообщения',
* 		'errFields': {
*			'name' : {'Поле "Имя" обязательно для заполнения.'}
*		},
* 		'url': '/url/to/redirect',
* 	}
*
*/
function ajaxForm($form){
	var url = $form.attr('action');
	var method = $form.attr('method');
	var data = $form.serializeArray();

	$.ajax({
		url: url,
		type: method,
		data: data,
		success: function(data){
			successDo(data, $form);
		},
		error: function() {
			notie.alert(3, 'Произошла ошибка', 1.5);
		}
	});
}



/*
* initAjaxForm ajax form
*/
function initAjaxForm(){
	$("form:not(.no-ajax)").submit(function(event){
		event.preventDefault();

		var $form = $(this);
		var confirm = $form.attr('data-confirm') || '';

		if(confirm != ''){
			confirmCall(confirm, function(){
				ajaxForm($form);
			});
			return false;
		}

		ajaxForm($form);
	});
}



/*
* initAjaxClass клик на ссылку с классом ajax
*/
function initAjaxClass(){
	$('.ajax').click(function(event){
		event.preventDefault();

		var url = $(this).attr('href') || $(this).attr('data-url');		//URL
		var method = $(this).attr('data-method') || 'GET';			//Метод POST или GET
		var content = $(this).attr('data-content') || 'ajax-content';		//Контейнер в который вставится результат

		if(url && method && content){
			$.ajax({
				url: url,
				type: method,
				data: {
					'_token' : $('meta[name="csrf-token"]').attr('content')
				},
				success: function(result){
					$('.'+content).html(result);
				}
			});
		}else{
			if(!url){alert = 'Нет url';}
			if(!method){alert = 'Нет method';}
			if(!content){alert = 'Нет content';}
		}
	});
}



/*
* iCheck
*/
function initiCheck(){
	$('input[type="checkbox"]').iCheck({
		checkboxClass: 'icheckbox_flat-blue',
		radioClass: 'iradio_flat-blue'
	});
	
	$("input[name='item[]']").on('ifClicked', function () {
		$(this).iCheck("toggle");

		if($("input[name='item[]']:checked").length > 0){
			$('.table-controls .btn-group').find(':input').removeClass('disabled');
		}else{
			$('.table-controls .btn-group').find(':input').addClass('disabled');
		}
	});
}


/*
* checkbox-toggle
*/
function initiCheckboxToggle(){
	$(".checkbox-toggle").on('ifToggled', function () {
		var clicks = $(this).data('clicks');
		var name = $(this).data('name');
		if (clicks) {
			//Uncheck all checkboxes
			$("input[name='"+name+"']").iCheck("uncheck");
			$(".checkbox-toggle[data-name='"+name+"']").iCheck("uncheck");
			$('.table-controls .btn-group').find(':input').addClass('disabled');
		} else {
			//Check all checkboxes
			$("input[name='"+name+"']").iCheck("check");
			$(".checkbox-toggle[data-name='"+name+"']").iCheck("check");
			$('.table-controls .btn-group').find(':input').removeClass('disabled');
		}
		$(this).data("clicks", !clicks);
	});
}


/*
*	DataTable
*/
function initDataTable(){
	$(".data-table").DataTable({
		"language": {"url": "//cdn.datatables.net/plug-ins/1.10.10/i18n/Russian.json"},
		"pageLength": 25,
		"columnDefs": [{ targets: 'no-sort', orderable: false, width: "20px" }],
		"lengthMenu": [[25, 50, 100, -1], [25, 50, 100, "Все"]],
		stateSave: true
	});

	$(".data-table-small").DataTable({
		"language": {"url": "//cdn.datatables.net/plug-ins/1.10.10/i18n/Russian.json"},
		"pageLength": 25,
		"paging": false,
		"lengthChange": false,
		"searching": false,
		"ordering": true,
		"info": false,
		"autoWidth": false,
		"columnDefs": [{ targets: 'no-sort', orderable: false, width: "20px" }],
		stateSave: true
	});
}


//При фокусе убрать красную обводку
function initRemoveError(){
	$(':input').on('focus', function() {
		$(this).closest('.form-group').removeClass('has-error');
	});
}



/*
* Скролл к элементу
*/
function scrollTo($elem){
	var offset = 0;
	if($elem){
		offset = $elem.offset().top/1 + 100;
	}
	var body = $("html, body");
	body.stop().animate({scrollTop: offset}, '300', 'swing');
}



/*
* Вызов confirm
*/
function confirmCall(text, yesCallback){
	var text = text || '';
	var yesCallback = yesCallback || '';
	var $wrapConfirm = $('#confirmModal');
	
	// Перед открыванием модального окна
	$wrapConfirm.on('show.bs.modal', function (event) {
		$(this).find('.modal-body').html(text);
		$(this).unbind('show.bs.modal');
	});

	// Когда модальное окно видно
	$wrapConfirm.on('shown.bs.modal', function (event) {
		$(this).find(':submit').focus();
		$(this).unbind('shown.bs.modal');
	});

	//Показ модального окна
	$wrapConfirm.modal('show');

	// При нажатии на кнопку ок
	$wrapConfirm.find("form").submit(function (event) {
		event.preventDefault();
		$wrapConfirm.modal('hide');
		
		yesCallback();
		
		$(this).unbind('submit');
	});
	
	
	// При скрытии окна сбрасываем события
	$wrapConfirm.on('hide.bs.modal', function (event) {
		$wrapConfirm.find("form").unbind('submit');
		$(this).unbind('hide.bs.modal');
	});
	return false;
}



/*
* Вызов события в контроллере
*/
function actionCall(el, url, confirm){
	var el = el || '';
	var url = url || '';
	var confirm = confirm || '';
	
	if(el != '' && $(el).hasClass('disabled')){
		return false;
	}
	
	if(confirm != ''){
		confirmCall(confirm, function(){
			actionCall(url);
		});
		return false;
	}

	var $form = $('#form-items');
	var data = $('#form-items').serializeArray();	
	$.ajax({
		url: url,
		type: 'POST',
		data: data,
		success: function(data){
			successDo(data, $form);
		},
		error: function() {
			notie.alert(3, 'Произошла ошибка', 1.5);
		}
	});
	
	return false;
}



/*
*  стандартные действия при успешно ajax
*/
function successDo(data, $form){
	var data = data || '';
	var $form = $form || '';
	//console.log(data);
	
	if(data.url != ''){
		setPage(data.url);
	}
	
	$('#content-alert').slideUp(0);
	if(data.description != ''){
		scrollTo();
		$('#content-alert').slideUp(0);
		$('#content-alert').html(data.description);
		$('#content-alert').delay(300).slideDown(300);
	}

	if(data.errFields != ''){
		$.each(data.errFields, function(index, value) {
			if(index.indexOf("table_") === -1){
				//Поля формы
				$form.find(':input[name="'+index+'"]').closest('.form-group').addClass('has-error');
			}else{
				//Поля таблицы
				var item = index.replace("table_", "");
				blinkElem($('tr[data-item="'+item+'"]').delay(300));
			}
		});
	}


	switch (data.status) {
		case 'success':
			notie.alert(1, data.message.toString(), 1.5);
		break
		case 'info':
			notie.alert(4, data.message.toString(), 1.5);
		break
		case 'warning':
			notie.alert(2, data.message, 1.5);
		break
		case 'error':
			notie.alert(3, data.message, 1.5);
		break
		default:
			notie.alert(1, data.message.toString(), 1.5);
	}
	
	return false;
}



/*
* Мигание элемента
*/
function blinkElem($elem, count){
	var $elem = $elem || '';
	var count = count || 3;
	if($elem != ''){
		for(i=0;i<count;i++) {
			$elem.fadeTo(250, 0.3).fadeTo(250, 1.0);
		}
	}
}



/**
 * Где выделены checkbox появляются input
 */
function editTable(el, type){
	var el = el || '';
	var type = type || 'show';
	var $form = $('#form-items');
	
	if(el != '' && $(el).hasClass('disabled')){
		return false;
	}
	
	if(type == 'show'){
		
		$form.find("input[type='checkbox']").iCheck("disable");
		
		$form.find("input[name='item[]']:checked").each(function(){
			var id = $(this).val();
			$form.find('tr[data-item="'+id+'"]').addClass('show-input');
		});
		
		$form.find('.table-controls').addClass('edit');
	}
	
	
	if(type == 'hide'){
		$form.find("input[type='checkbox']").iCheck("enable");
		$form.find('tr').removeClass('show-input');
		$form.find('.table-controls').removeClass('edit');
	}
	return false;
}