<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller
{

	public function index()
	{
		$this->load->view('Login');
	}

	function Login_validation()
	{
		$this->load->library('form_validation');
		$this->form_validation->set_rules('emailAddress', 'EmailAddress', 'required');
		$this->form_validation->set_rules('password', 'Password', 'required');
		if ($this->form_validation->run())
		{
			//load model
			$this->load->model('userModel');
			//send data to model(in model run stored prcoedure send back true or false)
	     	$this->userModel->CheckValidUser();
			
			//if true run myMenu and create session store whatever you need (create just test session variable
			//if false error out

			$this->load->view('myMenu'); //display test session varaible 
		}
		else
		{
			$this->index();
		}
	}

}
