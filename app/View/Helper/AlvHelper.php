<?php
App::uses('AppHelper', 'View/Helper');
class AlvHelper extends AppHelper
{

	public $helpers = array(
		'Html',
		'Form',
		'Js'
	);

	function autocomplete($label, $field, $id, $value = '', $hiddenField, $hiddenValue = '', $hiddenId, $url, $classBS = '', $placeholder = '', $required = true, $onSelect = false)
	{
		//input autocomplete
		$inputAutocomplete = $this->Form->input(
			$field,
			array(
				'label'	=> array(
					'class' => 'bmd-label-floating',
					'text' => $label
				),
				'value' 		=> $value,
				'id'			=> $id,
				'placeholder' 	=> $placeholder,
				'required' 		=> $required,
				'type'			=> 'text',
				'class' 		=> 'form-control',
				'div' 			=> 'form-group bmd-form-group ' . $classBS,
				'after'			=> '<span id="' . $id . 'Help" style="color:red;font-style:italic;font-size:11px;"></span>'
			)
		);

		//campo hidden
		$inputAutocomplete .= $this->Form->hidden(
			$hiddenField,
			array(
				'value' => $hiddenValue,
				'id' 	=> $hiddenId
			)
		);

		//bloco de script do autocomplete
		$inputAutocomplete .= $this->Html->scriptBlock(
			'$(document).ready(function(){  
				
		  		(function($) {

		  			//variaveis padrão
		  			var msgVazio = "Nenhum resultado encontrado";

		  			//quando vai digitando procura o resulta	
					$("#' . $id . '").autocomplete({
						source: "' . $url . '",
					    minChars: 3,
				        delay: 200,
				        async: true,
				        autoFocus: true,
				        select: function (event, ui){     	
				            selecionaItem(ui.item.dados);
				            ' . $onSelect . '
				        },
				        status: "#autocompleteHelp",
				        change: function(event, ui){				        	
				        	deixarCampo(ui.item)
				        },
				        response: function (event, ui) {
			                if(ui.content == 0){
			                    $("#' . $id . 'Help").text(msgVazio);	
			                }else{
			                	$("#' . $id . 'Help").text("");	
			                }
			            },
				        messages: {
					        noResults: msgVazio,
					        results: function(total) {
								return total + ( total > 1 ? " registros encontrados" : " registro encontrado" ) +
									", utilize a seta para baixo para selecionar um registro";
							}
					    },
					    close: function (event, ui) {
				            $(".ui-helper-hidden-accessible").remove();
				        } 
				 	});

				})(jQuery);

				//quando seleciona o item
				function selecionaItem(dados){
					d = JSON.parse(dados);
					$("#' . $hiddenId . '").val(d["id"]);
				}

				//Quando sair do campo
				function deixarCampo(item){
					//se não foi selecionado nada
					if($.isEmptyObject(item)){
						//limpa os campos
						$("#' . $id . '").val("");
						$("#' . $hiddenId . '").val("");
						return false;
					}		
				}

		  	});',
			array('block' => 'scriptBottom')
		);

		$inputAutocomplete .= '<style>.ui-helper-hidden-accessible{display:none;}</style>';

		return $inputAutocomplete;
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

	function avatarPessoa($fotoField, $sexo, $class = '', $width = 100, $size = 'small')
	{
		//Se tem foto
		if (!empty($fotoField)) {
			$imgPath = '/anexos/' . $size . '/' .  $fotoField;
		} else {
			if ($sexo == 'Masculino') {
				$imgPath = '/img/avatar-masculino.png';
			} else {
				$imgPath = '/img/avatar-feminino.png';
			}
		}
		return $this->Html->image(
			$imgPath,
			array(
				'class' => 'img-avatar-pessoa ' . $class,
				'width' => $width,
				'height' => $width,
				'fullBase' => true
			)
		);
	}

	function getIdade($data)
	{

		// separando yyyy, mm, ddd
		list($ano, $mes, $dia) = explode('-', $data);

		// data atual
		$hoje = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
		// Descobre a unix timestamp da data de nascimento do fulano
		$nascimento = mktime(0, 0, 0, $mes, $dia, $ano);

		// cálculo
		$idade = floor((((($hoje - $nascimento) / 60) / 60) / 24) / 365.25);
		return $idade;
	}

	function getFaixaEtaria($data)
	{

		$idade = $this->getIdade($data);
		if($idade < 2){
			return 'Bebê';
		}
		if($idade < 10){
			return 'Criança';
		}
		if($idade < 14){
			return 'Pré-adolescente';
		}
		if($idade < 18){
			return 'Adolescente';
		}
		if($idade < 24){
			return 'Jovem';
		}
		if($idade < 65){
			return 'Adulto';
		}
		return 'Idoso(a)';
	}

}
