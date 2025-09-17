<?php
App::uses('Folder', 'Utility');
// App::import('Vendor', 'ImageCache', array('file' => 'imagecache/ImageCache.php'));
App::import('Vendor', 'ImageResize', array('file' => 'imageresize/ImageResize.php'));

class ImagemComponent extends Component {
	/**
	 * Verifica se o diretório existe, se não ele cria.
	 * @access public
	 * @param Array $imagem
	 * @param String $data
	*/ 
	public function checa_dir($dir)
	{	    
	    $folder = new Folder();
	    if (!is_dir($dir)){
	        $folder->create($dir);
	    }
	}

	/**
	 * Verifica se o nome do arquivo já existe, se existir adiciona um numero ao nome e verifica novamente
	 * @access public
	 * @param Array $imagem
	 * @param String $data
	 * @return nome da imagem
	*/ 
	public function checa_nome($imagem, $dir)
	{
	    $imagem_info = pathinfo($dir.$imagem['name']);
	    $imagem_nome = $this->trata_nome($imagem_info['filename']).'.'.$imagem_info['extension'];
	    //debug($imagem_nome);
	    $conta = 2;
	    while (file_exists($dir.$imagem_nome)) {
	        $imagem_nome  = $this->trata_nome($imagem_info['filename']).'-'.$conta;
	        $imagem_nome .= '.'.$imagem_info['extension'];
	        $conta++;
	        //debug($imagem_nome);
	    }
	    $imagem['name'] = $imagem_nome;
	    return $imagem;
	}

	/**
	 * Trata o nome removendo espaços, acentos e caracteres em maiúsculo.
	 * @access public
	 * @param Array $imagem
	 * @param String $data
	*/ 
	public function trata_nome($imagem_nome)
	{
	    $imagem_nome = strtolower(Inflector::slug($imagem_nome,'-'));
	    return $imagem_nome;
	}

	/**
	 * Move o arquivo para a pasta de destino.
	 * @access public
	 * @param Array $imagem
	 * @param String $data
	*/ 
	public function move_arquivos($imagem, $dir, $presets=false)
	{		
		//gera um novo nome
		$file = pathinfo($imagem['name']);

		$newName = md5(date('Y-m-d-H-i-s')).rand(111,999).'.'.$file['extension'];
		
	    //se é para criar os presets
	    if($presets){
			//small - cria os presets
			$this->imageResizeResize($imagem, 'small', false, false, $newName, $dir);
			//medium - cria os presets
			$this->imageResizeResize($imagem, 'medium', false, false, $newName, $dir);
			//large - cria os presets
			$this->imageResizeResize($imagem, 'large', false, false, $newName, $dir);
		}else{
			//apenas copia o arquivo	    	
			copy($imagem['tmp_name'], $dir.$newName);
		}
	    return $newName;
	}

	public function upload($imagem = array(), $preset = false, $path = 'uploads')
	{
	    $dir = WWW_ROOT.$path.DS;

	    $this->checa_dir($dir);

	    //move e retorna o nome arquivo
	    return $this->move_arquivos($imagem, $dir, $preset);
	}

	public function remove_imagem($imagem, $preset = false, $path = 'uploads')
	{
		$dir = WWW_ROOT.$path.DS;
		//se foi informado preset
		if($preset){
			$arquivo = new File($dir.DS.'small'.DS.$imagem);
	    	$arquivo->delete();
	    	$arquivo->close();
	    	$arquivo = new File($dir.DS.'medium'.DS.$imagem);
	    	$arquivo->delete();
	    	$arquivo->close();
	    	$arquivo = new File($dir.DS.'large'.DS.$imagem);
	    	$arquivo->delete();
	    	$arquivo->close();
		}else{
			$arquivo = new File($dir.$imagem);
	    	$arquivo->delete();
	    	$arquivo->close();
		}	    
	}

	public function imageResizeResize($img, $preset='small', $width=100, $heigth=100, $newName=false, $imgsDir){
		$presetDir = $imgsDir.$preset;
		//pega a imagem enviada
		$image = new ImageResize($img['tmp_name']);
		//se foi  informado o preset
		if(!is_null($preset)){
			switch ($preset) {
				case 'small':
					$image->resizeToWidth(200);
					break;			
				case 'medium':
					$image->resizeToWidth(400);
					break;
				case 'large':
					$image->resizeToWidth(900);
					break;
				default;
					return false;
					break;
			}
		//se NÃo foi informado um preset	
		}else{
			$image->resize($width,$heigth,true);
		}
		
		//se o diretorio ainda não existe
		if(!is_dir($presetDir)){
			//cria o diretorio
			mkdir($presetDir);
		}
		//se foi definido um novo nome
		if($newName){
			$filename = $newName;
		}
		//salva a imagem
		$image->save($presetDir.DS.$filename);
	}

}