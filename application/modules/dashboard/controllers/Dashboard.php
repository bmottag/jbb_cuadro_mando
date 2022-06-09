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

			$arrParam = array(
				"table" => "objetivos_estrategicos",
				"order" => "objetivo_estrategico",
				"id" => "x"
			);
			$data['listaObjetivos'] = $this->general_model->get_basic_search($arrParam);

			$arrParam = array(
				"table" => "param_dependencias",
				"order" => "dependencia",
				"id" => "x"
			);
			$data['listaDependencia'] = $this->general_model->get_basic_search($arrParam);

			$data["view"] = "dashboard";
			$this->load->view("layout_calendar", $data);
	}

	/**
	 * ACTIVIDADES
	 * @since 15/04/2022
	 */
	public function actividades($idCuadroBase, $numeroActividad = 'x', $numeroTrimestre = 'x')
	{				
			$data['numeroActividad'] = $numeroActividad;
			$data['idCuadroBase'] = $idCuadroBase;
			$data['numeroTrimestre'] = false;
			$data['infoEjecucion'] = false;
			$arrParam = array("idCuadroBase" => $idCuadroBase);
			$data['infoCuadroBase'] = $this->general_model->get_lista_cuadro_mando($arrParam);
			$data['infoDependencias'] = $this->general_model->get_dependencias($arrParam);
			$data['listaActividades'] = $this->general_model->get_actividades($arrParam);

			$arrParam = array("numeroEstrategia" => $data['infoCuadroBase'][0]['fk_numero_estrategia']);
			$data['listaEstrategias'] = $this->general_model->get_estrategias($arrParam);

			if($numeroActividad != 'x') {
				$arrParam = array("numeroActividad" => $numeroActividad);
				$data['listaActividades'] = $this->general_model->get_actividades($arrParam);
				if($numeroTrimestre != 'x') {
					$data['numeroTrimestre'] = $numeroTrimestre;
					$arrParam['numeroTrimestre'] = $numeroTrimestre;
					$data['listaHistorial'] = $this->general_model->get_historial_actividad($arrParam);
				}
				$data['infoEjecucion'] = $this->general_model->get_ejecucion_actividades($arrParam);
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

			$arrParam = array(
				"table" => " param_area_responsable",
				"order" => "area_responsable",
				"id" => "x"
			);
			$data['listaAreaResponsable'] = $this->general_model->get_basic_search($arrParam);

			$arrParam = array(
				"table" => "param_proceso_calidad",
				"order" => "proceso_calidad",
				"id" => "x"
			);
			$data['proceso_calidad'] = $this->general_model->get_basic_search($arrParam);

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


			$arrParam = array("idCuadroBase" => $data["idCuadrobase"]);
			$infoCuadroBase = $this->general_model->get_lista_cuadro_mando($arrParam);

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
			$numeroActividad = $this->input->post('numero_actividad');
		
			$msj = "Se guardo la información!";

			if ($nuevaActividad = $this->dashboard_model->guardarActividad()) 
			{	
				if ($idActividad == ''){
					$this->dashboard_model->save_programa_actividad($nuevaActividad);//generar los programas
					//generar REGISTRO DE ESTADO ACTIVIDAD
					$banderaActividad = false;
					$estadoActividad = 0;
					$this->dashboard_model->guardarTrimestre($banderaActividad, $estadoActividad, $numeroActividad, '', 0, 1);
					//guardar el historial de los 4 trimestres
					for($i=1;$i<5;$i++) {
						$arrParam = array(
							"numeroActividad" => $numeroActividad,
							"numeroTrimestre" => $i,
							"observacion" => 'Registro de la actividad',
							"estado" => 0
						);

						//actualizo el estado del trimestre de la actividad
						$this->dashboard_model->addHistorialActividad($arrParam);
					}
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

			//validar si ya exite programacion para el mes enviado
			$validarMes = false;

			$arrParam = array(
				'idActividad' => $idActividad,
				'idMes' => $this->input->post('mes')
			);
			$validarMes = $this->general_model->get_ejecucion_actividades($arrParam);

			if($validarMes){
					$data["result"] = "error";
					$data["mensaje"] = " Error. Este mes ya se encuentra dentro de la programación.";
					$this->session->set_flashdata('retornoError', 'Este mes ya se encuentra dentro de la programación');
			}else{
				if ($this->dashboard_model->guardarProgramado()) 
				{				
					$data["result"] = true;		
					$this->session->set_flashdata('retornoExito', '<strong>Correcto!</strong> ' . $msj);
				} else {
					$data["result"] = "error";
					$this->session->set_flashdata('retornoError', '<strong>Error!</strong> Ask for help');
				}
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
			$numeroActividad = $this->input->post('hddnumeroActividad');
			$idCuadroBase = $this->input->post('hddIdCuadroBase');
			$mes = $this->input->post('hddMes');

			if ($this->dashboard_model->guardarEjecucion())
			{
				//actualizo el estado del trimestre de la actividad
				$arrParam = array(
					"numeroActividad" => $numeroActividad,
					"numeroTrimestre" => $this->input->post('hddNumeroTrimestre'),
					"observacion" => 'Se realizó registro de información para el mes de ' . $mes . '.',
					"estado" => 1
				);
				$this->dashboard_model->addHistorialActividad($arrParam);

				//actualizo el estado del trimestre de la actividad
				$this->dashboard_model->updateEstadoActividad($arrParam);

				$data["result"] = true;
				$this->session->set_flashdata('retornoExito', "Se actualizó la información!!");
			} else {
				$data["result"] = "error";
				$this->session->set_flashdata('retornoError', '<strong>Error!!!</strong> Ask for help');
			}

			redirect(base_url('dashboard/actividades/' . $idCuadroBase . '/' . $numeroActividad), 'refresh');
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
	public function update_trimestre()
	{
		header('Content-Type: application/json');

		$data["idCuadroBase"] = $this->input->post('idCuadroBase');
		$data["numeroActividad"] = $numeroActividad = $this->input->post('numeroActividad');
		$cumplimientoTrimestre = $this->input->post('cumplimientoTrimestre');
		$avancePOA = $this->input->post('avancePOA');
		$numeroTrimestre = $this->input->post('numeroTrimestre');

		$banderaActividad = true;
		$estadoActividad = 2;
		if ($this->dashboard_model->guardarTrimestre($banderaActividad, $estadoActividad, $numeroActividad, $cumplimientoTrimestre, $avancePOA, $numeroTrimestre)){

			$arrParam = array(
				"numeroActividad" => $numeroActividad,
				"numeroTrimestre" => $numeroTrimestre,
				"observacion" => 'Se cerro el trimestre por parte del supervisor.',
				"estado" => 2
			);
			$this->dashboard_model->addHistorialActividad($arrParam);

			$data["result"] = true;
			$data["msj"] = "Se cerro el trimestre.";
		} else {
			$data["result"] = true;
		}
		echo json_encode($data);
    }

	/**
	 * SUPERVISOR DASHBOARD
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
					$valor = $valor . $filtroEstrategias[$i]['id_estrategia'];
					if($i != ($tot-1)){
						$valor .= ",";
					}
				}
			}
			$data['listaEstrategias'] = false;
			if($valor){
				$arrParam = array("filtroEstrategias" => $valor);
				$data['listaEstrategias'] = $this->general_model->get_estrategias($arrParam);
			}

			$data["view"] = "dashboard_supervisor";	
			$this->load->view("layout_calendar", $data);
	}

	/**
	 * PLANEACION DASHBOARD
	 * @since 23/04/2022
	 */
	public function seguimiento()
	{				
			$arrParam = array();
			$data['listaEstrategias'] = $this->general_model->get_estrategias($arrParam);

			$arrParam = array(
				"table" => "objetivos_estrategicos",
				"order" => "objetivo_estrategico",
				"id" => "x"
			);
			$data['listaObjetivos'] = $this->general_model->get_basic_search($arrParam);

			$arrParam = array(
				"table" => "param_dependencias",
				"order" => "dependencia",
				"id" => "x"
			);
			$data['listaDependencia'] = $this->general_model->get_basic_search($arrParam);
			$data["view"] = "dashboard";
			$this->load->view("layout_calendar", $data);
	}

	/**
	 * Save estado de la actividad
     * @since 24/04/2022
     * @author BMOTTAG
	 */
	public function save_estado_actividad()
	{			
			header('Content-Type: application/json');
			$data = array();
			$data["idCuadroBase"] = $this->input->post('hddIdCuadroBase');
			$data["idActividad"] = $this->input->post('hddIdActividad');
			$data["numeroTrimestre"] = $this->input->post('hddNumeroTrimestre');
			$data["record"] = $data["idCuadroBase"] . '/' . $data["idActividad"] . '/' . $data["numeroTrimestre"];
			$msj = "Se cambio el estado del trimestre de la actividad.";
			
			$arrParam = array(
				"idActividad" => $data["idActividad"],
				"numeroTrimestre" => $data["numeroTrimestre"],
				"observacion" => $this->input->post('observacion'),
				"estado" => $this->input->post('estado')
			);

			if ($this->dashboard_model->addHistorialActividad($arrParam)) 
			{
				//actualizo el estado del trimestre de la actividad
				$this->dashboard_model->updateEstadoActividad($arrParam);
				
				$data["result"] = true;
				$data["mensaje"] = $msj;
				$this->session->set_flashdata('retornoExito', $msj);
			} else {
				$data["result"] = "error";
				$data["mensaje"] = "Error!!! Ask for help.";
				$this->session->set_flashdata('retornoError', '<strong>Error!!!</strong> Ask for help');
			}

			echo json_encode($data);
    }

    /**
     * Eliminar actividad 
     * @since 16/05/2022
     * @author BMOTTAG
     */
	public function delete_actividad()
	{
		header('Content-Type: application/json');

		$data["idActividad"] = $this->input->post('idActividad');

		$arrParam = array("idActividad" => $data["idActividad"]);
		$infoActividad = $this->general_model->get_actividades($arrParam);
		$data["idCuadrobase"] = $infoActividad[0]['fk_id_cuadro_base'];

		$arrParam = array(
			"table" => "actividad_ejecucion",
			"primaryKey" => "fk_id_actividad",
			"id" => $data["idActividad"]
		);
		$this->general_model->deleteRecord($arrParam);

		$arrParam = array(
			"table" => "actividad_estado",
			"primaryKey" => "fk_id_actividad",
			"id" => $data["idActividad"]
		);
		$this->general_model->deleteRecord($arrParam);

		$arrParam = array(
			"table" => " actividad_historial",
			"primaryKey" => "fk_id_actividad",
			"id" => $data["idActividad"]
		);
		$this->general_model->deleteRecord($arrParam);

		$arrParam = array(
			"table" => " actividades",
			"primaryKey" => "id_actividad",
			"id" => $data["idActividad"]
		);
		if ($this->general_model->deleteRecord($arrParam)) {
			$data["result"] = true;
			$data["msj"] = "Se eliminó la actividad.";
		} else {
			$data["result"] = true;
		}
		echo json_encode($data);
    }





	
	
}