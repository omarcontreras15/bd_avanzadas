<?php

	class ClassUtil{

		private $atribute1;
		private $atribute2;
		private $atribute3;
		private $atribute4;
		private $atribute5;
		private $atribute6;

		public function __construct($atribute1, $atribute2, $atribute3, $atribute4, $atribute5, $atribute6){
			$this->$atribute1 = $atribute1;
			$this->$atribute2 = $atribute2;
			$this->$atribute3 = $atribute3;
			$this->$atribute4 = $atribute4;
			$this->$atribute5 = $atribute5;
			$this->$atribute6 = $atribute6;
		}

		public function getAtribute1(){
			return $this->$atribute1;
		}

		public function setAtribute1($atribute1){
			$this->$atribute1 = $atribute1;
		}

		public function getAtribute2(){
			return $this->$atribute2;
		}

		public function setAtribute2($atribute2){
			$this->$atribute2 = $atribute2;
		}

		public function getAtribute3(){
			return $this->$atribute3;
		}

		public function setAtribute3($atribute3){
			$this->$atribute3 = $atribute3;
		}

		public function getAtribute4(){
			return $this->$atribute4;
		}

		public function setAtribute4($atribute4){
			$this->$atribute4 = $atribute4;
		}

		public function getAtribute5(){
			return $this->$atribute5;
		}

		public function setAtribute5($atribute5){
			$this->$atribute5 = $atribute5;
		}

		public function getAtribute6(){
			return $this->$atribute6;
		}

		public function setAtribute6($atribute6){
			$this->$atribute6 = $atribute6;
		}

	}

?>