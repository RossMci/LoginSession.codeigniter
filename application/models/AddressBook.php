<?php

class AddressBook extends CI_Model
{

	public function record_count()
	{
		return $this->db->count_all("master_name");
	}

	function addEntryMaster()
	{
//		$master_data['f_name'] = $this->input->post('f_name');
//		$master_data['l_name'] = $this->input->post('l_name');
//		$addEntry = "CALL addContactDetails(?,?,@master_id)";
//		return $this->db->query($addEntry, $master_data);
		$master_data['f_name'] = $this->input->post('f_name'); // retrieve f_name from form post
		$master_data['l_name'] = $this->input->post('l_name'); // retrieve l_name from form post
//Call Stored Procedure to insert into the master name Table
		$addEntry = "CALL addContactDetails(?,?,@master_id)";
		$this->db->query($addEntry, $master_data);

		$sqlOutParam = "SELECT @master_id as master_id";
		$master_id_Value = $this->db->query($sqlOutParam);
		return $master_id_Value->row()->master_id;

	}

	function addEntryAddress($insert_id)
	{
		$address_data['master_id'] = $insert_id;
//		$address_data['date_added'] = date('Y-m-d');
//		$address_data['date_modified'] = date('Y-m-d');
		$address_data['address'] = $this->input->post('address');
		$address_data['city'] = $this->input->post('city');
		$address_data['town'] = $this->input->post('town');
		$address_data['type'] = $this->input->post('add_type');

//		$this->db->insert('address', $address_data);
		$stored_proc_call = "CALL addAddressDetails(?, ?, ?, ?, ?)";
		$this->db->query($stored_proc_call, $address_data);
	}

	function addEntryTelephone($insert_id)
	{
		$tel_data['master_id'] = $insert_id;
//		$tel_data['date_added'] = date('Y-m-d');
//		$tel_data['date_modified'] = date('Y-m-d');
		$tel_data['telephoneNo'] = $this->input->post('tel_number');
		$tel_data['type'] = $this->input->post('tel_type');

//		$this->db->insert('telephone', $tel_data);
		$stored_proc_call = "CALL addPhoneDetails(?, ?, ?)";
		$this->db->query($stored_proc_call, $tel_data);
	}

	function addEntryEmail($insert_id)
	{
		$email_data['master_id'] = $insert_id;
		$email_data['email'] = $this->input->post('email');
		$email_data['type'] = $this->input->post('email_type');


//		$this->db->insert('email', $email_data);
		$stored_proc_call = "CALL addEmailDetails(?, ?, ?)";
		$this->db->query($stored_proc_call, $email_data);
	}

	//Adds new Fax details to the Fax table
	function addEntryFax($insert_id)
	{
		//Inserts email details from form into associative array 
		// with keys same name as database fields
		$fax_data['master_id'] = $insert_id;
		$fax_data['fax_number'] = $this->input->post('fax');
		$fax_data['type'] = $this->input->post('fax_type');

//		$this->db->insert('fax', $fax_data); // returns True or False whether the insert was correct
		$stored_proc_call = "CALL addFax(?,?,?)";
		$this->db->query($stored_proc_call, $fax_data);
	}

	//Adds new Notes details to the Personal Notes table
	function addEntryNotes($insert_id)
	{
		//Inserts email details from form into associative array with keys 
		//same name as database fields
		$note_data['master_id'] = $insert_id;
		$note_data['note'] = $this->input->post('note');
//		$note_data['date_added'] = date('Y-m-d');
//		$note_data['date_modified'] = date('Y-m-d');
//		$this->db->insert('Personal_Notes', $note_data); // returns True or False whether the insert was correct
		$stored_proc_call = "CALL addPersonal_Notes(?,?)";
		$this->db->query($stored_proc_call, $note_data);
	}

	function selectContacts()
	{
		$display_block = ""; //used to build the option values for the dropdown list box 
//		$contacts = $this->db->select("master_id, CONCAT(l_name,' ', f_name) AS display_name");
//		$query = $this->db->get('master_name');
		$stored_proc_call = "CALL selectContacts()";
		$query = $this->db->query($stored_proc_call);
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
		mysqli_next_result($this->db->conn_id);
		return $display_block;
	}

	function getSelectedContact($master_id)
	{
		//This method selects the all the details for the contact = master_id 

		$display_block2 = "";

		$master_data['master_id'] = $master_id;
//Calls the stored procedure to get the details (Name, Address, Email, phone etc.) of
		//the selected Contact
		$stored_proc_call = "CALL SelectContactDetails(?)";
		$query = $this->db->query($stored_proc_call, $master_data);
		if ($query->num_rows() > 0)
		{
			$result = $query->row(); // one row retuned as master_id is a primary key
			$display_block2 .= "<h3>Showing Record for: " . $result->display_name . "</h3>";
			$display_block2 .= 'Address: ' . $result->address . ',  ' . $result->city . ',  ' . $result->town . ',  (' . $result->AddressType . ')</br>';
			$display_block2 .= 'Telephone: ' . $result->telephoneNo . '   (' . $result->TelType . ')</br>';
			$display_block2 .= 'Email: ' . $result->email . '(' . $result->EmailType . ')</br>';
			$display_block2 .= 'Fax: ' . $result->fax_number . '(' . $result->FaxType . ')</br>';
			$display_block2 .= 'Notes: ' . $result->note . '</br>';
		}
		else
		{
			$display_block2 = "Selected Contact not found in Address Book";
		}
		mysqli_next_result($this->db->conn_id);
		return $display_block2;
	}

	function deleteEntry($master_id)
	{
//		$this->db->where('master_id', $master_id);
//		$this->db->delete('master_name');

		$data = array('master_id' => $master_id);
		$stored_proc_call = "CALL deleteMaster(?)";
		$query = $this->db->query($stored_proc_call, $data);

		if ($this->db->affected_rows() > 0)
		{
//			$this->db->where('master_id', $master_id);
//			$this->db->delete(['address', 'telephone', 'email', 'fax', 'personal_notes']);
			$stored_proc_call = "CALL deleteAddress(?)";
			$query = $this->db->query($stored_proc_call, $data);
			//Delete from the telephone table
			$stored_proc_call = "CALL deletePhone(?)";
			$query = $this->db->query($stored_proc_call, $data);
			//Delete from the email table
			$stored_proc_call = "CALL deleteEmail(?)";
			$query = $this->db->query($stored_proc_call, $data);
		}
	}

	function updateEntry($master_id)
	{

		$update_data = array();
		$update_data['date_modified'] = date('Y-m-d');
		$update_data['f_name'] = $this->input->post('f_name');
		$update_data['l_name'] = $this->input->post('l_name');
		$this->db->where('master_id', $master_id);
		$this->db->update('master_name');

		if ($this->db->affected_rows() > 0)
		{
			$this->db->where('master_id', $master_id);
			$this->db->update(['address', 'telephone', 'email', 'fax', 'personal_notes']);
		}
	}

	function UpdateContact($contact_details)
	{

		$this->updateAddressDetails($contact_details);
		$this->updateTelephoneDetails($contact_details);
		$this->updateFaxDetails($contact_details);
		$this->updateEmailDetails($contact_details);
		$this->updatePersonalNotesDetails($contact_details);
	}

	//-------------------------------------------------------------------------

	function updateAddressDetails($update_id, $address, $city, $town, $address_type)
	{
		//Updates the Address details to the Address Table
		//Update address details from form into associative array with keys same name as database fields  
		$address_data['master_id'] = $update_id;
		$address_data['address'] = $address;
		$address_data['city'] = $city;
		$address_data['town'] = $town;
		$address_data['type'] = $address_type;

		$stored_proc_call = "CALL updateAddressDetails(?,?,?,?,?)";
		$query = $this->db->query($stored_proc_call, $address_data);
//		$this->db->where('master_id', $update_id);
//        $this->db->update('address', $address_data);
	}

	//Adds new Telephone Details to the Telephone Table
	function updateTelephoneDetails($update_id, $telephone_number, $telephone_type)
	{

		//Inserts telephone details from form into associative array with keys same name as database fields
		$tel_data['master_id'] = $update_id;
		$tel_data['telephoneNo'] = $telephone_number;
		$tel_data['type'] = $telephone_type;


		$stored_proc_call = "CALL updateTelephoneDetails(?,?,?)";
		$query = $this->db->query($stored_proc_call, $tel_data);
	}

	//Adds new Email details to the Email table
	function updateEmailDetails($update_id, $email, $email_type)
	{

		//Inserts email details from form into associative array with keys same name as database fields  
		$email_data['master_id'] = $update_id;
		$email_data['email'] = $email;
		$email_data['type'] = $email_type;

		$stored_proc_call = "CALL updateEmailDetails(?,?,?)";
		$query = $this->db->query($stored_proc_call, $email_data);

//        $this->db->where('master_id', $update_id);
//        $this->db->update('email', $email_data);
	}

	//Update Fax table with new fax details if changed
	function updateFaxDetails($update_id, $fax_number, $fax_type)
	{

		//Inserts email details from form into associative array with keys same name as database fields
		$fax_data['master_id'] = $update_id;
		$fax_data['fax_number'] = $fax_number;
		$fax_data['type'] = $fax_type;


		$stored_proc_call = "CALL updateFaxDetails(?,?,?)";
		$query = $this->db->query($stored_proc_call, $fax_data);
//        $this->db->where('master_id', $update_id);
//        $this->db->update('fax', $fax_data);
	}

	//Update PersonalNotes table with new note details if changed
	function updatePersonalNotesDetails($update_id, $note)
	{

		//Inserts note details from form into associative array with keys same name as database fields        
		$note_data['master_id'] = $update_id;
		$note_data['note'] = $note;

		$stored_proc_call = "CALL updatePersonalNotesDetails(?,?)";
		$query = $this->db->query($stored_proc_call, $note_data);
	}

	//Select the contact name details for the selected contact
	function getSelectedContactDetailsForUpdate($master_id)
	{

		$display_block2 = "";


		//Get the Contact Address Details
		$address_data['master_id'] = $master_id;
		$stored_proc_call = "CALL SelectContactAddressDetails(?)";
		$query = $this->db->query($stored_proc_call, $address_data);
		mysqli_next_result($this->db->conn_id);

		if ($query->num_rows() > 0)
		{
			$result = $query->row();
			$display_block2 .= "<strong>Address: </strong>";
			$display_block2 .= "<input type=\"text\" name=\"address\" size=\"55\" maxlength=\"60\" value=$result->address></p>";
			$display_block2 .= "<strong>City:  </strong>";
			$display_block2 .= "<input type=\"text\" name=\"city\" size=\"20\" maxlength=\"50\" value=$result->city></p>";
			$display_block2 .= "<strong>Town:  </strong>";
			$display_block2 .= "<input type=\"text\" name=\"town\" size=\"20\" maxlength=\"30\" value=$result->town></p>";
			$display_block2 .= "<strong>Address Type: </strong>";

			if ($result->type = 'home')
			{
				$display_block2 .= "<input type=\"radio\" name=\"add_type\" value=\"home\" checked> home
									<input type=\"radio\" name=\"add_type\" value=\"work\"> work
									<input type=\"radio\" name=\"add_type\" value=\"other\"> other</p>";
			}
			else if ($result->type = 'work')
			{
				$display_block2 .= "<input type=\"radio\" name=\"add_type\" value=\"home\"> home
									<input type=\"radio\" name=\"add_type\" value=\"work\" checked> work
									<input type=\"radio\" name=\"add_type\" value=\"other\"> other</p>";
			}
			else
			{
				$display_block2 .= "<input type=\"radio\" name=\"add_type\" value=\"home\"> home
									<input type=\"radio\" name=\"add_type\" value=\"work\"> work
									<input type=\"radio\" name=\"add_type\" value=\"other\" checked> other</p>";
			}
		}

		//Get the Contact Telephone Details
		$tel_data['master_id'] = $master_id;
		$stored_proc_call = "CALL SelectphoneDetails(?)";
		$query = $this->db->query($stored_proc_call, $tel_data);
		mysqli_next_result($this->db->conn_id);

//		$this->db->select("telephoneNo, type", false);
//
//		$query = $this->db->get_where('telephone', array('master_id' => $master_id));

		if ($query->num_rows() > 0)
		{
			$result = $query->row();
			$display_block2 .= "<strong>Telephone:  </strong>";
			$display_block2 .= "<input type=\"text\" name=\"tel_number\" size=\"18\" maxlength=\"45\" value=$result->telephoneNo></p>";
			$display_block2 .= "<strong>Telephone Type: </strong>";

			if ($result->type = 'home')
			{
				$display_block2 .= "<input type=\"radio\" name=\"tel_type\" value=\"home\" checked> home
									<input type=\"radio\" name=\"tel`_type\" value=\"work\"> work
									<input type=\"radio\" name=\"tel_type\" value=\"other\"> other</p>";
			}
			else if ($result->type = 'work')
			{
				$display_block2 .= "<input type=\"radio\" name=\"tel_type\" value=\"home\"> home
									<input type=\"radio\" name=\"tel_type\" value=\"work\" checked> work
									<input type=\"radio\" name=\"tel_type\" value=\"other\"> other</p>";
			}
			else
			{

				$display_block2 .= "<input type=\"radio\" name=\"tel_type\" value=\"home\"> home
									<input type=\"radio\" name=\"tel_type\" value=\"work\"> work
									<input type=\"radio\" name=\"tel_type\" value=\"other\" checked> other</p>";
			}
		}

		//Get the Contact Email Details
		$email_data['master_id'] = $master_id;
		$stored_proc_call = "CALL SelectemailDetails(?)";
		$query = $this->db->query($stored_proc_call, $email_data);
		mysqli_next_result($this->db->conn_id);

		if ($query->num_rows() > 0)
		{
			$result = $query->row();
			$display_block2 .= "<strong>Email:  </strong>";
			$display_block2 .= "<input type=\"text\" name=\"email\" size=\"30\" maxlength=\"150\" value=$result->email></p>";
			$display_block2 .= "<strong>Email Type: </strong>";

			if ($result->type = 'home')
			{
				$display_block2 .= "<input type=\"radio\" name=\"email_type\" value=\"home\" checked> home
									<input type=\"radio\" name=\"email_type\" value=\"work\"> work
									<input type=\"radio\" name=\"email_type\" value=\"other\"> other</p>";
			}
			else if ($result->type = 'work')
			{
				$display_block2 .= "<input type=\"radio\" name=\"email_type\" value=\"home\"> home
									<input type=\"radio\" name=\"email_type\" value=\"work\" checked> work
									<input type=\"radio\" name=\"email_type\" value=\"other\"> other</p>";
			}
			else
			{
				$display_block2 .= "<input type=\"radio\" name=\"email_type\" value=\"home\"> home
									<input type=\"radio\" name=\"email_type\" value=\"work\"> work
									<input type=\"radio\" name=\"email_type\" value=\"other\" checked> other</p>";
			}
		}

		//Get the fax Details
		$fax_data['master_id'] = $master_id;
		$stored_proc_call = "CALL SelectfaxDetails(?)";
		$query = $this->db->query($stored_proc_call, $fax_data);
		mysqli_next_result($this->db->conn_id);
//		$this->db->select("fax_number, type", false);
//
//		$query = $this->db->get_where('fax', array('master_id' => $master_id));

		if ($query->num_rows() > 0)
		{
			$result = $query->row();
			$display_block2 .= "<strong>Fax:  </strong>";
			$display_block2 .= "<input type=\"text\" name=\"fax\" size=\"30\" maxlength=\"150\" value=$result->fax_number></p>";
			$display_block2 .= "<strong>Fax Type: </strong>";

			if ($result->type = 'home')
			{
				$display_block2 .= "<input type=\"radio\" name=\"fax_type\" value=\"home\" checked> home
									<input type=\"radio\" name=\"fax_type\" value=\"work\"> work
									<input type=\"radio\" name=\"fax_type\" value=\"other\"> other</p>";
			}
			else if ($result->type = 'work')
			{
				$display_block2 .= "<input type=\"radio\" name=\"fax_type\" value=\"home\"> home
									<input type=\"radio\" name=\"fax_type\" value=\"work\" checked> work
									<input type=\"radio\" name=\"fax_type\" value=\"other\"> other</p>";
			}
			else
			{
				$display_block2 .= "<input type=\"radio\" name=\"fax_type\" value=\"home\"> home
									<input type=\"radio\" name=\"fax_type\" value=\"work\"> work
									<input type=\"radio\" name=\"fax_type\" value=\"other\" checked> other</p>";
			}
		}

		//Get the personal_notes Details
		$personal_notes_data['master_id'] = $master_id;
		$stored_proc_call = "CALL Selectpersonal_notesDetails(?)";
		$query = $this->db->query($stored_proc_call, $personal_notes_data);
		mysqli_next_result($this->db->conn_id);
//		$this->db->select("note", false);
//
//		$query = $this->db->get_where('personal_notes', array('master_id' => $master_id));

		if ($query->num_rows() > 0)
		{
			$result = $query->row();
			$display_block2 .= "<strong>Personal Notes:  </strong>";
			$display_block2 .= "<input type=\"text\" name=\"note\" size=\"80\" maxlength=\"80\" value=$result->note></p>";
		}
		mysqli_next_result($this->db->conn_id);
		return $display_block2;
	}

	function getSelectedContactDetails($master_id)
	{

		//This method selects the all the details for the contact = master_id 

		$display_block2 = "";

		// Get the Details from the relevant tables for the selected contact 
		// tables are joined based on master_id
//		$this->db->select('Master_name.master_id, l_name, f_name, Address.address, 
//						Address.city, Address.town, Address.type as AddType, 
//						telephone.telephoneNo, Telephone.type as TelType, Email, 
//						Email.type as EmailType, Fax_number, Fax.type as FaxType, note');
//
//		$this->db->from('Master_name');
//		$this->db->join('address', 'address.master_id = master_name.master_id', 'left');
//		$this->db->join('telephone', 'telephone.master_id = master_name.master_id', 'left');
//		$this->db->join('email', 'email.master_id = master_name.master_id', 'left');
//		$this->db->join('fax', 'fax.master_id = master_name.master_id', 'left');
//		$this->db->join('personal_notes', 'personal_notes.master_id = master_name.master_id', 'left');
//		$this->db->where('Master_name.master_id', $master_id);
//		$query = $this->db->get();
		$master_data['master_id'] = $master_id;
		$stored_proc_call = "CALL SelectContactDetails(?)";
		$query = $this->db->query($stored_proc_call, $master_data);

		//If the a record is found
		if ($query->num_rows() > 0)
		{
			$result = $query->row(); // one row retuned as master_id is a primary key
			$display_block2 .= "<h3>Showing Record for: " . $result->l_name . " " . $result->f_name . "</h3>";
			$display_block2 .= 'Address: ' . $result->address . ',  ' . $result->city . ',  ' . $result->town . ',  (' . $result->AddType . ')</br>';
			$display_block2 .= 'Telephone: ' . $result->telephoneNo . '   (' . $result->TelType . ')</br>';
			$display_block2 .= 'Email: ' . $result->Email . '(    ' . $result->EmailType . ')</br>';
			$display_block2 .= 'Fax: ' . $result->Fax_number . '(    ' . $result->FaxType . ')</br>';
			$display_block2 .= 'Notes: ' . $result->note . '</br>';
		}
		else
		{
			$display_block2 = "Selected Contact not found in Address Book";
		}
		mysqli_next_result($this->db->conn_id);
		return $display_block2;
	}

}
?>

