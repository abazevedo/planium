<!DOCTYPE html>
<html>
<body>

<?php 
	$totalBenef = $_GET["q"];	
	
	echo "<form id=\"formBenef\" action=\"proposta.php\" method=\"post\">";
	
	for ($i = 0; $i < $totalBenef; $i++) {
	  echo "<input type=\"text\" id=\"nome".$i."\" name=\"nome".$i."\" placeholder=\"Nome\">";
	  echo "<input type=\"number\" id=\"idade".$i."\" name=\"idade".$i."\" placeholder=\"Idade\">";
	  echo "<input type=\"text\" id=\"registro".$i."\" name=\"registro".$i."\" placeholder=\"Plano (registro)\"><br><br>";
	}
	echo "<button type=\"submit\" form=\"formBenef\" value=\"Submit\">Enviar</button>";
	echo "</form>";

?>


</body>
</html>