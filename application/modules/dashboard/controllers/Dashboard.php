<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {
	
    public function __construct() {
        parent::__construct();
		$this->load->model("dashboard_model");
		$this->load->model("general_model");
    }
		
	/**
	 * SUPER ADMIN DASHBOARD
	 * @since 15/04/2022
	 */
	public function admin()
	{				
			$arrParam = array();
			$data['listaEstrategias'] = $this->general_model->get_estrategias($arrParam);
			$data["view"] = "dashboard";
			$this->load->view("layout_calendar", $data);
	}

    /**
     * Cargo modal - formulario cuadro base
     * @since 16/04/2022
     */
    public function cargarModalCuadroBase() 
	{
			header("Content-Type: text/plain; charset=utf-8"); //Para evitar problemas de acentos
			
			$data['information'] = FALSE;
			$data["idEstrategia"] = $this->input->post("idEstrategia");

			$arrParam = array("idEstratega" => $data["idEstrategia"]);
			$data['infoEstrategia'] = $this->general_model->get_estrategias($arrParam);

			$arrParam = array(
				"table" => "proyecto_inversion",
				"order" => "id_proyecto_inversion",
				"id" => "x"
			);
			$data['listaProyectos'] = $this->general_model->get_basic_search($arrParam);

			$arrParam = array(
				"table" => "meta_proyecto_inversion",
				"order" => "numero_meta_proyecto",
				"id" => "x"
			);
			$data['listaMetasProyectos'] = $this->general_model->get_basic_search($arrParam);

			$arrParam = array(
				"table" => " propositos",
				"order" => "numero_proposito",
				"id" => "x"
			);
			$data['listaPropositos'] = $this->general_model->get_basic_search($arrParam);

			$arrParam = array(
				"table" => " logros",
				"order" => "numero_logro",
				"id" => "x"
			);
			$data['listaLogros'] = $this->general_model->get_basic_search($arrParam);

			$arrParam = array(
				"table" => " programa_estrategico",
				"order" => "numero_programa_estrategico",
				"id" => "x"
			);
			$data['listaPrograma'] = $this->general_model->get_basic_search($arrParam);

			$arrParam = array(
				"table" => "meta_pdd",
				"order" => "numero_meta_pdd",
				"id" => "x"
			);
			$data['listaMetasPDD'] = $this->general_model->get_basic_search($arrParam);

			$arrParam = array(
				"table" => "ods",
				"order" => "numero_ods",
				"id" => "x"
			);
			$data['listaODS'] = $this->general_model->get_basic_search($arrParam);

			$arrParam = array(
				"table" => "param_dependencias",
				"order" => "dependencia",
				"id" => "x"
			);
			$data['listaDependencia'] = $this->general_model->get_basic_search($arrParam);
						
			$this->load->view("cuadro_base_modal", $data);
    }
	
	/**
	 * Ingresar/Actualizar cuadro base
     * @since 16/04/2022
     * @author BMOTTAG
	 */
	public function save_cuadro_base()
	{			
			header('Content-Type: application/json');
			$data = array();
			
			$idEstrategia = $this->input->post('hddIdEstrategia');
			
			$msj = "Se adicionó la información!";
			if ($idEstrategia != '') {
				$msj = "Se actualizó la información!";
			}

			if ($idEstrategia = $this->dashboard_model->saveCuadroBase()) {
				$data["result"] = true;				
				$this->session->set_flashdata('retornoExito', '<strong>Correcto!</strong> ' . $msj);
			} else {
				$data["result"] = "error";			
				$this->session->set_flashdata('retornoError', '<strong>Error!</strong> Ask for help');
			}

			echo json_encode($data);	
    }

	/**
	 * ACTIVIDADES
	 * @since 15/04/2022
	 */
	public function actividades($idCuadroBase, $idActividad = 'x')
	{				
			$data['idActividad'] = $idActividad;
			$data['idCuadroBase'] = $idCuadroBase;
			$data['infoEjecucion'] = false;
			$arrParam = array("idCuadroBase" => $idCuadroBase);
			$data['infoCuadroBase'] = $this->general_model->get_lista_cuadro_mando($arrParam);
			$data['listaActividades'] = $this->general_model->get_actividades($arrParam);

			$arrParam = array("idEstratega" => $data['infoCuadroBase'][0]['fk_id_estrategia']);
			$data['listaEstrategias'] = $this->general_model->get_estrategias($arrParam);

			if($idActividad != 'x') {
				$arrParam = array("idActividad" => $idActividad);
				$data['listaActividades'] = $this->general_model->get_actividades($arrParam);

				$data['infoEjecucion'] = $this->general_model->get_ejecucion_actividades($arrParam);
				//consulta sumatorias de programacion
				if($data['infoEjecucion']){
					$data['sumaProgramado'] = $this->general_model->sumarProgramado($arrParam);
					$data['sumaEjecutado'] = $this->general_model->sumarEjecutado($arrParam);
					$arrParam['numeroTrimestre'] = 1;
					$data['sumaProgramadoTrimestre1'] = $this->general_model->sumarProgramado($arrParam);
					$data['sumaEjecutadoTrimestre1'] = $this->general_model->sumarEjecutado($arrParam);
					$arrParam['numeroTrimestre'] = 2;
					$data['sumaProgramadoTrimestre2'] = $this->general_model->sumarProgramado($arrParam);
					$data['sumaEjecutadoTrimestre2'] = $this->general_model->sumarEjecutado($arrParam);
					$arrParam['numeroTrimestre'] = 3;
					$data['sumaProgramadoTrimestre3'] = $this->general_model->sumarProgramado($arrParam);
					$data['sumaEjecutadoTrimestre3'] = $this->general_model->sumarEjecutado($arrParam);
					$arrParam['numeroTrimestre'] = 4;
					$data['sumaProgramadoTrimestre4'] = $this->general_model->sumarProgramado($arrParam);
					$data['sumaEjecutadoTrimestre4'] = $this->general_model->sumarEjecutado($arrParam);
				}

				$arrParam = array(
					"table" => "actividad_estado",
					"order" => "id_estado_actividad",
					"column" => "fk_id_actividad",
					"id" => $idActividad
				);
				$data['estadoActividad']  = $this->general_model->get_basic_search($arrParam);
			}

			$data["activarBTN1"] = true;//para activar el boton
			$data["view"] = "actividades";
			$this->load->view("layout_calendar", $data);
	}	

    /**
     * Cargo modal - formulario actividades
     * @since 15/04/2022
     */
    public function cargarModalActividad() 
	{
			header("Content-Type: text/plain; charset=utf-8"); //Para evitar problemas de acentos
			
			$data['information'] = FALSE;
			$data["idCuadrobase"] = $this->input->post("idCuadrobase");
			$data["idActividad"] = $this->input->post("idActividad");

			//Lista de usuarios activos
			$arrParam = array("filtroState" => TRUE);
			$data['listaUsuarios'] = $this->general_model->get_user($arrParam);

			$arrParam = array(
				"table" => "param_meses",
				"order" => "id_mes",
				"id" => "x"
			);
			$data['listaMeses'] = $this->general_model->get_basic_search($arrParam);
			
			if ($data["idActividad"] != 'x') 
			{
				$arrParam = array("idActividad" => $data["idActividad"]);
				$data['information'] = $this->general_model->get_actividades($arrParam);
				
				$data["idCuadrobase"] = $data['information'][0]['fk_id_cuadro_base'];
			}
			$this->load->view("actividad_modal", $data);
    }

	/**
	 * Guardar actividades
	 * @since 15/04/2022
     * @author BMOTTAG
	 */
	public function guardar_actividad()
	{			
			header('Content-Type: application/json');
			$data = array();
			$idActividad = $this->input->post('hddId');
			$data["idRecord"] = $this->input->post('hddIdCuadroBase');
		
			$msj = "Se guardo la información!";

			if ($nuevaActividad = $this->dashboard_model->guardarActividad()) 
			{	
				if ($idActividad == ''){
					//generar los programas
					$this->dashboard_model->save_programa_actividad($nuevaActividad);					
				}
				$data["result"] = true;		
				$this->session->set_flashdata('retornoExito', '<strong>Correcto!</strong> ' . $msj);
			} else {
				$data["result"] = "error";
				$this->session->set_flashdata('retornoError', '<strong>Error!</strong> Ask for help');
			}
		
			echo json_encode($data);
    }

    /**
     * Cargo modal - formulario programa actividad
     * @since 17/04/2022
     */
    public function cargarModalProgramarActividad() 
	{
			header("Content-Type: text/plain; charset=utf-8"); //Para evitar problemas de acentos
			
			$data["idActividad"] = $this->input->post("idActividad");

			$arrParam = array(
				"table" => "param_meses",
				"order" => "id_mes",
				"id" => "x"
			);
			$data['listaMeses'] = $this->general_model->get_basic_search($arrParam);
			
			$arrParam = array("idActividad" => $data["idActividad"]);
			$data['information'] = $this->general_model->get_actividades($arrParam);
			
			$data["idCuadrobase"] = $data['information'][0]['fk_id_cuadro_base'];

			$this->load->view("actividad_programar_modal", $data);
    }

	/**
	 * Guardar programado actividades
	 * @since 17/04/2022
     * @author BMOTTAG
	 */
	public function guardar_programado()
	{			
			header('Content-Type: application/json');
			$data = array();
			$idCuadroBase = $this->input->post('hddIdCuadroBase');
			$idActividad = $this->input->post('hddIdActividad');
			
			$data["idRecord"] = $idCuadroBase . "/" . $idActividad ;
		
			$msj = "Se guardo la información!";

			if ($this->dashboard_model->guardarProgramado()) 
			{				
				$data["result"] = true;		
				$this->session->set_flashdata('retornoExito', '<strong>Correcto!</strong> ' . $msj);
			} else {
				$data["result"] = "error";
				$this->session->set_flashdata('retornoError', '<strong>Error!</strong> Ask for help');
			}
		
			echo json_encode($data);
    }

	/**
	 * Actualizar ejecucion
     * @since 17/04/2022
     * @author BMOTTAG
	 */
	public function update_ejecucion()
	{					
			$idActividad = $this->input->post('hddIdActividad');
			$idCuadroBase = $this->input->post('hddIdCuadroBase');

			$arrParam = array("idActividad" => $idActividad);
			$infoActividad = $this->general_model->get_actividades($arrParam);

			if ($this->dashboard_model->guardarEjecucion()) {
				$data["result"] = true;
				$this->session->set_flashdata('retornoExito', "Se actualizó la información!!");
			} else {
				$data["result"] = "error";
				$this->session->set_flashdata('retornoError', '<strong>Error!!!</strong> Ask for help');
			}

			redirect(base_url('dashboard/actividades/' . $idCuadroBase . '/' . $idActividad), 'refresh');
    }

    /**
     * Delete Ejecucion
     * @since 17/04/2022
     * @author BMOTTAG
     */
    public function deleteEjecucion($idCuadroBase, $idActividad, $idEjecucion) 
	{
			if (empty($idCuadroBase) || empty($idActividad) || empty($idEjecucion) ) {
				show_error('ERROR!!! - You are in the wrong place.');
			}
		
			$arrParam = array(
				"table" => "actividad_ejecucion",
				"primaryKey" => "id_ejecucion_actividad",
				"id" => $idEjecucion
			);

			if ($this->general_model->deleteRecord($arrParam)) {
				$this->session->set_flashdata('retornoExito', 'Se elimio la ejecución de la actividad.');
			} else {
				$this->session->set_flashdata('retornoError', '<strong>Error!!!</strong> Ask for help');
			}

			redirect(base_url('dashboard/actividades/' . $idCuadroBase . '/' . $idActividad), 'refresh');
    }

    /**
     * Datos de actividades por TRIMESTRE
     * @since 17/04/2022
     * @author BMOTTAG
     */
    public function update_trimestre($idCuadroBase, $idActividad, $cumplimientoTrimestre, $avancePOA, $numeroTrimestre) 
	{
			if (empty($idCuadroBase) || empty($idActividad) || empty($numeroTrimestre) ) {
				show_error('ERROR!!! - You are in the wrong place.');
			}
		
			$arrParam = array(
				"table" => "actividad_estado",
				"order" => "id_estado_actividad",
				"column" => "fk_id_actividad",
				"id" => $idActividad
			);
			$estadoTrimestre = $this->general_model->get_basic_search($arrParam);

			if ($this->dashboard_model->guardarTrimestre($estadoTrimestre, $idActividad, $cumplimientoTrimestre, $avancePOA, $numeroTrimestre)) {
				$this->session->set_flashdata('retornoExito', 'Se cerro el trimestre.');
			} else {
				$this->session->set_flashdata('retornoError', '<strong>Error!!!</strong> Ask for help');
			}

			redirect(base_url('dashboard/actividades/' . $idCuadroBase . '/' . $idActividad), 'refresh');
    }







	
	/**
	 * Informacion de los roles
     * @since 1/12/2020
     * @author BMOTTAG
	 */
	public function rol_info()
	{		
			$data["view"] ='rol_info';
			$this->load->view("layout", $data);
	}

	/**
	 * Calendario
     * @since 6/1/2021
     * @author BMOTTAG
	 */
	public function calendar()
	{
			$data["view"] = 'calendar';
			$this->load->view("layout", $data);
	}

	/**
	 * Consulta desde el calendario
     * @since 6/1/2021
     * @author BMOTTAG
	 */
    public function consulta() 
    {
	        header("Content-Type: text/plain; charset=utf-8"); //Para evitar problemas de acentos

			$start = $this->input->post('start');
			$end = $this->input->post('end');
			$start = substr($start,0,10);
			$end = substr($end,0,10);

			$arrParam = array(
				"from" => $start,
				"to" => $end
			);
			
			//informacion Work Order
			$polizas = $this->general_model->get_polizas($arrParam);

			echo  '[';

			if($polizas)
			{
				$longitud = count($polizas);
				$i=1;
				foreach ($polizas as $data):
					echo  '{
						      "title": "Póliza a vencerse #: ' . $data['numero_poliza'] . ' - Equipo No. Inventario: ' . $data['numero_inventario'] . '",
						      "start": "' . $data['fecha_vencimiento'] . '",
						      "end": "' . $data['fecha_vencimiento'] . '",
						      "color": "green",
						      "url": "' . base_url("equipos/detalle/" . $data['id_equipo']) . '"
						    }';

					if($i<$longitud){
							echo ',';
					}
					$i++;
				endforeach;
			}

			echo  ']';
    }

	/**
	 * OPERADOR DASHBOARD
	 */
	public function operador()
	{				
			$data = array();
			$arrParam = array();

			//filtrar por estado y fecha, para el cuadro de notificaciones
			$year = date('Y');
			$firstDay = date('Y-m-d', mktime(0,0,0, 1, 1, $year));//primer dia del año, para filtrar por año

			$arrParam['from'] = $firstDay;//filtro registros desde el primeri dia del año
			$arrParam['estado'] = 1;
			$data['infoOrdenesTrabajo'] = $this->general_model->get_orden_trabajo($arrParam);
			$data['noOrdenesTrabajo'] = $data['infoOrdenesTrabajo']?count($data['infoOrdenesTrabajo']):0;

			$arrParam['estadoMantenimiento'] = 1;
			$arrParam['idUser'] = $this->session->id;
			$data['infoMantenimientoCorrectivo'] = $this->general_model->get_mantenimiento_correctivo($arrParam);

			$data['asignadas'] = $this->general_model->get_orden_trabajo($arrParam);
			$data['asignadas'] = $data['asignadas']?count($data['asignadas']):0;

			$arrParam['estado'] = 2;
			$data['solucionadas'] = $this->general_model->get_orden_trabajo($arrParam);
			$data['solucionadas'] = $data['solucionadas']?count($data['solucionadas']):0;

			$arrParam['estado'] = 3;
			$data['canceladas'] = $this->general_model->get_orden_trabajo($arrParam);
			$data['canceladas'] = $data['canceladas']?count($data['canceladas']):0;

			//Tipo -> vehiculos
			$arrParam = array(
				"idTipoEquipo" => 1,
				"estadoEquipo" => 1
			);
			$infoVehiculos = $this->general_model->get_equipos_info($arrParam);//info de vehiculos
			$data['noVehiculos'] = $infoVehiculos?count($infoVehiculos):0;

			//Tipo -> Bombas
			$arrParam = array(
				"idTipoEquipo" => 2,
				"estadoEquipo" => 1
			);
			$infoBombas = $this->general_model->get_equipos_info($arrParam);//info de bombas
			$data['noBombas'] = $infoBombas?count($infoBombas):0;
			
			$data["view"] = "dashboard";
			$this->load->view("layout", $data);
	}

	/**
	 * SUPERVISOR DASHBOARD
	 */
	public function supervisor()
	{				
			$data = array();
			$arrParam = array();

			//filtrar por estado y fecha, para el cuadro de notificaciones
			$year = date('Y');
			$firstDay = date('Y-m-d', mktime(0,0,0, 1, 1, $year));//primer dia del año, para filtrar por año

			$arrParam['from'] = $firstDay;//filtro registros desde el primeri dia del año
			$arrParam['estado'] = 1;
			$data['infoOrdenesTrabajo'] = $this->general_model->get_orden_trabajo($arrParam);
			$data['noOrdenesTrabajo'] = $data['infoOrdenesTrabajo']?count($data['infoOrdenesTrabajo']):0;

			$arrParam['estadoMantenimiento'] = 1;
			$data['infoMantenimientoCorrectivo'] = $this->general_model->get_mantenimiento_correctivo($arrParam);

			$data['asignadas'] = $this->general_model->get_orden_trabajo($arrParam);
			$data['asignadas'] = $data['asignadas']?count($data['asignadas']):0;

			$arrParam['estado'] = 2;
			$data['solucionadas'] = $this->general_model->get_orden_trabajo($arrParam);
			$data['solucionadas'] = $data['solucionadas']?count($data['solucionadas']):0;

			$arrParam['estado'] = 3;
			$data['canceladas'] = $this->general_model->get_orden_trabajo($arrParam);
			$data['canceladas'] = $data['canceladas']?count($data['canceladas']):0;

			//Tipo -> vehiculos
			$arrParam = array(
				"idTipoEquipo" => 1,
				"estadoEquipo" => 1
			);
			$infoVehiculos = $this->general_model->get_equipos_info($arrParam);//info de vehiculos
			$data['noVehiculos'] = $infoVehiculos?count($infoVehiculos):0;

			//Tipo -> Bombas
			$arrParam = array(
				"idTipoEquipo" => 2,
				"estadoEquipo" => 1
			);
			$infoBombas = $this->general_model->get_equipos_info($arrParam);//info de bombas
			$data['noBombas'] = $infoBombas?count($infoBombas):0;
			
			$data["view"] = "dashboard";
			$this->load->view("layout", $data);
	}

	/**
	 * ENCARGADO DASHBOARD
	 */
	public function encargado()
	{				
			$data = array();
			$arrParam = array();

			//filtrar por estado y fecha, para el cuadro de notificaciones
			$year = date('Y');
			$firstDay = date('Y-m-d', mktime(0,0,0, 1, 1, $year));//primer dia del año, para filtrar por año

			$arrParam['from'] = $firstDay;//filtro registros desde el primeri dia del año
			$arrParam['estado'] = 1;
			$data['infoOrdenesTrabajo'] = $this->general_model->get_orden_trabajo($arrParam);
			$data['noOrdenesTrabajo'] = $data['infoOrdenesTrabajo']?count($data['infoOrdenesTrabajo']):0;

			$arrParam['estadoMantenimiento'] = 1;
			$data['infoMantenimientoCorrectivo'] = $this->general_model->get_mantenimiento_correctivo($arrParam);

			$data['asignadas'] = $this->general_model->get_orden_trabajo($arrParam);
			$data['asignadas'] = $data['asignadas']?count($data['asignadas']):0;

			$arrParam['estado'] = 2;
			$data['solucionadas'] = $this->general_model->get_orden_trabajo($arrParam);
			$data['solucionadas'] = $data['solucionadas']?count($data['solucionadas']):0;

			$arrParam['estado'] = 3;
			$data['canceladas'] = $this->general_model->get_orden_trabajo($arrParam);
			$data['canceladas'] = $data['canceladas']?count($data['canceladas']):0;

			//Tipo -> vehiculos
			$arrParam = array(
				"idTipoEquipo" => 1,
				"estadoEquipo" => 1
			);
			$infoVehiculos = $this->general_model->get_equipos_info($arrParam);//info de vehiculos
			$data['noVehiculos'] = $infoVehiculos?count($infoVehiculos):0;

			//Tipo -> Bombas
			$arrParam = array(
				"idTipoEquipo" => 2,
				"estadoEquipo" => 1
			);
			$infoBombas = $this->general_model->get_equipos_info($arrParam);//info de bombas
			$data['noBombas'] = $infoBombas?count($infoBombas):0;
			
			$data["view"] = "dashboard";
			$this->load->view("layout", $data);
	}
	
	
}