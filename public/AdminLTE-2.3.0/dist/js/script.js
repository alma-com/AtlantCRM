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