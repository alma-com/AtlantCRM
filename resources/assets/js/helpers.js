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
			$("#"+ID_AJAX_CONTENT).html(content);
			$('.'+CLASS_CONTENT_HEADER).html(content_header);
			scrollTo();

			initAjaxClass();
			initAjaxForm();
			initRemoveError();
			initCheck();
			initCheckboxToggle();
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
	var $wrapConfirm = $('#'+CONFIRM_MODAL);
	
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
	
	if(el != '' && $(el).hasClass(BUTTON_DISABLED)){
		return false;
	}
	
	if(confirm != ''){
		confirmCall(confirm, function(){
			actionCall(url);
		});
		return false;
	}

	var $form = $('#'+FORM_ITEMS);
	var data = $form.serializeArray();	
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
	
	$('#'+ID_CONTENT_HEADER).slideUp(0);
	if(data.description != ''){
		scrollTo();
		$('#'+ID_CONTENT_HEADER).slideUp(0);
		$('#'+ID_CONTENT_HEADER).html(data.description);
		$('#'+ID_CONTENT_HEADER).delay(300).slideDown(300);
	}

	if(data.errFields != ''){
		$.each(data.errFields, function(index, value) {
			if(index.indexOf("table_") === -1){
				//Поля формы
				$form.find(':input[name="'+index+'"]').closest('.form-group').addClass(CLASS_ERROR);
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
	var $form = $('#'+FORM_ITEMS);
	
	if(el != '' && $(el).hasClass(BUTTON_DISABLED)){
		return false;
	}
	
	if(type == 'show'){
		
		$form.find("input[type='checkbox']").iCheck("disable");
		
		$form.find("input[name='"+NAME_CHECKBOX_TABLE+"']:checked").each(function(){
			var id = $(this).val();
			$form.find('tr[data-item="'+id+'"]').addClass(CLASS_SHOW_INPUT);
		});
		
		$form.find('.'+TABLE_CONTROLS).addClass(TABLE_CONTROLS_EDIT);
	}
	
	
	if(type == 'hide'){
		$form.find("input[type='checkbox']").iCheck("enable");
		$form.find('tr').removeClass(CLASS_SHOW_INPUT);
		$form.find('.'+TABLE_CONTROLS).removeClass(TABLE_CONTROLS_EDIT);
	}
	return false;
}