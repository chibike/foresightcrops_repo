<?php
	
	if( $_SERVER["REQUEST_METHOD"] == "POST" )
	{
		echo 'Request Received!'."\n\n";
		
		foreach ($_POST as $key => $value)
		{
			echo "{$key} = {$value};\n";
		}
	}
	else
	{
		echo "Request Denied";
	}
	
?>