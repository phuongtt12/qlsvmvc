<?php 
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
class RegisterController {
	function list() {
		$search =!empty($_GET["search"]) ? $_GET["search"] : "";
			//đã require registerRepository trong file index.php nên ở đây có thể dùng dc
		$registerRepository = new RegisterRepository();

		if (!empty($search)) {
			$registers = $registerRepository->getByPattern($search);
		}
		else {
			$registers = $registerRepository->getAll();
		}

		require "view/register/list.php";
	}

	function add() {
		$studentRepository = new StudentRepository();
		$students = $studentRepository->getAll();
		require "view/register/add.php";
	}

	function listSubject() {
		$student_id = $_GET["student_id"];
		$subjectRepository = new SubjectRepository();
		$subjects = $subjectRepository->getNotUnregistered($student_id);
		$notRegisteredSubjects = [];
		foreach ($subjects as $subject) {
			$notRegisteredSubjects[] = ["id" => $subject->getId(), "name" => $subject->getName()];
		}
		echo json_encode($notRegisteredSubjects);
	}

	function save() {
		$data = $_POST;
		$registerRepository = new RegisterRepository();
		if(!$registerRepository->save($data)) {
			$_SESSION["error"] = $registerRepository->getError();
		}
		else {
			$_SESSION["success"] = "Đã tạo đăng ký môn học mới thành công";
		}
		header("location: /?c=register");
	}
	function edit() {
		$student_id = $_GET["student_id"];
		$subject_id = $_GET["subject_id"];
		$registerRepository = new RegisterRepository();
		$register = $registerRepository->find($student_id, $subject_id);
		require "view/register/edit.php";
	}
	function update() {
		$student_id = $_POST["student_id"];
		$subject_id = $_POST["subject_id"];
		$score = $_POST["score"];
		$registerRepository = new RegisterRepository();
		$register = $registerRepository->find($student_id, $subject_id);
		$register->setScore($score);
		
		if(!$registerRepository->update($register)) {
			$_SESSION["error"] = $registerRepository->getError();
		}
		else {
			$_SESSION["success"] = "Đã cập nhật điểm thành công";
		}
		header("location: /?c=register");
	}
	function delete() {
		$student_id = $_GET["student_id"];
		$subject_id = $_GET["subject_id"];
		$registerRepository = new RegisterRepository();
		if(!$registerRepository->delete($student_id, $subject_id)) {
			$_SESSION["error"] = $registerRepository->getError();
		}
		else {
			$_SESSION["success"] = "Đã xóa đăng ký môn học thành công";
		}
		header("location: /?c=register");
	}
	function formImport() {
		require "view/register/formImport.php";
	}
	function import() {
		$inputFileName = $_FILES["excel"]["tmp_name"];
		/** Load $inputFileName to a Spreadsheet Object  **/
		$spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($inputFileName);
		$sheet = $spreadsheet->getActiveSheet();
		//echo $sheet->getCell("B2")->getValue(); //B2: cột B dòng 2
		$start = 2;
		$end = $sheet->getHighestRow();
		$registerRepository = new RegisterRepository();
		
		for ($row = $start; $row <= $end; $row++) {
			$student_id 	= $sheet->getCell("A$row")->getValue();
			$student_name	= $sheet->getCell("B$row")->getValue();
			$subject_id 	= $sheet->getCell("C$row")->getValue();
			$subject_name 	= $sheet->getCell("D$row")->getValue();
			$score 			= $sheet->getCell("E$row")->getValue();

			// if (empty($id)) {
			// 	continue;
			// }
			$register = $registerRepository->find($student_id, $subject_id);
			if (!empty($register)) {
				//update
				$register->setScore($score);
				
				if(!$registerRepository->update($register)) {
					$_SESSION["error"] = $registerRepository->getError();
					header("location: /?c=register");
					exit;
				}

			}
			else {
				//insert
				$data = [];
				$data["student_id"] = $student_id;
				$data["subject_id"] = $subject_id;
				$data["score"] = $score;
				
				if(!$registerRepository->save($data)) {
					$_SESSION["error"] = $registerRepository->getError();
					header("location: /?c=register");
					exit;
				}

			}
		}
		$_SESSION["success"] = "Đã import danh sách đăng ký môn học thành công";
		header("location: /?c=register");

	}
	function export() {

		//Cach 2: export file excel
		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();
		// Get list register and set into excel
		$registerRepository = new RegisterRepository();
		$registers = $registerRepository->getAll();

		$sheet->setCellValue("A1", "MaSV");
		$sheet->setCellValue("B1", "TênSV");
		$sheet->setCellValue("C1", "MaMH");
		$sheet->setCellValue("D1", "TenMH");
		$sheet->setCellValue("E1", "Điểm");

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
		$spreadsheet->getActiveSheet()->getStyle('A1:E1')->applyFromArray($styleArray);

			$row = 2;
			foreach ($registers as $register) {
			# code...
				$sheet->setCellValue("A$row", $register->getStudentId());
				$sheet->setCellValue("B$row", $register->getStudentName());
				$sheet->setCellValue("C$row", $register->getSubjectId());
				$sheet->setCellValue("D$row", $register->getSubjectName());
				$sheet->setCellValue("E$row", $register->getScore());
				
				$row++;
			}

			$writer = new XLsx($spreadsheet);
			$fileName = "ExportListRegister.xlsx";
			header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
			header('Content-Disposition: attachment; filename="'. urlencode($fileName).'"');
			$writer->save("php://output");
		
	}

}
?>