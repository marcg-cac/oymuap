<?php
namespace Oym\Uap\Includes;
use Oym\Uap\Includes;

class Uap_Email_Template {
    Public $date_now;
    Public $email_settings;

    #region variables to set
    Public $from_email;
    Public $from_name;
    Public $subject;
    Public $recipients;
    Public $content;
    Public $attachments;
    #endregion


    public function __construct() {
        $this->email_settings = new Includes\Uap_Email_Settings();
        $this->date_now = new \DateTime("now");
        $this->recipients = [];
        $this->attachments = [];
        $this->from_email = $this->email_settings->from_email;
        $this->from_name = $this->email_settings->from_name;
    }

    public function add_attachment($attachment_path, $attachment_name, $attachment_type){
        $attachment = new Uap_Email_Attachment($attachment_path, $attachment_name, $attachment_type);
        $this->attachments[] = $attachment;
    }

    public function add_recipient($email, $name){
        $email_address = new Uap_Email_Address($email, $name);
        $this->recipients[] = $email_address;
    }
}

class Uap_Email_Address {
    Public $email;
    Public $name;

    public function __construct($email, $name) {
        $this->email= $email;
        $this->name= $name;
    }
}

class Uap_Email_Attachment {
    Public $encoded_file;
    Public $attachment_name;
    Public $attachment_type;

    public function __construct($attachment_path, $attachment_name, $attachment_type ) {
        $this->encoded_file = base64_encode(file_get_contents($attachment_path));
        $this->attachment_name= $attachment_name;
        $this->attachment_type= $attachment_type;
    }
}

?>