/*
* ajaxStart
*/
$(document).ajaxStart(function() {
	Pace.restart();
});
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



/*
* AJAX click
*/
function setPage(page, popstate){
	$wrap = $("#ajax-content");
	
	$.get(page, function(data){
		var title = document.title;
		var content = '';
		var content_header = '';
		
		if(data.hasOwnProperty('title')){title = data['title'];}
		if(data.hasOwnProperty('content')){content = data['content'];}
		if(data.hasOwnProperty('content-header')){content_header = data['content-header'];}

		
		document.title = title;
		$wrap.html(content);
		$('.content-header').html(content_header);
		
		if(!popstate){
			history.pushState({page: page, type: "page"}, title, page);
		}        
	})  
}


if (history.pushState) {
	history.pushState({page: window.location.pathname, type: "page"}, document.title, window.location.pathname);
	 
	window.addEventListener('popstate', function(e){
		setPage(e.state.page, 'popstate');
	}, false);
	
	$(document).on('click','a:not(.no-ajax)',function(e){
		setPage($(this).attr('href'));
		return false;
	});
}



/*
* iCheck
*/
$('input[type="checkbox"]').iCheck({
	checkboxClass: 'icheckbox_flat-blue',
	radioClass: 'iradio_flat-blue'
});



/*
* checkbox-toggle
*/
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



/*
*	DataTable
*/
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