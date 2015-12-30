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
