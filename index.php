<!DOCTYPE html>
<html>
	<head>
		<style>
			.grid-container {
			  display: grid;
			  grid-template-columns: auto auto auto;
			  padding: 10px;
			}
			.grid-item {
			  border: 1px solid rgba(0, 0, 0, 0.8);
			  text-align: center;
			  font-size: 15px;
			}
</style>
	</head>
	<body>

	<?php

		$planos = json_decode(file_get_contents("plans.json"), true);
		$valores = json_decode(file_get_contents("prices.json"), true);
		
		echo "<h2>Planos</h2>";
		
		echo "<div class=\"grid-container\">";
		
		foreach($planos as $plano){
			foreach($valores as $valor){
				if($plano["codigo"] == $valor["codigo"]){
					echo "<div class=\"grid-item\">";
					echo nl2br("Nome do Plano: ".$plano["nome"].PHP_EOL);
					echo nl2br("Registro: ".$plano["registro"].PHP_EOL);
					echo nl2br("Código: ".$plano["codigo"].PHP_EOL);
					
					echo nl2br("Mínimo de vidas: ".$valor["minimo_vidas"].PHP_EOL);
					echo nl2br("Valores:".PHP_EOL);
					echo nl2br("Pessoas de 0 a 17 anos: R$ ".$valor["faixa1"].PHP_EOL);
					echo nl2br("Pessoas de 18 a 40 anos: R$ ".$valor["faixa2"].PHP_EOL);
					echo nl2br("Pessoas com mais de 40 anos: R$ ".$valor["faixa3"].PHP_EOL);
					
					echo "</div>";
				}
			}		
					
		}
		echo "</div>";
	?>

	<form>
		<input type="number" id="qtdBenef" name="qtdBenef" onkeyup="exibirForm(this.value)" placeholder="Número de beneficiários">
	</form>
	<p>
		<span id="tabBenef"></span>
	</p>

	<script type="text/javascript" src="https://code.jquery.com/jquery-1.4.3.min.js"></script>
	<script>
		function exibirForm(qtd) {
		  if (qtd.length != 0) {
			var xmlhttp = new XMLHttpRequest();
			xmlhttp.onreadystatechange = function() {
			  if (this.readyState == 4 && this.status == 200) {
				document.getElementById("tabBenef").innerHTML = this.responseText;
			  }
			}
			xmlhttp.open("GET", "beneficiario.php?q="+qtd, true);
			xmlhttp.send();
		  }
		}
	</script>
</body>
</html>