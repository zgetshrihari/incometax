<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {


	public function index()
	{
		$this->load->view('submit_income_vw',array('tax_vw' => '', 'income' => ''));
	}

	function calculate()
	{
		$error = '';
		$tax_vw = '';
		$income = '';
		$tax = -1;
		$get = $this->input->post();
		if(array_key_exists("income", $get))
		{
			$income = $get['income'];	
			
			if($income == "")
			{
				$error = 'Please provide some values for income';
			}
			else
			{
				//var_dump($income);exit;
				if( is_numeric($income))
				{
					if($income <=0 )
					{
						$error = 'Income should be greater than Zero';
					}
					else
					{
						
						$tax= $this->tax_slabs($income);
						$tax_vw = $this->load->view('slab_info',array("tax" => $tax, "income" => $income),true);
					}	
				}
				else
				{
					$error = 'Please provide only numbers';
				}
				
			}
		}
		else
			$error = 'No Value provided';


		$this->load->view('submit_income_vw',array("suc" => "", "error" => $error , 'tax_vw' => $tax_vw, 'income' => $income ));

	}

	function tax_slabs($inc=0)
	{

		$tax = 0;
		$income = $inc;
		/*
			this array will have the slab diffrences and general diffrence, already saved
		*/
		$array = array(  array('diff' => 250000, 'per' => 0 , 'tot' => 0), 
						 array('diff' => 250000, 'per' => 0.05 , 'tot' => 12500), 
						 array('diff' => 500000  , 'per' => 0.20, 'tot' => 100000), 
						 array('diff' => 1000000 , 'per' => 0.30, 'tot' => -1)  
					);
		foreach($array as $k=>$v)
		{
			if($income > 0)
			{
				if( $income >$v['diff']  )
				{
					$tax= $tax + $v['tot']; 
					$income = $income - $v['diff'];
				}
				else
				{
					$tax= $tax + ( $income*$v['per'] ) ; 
					$income = 0;
				}	
			}
			
		}

		return $tax;
	}

}
