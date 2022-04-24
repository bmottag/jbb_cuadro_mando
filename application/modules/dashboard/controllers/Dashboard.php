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

			$userRol = $this->session->userdata("role");
			$data["view"] = "actividades";				
			if($userRol == ID_ROL_SUPERVISOR){
				$data["view"] = "actividades_ejecucion";
			}
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
					$this->dashboard_model->save_programa_actividad($nuevaActividad);//generar los programas
					//generar REGISTRO DE ESTADO ACTIVIDAD
					$banderaActividad = false;
					$estadoActividad = 1;
					$this->dashboard_model->guardarTrimestre($banderaActividad, $estadoActividad, $nuevaActividad, '', 0, 1);			
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
	 * Actualizar programacion
     * @since 23/04/2022
     * @author BMOTTAG
	 */
	public function update_programacion()
	{					
			$idActividad = $this->input->post('hddIdActividad');
			$idCuadroBase = $this->input->post('hddIdCuadroBase');

			if ($this->dashboard_model->guardarProgramacion()) {
				$data["result"] = true;
				$this->session->set_flashdata('retornoExito', "Se actualizó la información!!");
			} else {
				$data["result"] = "error";
				$this->session->set_flashdata('retornoError', '<strong>Error!!!</strong> Ask for help');
			}

			redirect(base_url('dashboard/actividades/' . $idCuadroBase . '/' . $idActividad), 'refresh');
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
			$banderaActividad = true;
			$estadoActividad = 2;
			if ($this->dashboard_model->guardarTrimestre($banderaActividad, $estadoActividad, $idActividad, $cumplimientoTrimestre, $avancePOA, $numeroTrimestre)) {
				$this->session->set_flashdata('retornoExito', 'Se cerro el trimestre.');
			} else {
				$this->session->set_flashdata('retornoError', '<strong>Error!!!</strong> Ask for help');
			}

			redirect(base_url('dashboard/actividades/' . $idCuadroBase . '/' . $idActividad), 'refresh');
    }

	/**
	 * SUPERVIDOR DASHBOARD
	 * @since 23/04/2022
	 */
	public function supervisor()
	{				
			$arrParam = array();
			$filtroEstrategias = $this->general_model->get_estrategias_by_responsable($arrParam);
			$valor = '';
			if($filtroEstrategias){
				$tot = count($filtroEstrategias);
				for ($i = 0; $i < $tot; $i++) {
					$valor = $valor . $filtroEstrategias[$i]['fk_id_estrategia'];
					if($i != ($tot-1)){
						$valor .= ",";
					}
				}
			}
			$arrParam = array("filtroEstrategias" => $valor);
			$data['listaEstrategias'] = $this->general_model->get_estrategias($arrParam);
			$data["view"] = "dashboard_supervisor";
			$this->load->view("layout_calendar", $data);
	}





	
	
}