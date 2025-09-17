<?php
class AlvComponent extends Component
{

	function tratarData($valor, $tipo = 'us')
	{
		if ($tipo == 'us') {
			$valorReplace = str_replace('/', '-', $valor);
			$resultValor = date('Y-m-d', strtotime($valorReplace));
		} else {
			$resultValor = date('d/m/Y', strtotime($valor));
		}
		return $resultValor;
	}

	function tratarValor($valor, $tipo = 'us')
	{
		if ($tipo == 'us') {
			$valorReplace = str_replace('.', '', $valor);
			$valorReplace = str_replace(',', '.', $valorReplace);
			$resultValor = $valorReplace;
		} else {
			$resultValor = number_format($valor, 2, ',', '.');
		}
		return $resultValor;
	}

	function somenteNumeros($valor)
	{
		return preg_replace("/[^0-9]/", "", $valor);
	}

	function mask($val, $mask)
	{
		$maskared = '';
		$k = 0;
		for ($i = 0; $i <= strlen($mask) - 1; $i++) {
			if ($mask[$i] == '#') {
				if (isset($val[$k]))
					$maskared .= $val[$k++];
			} else {
				if (isset($mask[$i]))
					$maskared .= $mask[$i];
			}
		}
		return $maskared;
	}

	public static function enviarEmail($dados, $destinario, $arrayAttachments = array())
	{
		Configure::write('debug', 1);
		App::uses('CakeEmail', 'Network/Email');
		$email = new CakeEmail();
		$replayTo = (isset($dados['email']) && !empty($dados['email'])) ? $dados['email'] : Configure::read('Sistema.email');
		$nome = (isset($dados['nome']) && !empty($dados['nome'])) ? $dados['nome'] : Configure::read('Sistema.title');
		//prepara os dados        
		if (isset($dados['replyTo'])) {
			$email->from(array($dados['replyTo'] => $nome));
			$email->replyTo($dados['replyTo']);
		} else {
			$email->from(array($replayTo => $nome));
		}
		$email->to($destinario);
		$email->subject($dados['assunto']);
		if(!empty($arrayAttachments)){
			$email->attachments($arrayAttachments);
		}
		$email->emailFormat('html');
		//se enviar a mensagem com sucesso:
		if ($email->send($dados['mensagem'])) {
			return true;
		} else {
			return false;
		}
	}

	function getIpAddress()
	{
		//whether ip is from the share internet  
		if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
			$ip = $_SERVER['HTTP_CLIENT_IP'];
		}
		//whether ip is from the proxy  
		elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		}
		//whether ip is from the remote address  
		else {
			$ip = $_SERVER['REMOTE_ADDR'];
		}
		return $ip;
	}

	function isSecure()
	{
		return (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https');
	}
}
