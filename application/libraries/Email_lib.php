<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * FamilyCare Email class.
 *
 * @class gmc_email
 */
class Email_lib
{
	var $CI;
    var $active;

	/**
	 * Constructor - Sets up the object properties.
	 */
	function __construct()
    {
        $this->CI       =& get_instance();

	}

    /**
	 * Send email function.
	 *
     * @param string    $to         (Required)  To email destination
     * @param string    $subject    (Required)  Subject of email
     * @param string    $message    (Required)  Message of email
     * @param string    $from       (Optional)  From email
     * @param string    $from_name  (Optional)  From name email
     * @param string    $attachment (Optional)  File Attachment
	 * @return Mixed
	 */

    function send_email($to, $username, $debug=false){
        // ini_set("SMTP","ssl://smtp.gmail.com");
        // ini_set("smtp_port","465");

        $password_default = "P@ssw0rd";
        $subject = ' [NOTIFICATION] Akses Login Aplikasi Cosmic - https://cosmicsystem.id/cosmic';
        //$message = $html_spon;
        $message2 = '<h2 style="width: 453.594px; text-align: center; margin: 0px auto 20px;">
        <b>aplikasi Covid-19 Safe Management Information and Compliance (Cosmic)</b></h2><br />


        <div style="width: 90%; border: 2px solid #E02222; padding: 0; margin: 0 auto;">
        <div style="background-color: #E02222; padding: 5px; color: #FFF; text-align: center; font: bold 13px Arial;"><b> Terima Kasih sudah melakukan Registrasi di Aplikasi Cosmic, berikut Info Username dan Password untuk akses login.</b></div>

        <div style="padding 10px; color: #666666; font: 12px/20px Arial;">
        
        <p style="padding: 0 10px;">
        Username : '.$username.'<br />
        Password : '.$password_default.'<br />
        Date : '.date('d F Y').'</p>

        <p style="padding: 0 10px;">You are receiving this notification because your name and email address are registered into our KPI Manual Sistem user database.

Please DO NOT reply back to this email address sender as we are using it for sending purpose only and are not monitoring any incoming emails into it.

If you have any questions or informations regarding this notification, or if you do not want to receive any notifications in the future, please contact our Customer Care officer at below address.</p>

        <p style="width: 50%; padding: 20px 10px 0 10px; color: #888888; font-size: 11px;">Salam Sukses,</p>

        <!--p style="color: rgb(102, 102, 102); font-family: Arial; font-size: 12px; line-height: 20px; padding: 0px 10px;">IPC<br />
            Jl. Pasoso No. 1, Tanjung Priok, Jakarta 14310</p-->

        </div>';
        $headers = 'From: no-reply@cosmicsystem.id' . "\r\n" .
            'Reply-To: no-reply@cosmicsystem.id' . "\r\n" .
            "MIME-Version: 1.0" . "\r\n" .
            "Content-type: text/html; charset=UTF-8" . "\r\n".
            'X-Mailer: PHP/' . phpversion();

			$config = Array(
		        'protocol' => 'smtp',
		        'smtp_host' => 'ssl://smtp.googlemail.com',
		        'smtp_port' => 465,
		        // 'smtp_user' => 'indonesia.sehat@bumn.go.id',
		        // 'smtp_pass' => '?zE%cnm&H7v;9&t123',
                'smtp_user' => 'rajawali.king@gmail.com',
                'smtp_pass' => 'zlqxdibghgjjdfmv',
				//'smtp_timeout'=>300,
		        'mailtype'  => 'html',
		        'charset'   => 'utf-8',
                'newline' => '\r\n'
		    );

            /*
			$config = Array(
		        'protocol' => 'smtp',
	            'smtp_crypto' => 'tls',
		        'smtp_host' => 'mail2.indonesiaport.co.id',
		        'smtp_port' => 587,
		        'smtp_user' => 'ipc.databox@indonesiaport.co.id',
		        'smtp_pass' => 'Ipc.databox-2018',
		        'mailtype'  => 'html',
		        'charset'   => 'iso-8859-1'
		    );
			*/

            $this->CI->load->library('email');
            $this->CI->email->initialize($config);
            $this->CI->email->set_newline("\r\n");
            $mail = $this->CI->email;
            $mail->from('no-reply@cosmicsystem.id', 'Cosmic System');
            $mail->to($to);

            $mail->subject($subject);
            $mail->message($message2);

            //$mail->send();

        if($mail->send()){
            return true;
            //mail($to, $subject, $message2, $headers);
        }
        echo $this->CI->email->print_debugger();
        //return false;
    }

}
