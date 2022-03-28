<?php 
	class Student {
		protected $id; //protected chỉ gọi dc bên trong class, ko gọi dc bên ngoài
		protected $name; //tầm vực public gọi ở đâu cũng dc
		protected $birthday;
		protected $gender;

		function __construct($id, $name, $birthday, $gender) {
			$this->id = $id; //$this: truy xuất vào thuộc tính id của class
			$this->name = $name;
			$this->birthday = $birthday;
			$this->gender = $gender;
		}
		
		// mặc định tầm vực truy xuất của thuộc tính là hàm là public
		function getId() {
			return $this->id;
		}

		function getName() {
			return $this->name;
		}

		function getBirthday() {
			return $this->birthday;
		}

		function getGender() {
			return $this->gender;
		}
		
		function getGenderName() {
			$genderMap = [0=> "nam", 1=> "nữ", 2=> "khác"];
			return $genderMap[$this->gender];
		}

		function setName($name) {
			$this->name = $name;
			return $this;
		}

		function setBirthday($birthday) {
			$this->birthday = $birthday;
			return $this;
		}

		function setGender($gender) {
			$this->gender = $gender;
			return $this;
		}

		
	}
	
 ?>