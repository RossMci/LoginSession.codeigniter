<?php

class userModel extends CI_Model
{

//	public function record_count()
//	{
//		return $this->db->count_all("master_name");
//	}

	function CheckValidUser()
	{
		$master_data['emailAddress'] = $this->input->post('emailAddress'); // retrieve f_name from form post
		$master_data['Password'] = $this->input->post('Password');
		if (!empty($master_data))
		{
			$check = "CALL validUser(?,?)";
			if ($query = $this->db->query($check, $master_data))
			{
				return true;
			}
			else
			{
				return false;
			}
		}
		else
		{
			$this->load->view('Login');
		}
	}

}
?>

