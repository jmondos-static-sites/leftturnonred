<!-- you can include your sites header above if you like -->
<?php
// IMPORTANT: THIS FORM MUST BE SAVED AS A PHP FILE - USUALLY WITH A .php extension.


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

// you MUST include the config.php file before your form
include 'config.php';


// You can edit the form fields below if you like
// but you must leave intact all parts which are indicated
// with comments
?>
<!-- if you want to use basic JavaScript validation, keep the JS file call below -->
<script src="validation.js"></script>
<script>
// SPECIFY ALL REQUIRED FIELDS AND
// SEE validation.js for other options
required.add('fullname', 'NOT_EMPTY');
required.add('email', 'EMAIL');
required.add('comments', 'NOT_EMPTY');
required.add('answer_out', 'NUMERIC');
</script>
<link rel="stylesheet" type="text/css" href="form_style.css" />


<form name="fcform2" method="post" action="process_form.php" onsubmit="return validate.check()">
<div id="fcf2">
<p>All fields marked with <em>*</em> are required.</p>

<div class="r">
<label for="fullname" class="req">Full Name: <em>*</em></label>
<span class="f">
<input type="text" name="fullname" size="40" id="fullname" onBlur="trim('fullname')">
</span>
</div>

<div class="r">
<label for="email" class="req">Email Address: <em>*</em></label>
<span class="f">
<input type="text" name="email" size="40" id="email" onBlur="trim('email')">
</span>
</div>

<div class="r">
<label for="phone">Telephone: </label>
<span class="f">
<input type="text" name="phone" size="40" id="phone" onBlur="trim('phone')">
</span>
</div>

<div class="r">
<label for="comments" class="req">Comments: <em>*</em></label>
<span class="f">
<textarea cols="30" rows="8" name="comments" id="comments" onBlur="trim('comments')"></textarea>
</span>
</div>

<!-- the section below MUST remain for the magic to work -->
<!-- although feel free to change the style / layout -->
<div class="r">
<label for="quest" class="req"><?php echo $question; ?> <em>*</em></label>
<span class="f">
<input type="text" name="answer_out" size="6" id="answer_out" onBlur="trim('answer_out')"> &nbsp;

<!-- the link below MUST remain if you have not purchased a license -->
Spam prevention question.
<!-- link above MUST remain if you have not purchased a license -->

</span>
</div>
<!-- section above must remain -->


<div class="sp">&nbsp;</div>

<?php
if(isset($_GET['done'])) {
    echo '<div align="center" style="color:red;font-weight:bold">'.$confirmation_message.'</div><br />';
}
?>
<p align="center">
<input type="submit" value="Submit">
<br />
</p>
</div>

<!-- the 2 hidden fields below must REMAIN for the magic to work -->
        <input type="hidden" name="answer_p" value="<?php echo $answer_pass; ?>">
        <input type="hidden" name="enc" value="<?php echo $enc; ?>">
<!-- above 2 hidden fields MUST remain -->

</form>

<!-- you can include your sites footer below if you want -->