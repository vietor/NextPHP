<?php
require_once 'np-env.php';
NpFramework::execute('HelloWorld','internal');
?>
<HTML>
<BODY>
<h1>I'm in PHP</h1>
The value is <?php echo $value?>.
</BODY>
</HTML>
