<?php

require_once 'CRM/Core/Page.php';

class CRM_Pivotcharts_Page_PivotChart extends CRM_Core_Page {
  public function run() {
    // Example: Set the page-title dynamically; alternatively, declare a static title in xml/Menu/*.xml
    CRM_Utils_System::setTitle(ts('PivotChart DEMO for Compucorp'));
    /*Jquery libraries for pivot plugin and it s styles*/
    CRM_Core_Resources::singleton()->addScriptFile('org.civicrm.pivotcharts', 'pivottable-master/dist/jquery-1.11.2.js');
    CRM_Core_Resources::singleton()->addScriptFile('org.civicrm.pivotcharts', 'pivottable-master/dist/jquery-ui.js');
    CRM_Core_Resources::singleton()->addScriptFile('org.civicrm.pivotcharts', 'pivottable-master/dist/pivot.js');
    CRM_Core_Resources::singleton()->addStyleFile('org.civicrm.pivotcharts', 'pivottable-master/dist/pivot.css');


	$result = civicrm_api3('ActivityContact', 'get', array(
	  'sequential' => 1,
	  'api.Activity.get' => array(),
	  'api.Contact.get' => array()
	  
	  
	));

	$activity_types=civicrm_api3('activity', 'getoptions', array('field' => 'activity_type_id'));
	$activity_statuses=civicrm_api3('activity', 'getoptions', array('field' => 'status_id'));
	$activity_contact_record_types=civicrm_api3('activity_contact', 'getoptions', array('field' => 'record_type_id'));
	$countries=civicrm_api3('contact', 'getoptions', array('field' => 'country_id'));
	$genders=civicrm_api3('contact', 'getoptions', array('field' => 'gender_id'));
	
	//die(print_r($activity_types["values"]));
	$result=$result["values"];
	$data=array();
	foreach ($result as $key => $res) {

		//Activity info
		$activity_subject=$res["api.Activity.get"]["values"][0]["subject"];//activity subject
		$activity_type=$activity_types["values"][$res["api.Activity.get"]["values"][0]["activity_type_id"]];//activity subject
		$activity_date=explode(" ", $res["api.Activity.get"]["values"][0]["activity_date_time"]);
		$activity_date=$activity_date[0];//activity date time
		$activity_status=$activity_statuses["values"][$res["api.Activity.get"]["values"][0]["status_id"]];
		//Activity Contact info
		$type=$activity_contact_record_types["values"][$res["record_type_id"]]; //contact type related to activity
		

		//Contact info
		$contact_type=$res["api.Contact.get"]["values"][0]["contact_type"];
		$contact_age=$this->age_calculate($res["api.Contact.get"]["values"][0]["birth_date"]);
		$contact_name=$res["api.Contact.get"]["values"][0]["display_name"];
		$contact_gender=@$genders["values"][$res["api.Contact.get"]["values"][0]["gender_id"]];
		$country_contact=@$countries["values"][$res["api.Contact.get"]["values"][0]["country_id"]];
		
		if($contact_age!="unknown"){
			if($contact_age<=20)
				$contact_age="0-20";
			elseif($contact_age<=40 && $contact_age>20)
				$contact_age="21-40";
			elseif($contact_age>40)
				$contact_age="41+";
		}
		if($contact_gender=="")
			$contact_gender="unknown";
		if($country_contact=="")
			$country_contact="unknown";
		//getting the info to show
		$rowData=array(
				"Activity Id"=>$res["activity_id"],
				"Activity Subject"=>$activity_subject,
				"Activity Type"=>$activity_type,
				"Activity Date"=>$activity_date,
				"Activity Status"=>$activity_status,
				"Record Type"=>$type,
				"Contact Id"=>$res["api.Contact.get"]["values"][0]["contact_id"],
				"Contact Type"=>$contact_type,
				"Contact Age"=>$contact_age,
				"Contact Name"=>$contact_name,
				"Contact Gender"=>$contact_gender,
				"Contact Country"=>$country_contact

		);

		array_push($data, $rowData);
		
		
		
	}

  	$this->assign("data", $data);
    $this->assign('currentTime', date('Y-m-d H:i:s'));

    parent::run();
  }

  function age_calculate($fechanacimiento){
	  	if($fechanacimiento!=""){
			list($ano,$mes,$dia) = explode("-",$fechanacimiento);
			$ano_diferencia  = date("Y") - $ano;
			$mes_diferencia = date("m") - $mes;
			$dia_diferencia   = date("d") - $dia;
			if ($dia_diferencia < 0 || $mes_diferencia < 0)
				$ano_diferencia--;
			return $ano_diferencia;
		}else{
			return "unknown";
		}
   }
}
