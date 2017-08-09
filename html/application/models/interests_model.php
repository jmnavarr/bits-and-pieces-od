<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Interests_model extends CI_Model
{
   public function __construct()
   {
   	  parent::__construct();
   }

   public function get_interest_by_name($interest_name)
   {
   	  $this->db->where('interest', $interest_name);
   	  $query = $this->db->get('interest');
   	  $ret = false;
   	  
   	  if($query->num_rows() > 0)
   	  {
   	  	 $ret = $query->row();
   	  }
   	  
   	  return $ret;
   }
   
   public function get_interests($limit = 50, $offset = 0)
   {
   	  $query = $this->db->get('interest', $limit, $offset);
   	  $ret = array();
   	  
   	  if($query->num_rows() > 0)
   	  {
   	  	 $ret = $query->result();
   	  }
   	  
      return $ret;
   }

   public function get_interests_matching($interest, $limit = 20, $offset = 0)
   {
   	  $this->db->like('interest', $interest, 'after');
   	  $query = $this->db->get('interest', $limit, $offset);
   	  $ret = array();
   
   	  if($query->num_rows() > 0)
   	  {
   		 $ret = $query->result();
   	  }
   
   	  return $ret;
   }
   
   public function get_count_user_interests($user_id)
   {
   	  $this->db->from('interest2user');
   	  $this->db->where('uid', $user_id);
   	  $ret = $this->db->count_all_results();
   	  
   	  return $ret;
   }
   
   public function get_user_interests($logged_in_user_id, $user_id, $limit = 10, $offset = 0)
   {
   	  $sql = "SELECT interest2user.interest_id as id, interest2user.uid, interest.interest, if(interest2user2.interest_id is null, 'false', 'true') as is_shared 
   	  	FROM interest2user JOIN interest ON interest.id = interest2user.interest_id 
   	  		LEFT JOIN (SELECT * FROM interest2user WHERE uid = " . $logged_in_user_id . ") AS interest2user2 ON interest.id = interest2user2.interest_id 
   	  	WHERE interest2user.uid = " . $user_id . " LIMIT " . $limit . " OFFSET " . $offset;
   	  
   	  $query = $this->db->query($sql);
   	  $ret = array();
   	 
   	  if($query->num_rows() > 0)
   	  {
         $ret = $query->result();
   	  }
   	
   	  return $ret;
   }

   public function get_common_user_interests($user_id_1, $user_id_2)
   {
   	   $sql = "SELECT interest_id 
   	   		FROM interest2user 
   	   		WHERE uid = " . $user_id_1 .
   	   		" AND interest_id IN (SELECT interest_id
   	   							  FROM interest2user
   	   				              WHERE uid = " . $user_id_2 . ")";
   	   
   	   $query = $this->db->query($sql);
   	   $ret = array();
   	   
   	   if($query->num_rows() > 0)
   	   {
   	      $ret = $query->result();
   	   }
   	   
   	   return $ret;
   }
   
   public function is_existing_interest($user_id, $interest_id)
   {
   	  $data = array('uid' => $user_id,
   	     'interest_id' => $interest_id);
   	  $this->db->where($data);
   	  $query = $this->db->get('interest2user');
   	  $ret = false;
   	  
   	  if($query->num_rows() > 0)
   	  {
   	  	 $ret = true;
   	  }
   	  
   	  return $ret;
   }
   
   public function add_new_interest($interest_name)
   {
   	  $sql = "INSERT INTO interest (interest)
      		  VALUES ('" . $interest_name . "')
      		  ON DUPLICATE KEY UPDATE interest = '" . $interest_name . "'";
   	  
   	  $this->db->query($sql);
   	  
   	  $interest = $this->get_interest_by_name($interest_name);
   	  $ret = $interest->id;
   	  
   	  return $ret;
   }
   
   public function add_interest($user_id, $interest_id)
   {
      $data = array('uid' => $user_id,
         'interest_id' => $interest_id);
      $this->db->insert('interest2user', $data);
      
      return $this->db->insert_id();
   }  
   
   public function remove_interest($user_id, $interest_id)
   {
      $data = array('uid' => $user_id,
         'interest_id' => $interest_id);
      $this->db->delete('interest2user', $data);
      
      return $this->db->affected_rows();
   } 
}
?>


