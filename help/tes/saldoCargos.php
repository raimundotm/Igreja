<?php
	if (empty($_GET['ano'])) {
		$ano = date ('Y	');
	}else {
		$ano = (int)$_GET['ano'];
	}

	//Loops para o corpo da tabela
	$linhas = ($linhas=='') ? 10 : $linhas ;
	$igrejas = new cargos($_GET['pagina'],$linhas);$linha='';
	$cargoMembro = (empty($_GET['cargo'])) ? 0 : $_GET['cargo'] ;

	$cor= true;
	foreach ($igrejas->ArrayCargosDados($cargoMembro) as $igrejaDados) {
		$saldos = new tes_Cargos ($igrejaDados['rol'],$ano);
		$valores = $saldos->ArraySaldos();
		$bgcolor = $cor ? 'class="dados"' : 'class="odd"';


		//Monta link para detalhar a igreja
		if ($_GET['rec']=='13') {
			$linkIgreja = $igrejaDados['razao'];
		}else {
			//http://localhost/igrejas/GitHub/Igreja/?escolha=views/tesouraria/saldoMembros.php&bsc_rol=4352
			$linkMemb = 'target="_blank" href="./?escolha=views/tesouraria/saldoMembros.php&bsc_rol='.$igrejaDados['rol'].'" title="Detalhar entradas"';
			$linkIgreja  = '<a '.$linkMemb.' >';
			$linkIgreja .= mostra_foto ($igrejaDados['rol']).'</a></td><td>';
			$linkIgreja .= '<a '.$linkMemb.' >'.$igrejaDados['nome'].'</a><br /><span class="text-info">'.$igrejaDados['razao'].'</span>';
		}

		$linha .= '<tr '.$bgcolor.'><td>'.$linkIgreja.'</td>';
		//print_r( $saldos->ArraySaldos());echo '<br />';
		for ($i = 1; $i < 13; $i++) {
			$entrada =($valores[$i]>0) ? number_format($valores[$i],2,',','.'):'---';
			$linha .= '<td id="moeda">'.$entrada.'</td>';
			$total += $valores[$i];
			$totalMes[$i] += $valores[$i];
		}

		$linha .= '<td id="moeda">'.number_format($total,2,',','.').'</td></tr>';
		$totalGeral += $total;
		$total = 0;
		$cor = !$cor;
	}


	//Cabe�alho da tabela
	$colgroup = '<col id="Nome">';
	$tabThead = '<tr><th scope="col" colspan="2">Nomes</th>';
	$tabFoot = '<tr id="total"><td colspan="2">Totais</td>';

	foreach(arrayMeses() as $mes => $meses) {
		$colgroup .= '<col id="'.substr($meses, 0, 3).'">';
		$tabThead .= '<th scope="col" class="centro">'.substr($meses, 0, 3).'</th>';
		$tabFoot  .= '<td id="moeda">'.number_format($totalMes[(int)$mes],2,',','.').'</td>';
	}
	$colgroup .= '<col id="Total">';
	$tabThead .= '<th scope="col"  class="centro">Total</th></tr>';
	$tabFoot  .= '<td id="moeda">'.number_format($totalGeral,2,',','.').'</td></tr>';

	$titulo = '';

	switch ($cargoMembro){//verifica o cargo

				case "1"://Verifica se � auxiliar de trabalho
					$titulo = 'auxiliar de trabalho';
					break;

				case "2"://verifica se � di�cono
					$titulo = 'di�cono';
					break;

				case "3"://verifica se � Presb�tero
					$titulo = 'Presb�tero';
					break;

				case "4"://verifica se � Evangelista
					$titulo = 'Evangelista';
					break;

				case "5"://verifica se � Pastor
					$titulo = 'Pastor';
					break;

				default:
					$titulo = 'Todos os Membros - Limitado agora aos primeiros 300';
					break;
		}

		$totalLinhas = $igrejas->linhas($cargoMembro,$_GET['cargo']);
?>
