<!DOCTYPE html>
<html>
<body>

<?php 
	$varPost = $_POST;
	
	$JSONplanos = json_decode(file_get_contents("plans.json"), true);
	$JSONvalores = json_decode(file_get_contents("prices.json"), true);	
	
	$planosPorVidas = [];
	$auxPlanosIncluidos = [];

	$benefJSON = [];
	
	for($i = 0; isset($varPost["nome".$i]); $i++){
		
		//gerar JSON de beneficiários
		array_push($benefJSON,
			array(
				"nome" => $varPost["nome".$i],
				"idade" => $varPost["idade".$i],
				"registro" => $varPost["registro".$i]
			)
		);
		
		//buscar código de plano
		foreach($JSONplanos as $JSONplano){
			if($JSONplano["registro"] == $varPost["registro".$i]){
				$codigoPlano = $JSONplano["codigo"];
				break;
			}
		}
		
		//gerar objeto com array de planos escolhidos com números de vidas e códigos
		if(empty($planosPorVidas)){			
			array_push($planosPorVidas,
				array(
					"registro" => $varPost["registro".$i],
					"codigo" => $codigoPlano,
					"totalVidas" => 1
				)
			);
			array_push($auxPlanosIncluidos, $varPost["registro".$i]);
		}
		else{	
			foreach($planosPorVidas as $key => $registro){
				if($registro["registro"] == $varPost["registro".$i]){
					$planosPorVidas[$key]["totalVidas"] = $registro["totalVidas"] + 1;
				}
				else{					
					if(!in_array($varPost["registro".$i], $auxPlanosIncluidos)){
						array_push($planosPorVidas,
							array(
								"registro" => $varPost["registro".$i],
								"codigo" => $codigoPlano,
								"totalVidas" => 1
							)
						);
						array_push($auxPlanosIncluidos, $varPost["registro".$i]);
					}					
				}
			}			
		}
	}
	//var_dump($planosPorVidas);
	
	//verificar planos elegíveis pelo número de vidas
	foreach($planosPorVidas as $key => $plano){
		$numVidasPlanoElegivel = 0;
		foreach($JSONvalores as $JSONvalor){
			if($plano["codigo"] == $JSONvalor["codigo"]){
				if($plano["totalVidas"] >= $JSONvalor["minimo_vidas"]){
					if($JSONvalor["minimo_vidas"] > $numVidasPlanoElegivel){
						$planosPorVidas[$key]["valorFaixa1"] = $JSONvalor["faixa1"];
						$planosPorVidas[$key]["valorFaixa2"] = $JSONvalor["faixa2"];
						$planosPorVidas[$key]["valorFaixa3"] = $JSONvalor["faixa3"];	
						$numVidasPlanoElegivel = $JSONvalor["minimo_vidas"];
					}
				}
			}
		}
	}	
	
	$propostaJSON = $benefJSON;	
	
	foreach($benefJSON as $key => $proposta){
		foreach($planosPorVidas as $plano){			
			if($proposta["registro"] == $plano["registro"]){
				if($proposta["idade"] <= 17){
					$propostaJSON[$key]["valorPlano"] = $plano["valorFaixa1"];
				}
				else if($proposta["idade"] >= 40){
					$propostaJSON[$key]["valorPlano"] = $plano["valorFaixa3"];
				}
				else{
					$propostaJSON[$key]["valorPlano"] = $plano["valorFaixa2"];
				}
			}
		}
	}
	
	$valorTotal = 0;
	
	echo "<h2>Proposta</h2>";
	
	foreach($propostaJSON as $key => $proposta){
		echo "<p>Beneficiário: ".$proposta["nome"]."; Idade: ".$proposta["idade"]."; Valor do plano: R$ ".$proposta["valorPlano"]."</p><br>";
		$valorTotal = $valorTotal + $proposta["valorPlano"];
	}
	
	echo "<p>Valor Total: R$".$valorTotal."</p><br><br>";
	array_push($propostaJSON, array("valorTotal" => $valorTotal));
	
	//gerar arquivoS
	$file = fopen("beneficiarios.json","w");
	fwrite($file, json_encode($benefJSON));
	fclose($file);
	
	$file = fopen("proposta.json","w");
	fwrite($file, json_encode($propostaJSON));
	fclose($file);	

?>


</body>
</html>