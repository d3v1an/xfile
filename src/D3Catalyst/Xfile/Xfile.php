<?php namespace D3Catalyst\Xfile;

class Xfile
{
	// Directorio sobre el que trabajaremos
	private $current_path 	= NULL;

	// Ultimo error generado
	private $last_error 	= NULL;

	// Salida de la ejecucion exec
	private $output 		= array();

	// Codigo de ejecucion
	private $return_code 	= 0;

	// Establece nuevos permisos para un archivo o un directorio
	public function chmod($permisions = NULL) {

		try {
			if(!is_null($permisions) && !is_null($this->current_path) && \File::exists($this->current_path) && strlen($permisions)==3) {
				exec ("chmod 0{$permisions} {$this->current_path}", $this->output, $this->return_code);
				return true;
			} else {
				$this->last_error = "No se a definido una ruta de directoio o archivo o los permisos asignados son incorrectos";
				return $this;
			}
		} catch (Exception $e) {
			$this->last_error = $e->getMessage();
			return false;
		}
 
    }

    // Definimos un usuario y grupo para un archivo
    public function chown($user = NULL, $group = NULL) {
    	try {
			if(!is_null($user) && !is_null($group) && !is_null($this->current_path) && \File::exists($this->current_path)) {
				exec ("chown {$user}:{$group} {$this->current_path}", $this->output, $this->return_code);
				return true;
			} else {
				$this->last_error = "No se a definido una ruta de directoio o archivo o el usuario y/o grupo asignados son incorrectos";
				return $this;
			}
		} catch (Exception $e) {
			$this->last_error = $e->getMessage();
			return false;
		}
    }

    //Asignacion recursiva de permisos para archivos y/o directorios
    public function r_chmod($permisions = NULL, $type = 'f') {

    	try {
    		if(is_null($this->current_path)) {
    			$this->last_error = "El path del archivo o directorio no se han definido";
    			return $this;
    		}
    		if($type=='f') {
				exec ("find {$this->current_path} -type f -exec chmod 0{$permisions} {} +", $this->output, $this->return_code);
				return true;
			} else if($type=='d') {
				exec ("find {$this->current_path} -type d -exec chmod 0{$permisions} {} +", $this->output, $this->return_code);
				return true;
			} else if($type=='a') {
				if(is_array($permisions) && array_key_exists('dir', $permisions) && array_key_exists('file', $permisions)) {
					exec ("find {$this->current_path} -type f -exec chmod 0{$permisions['dir']} {} +", $this->output, $this->return_code);
					exec ("find {$this->current_path} -type d -exec chmod 0{$permisions['file']} {} +", $this->output, $this->return_code);
					return true;
				}
			} else {
				$this->last_error = "Typo de recursividad no definido";
				return $this;
			}
    	} catch (Exception $e) {
    		$this->last_error = $e->getMessage();
    		return $this;
    	}

    }

    //Asignacion recursiva de permisos para archivos y/o directorios
    public function r_chown($user = NULL, $group = NULL, $type = 'f') {

    	try {
    		if(is_null($this->current_path)) {
    			$this->last_error = "El path del archivo o directorio no se han definido";
    			return $this;
    		}
    		if($type=='f') {
				exec ("find {$this->current_path} -type f -exec chown {$user}:{$group} {} +", $this->output, $this->return_code);
				return true;
			} else if($type=='d') {
				exec ("find {$this->current_path} -type d -exec chown {$user}:{$group} {} +", $this->output, $this->return_code);
				return true;
			} else if($type=='a') {
				if(is_array($permisions) && array_key_exists('dir', $permisions) && array_key_exists('file', $permisions)) {
					exec ("find {$this->current_path} -type f -exec chown {$user}:{$group} {} +", $this->output, $this->return_code);
					exec ("find {$this->current_path} -type d -exec chown {$user}:{$group} {} +", $this->output, $this->return_code);
					return true;
				}
			} else {
				$this->last_error = "Typo de recursividad no definido";
				return $this;
			}
    	} catch (Exception $e) {
    		$this->last_error = $e->getMessage();
    		return $this;
    	}

    }

    // Definicion de path
    public function path($path = NULL, $la_path = NULL) {

    	if(!is_null($la_path)) {

    		switch ($la_path) {

    			case 'app':
    				$this->current_path = app_path() . '/';
    				break;

    			case 'base':
    				$this->current_path = base_path() . '/';
    				break;

    			case 'public':
    				$this->current_path = public_path() . '/';
    				break;

    			case 'storage':
    				$this->current_path = storage_path() . '/';
    				break;
    			
    			default:
    				$this->current_path = NULL;
    				break;
    		}
    	}

    	if(!is_null($path)) $this->current_path = $this->current_path . $path;

    	return $this;
    }

    // Obtenemos el ultimo error registrado
    public function getLastError() {
    	return is_null($this->last_error) ? "" : $this->last_error;
    }

    // Obtenemos la salida del exec()
    public function getOutput() {
    	return $this->output;
    }

    public function getReturnCode() {
    	return $this->return_code;
    }

}