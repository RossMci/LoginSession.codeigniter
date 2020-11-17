<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller
{

	public function index()
	{
		$this->load->view('myMenu');
	}
	
	function AddEntry()
	{



		$user_validation_rules = array(
			array('field' => 'f_name',
				'label' => 'Firstname',
				'rules' => 'required|max_length[75]',
//                  |text_size[30]
				'errors' => array('required' => 'You must provide a %s.',
//				'text_size' => '%s must be text size 30',
					'max_length' => '%s must be more then 1 character in length',)),
			array('field' => 'l_name',
				'label' => 'Surname',
				'rules' => 'required',
				'errors' => array('required' => 'You must provide a %s.')),
			array('field' => 'address',
				'label' => 'Address',
				'rules' => 'required',
				'errors' => array('required' => 'You must provide an %s.')),
			array('field' => 'city',
				'label' => 'City',
				'rules' => 'required',
				'errors' => array('required' => 'You must provide an %s.')),
			array('field' => 'town',
				'label' => 'Town',
				'rules' => 'required',
				'errors' => array('required' => 'You must provide an %s.')),
			array('field' => 'tel_number',
				'label' => 'Telephone Number',
				'rules' => 'required',
				'errors' => array('required' => 'You must provide an %s.')),
			array('field' => 'email',
				'label' => 'Email',
				'rules' => 'required',
				'errors' => array('required' => 'You must provide an %s.'))
		);





		$this->form_validation->set_rules($user_validation_rules);



		if ($this->form_validation->run() == FALSE)
		{

			//Load the Main Menu view 

			$this->load->view('addentry');
		}
		else
		{



			$this->load->model('AddressBook');  //Loads the AddressBook  Model  
			//Add details to master table and if successfull add the to the other tables 

			if ($insert_id =$this->AddressBook->addEntryMaster())
			{  //Calls the addEntry function in the AddressBook model                              
				//Get the master_id inserted  
//				$insert_id = $this->db->insert_id();

				$this->AddressBook->addEntryAddress($insert_id);

				$this->AddressBook->addEntryEmail($insert_id);

				$this->AddressBook->addEntryTelephone($insert_id);

				$this->AddressBook->addEntryFax($insert_id);

				$this->AddressBook->addEntryNotes($insert_id);


				//Reload the main menu 

				$this->load->view('mymenu');
			}
		}
	}

	function SelectEntry()
	{

		//Load the AddressBookModel
		$this->load->model('AddressBook');

		//Call the AddressBook Model method selectContacts                
		$data['display_block'] = $this->AddressBook->selectContacts();

		//View the selected contacts dropdown passing it in the $data      
//        block which contains all the names from the master_name table 
//        to be displayed in a dropdown.             
		$this->load->view('SelectEntry', $data);
	}

	function selectContacts()
	{
		$display_block = ""; //used to build the option values for the dropdown list box 
		$contacts = $this->db->select("master_id, CONCAT(l_name,' ', f_name) AS display_name");
		$query = $this->db->get('master_name');
		//If rows in master_name table exist
		if ($query->num_rows() > 0)
		{
			foreach ($query->result_array() as $contact)
			{  //For each entry        
				$id = $contact['master_id'];
				$display_name = stripslashes($contact['display_name']);
				//Sets the value and the text to display for the select list on the view 
				$display_block .= "<option value=\"" . $id . "\">" . $display_name . "</option>";
			}
		}
		else
		{
			$display_block .= "<option>No Contacts to Select</option>";
		}
		return $display_block;
	}

	function selectedContact()
	{
		if ($this->input->post('submit'))
		{

			$data['display_block2'] = "";

			$master_id = $this->input->post('master_id');



			$this->load->model('AddressBook');


			$data['display_block2'] = $this->AddressBook->getSelectedContact($master_id);

//                    Select all contacts in the addressbook
			$data['display_block'] = $this->AddressBook->selectContacts();

			//View the selected contacts dropdown            
			$this->load->view('SelectEntry', $data);
		}
	}

	function DeleteEntry()
	{
		$this->load->model('AddressBook');
		$data['display_block'] = $this->AddressBook->selectContacts();
		$this->load->view('deleteEntry', $data);
		if ($this->input->post('submit'))
		{
			$this->load->view('deleteEntry', $data);
			$master_id = $this->input->post('master_id');

			$this->AddressBook->deleteEntry($master_id);
			$this->load->view('deleteEntry', $data);
			redirect($this->uri->uri_string());
		}
	}

	function UpdateEntry()
	{
		$this->load->model('AddressBook');
		$data['display_block'] = $this->AddressBook->selectContacts();
		$this->load->view('UpdateEntry', $data);

		if ($this->input->post('submit'))
		{

			$this->getSelectedContactDetails($_POST['master_id']);
		}
	}

	function getSelectedContactDetails($master_id)
	{
		$this->load->model('AddressBook');
		$data2['master_id'] = $master_id;
		$data2['contact_details'] = $this->AddressBook->getSelectedContactDetailsForUpdate($master_id);


		//View the selected contacts dropdown 

		$this->load->view('UpdateContactDetails', $data2);

		if (isset($_POST['update']))
		{

			$this->AddressBook->updateAddressDetails($master_id, $_POST['address'], $_POST['city'], $_POST['town'], $_POST['add_type']);
			$this->AddressBook->updateTelephoneDetails($master_id, $_POST['tel_number'], $_POST['tel_type']);
			$this->AddressBook->updateFaxDetails($master_id, $_POST['fax'], $_POST['fax_type']);
			$this->AddressBook->updateEmailDetails($master_id, $_POST['email'], $_POST['email_type']);
			$this->AddressBook->updatePersonalNotesDetails($master_id, $_POST['note']);
			//View the selected contacts dropdown 

		}
	}

	function UpdateSelectedContact($master_id)
	{
		$this->load->model('AddressBook');
		$this->AddressBook->updateAddressDetails($master_id, $_POST['address'], $_POST['city'], $_POST['town'], $_POST['add_type']);
		$this->AddressBook->updateTelephoneDetails($master_id, $_POST['tel_number'], $_POST['tel_type']);
		$this->AddressBook->updateFaxDetails($master_id, $_POST['fax'], $_POST['fax_type']);
		$this->AddressBook->updateEmailDetails($master_id, $_POST['email'], $_POST['email_type']);
		$this->AddressBook->updatePersonalNotesDetails($master_id, $_POST['note']);
	}


}
