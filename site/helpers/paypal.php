<?php

defined('_JEXEC') or die('Restricted access');

class PayPalAPI {

	var $API_USERNAME = "";
	var $API_PASSWORD = "";
	var $API_SIGNATURE = "";
	var $API_ENDPOINT = "";
	var $SUBJECT = '';
	var $USE_PROXY = "FALSE";
	var $PROXY_HOST = '127.0.0.1';
	var $PROXY_PORT = '808';
	var $PAYPAL_URL = "";
	var $PPVERSION = '65.1';
	var $ACK_SUCCESS = 'SUCCESS';
	var $ACK_SUCCESS_WITH_WARNING = 'SUCCESSWITHWARNING';
	var $AUTH_TOKEN = '';
	var $AUTH_SIGNATURE = '';
	var $AUTH_TIMESTAMP = '';
	var $AUTH_MODE = '';
	var $nvp_header = '';
	
	function __construct($mode,$username,$pass,$sig) {
		$this->API_USERNAME = $username;
		$this->API_PASSWORD = $pass;
		$this->API_SIGNATURE = $sig;
		if ($mode == "sandbox") {
			$this->API_ENDPOINT = "https://api-3t.sandbox.paypal.com/nvp";
			$this->PAYPAL_URL = "https://www.sandbox.paypal.com/webscr&cmd=_express-checkout&token=";
		} else {
			$this->API_ENDPOINT = "https://api-3t.paypal.com/nvp";
			$this->PAYPAL_URL = "https://www.paypal.com/webscr&cmd=_express-checkout&token=";
			
		}
	}
	
	function submitPayment($cinfo) {
		
		JRequest::checkToken() or jexit( 'Invalid Token' );
		$db  =& JFactory::getDBO();
		$user  =& JFactory::getUser();
		$app=Jfactory::getApplication();
		$iid = JRequest::getVar('Itemdid');
		
		$q = 'INSERT INTO #__ce_purchased (purchase_user,purchase_course,purchase_status) VALUES ('.$user->id.','.$cinfo->course_id.',"notyetstarted")';
		$db->setQuery($q); $db->query();
		$purchaseid = $db->insertid();
		
		$ivn="Course:".$cinfo->course_id;
		
		$currencyCodeType="USD";
		$paymentType="Sale";
		$L_NAME0 = $cinfo->course_name;
		$L_AMT0 = $cinfo->course_purchaseprice;
		$L_QTY0 = 1;
		
		$itemamt = 0.00;
		$itemamt = $cinfo->course_purchaseprice;
		$amt = $itemamt;
		
		$returnURL =urlencode(JURI::base().'index.php?option=com_continued&view=purchase&layout=confirm&Itemid='.$iid.'&purchaseid='.$purchaseid.'&course='.$cinfo->course_id);
		$cancelURL =urlencode(JURI::base().'index.php?option=com_continued&view=purchase&layout=cancel&Itemid='.$iid.'&purchaseid='.$purchaseid.'&course='.$cinfo->course_id);
		
		$nvpstr="";
		$nvpstr  = "&NOSHIPPING=1&ALLOWNOTE=0&INVNUM=$ivn";
		$nvpstr .= "&L_NAME0=".$L_NAME0."&L_DESC0=Course Reg&L_AMT0=".$L_AMT0."&L_QTY0=".$L_QTY0;
		$nvpstr .= "&AMT=".(string)$itemamt."&ITEMAMT=".(string)$itemamt;
		$nvpstr .= "&ReturnUrl=".$returnURL."&CANCELURL=".$cancelURL ."&CURRENCYCODE=".$currencyCodeType."&PAYMENTACTION=".$paymentType;
		$nvpstr = $nvpstr;
		
		$resArray=$this->hash_call("SetExpressCheckout",$nvpstr);
		if ($resArray) {
			$ralogtxt = "";
			foreach($resArray as $key => $value) {
				$ralogtxt .= "$key: $value\r\n";
			}
			$ql = 'INSERT INTO #__ce_purchased_log (pl_pid,pl_user,pl_course,pl_resarray) VALUES ('.$purchaseid.','.$subid.','.$formid.',"'.$db->getEscaped($ralogtxt).'")';
			$db->setQuery($ql);
			$db->query();
		}
		
		$ack = strtoupper($resArray["ACK"]);
		$token = $resArray['TOKEN'];
		if($ack=="SUCCESS"){
			// Redirect to paypal.com here
			$qt='UPDATE #__ce_purchased SET purchase_paypalid = "'.$token.'", purchase_status="started" WHERE purchase_id = '.(int)$purchaseid;
			$db->setQuery($qt);
			$db->query();
			$token = urldecode($resArray["TOKEN"]);
			$payPalURL = $this->PAYPAL_URL.$token;
			$api->redirect($payPalURL);
		} else  {
			$q='UPDATE #__ce_purchased SET purchase_status="error" WHERE purchase_id = "'.$purchaseid.'"';
			$db->setQuery($q);
			$db->query();
			return false;
		
		}
		return true;
	}
	
	
	function nvpHeader()
	{
		$nvpHeaderStr = "";
	
		if($this->AUTH_MODE) {
			$AuthMode = $this->AUTH_MODE;
		}
		else {
	
			if((!empty($this->API_USERNAME)) && (!empty($this->API_PASSWORD)) && (!empty($this->API_SIGNATURE)) && (!empty($this->SUBJECT))) {
				$AuthMode = "THIRDPARTY";
			}
	
			else if((!empty($this->API_USERNAME)) && (!empty($this->API_PASSWORD)) && (!empty($this->API_SIGNATURE))) {
				$AuthMode = "3TOKEN";
			}
	
			elseif (!empty($this->AUTH_TOKEN) && !empty($this->AUTH_SIGNATURE) && !empty($this->AUTH_TIMESTAMP)) {
				$AuthMode = "PERMISSION";
			}
			elseif(!empty($this->SUBJECT)) {
				$AuthMode = "FIRSTPARTY";
			}
		}
		switch($AuthMode) {
	
			case "3TOKEN" :
				$nvpHeaderStr = "&PWD=".urlencode($this->API_PASSWORD)."&USER=".urlencode($this->API_USERNAME)."&SIGNATURE=".urlencode($this->API_SIGNATURE);
				break;
			case "FIRSTPARTY" :
				$nvpHeaderStr = "&SUBJECT=".urlencode($this->SUBJECT);
				break;
			case "THIRDPARTY" :
				$nvpHeaderStr = "&PWD=".urlencode($this->API_PASSWORD)."&USER=".urlencode($this->API_USERNAME)."&SIGNATURE=".urlencode($this->API_SIGNATURE)."&SUBJECT=".urlencode($this->SUBJECT);
				break;
			case "PERMISSION" :
				$nvpHeaderStr = $this->formAutorization($this->AUTH_TOKEN,$this->AUTH_SIGNATURE,$this->AUTH_TIMESTAMP);
				break;
		}
		return $nvpHeaderStr;
	}
	
	/**
	 * hash_call: Function to perform the API call to PayPal using API signature
	 * @methodName is name of API  method.
	 * @nvpStr is nvp string.
	 * returns an associtive array containing the response from the server.
	 */
	function hash_call($methodName,$nvpStr)
	{
		//declaring of global variables
		$session=JFactory::getSession();
		// form header string
		$nvpheader=$this->nvpHeader();
		//setting the curl parameters.
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$this->API_ENDPOINT);
		curl_setopt($ch, CURLOPT_VERBOSE, 1);
	
		//turning off the server and peer verification(TrustManager Concept).
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
	
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_POST, 1);
	
		//in case of permission APIs send headers as HTTPheders
		if(!empty($this->AUTH_TOKEN) && !empty($this->AUTH_SIGNATURE) && !empty($this->AUTH_TIMESTAMP))
		{
			$headers_array[] = "X-PP-AUTHORIZATION: ".$nvpheader;
	
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers_array);
			curl_setopt($ch, CURLOPT_HEADER, false);
		}
		else
		{
			$nvpStr=$nvpheader.$nvpStr;
		}
		//if USE_PROXY constant set to TRUE in Constants.php, then only proxy will be enabled.
		//Set proxy name to PROXY_HOST and port number to PROXY_PORT in constants.php
		if($this->USE_PROXY)
			curl_setopt ($ch, CURLOPT_PROXY, $this->PROXY_HOST.":".$this->PROXY_PORT);
	
		//check if version is included in $nvpStr else include the version.
		if(strlen(str_replace('VERSION=', '', strtoupper($nvpStr))) == strlen($nvpStr)) {
			$nvpStr = "&VERSION=" . urlencode("65.1") . $nvpStr;
		}
	
		$nvpreq="METHOD=".urlencode($methodName).$nvpStr;
	
		//setting the nvpreq as POST FIELD to curl
		curl_setopt($ch,CURLOPT_POSTFIELDS,$nvpreq);
	
		//getting response from server
		$response = curl_exec($ch);
	
		//convrting NVPResponse to an Associative Array
		$nvpResArray=$this->deformatNVP($response);
		$nvpReqArray=$this->deformatNVP($nvpreq);
		$session->set('nvpReqArray',$nvpReqArray);
	
		if (curl_errno($ch)) {
			// moving to display page to display curl errors
			$session->set('curl_error_no',curl_errno($ch)) ;
			$session->set('curl_error_msg',curl_error($ch));
			$location = "/components/com_programreg/APIError.php";
			die('<p>'.curl_error($ch).'<br><br>'.$nvpreq.'<br><br></p><pre>'.print_r(curl_getinfo($ch)).'</pre>');
		} else {
			//closing the curl
			curl_close($ch);
		}
	
		return $nvpResArray;
	}
	
	/** This function will take NVPString and convert it to an Associative Array and it will decode the response.
	 * It is usefull to search for a particular key and displaying arrays.
	 * @nvpstr is NVPString.
	 * @nvpArray is Associative Array.
	 */
	function deformatNVP($nvpstr)
	{
	
		$intial=0;
		$nvpArray = array();
	
	
		while(strlen($nvpstr)){
			//postion of Key
			$keypos= strpos($nvpstr,'=');
			//position of value
			$valuepos = strpos($nvpstr,'&') ? strpos($nvpstr,'&'): strlen($nvpstr);
	
			/*getting the Key and Value values and storing in a Associative Array*/
			$keyval=substr($nvpstr,$intial,$keypos);
			$valval=substr($nvpstr,$keypos+1,$valuepos-$keypos-1);
			//decoding the respose
			$nvpArray[urldecode($keyval)] =urldecode( $valval);
			$nvpstr=substr($nvpstr,$valuepos+1,strlen($nvpstr));
		}
		return $nvpArray;
	}
	function formAutorization($auth_token,$auth_signature,$auth_timestamp)
	{
		$authString="token=".$auth_token.",signature=".$auth_signature.",timestamp=".$auth_timestamp ;
		return $authString;
	}
}
