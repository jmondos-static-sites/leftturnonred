<html>
    <head>
        <title>Form Error</title>
    </head>
<body>
<style>
#er {font-family:arial}
#er .rd{color:#F00}
</style>
<div id="er">
<h1>Form Error</h1>

The error message returned was:<br /><br />
<div class="rd">
<?php
  if(isset($_GET['prob'])) {
      echo base64_decode(urldecode($_GET['prob']));
  }
?>
</div>
<br /><br />
<b>Please hit the browser back button and try again.....</a>
</div>
</body>
</html>