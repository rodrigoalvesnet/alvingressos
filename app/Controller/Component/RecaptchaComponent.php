<?php
App::uses('CakeLog', 'Log');

class RecaptchaComponent extends Component
{

    /**
     * Se site_key/secret_key não estiverem configurados em Configure::read('Recaptcha'),
     * o widget não é exibido nas views e a verificação é ignorada (retorna true) para não
     * travar o login antes da configuração ser feita.
     */
    public function isEnabled()
    {
        return !empty(Configure::read('Recaptcha.site_key')) && !empty(Configure::read('Recaptcha.secret_key'));
    }

    public function verify($token, $remoteIp = null)
    {
        if (!$this->isEnabled()) {
            return true;
        }

        if (empty($token)) {
            return false;
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://www.google.com/recaptcha/api/siteverify');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(array(
            'secret' => Configure::read('Recaptcha.secret_key'),
            'response' => $token,
            'remoteip' => $remoteIp,
        )));
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, Configure::read('debug') == 0);
        $result = curl_exec($ch);
        $curlError = curl_error($ch);
        curl_close($ch);

        if ($result === false) {
            CakeLog::write('error', 'Recaptcha: falha ao chamar siteverify - ' . $curlError);
            return false;
        }

        $response = json_decode($result, true);
        if (empty($response['success'])) {
            CakeLog::write('error', 'Recaptcha: verificação falhou - ' . $result);
            return false;
        }

        return true;
    }
}
