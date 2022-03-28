$("#student_id").change(function(event) {
	/* Act on the event */
	var student_id = $(this).val();
	$("#subject_id").children().not(":first-child").remove();
	if (student_id=="") {
		return;
	}
	$("#load").html("Loading...");
	$.ajax({
		url: 'index.php?c=register&a=listSubject',
		data: {student_id: student_id},
	})
	.done(function(data) {
		var subjects = JSON.parse(data);
		$(subjects).each(function(index, el) {
			var option = "<option value='" + el.id + "'>" + el.id + " - "+  el.name+ "</option>";
			$("#subject_id").append(option)
		});
		$("#load").empty();
		console.log("success");
	})
	.fail(function() {
		console.log("error");
	})
	.always(function() {
		console.log("complete");
	});
	
});

$(".delete").click(function(event) {
	/* Act on the event */
	var url = $(this).attr("url");
	$("#deletingConfirmModal a").attr("href", url);
	$("#deletingConfirmModal").modal("show");
});

