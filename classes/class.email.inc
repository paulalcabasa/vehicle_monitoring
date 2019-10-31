<?php
//include("libs/class.phpmailer.php");
// 	haniel-honra@isuzuphil.com
class Email {
	
	private $objPHPMailer;

	public function __construct(){

		$conn = Connection::getInstance();
		$sql = "SELECT ne.id email_id,
				       ne.email,
				       ne.email_password,
				       st.system,
				       st.id system_id,
				       CASE WHEN ne.status = 1 THEN 'Active' ELSE 'Inactive' END STATUS
				FROM ipc_central.notification_system_email nse
					LEFT JOIN ipc_central.notification_emails ne
						ON nse.notif_email_id = ne.id
					LEFT JOIN ipc_central.system_tab st
						ON st.id = nse.system_id
				WHERE st.system = 'Vehicle Monitoring'
					AND ne.status = 1";
		$email_data = $conn->query($sql,false);

		// default email to be used if email is not set 
		$email_user_name = "paul-alcabasa@isuzuphil.com";
		$email_password = "Kalabasa3";

		if(!empty($email_data)){
			$email_user_name = $email_data[0]['email'];
			$email_password = $email_data[0]['email_password'];
		}

		$this->objPHPMailer = new PHPMailer();
		$this->objPHPMailer->CharSet = 'UTF-8';
		$this->objPHPMailer->isSMTP();                                      // Set mailer to use SMTP
		$this->objPHPMailer->CharSet = "iso-8859-1";
		$this->objPHPMailer->Host = 'smtp.office365.com';                      // Specify main and backup SMTP servers
		$this->objPHPMailer->SMTPAuth = true;    
		$this->objPHPMailer->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
		$this->objPHPMailer->Port = 587;     
		$this->objPHPMailer->Username   = $email_user_name;  
		$this->objPHPMailer->Password   = $email_password;
		$this->objPHPMailer->From       = $email_user_name;
		$this->objPHPMailer->FromName  =  $email_user_name;
		$this->objPHPMailer->IsHTML(true);
	}
	
	public function sendMailToRequestor($data){

		try {
			$this->objPHPMailer->Subject   = 'Trip Ticket';
			$this->objPHPMailer->AddAddress ($data['requestor_email'], $data['requestor_name_email']);
			if($data['signatory_email']!=""){
				$this->objPHPMailer->AddCC ($data['signatory_email'], $data['signatory_name']);
			}

			/*$checklist_attachments = $data['attachments'];
			foreach($checklist_attachments as $attachment){
				$attachment = (object)$attachment;
				$this->objPHPMailer->AddAttachment("../attachments/".$attachment->attachment);
			}*/
			$this->objPHPMailer->AddBCC ('paul-alcabasa@isuzuphil.com');
			$this->objPHPMailer->AddBCC ('haniel-honra@isuzuphil.com');
			$this->objPHPMailer->Body = $this->get_include('../mail_template/trip_ticket_details.html', $data); // HTML -> PHP!
			$this->objPHPMailer->Send();
		}

		catch (phpmailerException $e) {
			echo $e->errorMessage(); //Pretty error messages from PHPMailer
		} 
	}

	public function sendNotifMailToApprover($data){

		try {
			$this->objPHPMailer->Subject   = 'Trip Ticket';
			$this->objPHPMailer->AddAddress ($data['approver_email'], $data['approver_name']);
			$this->objPHPMailer->AddCC ($data['approved_by_email'], $data['approved_by_name']);
			//$this->objPHPMailer->AddBCC ('paul-alcabasa@isuzuphil.com');
			/*$checklist_attachments = $data['attachments'];
			foreach($checklist_attachments as $attachment){
				$attachment = (object)$attachment;
				$this->objPHPMailer->AddAttachment("../attachments/".$attachment->attachment);
			}*/
			$this->objPHPMailer->Body = $this->get_include('../mail_template/on_behalf_of_notif.html', $data); // HTML -> PHP!
			$this->objPHPMailer->Send();
		}

		catch (phpmailerException $e) {
			echo $e->errorMessage(); //Pretty error messages from PHPMailer
		} 
	}

	public function sendMailToApprover($data,$approver_details){
		$approver_details = (object)$approver_details;

		$approver_name = Format::replaceInye(
							Format::format_formal_name(
									$approver_details->first_name,
									$approver_details->middle_name,
									$approver_details->last_name
							)
						);
		try {
			
			$this->objPHPMailer->Subject   = 'Trip Ticket Approval';
			$this->objPHPMailer->AddAddress ($approver_details->email, $approver_name);
			//$this->objPHPMailer->AddBCC ("paul-alcabasa@isuzuphil.com");
			/*$checklist_attachments = $data['attachments'];
			foreach($checklist_attachments as $attachment){
				$attachment = (object)$attachment;
				$this->objPHPMailer->AddAttachment("../attachments/".$attachment->attachment);
			}*/
			$this->objPHPMailer->Body = $this->get_include('../mail_template/trip_ticket_approval.php', $data); // HTML -> PHP!
			$this->objPHPMailer->IsHTML(true); 
			if(!$this->objPHPMailer->Send()){
				echo "Message was not sent.";
			}
			else {
				echo "Message was sent successfully!";
			}
		}

		catch (phpmailerException $e) {
			echo $e->errorMessage(); //Pretty error messages from PHPMailer
		} 
	}

	public function sendMailNotifToRequestor($data){
		try {
			$this->objPHPMailer->Subject   = 'Trip Ticket Approval';
			$this->objPHPMailer->AddAddress ($data['requestor_email'], $data['requestor_name_email']);
			if($data['signatory_email']!=""){
				$this->objPHPMailer->AddCC ($data['signatory_email'], $data['signatory_name']);
			}
			/*$checklist_attachments = $data['attachments'];
			foreach($checklist_attachments as $attachment){
				$attachment = (object)$attachment;
				$this->objPHPMailer->AddAttachment("../attachments/".$attachment->attachment);
			}*/
			//$this->objPHPMailer->AddBCC ('paul-alcabasa@isuzuphil.com');
			$this->objPHPMailer->AddBCC ('haniel-honra@isuzuphil.com');
			$this->objPHPMailer->Body = $this->get_include('../mail_template/trip_ticket_approval_notif.html', $data); // HTML -> PHP!
			$this->objPHPMailer->IsHTML(true); 
			$this->objPHPMailer->Send();
		}

		catch (phpmailerException $e) {
			echo $e->errorMessage(); //Pretty error messages from PHPMailer
		} 
	}

	public function sendEmergencyTripMail($data){
		try {
			$this->objPHPMailer->Subject   = 'Emergency Trip Ticket';
		/*	$this->objPHPMailer->AddAddress ("alcabasapaul.pj@gmail.com", 'YANDOC JR., DAVID L.');
			$this->objPHPMailer->AddAddress ("paulalcabasa@yahoo.com.ph", 'MACABENTA, ARIEL A.');*/
			$this->objPHPMailer->AddCC ($data['requestor_email'], $data['requestor_name_email']);
			$this->objPHPMailer->AddAddress ("dave-yandoc@isuzuphil.com", 'Dave Yandoc');
			$this->objPHPMailer->AddAddress ("ariel-macabenta@isuzuphil.com","Ariel Macabenta");
			//$this->objPHPMailer->AddBCC ('paul-alcabasa@isuzuphil.com');
			$this->objPHPMailer->AddBCC ('haniel-honra@isuzuphil.com');
			$this->objPHPMailer->Body = $this->get_include('../mail_template/emergency_trip_ticket.php', $data); // HTML -> PHP!
			$this->objPHPMailer->IsHTML(true); 
			$this->objPHPMailer->Send();
		}

		catch (phpmailerException $e) {
			echo $e->errorMessage(); //Pretty error messages from PHPMailer
		} 
	}

	public function sendChangeStatusMail($data){
		try {
			$this->objPHPMailer->Subject   = 'Vehicle Monitoring - Trip Ticket Status';
	/*		$this->objPHPMailer->AddAddress ("alcabasapaul.pj@gmail.com", 'YANDOC JR., DAVID L.');
			$this->objPHPMailer->AddAddress ("paulalcabasa@yahoo.com.ph", 'MACABENTA, ARIEL A.');*/
			$this->objPHPMailer->AddCC($data['user_trigger_email'], $data['user_trigger']);
			$this->objPHPMailer->AddAddress ("dave-yandoc@isuzuphil.com", 'Dave Yandoc');
			$this->objPHPMailer->AddAddress ("ariel-macabenta@isuzuphil.com","Ariel Macabenta");
			//$this->objPHPMailer->AddBCC ('paul-alcabasa@isuzuphil.com');
			$this->objPHPMailer->AddBCC ('haniel-honra@isuzuphil.com');
			/*$checklist_attachments = $data['attachments'];
			foreach($checklist_attachments as $attachment){
				$attachment = (object)$attachment;
				$this->objPHPMailer->AddAttachment("../attachments/".$attachment->attachment);
			}*/
			$this->objPHPMailer->Body = $this->get_include('../mail_template/trip_ticket_status_changed_notif.html', $data); // HTML -> PHP!
			$this->objPHPMailer->IsHTML(true); 
			$this->objPHPMailer->Send();
		}

		catch (phpmailerException $e) {
			echo $e->errorMessage(); //Pretty error messages from PHPMailer
		} 
	}
	
	public function get_include($filename, $data){
		extract($data);
		if (is_file($filename)) {
			ob_start();
			include $filename;
			return ob_get_clean();
		}
		return false;
	}

	public function send_overdue_mail_notif($data){

		try {
			$this->objPHPMailer->Subject   = 'Trip Ticket Expiration Notice';
			
			$this->objPHPMailer->AddAddress ($data['requestor_email'], $data['requestor_email']);
			$this->objPHPMailer->AddCC ($data['manager_mail_address']);
			$this->objPHPMailer->AddCC ('haniel-honra@isuzuphil.com');
			$this->objPHPMailer->AddCC ('marvin-galvadores@isuzuphil.com');
			//$this->objPHPMailer->AddBCC ('paul-alcabasa@isuzuphil.com');
			
			$this->objPHPMailer->Body = $this->get_include('../mail_template/overdue_notif.php', $data); // HTML -> PHP!
			$this->objPHPMailer->Send();
			echo "mail sent!\n";
		}

		catch (phpmailerException $e) {
			echo $e->errorMessage(); //Pretty error messages from PHPMailer
		} 
	}

	public function send_test_mail($data){

		try {
			$this->objPHPMailer->Subject   = 'This is a test email';
			
			$this->objPHPMailer->AddAddress ($data['requestor_email'], $data['requestor_email']);
	//		$this->objPHPMailer->AddCC ($data['manager_mail_address']);
		/*	$this->objPHPMailer->AddCC ('haniel-honra@isuzuphil.com');
			$this->objPHPMailer->AddCC ('marvin-galvadores@isuzuphil.com');
			$this->objPHPMailer->AddBCC ('paul-alcabasa@isuzuphil.com');*/
			
			$this->objPHPMailer->Body = "This is test email"; // HTML -> PHP!
			$this->objPHPMailer->Send();
			echo "mail sent!\n";
		}

		catch (phpmailerException $e) {
			echo $e->errorMessage(); //Pretty error messages from PHPMailer
		} 
	}
}
