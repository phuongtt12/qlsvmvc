<?php 
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
class StudentController {
	function list() {
		$search =!empty($_GET["search"]) ? $_GET["search"] : "";
			//đã require StudentRepository trong file index.php nên ở đây có thể dùng dc
		$studentRepository = new StudentRepository();

		if (!empty($search)) {
			$students = $studentRepository->getByPattern($search);
		}
		else {
			$students = $studentRepository->getAll();
		}

		require "view/student/list.php";
	}

	function add() {
		require "view/student/add.php";
	}

	function save() {
		$data = $_POST;
		$studentRepository = new StudentRepository();
		if(!$studentRepository->save($data)) {
			$_SESSION["error"] = $studentRepository->getError();
		}
		else {
			$_SESSION["success"] = "Đã tạo sv mới thành công";
		}
		header("location: /");
	}
	function edit() {
		$id = $_GET["id"];
		$studentRepository = new StudentRepository();
		$student = $studentRepository->find($id);
		require "view/student/edit.php";
	}
	function update() {
		$id = $_POST["id"];
		$name = $_POST["name"];
		$birthday = $_POST["birthday"];
		$gender = $_POST["gender"];
		$studentRepository = new StudentRepository();
		$student = $studentRepository->find($id);
		$student->setName($name);
		$student->setBirthday($birthday);
		$student->setGender($gender);
		if(!$studentRepository->update($student)) {
			$_SESSION["error"] = $studentRepository->getError();
		}
		else {
			$_SESSION["success"] = "Đã cập nhật sv mới thành công";
		}
		header("location: /");
	}
	function delete() {
		$id = $_GET["id"];
		$studentRepository = new StudentRepository();
		if(!$studentRepository->delete($id)) {
			$_SESSION["error"] = $studentRepository->getError();
		}
		else {
			$_SESSION["success"] = "Đã xóa sv thành công";
		}
		header("location: /");
	}
	function formImport() {
		require "view/student/formImport.php";
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
		$studentRepository = new StudentRepository();
		$genderMapRevert = ["nam"=> 0, "nữ"=> 1, "khác"=> 2 ];
		for ($row = $start; $row <= $end; $row++) {
			$id 		= $sheet->getCell("A$row")->getValue();
			$name 		= $sheet->getCell("B$row")->getValue();
			$birthday 	= $sheet->getCell("C$row")->getValue();
			if (!DateTime::createFromFormat('Y-m-d', $birthday) !== false){
				$timestame = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToTimestamp($birthday);
				$birthday = date("Y-m-d", $timestame);
			}
			
			$genderName = $sheet->getCell("D$row")->getValue();
			
			if (empty($id)) {
				continue;
			}
			$student = $studentRepository->find($id);
			if (!empty($student)) {
				//update
				$student->setName($name);
				$student->setBirthday($birthday);
				//need to change from string to int (0,1,2)
				$student->setGender($genderMapRevert[$genderName]);
				
				if(!$studentRepository->update($student)) {
					$_SESSION["error"] = $studentRepository->getError();
					header("location: /");
					exit;
				}

			}
			else {
				//insert
				$data = [];
				$data["id"] = $id;
				$data["name"] = $name;
				$data["birthday"] = $birthday;
				//need to change from string to int (0,1,2)
				$data["gender"] = $genderMapRevert[$genderName];

				if(!$studentRepository->save($data)) {
					$_SESSION["error"] = $studentRepository->getError();
					header("location: /");
					exit;
				}

			}
		}
		$_SESSION["success"] = "Đã import danh sách sv thành công";
		header("location: /");

	}
	function export() {
		//download file excel
		// $file = "upload/ExportStudentList.xlsx";
		// header("Content-Type: application/vnd.ms-excel");
		// header("Content-Disposition: attachment; filename=$file");
		// header("Pragma: no-cache");
		// header("Expires: 0");
		// echo readfile($file);

		// Cách 2: export file excel
		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();
		// Get list student and set into excel
		$studentRepository = new StudentRepository();
		$students = $studentRepository->getAll();

		$sheet->setCellValue("A1", "MaSv");
		$sheet->setCellValue("B1", "Tên");
		$sheet->setCellValue("C1", "Ngày sinh");
		$sheet->setCellValue("D1", "Giới tính");

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
		$spreadsheet->getActiveSheet()->getStyle('A1:D1')->applyFromArray($styleArray);

			$row = 2;
			foreach ($students as $student) {
			# code...
				$sheet->setCellValue("A$row", $student->getId());
				$sheet->setCellValue("B$row", $student->getName());
				$sheet->setCellValue("C$row", $student->getBirthday());
				$sheet->setCellValue("D$row", $student->getGenderName());
				$row++;
			}

			$writer = new XLsx($spreadsheet);
			$fileName = "ExportListStudent.xlsx";
			header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
			header('Content-Disposition: attachment; filename="'. urlencode($fileName).'"');
			$writer->save("php://output");
		}
	}

	?>