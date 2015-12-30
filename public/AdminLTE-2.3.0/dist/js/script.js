/*
*	DataTable
*/
$(".data-table").DataTable({
	"language": {"url": "//cdn.datatables.net/plug-ins/1.10.10/i18n/Russian.json"},
	"pageLength": 25
});

$(".data-table-small").DataTable({
	"language": {"url": "//cdn.datatables.net/plug-ins/1.10.10/i18n/Russian.json"},
	"pageLength": 25,
	"paging": false,
	"lengthChange": false,
	"searching": false,
	"ordering": true,
	"info": false,
	"autoWidth": false
});


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
$(".checkbox-toggle").click(function () {
	var clicks = $(this).data('clicks');
	var name = $(this).data('name');
	if (clicks) {
		//Uncheck all checkboxes
		$("input[name='"+name+"']").iCheck("uncheck");
		$(".checkbox-toggle[data-name='"+name+"']").find(".fa").removeClass("fa-check-square-o").addClass('fa-square-o');
	} else {
		//Check all checkboxes
		$("input[name='"+name+"']").iCheck("check");
		$(".checkbox-toggle[data-name='"+name+"']").find(".fa").removeClass("fa-square-o").addClass('fa-check-square-o');
	}
	$(this).data("clicks", !clicks);
});