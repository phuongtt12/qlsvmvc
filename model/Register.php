<?php 
	class Register {
		protected $student_id; 
		protected $subject_id; 
		protected $score;
		protected $student_name;
		protected $subject_name;
		

		function __construct($student_id, $subject_id, $score, $student_name, $subject_name) {
			$this->student_id = $student_id; //$this: truy xuất vào thuộc tính student_id của class
			$this->subject_id = $subject_id;
			$this->score = $score;
			$this->student_name = $student_name;
			$this->subject_name = $subject_name;
		}
		
		// mặc định tầm vực truy xuất của thuộc tính là hàm là public
		function getStudentId() {
			return $this->student_id;
		}

		function getSubjectId() {
			return $this->subject_id;
		}
		function getScore() {
			return $this->score;
		}

		function getStudentName() {
			return $this->student_name;
		}

		function getSubjectName() {
			return $this->subject_name;
		}

		function setScore($score) {
			$this->score = $score;
			return $this;  //return về $this để có thể set liên tục nếu cần
		}

	}
 ?>