<?php
include "orders.php";
class Ups_order extends CI_Controller {
	
		var $output_xml = '';
		
		function __construct() {
			parent :: __construct();
			require_once (APPPATH . 'controllers/default_constructor.php');
			require_once (APPPATH . 'controllers/admin/default_constructor.php');
			$this->load->library('ups');
	
		}
		function Ups_order(){
			$this->__construct();
		}
        
        function index(){
			$data['output_xnl'] = $this->output_xml;
			CI::view ( 'admin/ups' ,$data);
        }
        function id($id, $image=''){
        	global $cms_db_tables;

        	$opt = array();
        	
        	$query = "SELECT * from {$cms_db_tables['table_cart_orders']} WHERE id=$id";        	        
        	$tracking_number = $this->core_model->dbQuery($query);
        	
        	$opt['shipping_service'] = $tracking_number[0]['shipping_service'];
        	$opt['transactionid'] = $tracking_number[0]['transactionid'];
        	
        	$opt['user_shipper_company_name'] = $tracking_number[0]['scompany'];
        	$opt['user_shipper_person_name'] = $tracking_number[0]['sname'];
        	$opt['user_phone_dial_plan_number'] = trim(substr($tracking_number[0]['sphone'],0,6));
        	$opt['user_phone_line_number'] = trim(substr($tracking_number[0]['sphone'],6));
        	$opt['user_shipper_company_address1'] = $tracking_number[0]['saddress1'];
        	$opt['user_shipper_company_address2'] = $tracking_number[0]['saddress2'];
        	$opt['user_shipper_company_city'] = $tracking_number[0]['scity'];
        	$opt['user_shipper_state'] = $tracking_number[0]['sstate'];
        	$opt['user_shipper_zip'] = $tracking_number[0]['szipcode'];
        	
        	
        	$query = "SELECT * from {$cms_db_tables['table_cart']} WHERE order_id='{$tracking_number[0]['order_id']}'";        	        
        	$shipment = $this->core_model->dbQuery($query);
        	
        	$opt['shipment_weight'] = $shipment[0]['weight'];
        	$opt['shipment_length'] = $shipment[0]['length'];
        	$opt['shipment_width'] = $shipment[0]['width'];
        	$opt['shipment_height'] = $shipment[0]['height'];
        	
        	
        	if($image){
        		$dat = base64_decode($tracking_number[0]["tracking_number"]);
        		$data['image'] = $dat["tracking_number"];
        	}else{
	        	if(trim($tracking_number[0]["tracking_number"]) != ''){
	        		$tr = array();
	        		$tr_buf = array();
	        		$tr_buf = explode('|',base64_decode($tracking_number[0]["tracking_number"]));
	        		for($i=0; $i<count($tr_buf);$i++){
	        			$res = explode(':',$tr_buf[$i]);
	        			if($res[0] != 'graphic_image')
	        			$tr[$res[0]] = $res[1];
	        		}
	        		$data['output_xml'] = $tr;	        			        		        
	        	}else{
		        	$options = array();
		        	$options = $this->core_model->optionsGetByKey('shop_ups_xml_key', true);
		        	$opt['ups_xml_access_key'] = $options['option_value'];
		        	
		        	$options = $this->core_model->optionsGetByKey('shop_ups_username', true);
		        	$opt['ups_userid'] = $options['option_value'];
		        	
		        	$options = $this->core_model->optionsGetByKey('shop_ups_password', true);
		        	$opt['ups_password'] = $options['option_value'];
		        	
		        	$options = $this->core_model->optionsGetByKey('shop_ups_shipper_number', true);
		        	$opt['ups_shipper_number'] = $options['option_value'];
		        	
		        	$opt['shop_ups_shipper_company_name'] = $this->core_model->optionsGetByKey("shop_ups_shipper_company_name",false);
		        	$opt['shop_ups_shipper_person_name'] = $this->core_model->optionsGetByKey("shop_ups_shipper_person_name",false);
		        	$opt['phone_dial_plan_number'] = trim(substr($this->core_model->optionsGetByKey("shop_ups_shipper_company_phone",false),0,6));
		        	$opt['phone_line_number'] = trim(substr($this->core_model->optionsGetByKey("shop_ups_shipper_company_phone",false),6));
		        	$opt['shop_ups_shipper_company_address'] = $this->core_model->optionsGetByKey("shop_ups_shipper_company_address",false);
		        	$opt['shop_ups_shipper_company_city'] = $this->core_model->optionsGetByKey("shop_ups_shipper_company_city",false);
		        	$opt['shop_orders_ship_from_zip'] = $this->core_model->optionsGetByKey("shop_orders_ship_from_zip",false);
		        		        	
		        	$result = $this->getUPS($opt);
				
		        	if(!empty($result) && $result["tracking_number"]){
		        		$bufer = "";
		        		foreach($result as $key => $val){
		        			$bufer .= "$key:$val|";
		        		}
		        		$bufer = rtrim($bufer, '|');
		        		$dat = base64_encode($bufer);
		        		$query = "UPDATE {$cms_db_tables['table_cart_orders']} set tracking_number='{$dat}' WHERE id=$id";        	        
	        			$this->core_model->dbQ($query);
		        		$data['output_xml'] = $result;
		        	}
		        	
	        	}
	        	
	        	$data['id'] = $id;
        	}
        	
			CI::view ( 'admin/ups' ,$data);
        }
        
        
        
        
        
        
        
        function getUPS($options = array()){
        	global $CFG;
        /*
		UPS XML Shipping Tool PHP Function Library
		
		file: xmlship.php
		
		
		*** Copyrights
		
		Ownership rights and intellectual property rights of this software belong to
		Sonicode.  This software is protected by copyright laws and treaties. Title and
		related rights in the content accessed through the software is the property of
		the applicable content owner and may be protected by applicable law. This
		license gives you no rights to such content.
		
		*** Scope of grant
		
		You may:
		-	Use the software on one or more computers.
		-	Customize the software's design to suit your own needs.
		
		You may not:
		-	Modify and/or remove the copyright notice in the the header of this source file.
		-	Reverse engineer, disassemble, or create derivative works based on this script
			for distribution or usage outside your website.
		-	Distribute this script without written consent from Sonicode.
		-	Permit other individuals to use this script except under the terms listed above.
		
		*** Third party modifications
		
		Technical support will not be provided for third-party modifications to the
		software including modifications to code to any license holder.
		
		*** Disclaimer of warranty
		
		The UPS XML Shipping Tool PHP Function Library is provided on an "as is" basis,
		without warranty of any kind, including without limitation the warranties of
		merchantability, fitness for a particular purpose and non-infringement. The
		entire risk as to the quality and performance of this software is borne by you.
		
		*/
		
		
		/* UPS user information.  These values will be persisitant in your web application */
			$CFG->ups_userid			= $options['ups_userid'];				// Enter your UPS User ID
			$CFG->ups_password			= $options['ups_password'];				// Enter your UPS Password
			$CFG->ups_xml_access_key	= $options['ups_xml_access_key'];		// Enter your UPS Access Key
			$CFG->ups_shipper_number	= $options['ups_shipper_number'];				// Enter your UPS Shipper Number
			$CFG->ups_testmode			= "TRUE";					// "TRUE" for test transactions "FALSE" for live transactions
			$CFG->companyzipcode		= $options['shop_orders_ship_from_zip'];//$options['zip'];					// Your Zipcode	
			$CFG->companyname			= $options['shop_ups_shipper_company_name'];			// Your Company Name
			$CFG->companystreetaddress1	= $options['shop_ups_shipper_company_address'];	// Your Street Addres
			$CFG->companystreetaddress2	= "";						// Your Street Address
			$CFG->companycity			= $options['shop_ups_shipper_company_city'];				// Your City
			$CFG->companystate			= "WA";		
		
		/*
			You will most likely want to load this data from a database, or receive the data from
			a html form.  For details on each of the field requirements, please refer to the UPS
			API Documentation.
		*/
		
			$ship_to = array();
			$ship_to["company_name"]					= $options['user_shipper_company_name'];			// Ship To Company
			$ship_to["attn_name"]						= $options['user_shipper_person_name'];				// Ship To Name
			$ship_to["phone_dial_plan_number"]			= $options['user_phone_dial_plan_number'];					// Ship To First 6 Of Phone Number
			$ship_to["phone_line_number"]				= $options['user_phone_line_number'];					// Ship To Last 4 Of Phone Number
			$ship_to["phone_extension"]					= "1";					// Ship To Phone Extension
			$ship_to["address_1"]						= $options['user_shipper_company_address1'];		// Ship To 1st Address Line
			$ship_to["address_2"]						= $options['user_shipper_company_address2'];;						// Ship To 2nd Address Line
			$ship_to["address_3"]						= "";						// Ship To 3rd Address Line
			$ship_to["city"]							= $options['user_shipper_company_city'];					// Ship To City
			$ship_to["state_province_code"]				= $options['user_shipper_state'];						// Ship To State
			$ship_to["postal_code"]						= $options['user_shipper_zip'];					// Ship To Postal Code
			$ship_to["country_code"]					= "US";					// Ship To Country Code
		
			$ship_from["company_name"]					= $options['shop_ups_shipper_company_name'];				// Ship From Company
			$ship_from["attn_name"]						= $options['shop_ups_shipper_person_name'];						// Ship From Name
			$ship_from["phone_dial_plan_number"]		= $options['phone_dial_plan_number'];					// Ship From First 6 Of Phone Number
			$ship_from["phone_line_number"]				= $options['phone_line_number'];					// Ship From Last 4 Of Phone Number
			$ship_from["phone_extension"]				= "1";					// Ship From Phone Extension
			$ship_from["address_1"]						= $options['shop_ups_shipper_company_address'];	// Ship From 1st Address Line
			$ship_from["address_2"]						= "";				// Ship From 2nd Address Line
			$ship_from["address_3"]						= "";						// Ship From 3rd Address Line
			$ship_from["city"]							= $options['shop_ups_shipper_company_city'];					// Ship From City
			$ship_from["state_province_code"]			= "WA";						// Ship From State
			$ship_from["postal_code"]					= $options['shop_orders_ship_from_zip'];					// Ship From Postal Code
			$ship_from["country_code"]					= "US";					// Ship From Country Code
		
			$shipment["bill_shipper_account_number"]	= $CFG->ups_shipper_number;	// This will bill the shipper
			$shipment["service_code"]					= $options['shipping_service'];
			$shipment["packaging_type"]					= "02";						// 02 For "Your Packaging"
			$shipment["invoice_number"]					= $options['transactionid'];					// Invoice Number
			$shipment["weight"]							= $options['shipment_weight'];						// Total Weight Of Package (Not Less Than 1lb.)
			$shipment["length"]							= $options['shipment_length'];
			$shipment["width"]							= $options['shipment_width'];
			$shipment["height"]							= $options['shipment_height'];
			$shipment["insured_value"]					= "120.00";					// Insured Value Of Package
			
			
		// Post the XML query for UPS ship confirm
		$result = $this->ups->ups_ship_confirm($ship_to, $ship_from, $shipment);
			
		if ($result["response_status_code"] == 1) {
			// The result was successful
		
			 $this->output_xml .= sprintf("%s", "Transportation Charges: ".number_format($result["transportation_charges"],2)."<br>");
			 $this->output_xml .= sprintf("%s",  "Service Option Charges: ".number_format($result["service_options_charges"],2)."<br>");
			 $this->output_xml .= sprintf("%s",  "Package Length: ".$result["package_length"]."<br>");
			 $this->output_xml .= sprintf("%s",  "Package Width: ".$result["package_width"]."<br>");
			 $this->output_xml .= sprintf("%s",  "Package Height: ".$result["package_height"]."<br>");
			 $this->output_xml .= sprintf("%s",  "Total Charges: ".number_format($result["total_charges"],2)."<br>");
			 $this->output_xml .= sprintf("%s",  "Billing Weight: ".$result["billing_weight"]."<br>");
			 $this->output_xml .= sprintf("%s",  "Tracking Number: ".$result["tracking_number"]."<br>");
			 $this->output_xml .= sprintf("%s",  "Insured Value: ".number_format($result["insured_value"],2)."<br>");
			 //$this->output_xml .= sprintf("%s",  "Shipment Digest: ".$result["shipment_digest"]);
			 
			 return $this->ups->ups_ship_accept($result);
			 
		
		} else {
			// There was an error
		
			$this->output_xml= sprintf("%s",   "Error: ".$result["error_description"]);
		
		}
		
		/*
		NOTES:
		
		Once the Ship Confirm request is successful, you will want to move on to the
		Ship Accept transaction.  You will only need to pass the
		$result["shipment_digest"] value to the ups_ship_accept function for UPS
		to finalize the shipment as "billable" and return the shipping label.  The
		shipping label data is base64 encoded for transport from UPS's XML servers.
		You will need to simply base64 decode the result data and output the data
		to be spooled to the printer.  Thermal label support exists, as well as GIF
		labels to be printed on laser printers.  Please consult your UPS Ship Tools
		API documentation for more details on shipping options.
		*/
			}//End getUPS
}
?>