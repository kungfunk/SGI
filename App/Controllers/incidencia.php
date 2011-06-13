<?php 
/**
 * Description of incidencia
 *
 * @author Equipo101
 */
namespace App\Controllers;
 
class incidencia extends \Core\Libs\Controller
{
    public $permissions = array('item' => 'INCIDENCIA_ITEM',
                                'alta' => 'INCIDENCIA_ALTA');

    public function index() {
        throw new \Core\Libs\Fail('Debe especificarse el numero de Incidencia', 403);
    }

    public function item() {
        try {
            $incidencia = \App\Models\Incidencia::find($this->url[0]);
        }
        catch(\ActiveRecord\RecordNotFound $e) {
            throw new \Core\Libs\Fail('No existe esta incidencia', 404);
        }
        
        $sql = "select * from modificaciones where incidencia_id  = '".$incidencia->id."' order by updated_at asc";
        $modificaciones = \App\Models\Modificacion::find_by_sql($sql);
        	
        if(\Core\Libs\auth::getAuth()->rol == 'Cliente' && $incidencia->usuario->empresa_id != \Core\Libs\Auth::getAuth()->user->empresa_id)
            throw new \Core\Libs\Fail('No puedes acceder a esta incidencia', 404);
        
        $this->header->title = 'SGI › Incidencia ID'.$incidencia->id;
        $this->header->js = js('jquery-1.4.2.min.js', true);
        if(!empty($this->input['post'])) {
            $resultado = new \Core\Libs\View();
            $incidencia->descripcion = ($incidencia->status_id == 1 && !empty($this->input['post']['descripcion'])) ? $this->input['post']['descripcion'] : $incidencia->descripcion;
            if(\Core\Libs\auth::getAuth()->rol == 'Gestor' && !empty($this->input['post']['status_id'])) {
                $incidencia->status_id = $this->input['post']['status_id'];
            }
// Si recibimos un id de servicio relacionado con la incidencia lo guardamos
            if(\Core\Libs\auth::getAuth()->rol == 'Gestor' && !empty($this->input['post']['servicio_id'])) {
                $incidencia->servicio_id = $this->input['post']['servicio_id'];
            } 
// Si recibimos una respuesta para el cliente, la guardamos en la base de datos
            if(isset($this->input['post']['respuesta'])){
				$incidencia->respuesta = $this->input['post']['respuesta'];
			}
// Si recibimos el tiempo invertido en la incidencia lo guardamos multiplicando las horas por 60 y sum�ndolo a los minutos
			if(\Core\Libs\auth::getAuth()->rol == 'Gestor' && isset($this->input['post']['minutos_id'])){
				$incidencia->tiempo = $this->input['post']['minutos_id'];
			}		
			if(\Core\Libs\auth::getAuth()->rol == 'Gestor' && isset($this->input['post']['horas_id'])){
				$incidencia->tiempo += $this->input['post']['horas_id']*60;
			}
// Si el estado al que cambia la incidencia es el 4 (finalizada) guardamos la fecha actual en el campo finished_at
			$incidencia->finished_at = (\Core\Libs\auth::getAuth()->rol == 'Gestor' && !empty($this->input['post']['status_id']) && $this->input['post']['status_id'] == 4 /* finalizada */) ? date ("Y-m-d H:m:s") : null;
            
            if($incidencia->save()) {
				if(isset($this->input['post']['comentario'])){
            		$datos['comentario'] = $this->input['post']['comentario'];
				}
	            $datos['usuario_id'] = \Core\Libs\Auth::getAuth()->id;
	            $datos['status_id'] = $this->input['post']['status_id'];
	            $datos['incidencia_id'] = $incidencia->id;
	            $modificacion = new \App\Models\Modificacion($datos);
	            if($modificacion->save()) { 
	            	$resultado->mensaje = 'Incidencia modificada con exito <a href="'.DOC_URL.'">Volver al indice.</a>';
                	\App\Components\Notificaciones::cambioStatus($incidencia->usuario_id, $incidencia->status_id, $incidencia->id);
	            	return $resultado->display(array('common', 'success'));
	            }else{
	                $resultado->mensaje = 'Ha ocurrido un fallo en la modificacion <a href="'.DOC_URL.'">Volver al indice.</a>';
	                return $resultado->display(array('common', 'fail'));
            	}
            }else{
                $resultado->mensaje = 'Ha ocurrido un fallo en la modificacion <a href="'.DOC_URL.'">Volver al indice.</a>';
                return $resultado->display(array('common', 'fail'));
            }
        }
        else {
            $vista = new \Core\Libs\View();
            $vista->id = $incidencia->id;
            $vista->status = $incidencia->status->name;
            $vista->servicio = $incidencia->servicio->name;
            $vista->descripcion = $incidencia->descripcion;
            $vista->status_select = \App\Components\Combo::status($incidencia->status_id);
            $vista->status_id = $incidencia->status_id;
            $vista->servicio_select = \App\Components\Combo::servicios($incidencia->servicio_id);
            $vista->horas_select = \App\Components\Combo::horas();
            $vista->minutos_select = \App\Components\Combo::minutos();
            $vista->respuesta = $incidencia->respuesta;
            $vista->modificaciones = $modificaciones;
            
			return $vista->display(array('incidencia', 'item'));
        }
    }

    public function alta() {
        $this->header->title = 'SGI › Alta de incidencia';
        if(!empty($this->input['post'])) {
            $resultado = new \Core\Libs\View();
            $datos['descripcion'] = $this->input['post']['descripcion'];
            $datos['usuario_id'] = (\Core\Libs\Auth::getAuth()->rol == 'Gestor') ? $this->input['post']['usuario_id'] : \Core\Libs\Auth::getAuth()->id;
            $datos['servicio_id'] = $this->input['post']['servicio_id'];
            $datos['suplantado'] = ($datos['usuario_id'] != \Core\Libs\Auth::getAuth()->id) ? true : false;
            $datos['status_id'] = '1';
            $incidencia = new \App\Models\Incidencia($datos);
            if($incidencia->save()) {
                $resultado->mensaje = 'Incidencia creada con exito <a href="'.DOC_URL.'">Volver al indice.</a>';
                \App\Components\Notificaciones::nuevaIncidencia($incidencia->usuario_id, $incidencia->id);
                return $resultado->display(array('common', 'success'));
            }
            else {
                $resultado->mensaje = 'Ha ocurrido un fallo en la insercion <a href="'.DOC_URL.'">Volver al indice.</a>';
                return $resultado->display(array('common', 'fail'));
            }
        }
        else {
            $alta = new \Core\Libs\View();
            $alta->usuarios = \App\Components\Combo::usuarios(\Core\Libs\Auth::getAuth()->id);
            $alta->servicios = \App\Components\Combo::servicios(\Core\Libs\Auth::getAuth()->id);
            return $alta->display(array('incidencia', 'alta'));
        }
    }

}
?>
