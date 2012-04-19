<?php
/*
Author: Stuart Cochrane
URL: www.freecontactform.com
Email: stuartc1@gmail.com
Date: 26th July 2007
Version: 3.0 Beta 2
Updates: Additional protection from human compromise
License: Free to use and edit,
 but all comments and must remain intact.
 Link to author website MUST remain -
 unless you have purchased the rights to
 remove it!! - see README file for details.
*/


// THIS CODE IS KEPT LINEAR FOR EASE OF USER UNDERSTANDING


/* YOU DO NOT NEED TO CHANGE ANYTHING IN HERE */
include 'config.php';


// set-up redirect page
if($send_back_to_form == "yes") {
    $redirect_to = $form_page_name."?done=1";   
} else {
    $redirect_to = $success_page;
}


if(isset($_POST['enc'])) {

    
/* THIS IS THE NEW FORM VALIDATION SECTION */
include 'validation.class.php';



// check for any human hacking attempts
class clean {
    function comments($message) {
        $this->naughty = false;
        $this->message = $message;
        $bad = array("content-type","bcc:","to:","cc:","href");
        $for = array( "\r", "\n", "%0a", "%0d");
        foreach($bad as $b) {
            if(eregi($b, $this->message)) {
                $this->naughty = true;
            }   
        }   
        $this->message = str_replace($bad,"#removed#", $this->message);
        $this->message = stripslashes(str_replace($for, ' ', $this->message));
        
        // check for HTML/Scripts
        $length_was = strlen($this->message);
        $this->message = strip_tags($this->message);
        if(strlen($this->message) < $length_was) {
            $this->naughty = true;
        }
   }
} // class


// function to handle errors
function error_found($mes,$failure_accept_message,$failure_page) {   
   if($failure_accept_message == "yes") {
        $qstring = "?prob=".urlencode(base64_encode($mes));
   } else {
        $qstring = "";
   }
   $error_page_url = $failure_page."".$qstring;
   header("Location: $error_page_url"); 
   die();     
}







/* SET REQUIRED */
$reqobj = new required;
// ADD ALL REQUIRED FIELDS TO VALIDATE!
$reqobj->add("fullname","NOT_EMPTY");
$reqobj->add("email","EMAIL");
$reqobj->add("comments","NOT_EMPTY");
$reqobj->add("answer_out","NUMERIC");
$out = $reqobj->out();
$val = new validate($out, $_POST);
if($val->error) {
  $er = $val->error_string;
  error_found($er,$failure_accept_message,$failure_page);
  die(); 
}


/* validate the encrypted strings */
$dec = false;
$valid = false;

$dec = valEncStr(trim($_POST['enc']), $mkMine);
if($dec == true) {
    $valid = true;   
} else {
  $er = "Field data was incorrect.<br />$dec";
  error_found($er,$failure_accept_message,$failure_page);
  die(); 
}


// check the spam question has the correct answer
$ans_one = $_POST['answer_out'];
$fa = new encdec;
$ans_two = $fa->decrypt($_POST['answer_p']);

if($ans_one === $ans_two) {
    $valid = true;
} else {
    $er ='Your spam prevention answer was wrong.';
    error_found($er,$failure_accept_message,$failure_page);
    die(); 
}



if($valid) {
	$email_from = $_POST['email'];
	$email_message = "Please find below a message submitted on ".date("Y-m-d")." at ".date("H:i")."\n\n";
  
  // loop through all form fields submitted
  // ignore all fields used for security measures
  foreach($_POST as $field_name => $field_value) {
    if($field_name == "answer_out" || $field_name == "answer_p" || $field_name == "enc") {
      // do not email these security details
    } else {
        // run all submitted content through string checker
        // removing any dangerous code
      $ms = new clean;
      $ms->comments($field_value);
      $is_naughty = $ms->naughty;
      $this_val = $ms->message;
      $email_message .= $field_name.": ".$this_val."\n\n";
    }
  }

  if($is_naughty) { 
      if($accept_suspected_hack == "yes") {
        // continue
      } else {
        // pretend the email was sent
        header("Location: $redirect_to");
        die();  
      }
      $email_subject = $email_suspected_spam; 
  }
  
// create email headers
$headers = 'From: '.$email_from."\r\n" .
'Reply-To: '.$email_from."\r\n" .
'X-Mailer: PHP/' . phpversion();
  // send the email
  @mail($email_it_to, $email_subject, $email_message, $headers);  
  // redirect
  header("Location: $redirect_to");
  die(); 
}

} else {
    echo "register globals may be on, please switch this setting off (look at php.net for details, specificall ini_set() function )";
}
?>