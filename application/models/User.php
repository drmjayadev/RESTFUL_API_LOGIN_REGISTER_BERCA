<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Model {

	function saveUser($data){
		return $this->db->insert('user', $data);
	}

	function updateUser($id,$data){
		$this->db->where('id', $id);
		return $this->db->update('user', $data);
	}

	function cekUserEmail($username,$email){
		$this->db->from('user');
		$this->db->where('username', $username);
		$this->db->or_where('email', $email);
		$query = $this->db->get(); 
		return $query;
	}

	function getUser($id){
		$this->db->from('user');
		$this->db->where('id', $id);
		$query = $this->db->get(); 
		return $query;
	}
	
	function getUserWhere($where){		
		return $this->db->get_where('user',$where);
	}	


	function getUserByUsername($username){
		$this->db->from('user');
		$this->db->where('username', $username);
		$query = $this->db->get(); 
		return $query;
	}




}

