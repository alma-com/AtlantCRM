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
			
			if(!popstate){
				history.pushState({page: page, type: "page"}, title, page);
			}
		},
		error: function() {
			alert('Произошла ошибка!');
		}
	});
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
			if(data.hasOwnProperty('url')){
				setPage(data.url);
			}
			
			if(data.hasOwnProperty('description')){
				scrollTo();
				$('#content-alert').hide(0);
				$('#content-alert').html(data.description);
				$('#content-alert').delay(300).slideDown(300);
			}
			
			if(data.hasOwnProperty('errFields')){
				$.each(data.errFields, function(index, value) {
					$form.find(':input[name="'+index+'"]').closest('.form-group').addClass('has-error');
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
				case 'error':
					notie.alert(3, data.message, 1.5);
				break
				default:
					notie.alert(1, data.message.toString(), 1.5);
			}
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
		
		$wrapConfirm = $('#confirmModal');
		var $form = $(this);
		var confirm = $form.attr('data-confirm') || '';
		
		if(confirm != ''){			
			// Перед открыванием модального окна
			$wrapConfirm.on('show.bs.modal', function (event) {
				$(this).find('.modal-body').html(confirm);
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
				ajaxForm($form);
				$(this).unbind('submit');
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
		} else {
			//Check all checkboxes
			$("input[name='"+name+"']").iCheck("check");
			$(".checkbox-toggle[data-name='"+name+"']").iCheck("check");
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



