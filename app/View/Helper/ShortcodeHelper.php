<?php
App::uses('AppHelper', 'View/Helper');

class ShortcodeHelper extends AppHelper {
    public $helpers = ['Html'];

    public function parse($content) {
        // Regex para capturar shortcodes: [nome param="valor" outro='valor']
        // Note o modificador s (dotall) para permitir newlines nos params
        $pattern = '/\[(\w+)(.*?)\]/s';

        return preg_replace_callback($pattern, function ($matches) {
            $tag = $matches[1];               // ex: atracoes, botao_whats
            $rawParams = trim($matches[2]);   // ex: id="3" title="teste"

            // Decodifica entidades (ex: &quot;) — importante quando usado dentro do CKEditor
            $rawParams = html_entity_decode($rawParams, ENT_QUOTES, 'UTF-8');

            $params = $this->parseAttributes($rawParams);

            // Suporte para theme via atributo theme="Nome"
            $theme = isset($params['theme']) ? $params['theme'] : $this->_View->theme;
            if (isset($params['theme'])) {
                unset($params['theme']);
            }

            $elementPath = 'site/' . $tag;

            if ($this->_View->elementExists($elementPath, ['theme' => $theme])) {
                // $params será extraído como variáveis dentro do element
                return $this->_View->element($elementPath, $params, ['theme' => $theme]);
            }

            // Se não encontrou element, retorna o shortcode original
            return $matches[0];
        }, $content);
    }

    /**
     * Parse de atributos aceitando:
     * - double quotes:  name="value"
     * - single quotes:  name='value'
     * - unquoted:       name=value
     */
    private function parseAttributes($text) {
        $attrs = [];
        if (empty($text)) {
            return $attrs;
        }

        // Captura name="value" ou name='value' ou name=value (até espaço ou ])
        preg_match_all('/(\w+)\s*=\s*(?:"([^"]*)"|\'([^\']*)\'|([^\s\]]+))/U', $text, $matches, PREG_SET_ORDER);

        foreach ($matches as $m) {
            $key = $m[1];
            // valor pode estar em $m[2] (double), $m[3] (single) ou $m[4] (no quotes)
            $val = isset($m[2]) && $m[2] !== '' ? $m[2] : (isset($m[3]) && $m[3] !== '' ? $m[3] : (isset($m[4]) ? $m[4] : ''));

            // Decodifica entidades dentro do valor (safety)
            $attrs[$key] = html_entity_decode($val, ENT_QUOTES, 'UTF-8');
        }

        return $attrs;
    }
}
