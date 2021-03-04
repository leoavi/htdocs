<?php
$numeroPedidoDeVendaMobile = null;
    $queryMobile = $connect->prepare("SELECT A.HANDLE, 
								A.STATUS STATUS, 
								A.NUMERO PEDIDO,
								B1.NOME FILIAL, 
								B2.SIGLA TIPO, 
								A.DATA DATA, 
								B3.APELIDO CLIENTE, 
								A.VALORTOTAL VALORTOTAL, 
								A.QUANTIDADE QUANTIDADE, 
								B4.NOME TRANSPORTADOR, 
								B5.NOME CONDICAOPAGAMENTO, 
								B6.NOME FORMAPAGAMENTO, 
								B8.NOME CONTATESOURARIA, 
								B9.NOME FRETE, 
								A.LOGDATACADASTRO DATAINCLUSAO, 
								B7.LOGIN USUARIOINCLUSAO
								FROM VE_ORDEM A
								LEFT JOIN MS_FILIAL B1 ON A.FILIAL = B1.HANDLE 
								LEFT JOIN VE_TIPOORDEM B2 ON A.TIPO = B2.HANDLE 
								LEFT JOIN MS_PESSOA B3 ON A.CLIENTE = B3.HANDLE 
								LEFT JOIN MS_PESSOA B4 ON A.TRANSPORTADOR = B4.HANDLE 
								LEFT JOIN FN_CONDICAOPAGAMENTO B5 ON A.CONDICAOPAGAMENTO = B5.HANDLE 
								LEFT JOIN FN_TIPOPAGAMENTO B6 ON A.FORMAPAGAMENTO = B6.HANDLE 
								LEFT JOIN MS_USUARIO B7 ON A.LOGUSUARIOCADASTRO = B7.HANDLE 
								LEFT JOIN TS_CONTA B8 ON A.CONTA = B8.HANDLE
								LEFT JOIN GD_FRETEPORCONTA B9 ON A.FRETE = B9.HANDLE
								WHERE A.EMPRESA = '".$empresa."'
								AND A.VENDEDOR = '".$handleUsuario."'
								ORDER BY NUMERO ASC
								") or die ('Erro ao executar sql de atendimentos.');
	
   
	$queryMobile->execute();
		while($rowMobile = $queryMobile->fetch(PDO::FETCH_ASSOC)){
			
			$numeroPedidoDeVendaMobile = $rowMobile['PEDIDO'];
			$handlePedidoDeVendaMobile = $rowMobile['HANDLE'];
			$statusMobile = $rowMobile['STATUS'];
			$filialPedidoDeVendaMobile = $rowMobile['FILIAL'];
		    $dataPedidoDeVendaMobile = date('d/m/Y H:i', strtotime($rowMobile['DATA']));
		    $clienteMobile = $rowMobile['CLIENTE']; 
			$tipoMobile = $rowMobile['TIPO']; 
			$valorTotalMobile = number_format($rowMobile['VALORTOTAL'], '2', ',', '.');
			$quantidadeMobile = number_format($rowMobile['QUANTIDADE'], '2', ',', '.');
			$transportadorMobile = $rowMobile['TRANSPORTADOR'];
			$condicaoPagamentoMobile = $rowMobile['CONDICAOPAGAMENTO'];
			$formaPagamentoMobile = $rowMobile['FORMAPAGAMENTO'];
			$contaTesourariaMobile = $rowMobile['CONTATESOURARIA'];
			$freteMobile = $rowMobile['FRETE'];
			$dataInclusaoMobile = date('d/m/Y H:i', strtotime($rowMobile['DATAINCLUSAO']));
			$usuarioInclusaoMobile = $rowMobile['USUARIOINCLUSAO'];

			if($statusMobile == '1'){
				$statusIconeMobile = "<img src='../../view/tecnologia/img/status/cinza/vazio.png' widtd='13px' height='13px'>";	
			}
			
			if($statusMobile == '2'){
				$statusIconeMobile = "<img src='../../view/tecnologia/img/status/amarelo/exclamacao.png' widtd='13px' height='13px'>";	
			}
			
			if($statusMobile == '3'){
				$statusIconeMobile = "<img src='../../view/tecnologia/img/status/vermelho/x.png' widtd='13px' height='13px'>";	
			}
			if($statusMobile == '4'){
				$statusIconeMobile = "<img src='../../view/tecnologia/img/status/verde/ok.png' widtd='13px' height='13px'>";	
			}
			if($statusMobile == '5'){
				$statusIconeMobile = "<img src='../../view/tecnologia/img/status/amarelo/vazio.png' widtd='13px' height='13px'>";	
			}
			if($statusMobile == '6'){
				$statusIconeMobile = "<img src='../../view/tecnologia/img/status/azul/vazio.png' widtd='13px' height='13px'>";	
			}
			if($statusMobile == '7'){
				$statusIconeMobile = "<img src='../../view/tecnologia/img/status/verde/cifrao.png' widtd='13px' height='13px'>";	
			}
			if($statusMobile == '8'){
				$statusIconeMobile = "<img src='../../view/tecnologia/img/status/verde/ponto.png' widtd='13px' height='13px'>";	
			}
			if($statusMobile == '9'){
				$statusIconeMobile = "<img src='../../view/tecnologia/img/status/azul/interrogacao.png' widtd='13px' height='13px'>";	
			}
			
			if($numeroPedidoDeVendaMobile > null){
				$numeroPedidoDeVendaMobileExibe = 'Pedido: '.$numeroPedidoDeVendaMobile;
			}
			else{
				$numeroPedidoDeVendaMobileExibe = null;
			}
			if($dataPedidoDeVendaMobile > null){
				$dataPedidoDeVendaMobileExibe = ' - Data: '.$dataPedidoDeVendaMobile;
			}
			else{
				$dataPedidoDeVendaMobileExibe = null;
			}
			if($tipoMobile > null){
				$tipoMobileExibe = ' - Tipo: '.$tipoMobile;
			}
			else{
				$tipoMobileExibe = null;
			}
			if($clienteMobile > null){
				$clienteMobileExibe = ' - Cliente: '.$clienteMobile;
			}
			else{
				$clienteMobileExibe = null;
			}
			if($quantidadeMobile > null){
				$quantidadeMobileExibe = ' - Quantidade: '.$quantidadeMobile;
			}
			else{
				$quantidadeMobileExibe = null;
			}
			if($valorTotalMobile > null){
				$valorTotalMobileExibe = ' - Valor total: '.$valorTotalMobile;
			}
			else{
				$valorTotalMobileExibe = null;
			}
			if($condicaoPagamentoMobile > null){
				$condicaoPagamentoMobileExibe = ' - Condição de pagamento: '.$condicaoPagamentoMobile;
			}
			else{
				$condicaoPagamentoMobileExibe = null;
			}
			if($formaPagamentoMobile > null){
				$formaPagamentoMobileExibe = ' - Forma de pagamento: '.$formaPagamentoMobile;
			}
			else{
				$formaPagamentoMobileExibe = null;
			}
			if($contaTesourariaMobile > null){
				$contaTesourariaMobileExibe = ' - Conta de tesouraria: '.$contaTesourariaMobile;
			}
			else{
				$contaTesourariaMobileExibe = null;
			}
			if($freteMobile > null){
				$freteMobileExibe = ' - Frete: '.$freteMobile;
			}
			else{
				$freteMobileExibe = null;
			}
			if($transportadorMobile > null){
				$transportadorMobileExibe = ' - Transportador: '.$transportadorMobile;
			}
			else{
				$transportadorMobileExibe = null;
			}
			if($dataInclusaoMobile > null){
				$dataInclusaoMobileExibe = ' - Data de inclusão: '.$dataInclusaoMobile;
			}
			else{
				$dataInclusaoMobileExibe = null;
			}
			if($usuarioInclusaoMobile > null){
				$usuarioInclusaoMobileExibe = ' - Usuário inclusão: '.$usuarioInclusaoMobile;
			}
			else{
				$usuarioInclusaoMobileExibe = null;
			}
?>
    			<tr>
                  <td hidden="true"><input type="radio" name="check[]" hidden="true" id="check" value="<?php echo $statusMobile.';'.$handlePedidoDeVendaMobile.';'.$numeroPedidoDeVendaMobile; ?>"></td>
                  <td widtd="1%" style="font-size:14px;"><?php echo $statusIconeMobile; ?></td>
                  <td><?php echo $numeroPedidoDeVendaMobileExibe.$dataPedidoDeVendaMobileExibe.$tipoMobileExibe.$clienteMobileExibe.$quantidadeMobileExibe.$valorTotalMobileExibe.$condicaoPagamentoMobileExibe.$formaPagamentoMobileExibe.$contaTesourariaMobileExibe.$freteMobileExibe.$transportadorMobileExibe.$dataInclusaoMobileExibe.$usuarioInclusaoMobileExibe; ?></td>	
    			</tr>
<?php
		}
?>
<?php
if(@$numeroPedidoDeVendaMobile <= '' or @$numeroPedidoDeVendaMobile == null){
?>
     <div class="col-md-12">
     	<div class="alert alert-warning">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
  		<span aria-hidden="true">&times;</span>
		</button>
     		<strong>Atenção: </strong> Não encontramos registros a serem exibidos!
     	</div>
     </div>
<?php
}
?>