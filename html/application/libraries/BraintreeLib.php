<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(LIBPATH . '/Braintree/lib/Braintree.php');

/**
 * Handles credit card payments via Braintree and Paypal.
 * Braintree SDK Reference available at: https://developers.braintreepayments.com/javascript+php/reference/general/processor-responses/authorization-responses
 * 
 */
class BraintreeLib 
{
   private $ci;
   
   public function __construct()
   {
   	  $this->ci =& get_instance();
   	  $this->ci->load->config('braintree');
   	  
   	  Braintree_Configuration::environment($this->ci->config->item('environment'));
   	  Braintree_Configuration::merchantId($this->ci->config->item('merchant_id'));
   	  Braintree_Configuration::publicKey($this->ci->config->item('public_key'));
   	  Braintree_Configuration::privateKey($this->ci->config->item('private_key'));
   	  
   	  $this->ci->load->model("Payments_Braintree_model");
   	  $this->ci->load->library('PaymentMethodsSettings');
   	  $this->ci->load->library('User');
   	  $this->ci->load->library('Credits');
   }

   /**
    * Checks whether $user_id has any active Braintree tokens in the Person database.
    * 
    * @param int $user_id
    * @return [multitype:boolean]
    */
   public function is_braintree_user($user_id)
   {
   	  $ret = $this->ci->Payments_Braintree_model->is_braintree_user($user_id);
   	  
   	  return array('success' => $ret);
   }
   
   /**
    * Gets customer_id assigned by braintree, given a user_id
    * 
    * @return [multitype:boolean customer_id]
    */
   public function get_customer_id($user_id)
   {
   	  $token_info = $this->ci->Payments_Braintree_model->get_token_info($user_id);
   	  $customer_id = null;
   	  
   	  if($token_info)
   	  {
   	  	 $customer_id = $token_info->customer_id; 
   	  }
   	  
   	  return array('success' => !is_null($customer_id), 'customer_id' => $customer_id);
   }
   
   /**
    * Including a customer id when generating client token lets returning customers
    * select from previously used payment method options, improving their experience. If
    * $customer_id is null, then a new client token is generated.
    * 
    * @param int $customer_id Braintree-assigned customer id
    * @return [multitype:boolean client_token]
    */
   public function get_client_token($customer_id)
   {
   	  if(is_null($customer_id))
   	  {
   	  	 $clientToken = Braintree_ClientToken::generate();
   	  }
   	  else
   	  {
   	     $clientToken = Braintree_ClientToken::generate(array(
   	        'customerId' => $customer_id));
   	  }
   	  
   	  return array('success' => true, 'client_token' => $clientToken);
   }
   
   /**
    * Checks if a customer exists already. If not, then one is created in braintree,
    * and its customer_id and token are stored in the person db. If a customer exists
    * already, it finds the existing payment method associated to that customer. If no
    * payment method exists, then one is created.
    * 
    * @param array $user_details. $user_details object representing a user. Must at least
    *    contain id, first_name, and last_name fields.
    * @param string $nonce_token
    * @return array with success flag, customer_id, and payment token (not a nonce token)
    */
   public function create_payment_method($user_details, $nonce_token, $credit_card_number, $cvv, $postal_code)
   {
   	  $customer_id_info = $this->get_customer_id($user_details->id);
   	  $customer_id = $customer_id_info['customer_id'];
   	  
   	  if(!$customer_id_info['success'])
   	  {
         $result = Braintree_Customer::create(array(
    	    'firstName' => $user_details->first_name,
    	  	'lastName' => $user_details->last_name,
         	'email' => $user_details->email,
         	//'paymentMethodNonce' => $nonce_token,
         	'creditCard' => array(
         	    'number' => $credit_card_number,
         		'paymentMethodNonce' => $nonce_token,
         		'options' => array(
         		   	'verifyCard' => true
         		),
         		'cvv' => $cvv,
         		'billingAddress' => array(
         			'postalCode' => $postal_code
         		),
         	)
   	     ));
   	  
   	     if ($result->success) 
   	     {
   	     	$is_paypal_payment = count($result->customer->paypalAccounts) > 0;
   	     	if($is_paypal_payment)
   	     	{
   	     	   $payment_method_token = $result->customer->paypalAccounts[0]->token;
   	     	}
   	     	else
   	     	{
   	     	   $payment_method_token = $result->customer->creditCards[0]->token;
   	     	   
   	     	   /*$result = Braintree_PaymentMethod::update($payment_method_token, array(
   	     		      'paymentMethodNonce' => $nonce_token,
   	     			  'options' => array(
   	     			     'verifyCard' => true
   	     		   )
   	     	   ));*/
   	     	}
   	     	
   	        $result = array('success' => true,
   	           'customer_id' => $result->customer->id,
   	           'payment_method_token' => $payment_method_token);
   	        
    	    // save customer_id and token to db
   	        $this->ci->Payments_Braintree_model->add_token($user_details->id, 
   	           $result['customer_id'], $result['payment_method_token']);
   	     }
   	     else 
   	     {
   	     	$error_messages = "";
   	        foreach($result->errors->deepAll() AS $error) {
    	       $error_messages .= $error->code . ": " . $error->message . "\n";
   	        }
   	        
   	        $result = array('success' => false, 'message' => $error_messages);
   	     }
   	  }
   	  else 
   	  {
   	  	 $token_info = $this->ci->Payments_Braintree_model->get_token_info($user_details->id);
   	  	 $is_existing_token = false;
   	  	 
   	  	 if($token_info)
   	  	 {
   	  	    try 
   	  	    {
   	  	 	   $ret = BrainTree_PaymentMethod::find($token_info->token);
   	  	 	   $is_existing_token = true;
   	  	    }
   	  	    catch (Braintree_Exception_NotFound $bte_notfound)
   	  	    {
   	  	 	   // echo "token not found<br/>"; // TODO: add this to a log, instead of swallowing exception
   	  	    }
   	  	 }
   	  	 
   	  	 if($is_existing_token)
   	  	 {
   	  	    //echo "find start:<br/>";
   	  	    //var_dump($ret);
   	  	    //echo "find end<br/>";
   	  	    
   	  	    $result = array('success' => true,
   	  	       'customer_id' => $customer_id,
   	  	       'payment_method_token' => $token_info->token);
   	  	 }
   	  	 else 
   	  	 {
   	  	    $result = Braintree_PaymentMethod::create(array(
   	  	       'customerId' => $customer_id,
   	  		   'paymentMethodNonce' => $nonce_token,
   	  	 	   'options' => array(
   	  	 	      'verifyCard' => true
   	  	 	   )
   	  	    ));

   	  	    //$this->print_formatted($result, 'Braintree_PaymentMethod');
   	  	 
   	  	    $result = array('success' => true,
   	  	       'customer_id' => $result->paymentMethod->customerId,
   	  	       'payment_method_token' => $result->paymentMethod->token);
   	  	 }
   	  }
   	  
   	  return $result;
   }

   /**
    * Process payment. Uses payment method token instead of nonce token.
    * 
    * @param int $user_id
    * @param array $payment_info. Must include these fields:
    *    'price' => double in USD
    *    'credit_name' => enum('credits', 'premium')
    *    'amount' => amount of credits purchased, or 1 if premium
    *    'payment_method_token' => payment method token from Braintree
    *    'option_id' => needed only if payment for a premium subscription is made. Indicates which purchase_premium_option
    *       the user has selected for their subscription.
    * @return [success:bool]
    */
   public function process_payment($user_id, $payment_info)
   {	
   	  $result = Braintree_Transaction::sale(array(
   	     'amount' => $payment_info['price'],
   	  	 'paymentMethodToken' => $payment_info['payment_method_token']
   	  ));
   	  
   	  if($payment_info['price'] == 0 || $result->success)
   	  {	
   	     //echo "transaction result start:<br/>";
   	     //var_dump($result);
   	     //echo "transaction result end<br/>";
   	     $is_premium = $payment_info['credit_name'] != 'credits';
   	  
   	     if(!$is_premium)
   	     {
   	  	    $this->ci->credits->add_credits($user_id, $payment_info['amount']);
   	  	    $this->ci->paymentmethodssettings->update_payment_methods_settings($user_id, 'braintree', $is_premium, 0, true);
   	     }
   	     else
   	     {
   	  	    $this->ci->user->update_premium_settings($user_id);
   	  	    $this->ci->paymentmethodssettings->update_payment_methods_settings($user_id, 'braintree', $is_premium, $payment_info['option_id'], true);
   	     }
   	  
   	     $this->ci->Payments_Braintree_model->log_transaction($user_id, $payment_info);
   	  
   	     $ret = array_merge(array('success' => true), $payment_info);
   	  }
   	  else
   	  {
   	  	 $message = '';
   	     $error_messages = array();
   	  	 foreach($result->errors->deepAll() as $error) 
   	  	 {
   	  	    $error_messages[] = array('attribute' => $error->attribute, 'error_code' => $error->code, 'error_message' => $error->message);
   	  	    $message .= $error->message . "\n";
   	  	 }
   	  	
   	  	 $trans = $result->transaction;
   	  	 if($trans->status == 'processor_declined')
   	  	 {
   	  	 	$transaction_info = array('status' => $trans->status, 'code' => $trans->processorResponseCode,
   	  	 	   'message' => $trans->processorResponseText);
   	  	 }
   	  	 
   	  	 if($trans->status == 'settlement_declined')
   	  	 {
   	  	 	$transaction_info = array('status' => $trans->status, 'code' => $trans->processorSettlementResponseCode,
   	  	 	   'message' => $trans->processorSettlementResponseText);
   	  	 }
   	  	 
   	  	 if($trans->status == 'gateway_rejected')
   	  	 {
   	  	 	$transaction_info = array('status' => $trans->status, 'code' => 0,
   	  	 	   'message' => $trans->gatewayRejectionReason);
   	  	 }

   	  	 $this->log_transaction_failure($user_id, $payment_info['payment_method_token'], $transaction_info);
   	  	 
   	     $ret = array_merge(array('success' => false, 'message' => $message, 'transaction_info' => $transaction_info), $payment_info);
   	  }
   	  
   	  return $ret;
   }
   
   /**
	* Process payment, Uses payment option (incldue opiton_id and first_price of the payment option)
	*@param int $user_id
	*@param array $premium_option, must includes these fields:
	*		'option_id' => preimum option id
	*		'first_pay' => first_pay of premium option in USD, it is the price that should be paid when subscript to a premium option in first month.
	*@return [success:bool message: string]
	*/		
   	public function process_braintree_premium_upgrade($user_id, $premium_option)
	{

		$customer_id_info = $this->get_customer_id($user_id); 
		$customer_id = $customer_id_info['customer_id']; //customer_id is null if does not exist
				
		$client_token_info = $this->get_client_token($customer_id); //get client_token, or it is generated if customer_id does not exist 
		if ($client_token_info['success'] === false) {
			return array('success'=> false, 'message' => 'can not get client token');
		}
		//get branitree payment method token
		$bt_payment_method = $this->braintreelib->create_payment_method($user_details_info['user_details'], $nonce_token);
		
		
		$payment_info = array(
			'price' => $premium_option['first_pay'],
			'credit_name' => 'premium',
			'amount' => 1,
		//	'payment_method_token'=> ,
			'option_id' => $premium_option['option_id']
			);
			
		$ret_info = $this->process_payment($user_id, $payment_info);
		if ($ret_info['success'] === false) {
			
			return array('success' => false, 'message' => 'braintree transaction failed');
		}
		
		return array('success' => true, 'message' => 'transaction success');
	}   
   
   /**
    * Removes credit card info from braintree, and disables token from person database
    * 
    * @param int $user_id
    * @param string $token
    * @return [success:bool]
    */
   public function remove_credit_card($user_id, $token)
   {
   	  $this->ci->user->update_user_premium_cancellation($user_id);
   	  $this->ci->paymentmethodssettings->update_payment_methods_settings($user_id,
   	     'braintree', false, 0, false);
   	  
      $this->ci->Payments_Braintree_model->disable_token($user_id, $token);
   	  $ret = Braintree_CreditCard::delete($token);
   	  
   	  return array('success' => $ret->success);
   }
   
   /**
    * Gets details for active token given a $user_id. token_info object includes these fields:
    * id, user_fk, customer_id, token, enabled, modification_time, created_on
    * 
    * @param int $user_id
    * @param number $limit
    * @param number $offset
    * @return [multitype:boolean token_info]
    */
   public function get_token_info($user_id, $limit = 1, $offset = 0)
   {
   	  $ret = $this->ci->Payments_Braintree_model->get_token_info($user_id, $limit, $offset);
   	  
   	  return array('success' => !is_bool($ret), 'token_info' => $ret);
   }
   
   /**
    * Adds a new record for a Braintree payment token. 
    * 
    * @param int $user_id
    * @param int $customer_id
    * @param string $token
    * @return [multitype:boolean]
    */
   public function add_token($user_id, $customer_id, $token)
   {
   	  $ret = $this->ci->Payments_Braintree_model->add_token($user_id, $customer_id, $token);
   	  
   	  return array('success' => $ret >= 0);
   }
   
   /**
    * Disable a token in Person database, given a $user_id and a $token to disable
    * 
    * @param int $user_id
    * @param string $token
    * @return [multitype:boolean]
    */
   public function disable_token($user_id, $token)
   { 
   	  $ret = $this->ci->Payments_Braintree_model->disable_token($user_id, $token);
   	  
   	  return array('success' => $ret >= 0);
   }
   
   /**
    * Deletes all Braintree payment records for $user_id, including payment tokens and transactions.
    * 
    * @param int $user_id
    * @return [multitype:boolean]
    */
   public function delete_payments_braintree($user_id)
   {
   	  $ret = $this->ci->Payments_Braintree_model->delete_payments_braintree($user_id);
   	
   	  return array('success' => $ret >= 0);
   }
   
   /**
    * Log a transaction failure from Braintree, for record-keeping purposes. Logs $status, $code, and
    * $message variables returned from the function process_payment.
    * 
    * @param int $user_id
    * @param array $transaction_info - array with these keys: status, code, message
    * @return [success:bool]
    */
   public function log_transaction_failure($user_id, $token, $transaction_info)
   {
   	  $ret = $this->ci->Payments_Braintree_model->log_transaction_failure($user_id, $token, $transaction_info);
   	
   	  return array('success' => $ret >= 0);
   }
   
   /**
    * Gets the revenue made from Braintree payments by users.
    *
    * @param enum $payment_type - enum('credits', 'premium')
    * @param enum $site - enum('person', 'justcams')
    * @param date $start_date - start date (format: YYYY-MM-DD)
    * @param date $end_date - start date (format: YYYY-MM-DD)
    * @return [success:bool revenue:double]
    */
   public function get_revenue_summary($payment_type = '', $site = '', $start_date = '', $end_date = '')
   {
   	  $ret = $this->ci->Payments_Braintree_model->get_revenue_summary($payment_type, $site, $start_date, $end_date);
   	
   	  return array('success' => $ret >= 0, 'revenue' => $ret);
   }
   
   /**
	*Get all braintree transactions by user id
	*
	*@param int $user_id
	*return [success:bool transactions: array]  transactions is an array of objects with these fields:
	*(`id`,`user_fk`,`token`,`amount`,`credit_name`,`price`,`created_on`)
	*/
	public function get_transactions_by_user_id ($user_id)
	{
		$ret = $this->ci->Payments_Braintree_model->get_transactions_by_user_id($user_id);
		
		return array('success' => $ret >= 0, 'transactions' => $ret);
		
	}
	
	/**
	 *Get all braintree failed transactions by user id
	 *
	 *@param int $user_id
	 *return [success:bool failed_transactions: array ] failed_transactions is an array of objects with these fields:
	 *(`id`,`user_fk`,`token`,`status`,`code`,`message`,`created_on`)
	 */	
	public function get_failed_transactions_by_user_id($user_id)
	{
		$ret = $this->ci->Payments_Braintree_model->get_braintree_failed_transactions_by_user_id($user_id);
		
		return array('success' => $ret >= 0, 'failed_transactions' => $ret);
	}
 
}


?>

