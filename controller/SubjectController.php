<?php 
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
class SubjectController {
	function list() {
		$search =!empty($_GET["search"]) ? $_GET["search"] : "";
			//đã require subjectRepository trong file index.php nên ở đây có thể dùng dc
		$subjectRepository = new SubjectRepository();

		if (!empty($search)) {
			$subjects = $subjectRepository->getByPattern($search);
		}
		else {
			$subjects = $subjectRepository->getAll();
		}

		require "view/subject/list.php";
	}

	function add() {
		require "view/subject/add.php";
	}

	function save() {
		$data = $_POST;
		$subjectRepository = new SubjectRepository();
		if(!$subjectRepository->save($data)) {
			$_SESSION["error"] = $subjectRepository->getError();
		}
		else {
			$_SESSION["success"] = "Đã tạo môn học mới thành công";
		}
		header("location: /?c=subject");
	}
	function edit() {
		$id = $_GET["id"];
		$subjectRepository = new SubjectRepository();
		$subject = $subjectRepository->find($id);
		require "view/subject/edit.php";
	}
	function update() {
		$id = $_POST["id"];
		$name = $_POST["name"];
		$number_of_credit = $_POST["number_of_credit"];
		$subjectRepository = new SubjectRepository();
		$subject = $subjectRepository->find($id);
		$subject->setName($name);
		$subject->setNumberOfCredit($number_of_credit);
		
		if(!$subjectRepository->update($subject)) {
			$_SESSION["error"] = $subjectRepository->getError();
		}
		else {
			$_SESSION["success"] = "Đã cập nhật môn học mới thành công";
		}
		header("location: /?c=subject");
	}
	function delete() {
		$id = $_GET["id"];
		$subjectRepository = new SubjectRepository();
		if(!$subjectRepository->delete($id)) {
			$_SESSION["error"] = $subjectRepository->getError();
		}
		else {
			$_SESSION["success"] = "Đã xóa môn học thành công";
		}
		header("location: /?c=subject");
	}
	function formImport() {
		require "view/subject/formImport.php";
	}
	function import() {
		require 'vendor/autoload.php';
		$inputFileName = $_FILES["excel"]["tmp_name"];
		/** Load $inputFileName to a Spreadsheet Object  **/
		$spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($inputFileName);
		$sheet = $spreadsheet->getActiveSheet();
		//echo $sheet->getCell("B2")->getValue(); //B2: cột B dòng 2
		$start = 2;
		$end = $sheet->getHighestRow();
		$subjectRepository = new SubjectRepository();
		
		for ($row = $start; $row <= $end; $row++) {
			$id 	= $sheet->getCell("A$row")->getValue();
			$name 	= $sheet->getCell("B$row")->getValue();
			$number_of_credit = $sheet->getCell("C$row")->getValue();
			
			if (empty($id)) {
				continue;
			}
			$subject = $subjectRepository->find($id);
			if (!empty($subject)) {
				//update
				$subject->setName($name);
				$subject->setNumberOfCredit($number_of_credit);
				
				if(!$subjectRepository->update($subject)) {
					$_SESSION["error"] = $subjectRepository->getError();
					header("location: /?c=subject");
					exit;
				}

			}
			else {
				//insert
				$data = [];
				$data["id"] = $id;
				$data["name"] = $name;
				$data["number_of_credit"] = $number_of_credit;
				
				if(!$subjectRepository->save($data)) {
					$_SESSION["error"] = $subjectRepository->getError();
					header("location: /?c=subject");
					exit;
				}

			}
		}
		$_SESSION["success"] = "Đã import danh sách môn học thành công";
		header("location: /?c=subject"); 
		//khi cần test lỗi nên command dòng header
		// var_dump($_FILES);

	}
	function export() {
		// Cach 1: download file excel - export
		// $file = "upload/ExportSubjectList.xlsx";
		// header("Content-Type: application/vnd.ms-excel");
		// header("Content-Disposition: attachment; filename=$file");
		// header("Pragma: no-cache");
		// header("Expires: 0");
		// echo readfile($file);

		//Cach 2: export file excel
		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();
		// Get list subject and set into excel
		$subjectRepository = new SubjectRepository();
		$subjects = $subjectRepository->getAll();

		$sheet->setCellValue("A1", "MaMH");
		$sheet->setCellValue("B1", "Tên");
		$sheet->setCellValue("C1", "Number_of_credit");

		//Tạo màu cho các ô trong excel
		$styleArray = [                       
				'font' => [
					'color' => ['argb' => 'FFFFFFFF'],
				],
				'fill' => [
			        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
			        'startColor' => [
			            'argb' => 'FF375623',
			        ]
			    ]
			];
		$spreadsheet->getActiveSheet()->getStyle('A1:C1')->applyFromArray($styleArray);

			$row = 2;
			foreach ($subjects as $subject) {
			# code...
				$sheet->setCellValue("A$row", $subject->getId());
				$sheet->setCellValue("B$row", $subject->getName());
				$sheet->setCellValue("C$row", $subject->getNumberOfCredit());
				$row++;
			}

			$writer = new XLsx($spreadsheet);
			$fileName = "ExportListSubject.xlsx";
			header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
			header('Content-Disposition: attachment; filename="'. urlencode($fileName).'"');
			$writer->save("php://output");
		
	}

}
?>