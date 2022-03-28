<?php require "layout/header.php" ?>
<form action="/?c=register&a=import" class="container-fluid" style="max-width: 500px" enctype="multipart/form-data" method="POST">
	<h1>Upload Danh sách Môn học</h1>
	<div class="input-group mb-3">
		<div class="input-group-prepend">
			<span class="input-group-text">Upload</span>
		</div>
		<div class="custom-file">
			<input type="file" class="custom-file-input" id="excel" name="excel">
			<label class="custom-file-label" for="excel">Choose file</label>
		</div>
	</div>
	<div class="form-group" id="filename">

	</div>
	<div class="form-group text-right">
		<button type="submit" class="btn btn-primary">Submit</button>
	</div>
</form>
<?php require "layout/footer.php" ?>