<?php

/**
 * SattleGroupExpense - An expense calculator Class
 *
 * @author   Muhammed Imran <imranmailbd@gmail.com>
 */


class SattleGroupExpense {


	public $bill_input;

	public $payers_name;

	public $spender_name;

	public $sorted_nearest_obj;

	public $sattle_ower_info;

	public $sattle_payer_info;


	/*
	 * in constructor function we initialize the dependency object.
	 * also here we call the data source method "get_bill_input" to get the Raw Data as JSON format.
	 * in future there are also more scope to make the data source more visual by 
	 * get the data input direct from user input from system frontend
	 * 
	 */
	public function __construct(SortedNearest $sortNearest, $data_source_path){		

		$this->sorted_nearest_obj = $sortNearest;

		$json_string = $data_source_path;

		$bill_obj = $this->get_bill_input($json_string);

		$this->set_bill_input($bill_obj);
	}

	private function set_bill_input($bill_input){
		$this->bill_input = $bill_input;
	}

	private function get_bill_input($json_string){		
		$jsondata = file_get_contents($json_string);
		$bill_obj = json_decode($jsondata); 
		return $bill_obj;
	}

	public function test_print_bill(){
		echo "<pre>";
		print_r($this->bill_input);
		echo "</pre>";
	}

	/*
	 * there are huge probability that one payer pay several times, also in future 
	 * we may need individual payer name. so we need segregate individual payer function
	 */
	public function get_payers(){

		$bill_input_obj = $this->bill_input;

		foreach($bill_input_obj as $bill_obj){

			$payers_name[] = $bill_obj->Payer;
			
		}

		$this->payers_name = array_values(array_unique($payers_name));

		return $this->payers_name;		

	}


	public function get_sprnders(){

		
		$bill_input_obj = (array) $this->bill_input;

		foreach($bill_input_obj as $bill_obj){

			foreach ($bill_obj->Spend as $spending) {

				$spending_info = (array) $spending;

				foreach ($spending_info as $key => $value) {

					$spender_name[] = $key;

				}
				
			}			
			
		}

		$this->spender_name = array_values(array_unique($spender_name));

		return $this->spender_name;
	
	}


	/*
	 * there are huge probability that one payer pay several times, also in future 
	 * we may need individual payer payment info. so we need to introduce this individual payer function
	 */
	public function get_payers_payment(){

		
		$bill_input_obj = $this->bill_input;

		foreach($bill_input_obj as $bill_obj){

			$payers_payment[$bill_obj->Payer]["pay"] += $bill_obj->Total;
			
		}

		return $payers_payment;
		
	}


	public function get_individual_payer_payment($payername){

		$all_payer = $this->get_payers();

		
		if(in_array($payername, $all_payer)){

			$bill_input_obj = $this->bill_input;

			foreach($bill_input_obj as $bill_obj){

				if($bill_obj->Payer == $payername){
					$payer_payment += $bill_obj->Total;
				}
				
			}

		}

		return $payer_payment;

	}


	public function get_sprnders_spending(){

		
		$bill_input_obj = (array) $this->bill_input;

		foreach($bill_input_obj as $bill_obj){

			foreach ($bill_obj->Spend as $spending) {

				$spending_info = (array) $spending;

				foreach ($spending_info as $key => $value) {
					$sprnders_spending[$key]["spend"] += $value;
				}
				
			}			
			
		}

		return $sprnders_spending;		

	}


	public function get_individual_sprnder_spending($spendername){

		$all_spenders = $this->get_sprnders();


		if(in_array($spendername, $all_spenders)){

			$bill_input_obj = (array) $this->bill_input;

			foreach($bill_input_obj as $bill_obj){

				foreach ($bill_obj->Spend as $spending) {

					$spending_info = (array) $spending;
				
					foreach ($spending_info as $key => $value) {

						if($key == $spendername){
							$sprnder_spending += $value;
						}

					}
					
				}			
				
			}

		}

		return $sprnder_spending;

	}


	public function get_person_owed($person_name){

		$payer = $person_name;
		$spender = $person_name;

		$individual_payer_payment = $this->get_individual_payer_payment($payer);
		$individual_sprnder_spending = $this->get_individual_sprnder_spending($spender);

		$person_owed = $individual_payer_payment - $individual_sprnder_spending;

		echo "$person_name is owed Tk ".$person_owed."<br>";

	}

	/*
	 * calculate a spender owe total from a specific payer
	 */
	public function get_person_owe($person_owe,$person_owed){

		$bill_input_obj = (array) $this->bill_input;
		foreach($bill_input_obj as $bill_obj){
			if($bill_obj->Payer == $person_owed){

				$payer_payment += $bill_obj->Total;
				foreach ($bill_obj->Spend as $spending) {
					
					$spending_info = (array) $spending;				
					foreach ($spending_info as $key => $value) {
						if($key == $person_owe){
							$sprnder_spending += $value;
						}
					}											
				}

			}
		}

		return $sprnder_spending;

	}


	public function get_person_net_owe($person_owe,$person_owed){

		$all_payer = $this->get_payers();

		if(!in_array($person_owed, $all_payer)){

			echo "$person_owe owes $person_owed nothing<br>";

		} else {

			/*
			 * calculate a spender net owe from a specific payer
			 */
			$spender_owe = $this->get_person_owe($person_owe,$person_owed);
			$spender_owed = $this->get_person_owe($person_owed,$person_owe);
			
			
			if($spender_owed > $spender_owe){
				echo "$person_owe owes $person_owed nothing<br>";
			} else {
				echo "$person_owe owes $person_owed Tk". ($spender_owe-$spender_owed)."<br>";
			}

		}

	}


	public function sattle_group_expense(){
		
		
		$sprnders = $this->get_sprnders();
		$sprnders_spending = $this->get_sprnders_spending();
		$payers_payment = $this->get_payers_payment();


		foreach ($sprnders as $sprnder) {
			
			if($sprnders_spending[$sprnder]["spend"] > $payers_payment[$sprnder]["pay"]){
				$sattle_ower_info[$sprnder] = $sprnders_spending[$sprnder]["spend"] - $payers_payment[$sprnder]["pay"];
			}
		}

		arsort($sattle_ower_info);
		$this->sattle_ower_info = $sattle_ower_info;


		foreach ($payers_payment as $key => $payer_payment) {			
			
			if($sprnders_spending[$key]["spend"] < $payers_payment[$key]["pay"]){
				$sattle_payer_info[$key] = $payer_payment["pay"] - $sprnders_spending[$key]["spend"];
			}
		}

		arsort($sattle_payer_info);
		$this->sattle_payer_info = $sattle_payer_info;


		$this->process_ower_mapping($this->sattle_payer_info,$this->sattle_ower_info );
		

	}


	public function process_ower_mapping($payer,$ower){

		$owers_array = $ower;
		asort($owers_array);
		
		foreach ($payer as $key => $sattle_payer) {
		
		
			foreach ($owers_array as $okey => $sattle_owr) {	
			
				$nearest = $this->sorted_nearest_obj->array_numeric_sorted_nearest(array_values($owers_array), $sattle_payer);
				
				if($this->sattle_payer_info[$key] > $nearest){
					
					if($nearest != NULL){

						$this->sattle_payer_info[$key] = $sattle_payer - $nearest;
						
						$ower_name = array_search($nearest, $owers_array);						
						
						if($this->sattle_payer_info[$key] == 0){
							unset($this->sattle_payer_info[$key]);
						}
						
						echo "$ower_name pay $key Tk".$nearest."<br>";
						unset($owers_array[$ower_name]);
						unset($this->sattle_ower_info[$ower_name]);
						//break;

					} else {
						
						foreach ($payer as $key => $sattle_payer){
							echo key($owers_array)." pay $key Tk".$this->sattle_payer_info[$key]."<br>";
							$this->sattle_payer_info[$key] = $sattle_payer - $this->sattle_payer_info[$key];
							
							if($this->sattle_payer_info[$key] == 0){
								unset($this->sattle_payer_info[$key]);
							}	
						}

						break;

					}
					
				}

			}
		
			
		}

	}



}