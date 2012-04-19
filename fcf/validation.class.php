<?php
error_reporting(~0);
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


/* 
    PHP 4 compatible class - 
    change 'var' to 'public' if using PHP 5
*/
class required {
    var $fields; // public
    function required() // __construct()
    {
        $this->fields = array();
    } 
    function add($name, $type) // public
    {
        $this->fields[$name] = $type;
    } 
    function out() // public
    {
        return $this->fields;
    } 
} 

class validate {
    var $error = false; // public
    var $error_string; // publc
    var $error_tmp = "data not valid";

    function validate($ar, $post) // __construct()
    {
        if (!is_array($ar)) {
            /* no validation required */
        } else {
            foreach($ar as $a_name => $a_type) {
                /* if no validation specified, make not_empty */
                if (trim($a_type) == "") {
                    $a_type = "NOT_EMPTY";
                } 
                /* make sure $name is in $post */
                $found = false;
                foreach($post as $p_name => $p_value) {
                    if ($p_name == $a_name) {
                        $found = true;
                        break;
                    } 
                } 
                if (!$found) {
                    $this->error("FIELD: " . $a_name . " -> ERROR: no data submitted.<br />");
                } else {
                    if (is_array($a_type)) {
                        foreach($a_type as $tp) {
                            if (!$this->checkit($p_value, $tp)) {
                                echo "<i>$p_value,$tp</i><br />";
                                $this->error("FIELD: " . $a_name . " -> ERROR: " . $this->error_tmp . "<br />");
                            } 
                        } 
                    } else {
                        if (!$this->checkit($p_value, $a_type)) {
                            $this->error("FIELD: " . $a_name . " -> ERROR: " . $this->error_tmp . "<br />");
                        } 
                    } 
                } 
            } 
        } 
    } 
    /* ERRORS */
    function error($str) // private
    {
        $this->error = true;
        $this->error_string .= $str;
    } 
    /* VALIDATE FIELD AGAINST TYPE */
    function checkit($value, $type) // private
    {
        $length = "";
        if (eregi("^MIN[0-9]+$", $type)) {
            $tmp = explode(":", $type);
            $length = $tmp[1];
            $type = "MINLENGTH";
        } 
        if (eregi("^MAX[0-9]+$", $type)) {
            $tmp = explode(":", $type);
            $length = $tmp[1];
            $type = "MAXLENGTH";
        } 

        switch ($type) {
            case "NOT_EMPTY":
                $this->error_tmp = "string cannot be empty";
                return $this->not_empty($value);
                break;

            case "MINLENGTH":
                if (strlen($value) < $length) {
                    $this->error_tmp = "string to short";
                    return false;
                } else {
                    return true;
                } 
                break;

            case "MAXLENGTH":
                if (strlen($value) > $length) {
                    $this->error_tmp = "string to long";
                    return false;
                } else {
                    return true;
                } 
                break;

            case "ALPHA":
                $exp = "^[a-z]+$";
                if ($this->not_empty($value) && eregi($exp, $value)) {
                    return true;
                } else {
                    $this->error_tmp = "string not alpha";
                    return false;
                } 
                break;

            case "ALPHASPACE":
                $exp = "^[a-z ]+$";
                if ($this->not_empty($value) && eregi($exp, $value)) {
                    return true;
                } else {
                    $this->error_tmp = "string not alphaspace";
                    return false;
                } 
                break;

            case "ALPHANUM":
                $exp = "^[a-z0-9]+$";
                if ($this->not_empty($value) && eregi($exp, $value)) {
                    return true;
                } else {
                    $this->error_tmp = "string not alphanum";
                    return false;
                } 
                break;

            case "ALPHANUMSPACE":
                $exp = "^[a-z0-9 ]+$";
                if ($this->not_empty($value) && eregi($exp, $value)) {
                    return true;
                } else {
                    $this->error_tmp = "string not alphanumspace";
                    return false;
                } 
                break;

            case "NUMERIC":
                $exp = "^[0-9]+$";
                if ($this->not_empty($value) && eregi($exp, $value)) {
                    return true;
                } else {
                    $this->error_tmp = "string not numeric";
                    return false;
                } 
                break;

            case "NUMERICPLUS":
                $exp = "^[0-9+-.]+$";
                if ($this->not_empty($value) && eregi($exp, $value)) {
                    return true;
                } else {
                    $this->error_tmp = "string not numericplus";
                    return false;
                } 
                break;

            case "EMAIL":
                $exp = "^[A-Z0-9._%-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$";
                if ($this->not_empty($value) && eregi($exp, $value)) {
                    return true;
                } else {
                    $this->error_tmp = "not a valid email";
                    return false;
                } 
                break;

            case "YYYYMMDD":
                $exp = "^(19|20)[0-9][0-9][- /.](0[1-9]|1[012])[- /.](0[1-9]|[12][0-9]|3[01])$";
                if ($this->not_empty($value) && eregi($exp, $value)) {
                    return true;
                } else {
                    $this->error_tmp = "string not YYYYMMDD";
                    return false;
                } 
                break;

            case "DDMMYYYY":
                $exp = "^(0[1-9]|[12][0-9]|3[01])[- /.](0[1-9]|1[012])[- /.](19|20)[0-9][0-9]$";
                if ($this->not_empty($value) && eregi($exp, $value)) {
                    return true;
                } else {
                    $this->error_tmp = "string not DDMMYYYY";
                    return false;
                } 
                break;

            case "MMDDYYYY":
                $exp = "^(0[1-9]|1[012])[- /.](0[1-9]|[12][0-9]|3[01])[- /.](19|20)[0-9][0-9]$";
                if ($this->not_empty($value) && eregi($exp, $value)) {
                    return true;
                } else {
                    $this->error_tmp = "string not MMDDYYYY";
                    return false;
                } 
                break;

            default:
                if ($this->not_empty($value) && $this->regex($type, $value)) {
                    return true;
                } else {
                    $this->error_tmp = "string not valid";
                    return false;
                } 
        } 
    } 
    /* NOT_EMPTY */
    function not_empty($value) // private
    {
        if (trim($value) == "") {
            return false;
        } else {
            return true;
        } 
    } 

    /* REGULAR EXPRESSION */
    function regex($regex, $value) // private
    {
        $the_regex = 'ereg("' . $regex . '", "' . $value . '")';
        $the_code = '<?php if(' . $the_regex . ') { return true; } else { return false; } ?>';
        if (!eval('?>' . $the_code . '<?php ')) {
            return false;
        } else {
            return true;
        } 
    } 
}
?>