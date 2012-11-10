<?php
require_once 'np-env.php';
extract(NpBootstrap::execute('HelloWorld','Test3'));
?>
<HTML>
<BODY>
	<center>
		<?php echo $method?>
	</center>
	<p />
	<p />
	<center>
		<?php echo $sessionId?>
	</center>
	<center>
		<?php echo $sessionValue?>
	</center>
	<p />
	<p />
	<center>
		<?php echo $uniqueKey?>
	</center>
</BODY>
</HTML>
