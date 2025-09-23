<?php
class AlvComponent extends Component
{

	// Referência do controller que está usando o componente
	protected $controller;

	// Inicializa o componente e guarda o controller
	public function initialize(Controller $controller)
	{
		$this->controller = $controller;
	}

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
		if (!empty($arrayAttachments)) {
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

	function checkRecaptcha($data)
	{
		$recaptchaActive = Configure::read('Google.recaptcha.active');
		if ($recaptchaActive) {
			$recaptchaResponse = $data['g-recaptcha-response'];
			$secretKey = Configure::read('Google.recaptcha.secretkey');

			$remoteIp = $this->controller->request->clientIp();

			// Verifica via API do Google
			$url = 'https://www.google.com/recaptcha/api/siteverify';
			$data = [
				'secret' => $secretKey,
				'response' => $recaptchaResponse,
				'remoteip' => $remoteIp
			];

			$options = [
				'http' => [
					'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
					'method'  => 'POST',
					'content' => http_build_query($data),
				],
			];

			$context  = stream_context_create($options);
			$result = file_get_contents($url, false, $context);
			$resultJson = json_decode($result);

			if ($resultJson->success) {
				return true;
			} else {
				return false;
			}
		} else {
			return true;
		}
	}
}
