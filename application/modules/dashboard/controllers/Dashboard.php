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
				"filtro" => true
			);
			$data['listaDependencia'] = $this->general_model->get_app_dependencias($arrParam);

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
			$data['listaHistorial'] = false;

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
			if($userRol == ID_ROL_ENLACE){
				$data["view"] = "actividades_enlace";
			}elseif($userRol == ID_ROL_SUPERVISOR){
				$data["view"] = "actividades_supervisor";
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
				"observacion" => 'Se cerro el trimestre por parte del ENLACE.',
				"estado" => 2
			);
			$this->dashboard_model->addHistorialActividad($arrParam);

			//INICIO
			//SE BUSCA EL SUPERVISOR DE LA DEPENDENCIA Y SE ENVIA CORREO
            $arrParam2 = array(
                "numeroActividad" => $numeroActividad,
                "idRol" => ID_ROL_SUPERVISOR
            );
            $listaSupervisores = $this->general_model->get_user_encargado_by_actividad($arrParam2);

            if($listaSupervisores){
            	foreach ($listaSupervisores as $infoSupervisor):
					$arrParam = array(
						"mensaje" => 'la actividad No. ' . $numeroActividad . ' fue cerrada por el ENLACE para el Trimeste '. $numeroTrimestre .', por favor ingresar a la plataforma y revisar la información.',
						"idSupervisor" => $infoSupervisor["id_user"]
					);
					//$this->send_email($arrParam);
				endforeach;
            }
            //FIN

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
			$userRol = $this->session->userdata("role");
			$idUser = $this->session->userdata("id");
			$idDependencia = $this->session->userdata("dependencia");

			$arrParam = array(
				"table" => "objetivos_estrategicos",
				"order" => "objetivo_estrategico",
				"id" => "x"
			);
			$data['listaObjetivos'] = $this->general_model->get_basic_search($arrParam);

			$arrParam = array(
				"filtro" => true
			);
			$data['listaDependencia'] = $this->general_model->get_app_dependencias($arrParam);

			$arrParam = array(
				"idDependencia" => $idDependencia,
				"vigencia" => date("Y")
			);
			$filtroEstrategias = $this->general_model->get_estrategias_by_dependencia($arrParam);

			$data['nroActividadesDependencia'] = $this->dashboard_model->countActividades($arrParam);
			$data['avance'] = $this->dashboard_model->sumAvance($arrParam);

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

			$arrParam = array(
				"table" => "param_dependencias",
				"order" => "dependencia",
				"column" => "id_dependencia",
				"id" => $idDependencia
			);
			$data['infoDependencia'] = $this->general_model->get_basic_search($arrParam);

			$data["view"] = "dashboard_principal";
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
				"filtro" => true
			);
			$data['listaDependencia'] = $this->general_model->get_app_dependencias($arrParam);
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
			$data["numeroActividad"] = $numeroActividad = $this->input->post('hddNumeroActividad');
			$data["numeroTrimestre"] = $this->input->post('hddNumeroTrimestre');
			$observacion = $this->input->post('observacion');
			$idEstado = $this->input->post('estado');
			$data["record"] = $data["idCuadroBase"] . '/' . $data["numeroActividad"] . '/' . $data["numeroTrimestre"];
			$msj = "Se cambio el estado del trimestre de la actividad.";
			
			$arrParam = array(
				"numeroActividad" => $data["numeroActividad"],
				"numeroTrimestre" => $data["numeroTrimestre"],
				"observacion" => $observacion,
				"estado" => $idEstado
			);

			if($this->dashboard_model->addHistorialActividad($arrParam)) 
			{
				//actualizo el estado del trimestre de la actividad
				if($this->dashboard_model->updateEstadoActividad($arrParam)){
					//envio correos a los usuarios
					if($idEstado == 3){
						$mensaje = "se revisó la información registrada para la actividad No. " . $data["numeroActividad"]  . ", para el Trimestre " . $data["numeroTrimestre"] . ", fue APROBADA y se escalo al Área de Planeación para realizar el respectivo seguimiento.";
					}elseif($idEstado == 4){
						$mensaje = "se revisó la información registrada para la actividad No. " . $data["numeroActividad"]  . ", para el Trimestre " . $data["numeroTrimestre"] . ", fue RECHAZADA. Por favor ingresar y realizar los ajustes respectivos.";
					}elseif($idEstado == 5){
						$mensaje = "se realizó seguimiento a la información registrada para la actividad No. " . $data["numeroActividad"]  . ", para el Trimestre " . $data["numeroTrimestre"] . "y fue APROBADA por Planeación.";
					}elseif($idEstado == 6){
						$mensaje = "se realizó seguimiento a la información registrada para la actividad No. " . $data["numeroActividad"]  . ", para el Trimestre " . $data["numeroTrimestre"] . "y fue RECHAZADA por Planeación. Por favor ingresar y realizar los ajustes respectivos.";
					}

					$mensaje .= "<br><b>Observación: </b>" . $observacion;

					//INICIO
					//SE BUSCA EL ENLACE DE LA DEPENDENCIA Y SE ENVIA CORREO
		            $arrParam2 = array(
		                "numeroActividad" => $numeroActividad,
		                "idRol" => ID_ROL_ENLACE
		            );
		            $listaUsuarios = $this->general_model->get_user_encargado_by_actividad($arrParam2);

		            if($listaUsuarios){
		            	foreach ($listaUsuarios as $infoUsuario):
							$arrParam = array(
								"mensaje" => $mensaje,
								"idUsuario" => $infoUsuario["id_user"]
							);
							//$this->send_email($arrParam);
						endforeach;
		            }
		            //FIN
				}
				
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

	/**
	 * INFO DEPENDENCIAS
	 * @since 09/06/2022
	 */
	public function dependencias($idDependencia)
	{				
			$arrParam = array(
				"idDependencia" => $idDependencia,
				"vigencia" => date("Y")
			);
			$filtroEstrategias = $this->general_model->get_estrategias_by_dependencia($arrParam);


			$data['nroActividadesDependencia'] = $this->dashboard_model->countActividades($arrParam);
			$data['avance'] = $this->dashboard_model->sumAvance($arrParam);

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

			$arrParam = array(
				"table" => "param_dependencias",
				"order" => "dependencia",
				"column" => "id_dependencia",
				"id" => $idDependencia
			);
			$data['infoDependencia'] = $this->general_model->get_basic_search($arrParam);

			$data["view"] = "info_dependencias";
			$this->load->view("layout_calendar", $data);
	}

	/**
	 * EJECUCION DASHBOARD
	 * @since 9/06/2022
	 */
	public function enlace()
	{		
			$userRol = $this->session->userdata("role");
			$idUser = $this->session->userdata("id");
			$idDependencia = $this->session->userdata("dependencia");

			$arrParam = array(
				"table" => "objetivos_estrategicos",
				"order" => "objetivo_estrategico",
				"id" => "x"
			);
			$data['listaObjetivos'] = $this->general_model->get_basic_search($arrParam);

			$arrParam = array(
				"filtro" => true
			);
			$data['listaDependencia'] = $this->general_model->get_app_dependencias($arrParam);

			$arrParam = array(
				"idDependencia" => $idDependencia,
				"vigencia" => date("Y")
			);
			$filtroEstrategias = $this->general_model->get_estrategias_by_dependencia($arrParam);

			$data['nroActividadesDependencia'] = $this->dashboard_model->countActividades($arrParam);

			$data['avance'] = $this->dashboard_model->sumAvance($arrParam);

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

			$arrParam = array(
				"table" => "param_dependencias",
				"order" => "dependencia",
				"column" => "id_dependencia",
				"id" => $idDependencia
			);
			$data['infoDependencia'] = $this->general_model->get_basic_search($arrParam);

			$data["view"] = "dashboard_principal";
			$this->load->view("layout_calendar", $data);
	}

	/**
	 * PLANEACION DASHBOARD
	 * @since 23/04/2022
	 */
	public function planeacion()
	{				
			$arrParam = array(
				"table" => "objetivos_estrategicos",
				"order" => "objetivo_estrategico",
				"id" => "x"
			);
			$data['listaObjetivos'] = $this->general_model->get_basic_search($arrParam);

			$arrParam = array(
				"filtro" => true
			);
			$data['listaDependencia'] = $this->general_model->get_app_dependencias($arrParam);

			$arrParam = array(
				"vigencia" => date("Y")
			);
			$filtroEstrategias = $this->general_model->get_estrategias_by_dependencia($arrParam);

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
			$data["view"] = "dashboard_principal";
			$this->load->view("layout_calendar", $data);
	}

	/**
	 * Evio de correo
     * @since 11/6/2022
     * @author BMOTTAG
	 */
	public function send_email($arrData)
	{
			$arrParam = array('idUser' => $arrData["idUsuario"]);
			$infoUsuario = $this->general_model->get_user($arrParam);
			$to = $infoUsuario[0]['email'];

			//busco datos parametricos de configuracion para envio de correo
			$arrParam2 = array(
				"table" => "parametros",
				"order" => "id_parametro",
				"id" => "x"
			);
			$parametric = $this->general_model->get_basic_search($arrParam2);

			$paramHost = $parametric[0]["parametro_valor"];
			$paramUsername = $parametric[1]["parametro_valor"];
			$paramPassword = $parametric[2]["parametro_valor"];
			$paramFromName = $parametric[3]["parametro_valor"];
			$paramCompanyName = $parametric[4]["parametro_valor"];
			$paramAPPName = $parametric[5]["parametro_valor"];

			//mensaje del correo
			$msj = 'Sr.(a) ' . $infoUsuario[0]['first_name'] . ', ';
			$msj .= $arrData["mensaje"] . '</br>';
			$msj .= '<strong>Enlace: </strong>' . base_url();
									
			$mensaje = "<p>$msj</p>
						<p>Cordialmente,</p>
						<p><strong>$paramCompanyName</strong></p>";		

			require_once(APPPATH.'libraries/PHPMailer/class.phpmailer.php');
            $mail = new PHPMailer(true);

            $mail->IsSMTP(); // set mailer to use SMTP
            $mail->Host = $paramHost; // specif smtp server
            $mail->SMTPSecure= "tls"; // Used instead of TLS when only POP mail is selected
            $mail->Port = 587; // Used instead of 587 when only POP mail is selected
            $mail->SMTPAuth = true;
			$mail->Username = $paramUsername; // SMTP username
            $mail->Password = $paramPassword; // SMTP password
            $mail->FromName = $paramFromName;
            $mail->From = $paramUsername;
            $mail->AddAddress($to, 'Usuario JBB Bienes');
            $mail->WordWrap = 50;
            $mail->CharSet = 'UTF-8';
            $mail->IsHTML(true); // set email format to HTML
            $mail->Subject = $paramCompanyName . ' - ' . $paramAPPName;
            $mail->Body = nl2br ($mensaje,false);
            $mail->Send();
			return true;
	}





	
	
}