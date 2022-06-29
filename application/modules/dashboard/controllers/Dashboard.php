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
            if($_POST && $_POST["numero_objetivo"] != ""){
                $arrParam["numeroObjetivoEstrategico"] = $this->input->post('numero_objetivo');
            }
			$data['listaObjetivosEstrategicos'] = $this->general_model->get_objetivos_estrategicos($arrParam);

			$arrParam = array();
			$data['listaNumeroObjetivoEstrategicos'] = $this->general_model->get_objetivos_estrategicos($arrParam);

	        $arrParam = array();
	        if($_POST && $_POST["numero_objetivo"] != ""){
	            $arrParam = array(
	                "numeroObjetivoEstrategico" => $_POST["numero_objetivo"]
	            );  
	        }
	        $data['listaProyectos'] = $this->general_model->get_numero_proyectos_full_by_dependencia($arrParam);

			$arrParam = array(
				"table" => "estrategias",
				"order" => "estrategia",
				"id" => "x"
			);
			$data['listaEstretegias'] = $this->general_model->get_basic_search($arrParam);

			$arrParam = array(
				"filtro" => true
			);
			$data['listaDependencia'] = $this->general_model->get_app_dependencias($arrParam);

			$arrParam = array(
				"filtro" => true
			);
	        if($_POST && $_POST["numero_objetivo"] != ""){
	            $arrParam = array(
	                "numeroObjetivoEstrategico" => $_POST["numero_objetivo"]
	            );  
	        }
            if($_POST && $_POST["numero_proyecto"] != ""){
                $arrParam["numeroProyecto"] = $_POST["numero_proyecto"];
            }
			$data['listaNumeroDependencia'] = $this->general_model->get_dependencia_full_by_filtro($arrParam);

			//$data["view"] = "dashboard";
			$data["view"] = "dashboard_principal";
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
			$data['listaActividades'] = $this->general_model->get_actividades($arrParam);
			$data['listaHistorial'] = false;

			$arrParam = array("numeroObjetivoEstrategico" => $data['infoCuadroBase'][0]['fk_numero_objetivo_estrategico']);
			$data['listaObjetivosEstrategicos'] = $this->general_model->get_objetivos_estrategicos($arrParam);

			if($numeroActividad != 'x') {
				$arrParam = array("numeroActividad" => $numeroActividad);
				$data['listaActividades'] = $this->general_model->get_actividades($arrParam);
				if($numeroTrimestre != 'x') {
					$data['numeroTrimestre'] = $numeroTrimestre;
					$arrParam['numeroTrimestre'] = $numeroTrimestre;
				}
				$data['listaHistorial'] = $this->general_model->get_historial_actividad($arrParam);
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
				"filtro" => true
			);
			$data['listaDependencia'] = $this->general_model->get_app_dependencias($arrParam);

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
			$numeroActividad = $this->input->post('numero_actividad');
			$data["idRecord"] = $this->input->post('hddIdCuadroBase') . "/" . $numeroActividad ;
		
			$msj = "Se guardo la información!";

			if ($this->dashboard_model->guardarActividad()) 
			{	
				if ($idActividad == ''){
					$this->dashboard_model->save_programa_actividad($numeroActividad);//generar los programas
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
						$this->general_model->addHistorialActividad($arrParam);
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
			$numeroActividad = $this->input->post('hddNumeroActividad');
			
			$data["idRecord"] = $idCuadroBase . "/" . $numeroActividad ;
		
			$msj = "Se guardo la información!";

			//validar si ya exite programacion para el mes enviado
			$validarMes = false;

			$arrParam = array(
				'numeroActividad' => $numeroActividad,
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
     * @since 18/06/2022
     * @author BMOTTAG
	 */
	public function update_ejecucion()
	{					
			$numeroActividad = $this->input->post('hddNumeroActividad');
			$idCuadroBase = $this->input->post('hddIdCuadroBase');

			if ($this->dashboard_model->guardarEjecucion()) {

				//actualizo el estado del trimestre de la actividad
				$arrParam = array(
					"numeroActividad" => $numeroActividad,
					"numeroTrimestre" => $this->input->post('hddNumeroTrimestre'),
					"observacion" => $this->input->post('observacion'),
					"estado" => 1
				);
				$this->general_model->addHistorialActividad($arrParam);

				//actualizo el estado del trimestre de la actividad
				$this->general_model->updateEstadoActividad($arrParam);

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
			$this->general_model->addHistorialActividad($arrParam);

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
				"table" => "estrategias",
				"order" => "estrategia",
				"id" => "x"
			);
			$data['listaEstretegias'] = $this->general_model->get_basic_search($arrParam);

			$arrParam = array(
				"filtro" => true
			);
			$data['listaDependencia'] = $this->general_model->get_app_dependencias($arrParam);

			$arrParam = array(
				"idDependencia" => $idDependencia,
				"vigencia" => date("Y")
			);
			$filtroObjetivosEstrategicos = $this->general_model->get_objetivos_estrategicos_by_dependencia($arrParam);

			$data['nroActividadesDependencia'] = $this->general_model->countActividades($arrParam);
			$data['avanceEspecifico'] = $this->general_model->sumAvance($arrParam);

			$valor = '';

			if($filtroObjetivosEstrategicos){
				$tot = count($filtroObjetivosEstrategicos);
				for ($i = 0; $i < $tot; $i++) {
					$valor = $valor . $filtroObjetivosEstrategicos[$i]['id_objetivo_estrategico'];
					if($i != ($tot-1)){
						$valor .= ",";
					}
				}
			}
			$data['listaObjetivosEstrategicos'] = false;
			if($valor){
				$arrParam = array("filtroEstrategias" => $valor);
		        if($_POST && $_POST["numero_objetivo"] != ""){
		            $arrParam = array(
		                "numeroObjetivoEstrategico" => $_POST["numero_objetivo"]
		            );  
		        }
				$data['listaObjetivosEstrategicos'] = $this->general_model->get_objetivos_estrategicos($arrParam);
			}

			//INICIO LISTAS PARA FILTROS
			$arrParam = array("filtroEstrategias" => $valor);
			$data['listaNumeroObjetivoEstrategicos'] = $this->general_model->get_objetivos_estrategicos($arrParam);

	        $arrParam = array();
	        if($_POST && $_POST["numero_objetivo"] != ""){
	            $arrParam = array(
	                "numeroObjetivoEstrategico" => $_POST["numero_objetivo"]
	            );  
	        }
	        $arrParam["idDependencia"] = $idDependencia;
	        $data['listaProyectos'] = $this->general_model->get_numero_proyectos_full_by_dependencia($arrParam);
            if($_POST && $_POST["numero_proyecto"] != ""){
                $arrParam["numeroProyecto"] = $_POST["numero_proyecto"];
            }
			$data['listaNumeroDependencia'] = $this->general_model->get_dependencia_full_by_filtro($arrParam);
	        //FIN LISTAS PARA FILTROS

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
	 * Save estado de la actividad
     * @since 24/04/2022
     * @author BMOTTAG
	 */
	public function save_estado_actividad()
	{			
			header('Content-Type: application/json');
			$data = array();
			$data["idCuadroBase"] = $this->input->post('hddIdCuadroBase');
			$numeroActividad = $this->input->post('hddNumeroActividad');
			$numeroTrimestre = $this->input->post('hddNumeroTrimestre');
			$observacion = $this->input->post('observacion');
			$idEstado = $this->input->post('estado');
			$data["record"] = $data["idCuadroBase"] . '/' . $numeroActividad . '/' . $numeroTrimestre;
			$msj = "Se cambio el estado de la actividad para el <b>Trimestre " . $numeroTrimestre .  "</b>.";
			
			$arrParam = array("numeroActividad" => $numeroActividad);
			$listadoActividades = $this->general_model->get_actividades($arrParam);

			$cumplimientoX = 0;
			$avancePOA = 0;
			$cumplimientoActual = 0;
			$ponderacion = $listadoActividades[0]['ponderacion'];
			//INICIO --- DEBO TENER EN CUENTA EL TRIMESTRE DE LOS DEMAS QUE ESTAN EN 5
			$estadoActividad = $this->general_model->get_estados_actividades($arrParam);

			$estadoTrimestre1 = $estadoActividad[0]["estado_trimestre_1"];
			$estadoTrimestre2 = $estadoActividad[0]["estado_trimestre_2"];
			$estadoTrimestre3 = $estadoActividad[0]["estado_trimestre_3"];
			$estadoTrimestre4 = $estadoActividad[0]["estado_trimestre_4"];

			$incluirTrimestre = 0;
			if(($numeroTrimestre != 1 && $estadoTrimestre1 == 5) || ($numeroTrimestre == 1 && $idEstado == 5) ){
				$incluirTrimestre = $incluirTrimestre . "," . 1;
			}
			if(($numeroTrimestre != 2 && $estadoTrimestre2 == 5) || ($numeroTrimestre == 2 && $idEstado == 5) ){
				$incluirTrimestre = $incluirTrimestre . "," . 2;
			}
			if(($numeroTrimestre != 3 && $estadoTrimestre3 == 5) || ($numeroTrimestre == 3 && $idEstado == 5) ){
				$incluirTrimestre = $incluirTrimestre . "," . 3;
			}
			if(($numeroTrimestre != 4 && $estadoTrimestre4 == 5) || ($numeroTrimestre == 4 && $idEstado == 5) ){
				$incluirTrimestre = $incluirTrimestre . "," . 4;
			}
			$arrParam = array(
				"numeroActividad" => $numeroActividad,
				"filtroTrimestre" => $incluirTrimestre
			);
			$sumaEjecutado = $this->general_model->sumarEjecutado($arrParam);	
			//FIN --- DEBO TENER EN CUENTA EL TRIMESTRE DE LOS DEMAS QUE ESTAN EN 5

			$sumaProgramado = $this->general_model->sumarProgramado($arrParam);
			if($sumaProgramado['programado'] > 0 && $sumaEjecutado){
				$avancePOA = round(($sumaEjecutado['ejecutado']/$sumaProgramado['programado']) * $ponderacion,2);
			}
			if($sumaProgramado['programado'] > 0 && $sumaEjecutado){
				$cumplimientoActual = round(($sumaEjecutado['ejecutado']/$sumaProgramado['programado']) * 100,2);
			}

			if($idEstado == 5){
				$arrParam = array(
					"numeroActividad" => $numeroActividad,
					"numeroTrimestre" => $numeroTrimestre
				);
				$sumaProgramadoTrimestreX = $this->general_model->sumarProgramado($arrParam);
				$sumaEjecutadoTrimestreX = $this->general_model->sumarEjecutado($arrParam);

				if($sumaProgramadoTrimestreX['programado'] > 0){
					$cumplimientoX = round($sumaEjecutadoTrimestreX['ejecutado'] / $sumaProgramadoTrimestreX['programado'] * 100, 2);
				}
			}

			$arrParam = array(
				"numeroActividad" => $numeroActividad,
				"numeroTrimestre" => $numeroTrimestre,
				"observacion" => $observacion,
				"estado" => $idEstado,
				"cumplimientoX" => $cumplimientoX,
				"avancePOA" => $avancePOA,
				"cumplimientoActual" => $cumplimientoActual
			);
			if($this->general_model->addHistorialActividad($arrParam)) 
			{
				//actualizo el estado del trimestre de la actividad
				if($this->general_model->updateEstadoActividadTotales($arrParam)){
					//envio correos a los usuarios
					if($idEstado == 3){
						$mensaje = "se revisó la información registrada para la actividad No. " . $numeroActividad  . ", para el Trimestre " . $numeroTrimestre . ", fue APROBADA y se escalo al Área de Planeación para realizar el respectivo seguimiento.";
					}elseif($idEstado == 4){
						$mensaje = "se revisó la información registrada para la actividad No. " . $numeroActividad  . ", para el Trimestre " . $numeroTrimestre . ", fue RECHAZADA. Por favor ingresar y realizar los ajustes respectivos.";
					}elseif($idEstado == 5){
						$mensaje = "se realizó seguimiento a la información registrada para la actividad No. " . $numeroActividad  . ", para el Trimestre " . $numeroTrimestre. "y fue APROBADA por Planeación.";
					}elseif($idEstado == 6){
						$mensaje = "se realizó seguimiento a la información registrada para la actividad No. " . $numeroActividad  . ", para el Trimestre " . $numeroTrimestre . "y fue RECHAZADA por Planeación. Por favor ingresar y realizar los ajustes respectivos.";
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
		$numeroActividad = $infoActividad[0]['numero_actividad'];

		$arrParam = array(
			"table" => "actividad_ejecucion",
			"primaryKey" => "fk_numero_actividad",
			"id" => $numeroActividad
		);
		$this->general_model->deleteRecord($arrParam);

		$arrParam = array(
			"table" => "actividad_estado",
			"primaryKey" => "fk_numero_actividad",
			"id" => $numeroActividad
		);
		$this->general_model->deleteRecord($arrParam);

		$arrParam = array(
			"table" => " actividad_historial",
			"primaryKey" => "fk_numero_actividad",
			"id" => $numeroActividad
		);
		$this->general_model->deleteRecord($arrParam);

		$arrParam = array(
			"table" => " actividades",
			"primaryKey" => "id_actividad",
			"id" => $data["idActividad"]
		);
		if ($this->general_model->deleteRecord($arrParam)) {
			$data["result"] = true;
			$this->session->set_flashdata('retornoExito', '<strong>Correcto!</strong> Se eliminó la actividad');
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
			$filtroObjetivosEstrategicos = $this->general_model->get_objetivos_estrategicos_by_dependencia($arrParam);
			$data['nroActividadesDependencia'] = $this->general_model->countActividades($arrParam);
			$data['avance'] = $this->general_model->sumAvance($arrParam);
			
			$valor = '';

			if($filtroObjetivosEstrategicos){
				$tot = count($filtroObjetivosEstrategicos);
				for ($i = 0; $i < $tot; $i++) {
					$valor = $valor . $filtroObjetivosEstrategicos[$i]['id_objetivo_estrategico'];
					if($i != ($tot-1)){
						$valor .= ",";
					}
				}
			}
			$data['listaObjetivosEstrategicos'] = false;
			if($valor){
				$arrParam = array("filtroEstrategias" => $valor);
				$data['listaObjetivosEstrategicos'] = $this->general_model->get_objetivos_estrategicos($arrParam);
			}

			$arrParam = array(
				"table" => "param_dependencias",
				"order" => "dependencia",
				"column" => "id_dependencia",
				"id" => $idDependencia
			);
			$data['infoDependencia'] = $this->general_model->get_basic_search($arrParam);

			//NO INICIADA
			$arrParam2 = array(
				"numeroTrimestre" => 1,
				"estadoTrimestre" => 0,
				"vigencia" => date("Y"),
				"idDependencia" => $idDependencia
			);
			$data['nroActividadesPrimerTrimestreNoIniciada'] = $this->general_model->countActividadesEstado($arrParam2);

			$arrParam2["numeroTrimestre"] = 2;
			$data['nroActividadesSegundoTrimestreNoIniciada'] = $this->general_model->countActividadesEstado($arrParam2);
			$arrParam2["numeroTrimestre"] = 3;
			$data['nroActividadesTercerTrimestreNoIniciada'] = $this->general_model->countActividadesEstado($arrParam2);
			$arrParam2["numeroTrimestre"] = 4;
			$data['nroActividadesCuartoTrimestreNoIniciada'] = $this->general_model->countActividadesEstado($arrParam2);

			//EN PROCESO
			$arrParam2 = array(
				"numeroTrimestre" => 1,
				"estadoTrimestre" => 1,
				"vigencia" => date("Y"),
				"idDependencia" => $idDependencia
			);
			$data['nroActividadesPrimerTrimestreEnProceso'] = $this->general_model->countActividadesEstado($arrParam2);

			$arrParam2["numeroTrimestre"] = 2;
			$data['nroActividadesSegundoTrimestreEnProceso'] = $this->general_model->countActividadesEstado($arrParam2);
			$arrParam2["numeroTrimestre"] = 3;
			$data['nroActividadesTercerTrimestreEnProceso'] = $this->general_model->countActividadesEstado($arrParam2);
			$arrParam2["numeroTrimestre"] = 4;
			$data['nroActividadesCuartoTrimestreEnProceso'] = $this->general_model->countActividadesEstado($arrParam2);

			//CERRADA
			$arrParam2 = array(
				"numeroTrimestre" => 1,
				"estadoTrimestre" => 2,
				"vigencia" => date("Y"),
				"idDependencia" => $idDependencia
			);
			$data['nroActividadesPrimerTrimestreCerrado'] = $this->general_model->countActividadesEstado($arrParam2);

			$arrParam2["numeroTrimestre"] = 2;
			$data['nroActividadesSegundoTrimestreCerrado'] = $this->general_model->countActividadesEstado($arrParam2);
			$arrParam2["numeroTrimestre"] = 3;
			$data['nroActividadesTercerTrimestreCerrado'] = $this->general_model->countActividadesEstado($arrParam2);
			$arrParam2["numeroTrimestre"] = 4;
			$data['nroActividadesCuartoTrimestreCerrado'] = $this->general_model->countActividadesEstado($arrParam2);

			//APROBADA SUPERVISOR
			$arrParam2 = array(
				"numeroTrimestre" => 1,
				"estadoTrimestre" => 3,
				"vigencia" => date("Y"),
				"idDependencia" => $idDependencia
			);
			$data['nroActividadesPrimerTrimestreAprobadoSupervisor'] = $this->general_model->countActividadesEstado($arrParam2);

			$arrParam2["numeroTrimestre"] = 2;
			$data['nroActividadesSegundoTrimestreAprobadoSupervisor'] = $this->general_model->countActividadesEstado($arrParam2);
			$arrParam2["numeroTrimestre"] = 3;
			$data['nroActividadesTercerTrimestreAprobadoSupervisor'] = $this->general_model->countActividadesEstado($arrParam2);
			$arrParam2["numeroTrimestre"] = 4;
			$data['nroActividadesCuartoTrimestreAprobadoSupervisor'] = $this->general_model->countActividadesEstado($arrParam2);

			//RECHAZADA SUPERVISOR
			$arrParam2 = array(
				"numeroTrimestre" => 1,
				"estadoTrimestre" => 4,
				"vigencia" => date("Y"),
				"idDependencia" => $idDependencia
			);
			$data['nroActividadesPrimerTrimestreRechazadaSupervisor'] = $this->general_model->countActividadesEstado($arrParam2);

			$arrParam2["numeroTrimestre"] = 2;
			$data['nroActividadesSegundoTrimestreRechazadaSupervisor'] = $this->general_model->countActividadesEstado($arrParam2);
			$arrParam2["numeroTrimestre"] = 3;
			$data['nroActividadesTercerTrimestreRechazadaSupervisor'] = $this->general_model->countActividadesEstado($arrParam2);
			$arrParam2["numeroTrimestre"] = 4;
			$data['nroActividadesCuartoTrimestreRechazadaSupervisor'] = $this->general_model->countActividadesEstado($arrParam2);

			//APROBADA PLANEACION
			$arrParam2 = array(
				"numeroTrimestre" => 1,
				"estadoTrimestre" => 5,
				"vigencia" => date("Y"),
				"idDependencia" => $idDependencia
			);
			$data['nroActividadesPrimerTrimestreAprobadaPlaneacion'] = $this->general_model->countActividadesEstado($arrParam2);

			$arrParam2["numeroTrimestre"] = 2;
			$data['nroActividadesSegundoTrimestreAprobadaPlaneacion'] = $this->general_model->countActividadesEstado($arrParam2);
			$arrParam2["numeroTrimestre"] = 3;
			$data['nroActividadesTercerTrimestreAprobadaPlaneacion'] = $this->general_model->countActividadesEstado($arrParam2);
			$arrParam2["numeroTrimestre"] = 4;
			$data['nroActividadesCuartoTrimestreAprobadaPlaneacion'] = $this->general_model->countActividadesEstado($arrParam2);

			//RECHAZADA PLANEACIOON
			$arrParam2 = array(
				"numeroTrimestre" => 1,
				"estadoTrimestre" => 6,
				"vigencia" => date("Y"),
				"idDependencia" => $idDependencia
			);
			$data['nroActividadesPrimerTrimestreRechazadaPlaneacion'] = $this->general_model->countActividadesEstado($arrParam2);

			$arrParam2["numeroTrimestre"] = 2;
			$data['nroActividadesSegundoTrimestreRechazadaPlaneacion'] = $this->general_model->countActividadesEstado($arrParam2);
			$arrParam2["numeroTrimestre"] = 3;
			$data['nroActividadesTercerTrimestreRechazadaPlaneacion'] = $this->general_model->countActividadesEstado($arrParam2);
			$arrParam2["numeroTrimestre"] = 4;
			$data['nroActividadesCuartoTrimestreRechazadaPlaneacion'] = $this->general_model->countActividadesEstado($arrParam2);

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
				"table" => "estrategias",
				"order" => "estrategia",
				"id" => "x"
			);
			$data['listaEstretegias'] = $this->general_model->get_basic_search($arrParam);

			$arrParam = array(
				"filtro" => true
			);
			$data['listaDependencia'] = $this->general_model->get_app_dependencias($arrParam);

			$arrParam = array(
				"idDependencia" => $idDependencia,
				"vigencia" => date("Y")
			);
			$filtroObjetivosEstrategicos = $this->general_model->get_objetivos_estrategicos_by_dependencia($arrParam);

			$data['nroActividadesDependencia'] = $this->general_model->countActividades($arrParam);

			$data['avanceEspecifico']  = $this->general_model->sumAvance($arrParam);

			$valor = '';

			if($filtroObjetivosEstrategicos){
				$tot = count($filtroObjetivosEstrategicos);
				for ($i = 0; $i < $tot; $i++) {
					$valor = $valor . $filtroObjetivosEstrategicos[$i]['id_objetivo_estrategico'];
					if($i != ($tot-1)){
						$valor .= ",";
					}
				}
			}
			$data['listaObjetivosEstrategicos'] = false;
			if($valor){
				$arrParam = array("filtroEstrategias" => $valor);
		        if($_POST && $_POST["numero_objetivo"] != ""){
		            $arrParam = array(
		                "numeroObjetivoEstrategico" => $_POST["numero_objetivo"]
		            );  
		        }
				$data['listaObjetivosEstrategicos'] = $this->general_model->get_objetivos_estrategicos($arrParam);
			}

			//INICIO LISTAS PARA FILTROS
			$arrParam = array("filtroEstrategias" => $valor);
			$data['listaNumeroObjetivoEstrategicos'] = $this->general_model->get_objetivos_estrategicos($arrParam);

	        $arrParam = array();
	        if($_POST && $_POST["numero_objetivo"] != ""){
	            $arrParam = array(
	                "numeroObjetivoEstrategico" => $_POST["numero_objetivo"]
	            );  
	        }
	        $arrParam["idDependencia"] = $idDependencia;
	        $data['listaProyectos'] = $this->general_model->get_numero_proyectos_full_by_dependencia($arrParam);
            if($_POST && $_POST["numero_proyecto"] != ""){
                $arrParam["numeroProyecto"] = $_POST["numero_proyecto"];
            }
			$data['listaNumeroDependencia'] = $this->general_model->get_dependencia_full_by_filtro($arrParam);
	        //FIN LISTAS PARA FILTROS

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
				"table" => "estrategias",
				"order" => "estrategia",
				"id" => "x"
			);
			$data['listaEstretegias'] = $this->general_model->get_basic_search($arrParam);

			$arrParam = array(
				"filtro" => true
			);
			$data['listaDependencia'] = $this->general_model->get_app_dependencias($arrParam);

			$arrParam = array(
				"vigencia" => date("Y")
			);
			$filtroObjetivosEstrategicos = $this->general_model->get_objetivos_estrategicos_by_dependencia($arrParam);

			$valor = '';
			if($filtroObjetivosEstrategicos){
				$tot = count($filtroObjetivosEstrategicos);
				for ($i = 0; $i < $tot; $i++) {
					$valor = $valor . $filtroObjetivosEstrategicos[$i]['id_objetivo_estrategico'];
					if($i != ($tot-1)){
						$valor .= ",";
					}
				}
			}
			$data['listaObjetivosEstrategicos'] = false;
			if($valor){
				$arrParam = array("filtroEstrategias" => $valor);
		        if($_POST && $_POST["numero_objetivo"] != ""){
		            $arrParam = array(
		                "numeroObjetivoEstrategico" => $_POST["numero_objetivo"]
		            );  
		        }
				$data['listaObjetivosEstrategicos'] = $this->general_model->get_objetivos_estrategicos($arrParam);
			}

			//INICIO LISTAS PARA FILTROS
			$arrParam = array("filtroEstrategias" => $valor);
			$data['listaNumeroObjetivoEstrategicos'] = $this->general_model->get_objetivos_estrategicos($arrParam);

	        $arrParam = array();
	        if($_POST && $_POST["numero_objetivo"] != ""){
	            $arrParam = array(
	                "numeroObjetivoEstrategico" => $_POST["numero_objetivo"]
	            );  
	        }
	        $data['listaProyectos'] = $this->general_model->get_numero_proyectos_full_by_dependencia($arrParam);
            if($_POST && $_POST["numero_proyecto"] != ""){
                $arrParam["numeroProyecto"] = $_POST["numero_proyecto"];
            }
			$data['listaNumeroDependencia'] = $this->general_model->get_dependencia_full_by_filtro($arrParam);
	        //FIN LISTAS PARA FILTROS

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

	/**
	 * Lista Numero de Proyectos filtrada por objetivos estrategicos
     * @since 17/06/2022
     * @author BMOTTAG
	 */
    public function numeroProyectosList() {
        header("Content-Type: text/plain; charset=utf-8"); //Para evitar problemas de acentos
		$numeroObjetivoEstrategico = $this->input->post('numero_objetivo');

		$userRol = $this->session->userdata("role");
		$idDependencia = $this->session->userdata("dependencia");

        $arrParam = array();
        if($numeroObjetivoEstrategico != ""){
            $arrParam = array(
                "numeroObjetivoEstrategico" => $numeroObjetivoEstrategico
            );  
        }
        if($userRol == ID_ROL_SUPERVISOR || $userRol == ID_ROL_ENLACE){
            $arrParam["idDependencia"] = $idDependencia;  
        }
        $listaNumeroProyectos = $this->general_model->get_numero_proyectos_full_by_dependencia($arrParam);

		echo "<option value=''>Todas...</option>";
		if ($listaNumeroProyectos) {
			foreach ($listaNumeroProyectos as $fila) {
				echo "<option value='" . $fila["numero_proyecto"] . "' >" . $fila["numero_proyecto"] . "</option>";
			}
		}
    }

	/**
	 * Lista Dependencia filtrada por objetivos estrategicos
     * @since 18/06/2022
     * @author BMOTTAG
	 */
    public function dependenciaList() {
        header("Content-Type: text/plain; charset=utf-8"); //Para evitar problemas de acentos

		$userRol = $this->session->userdata("role");
		$idDependencia = $this->session->userdata("dependencia");

		$arrParam = array();
        if($_POST["numero_objetivo"]){
			$numeroObjetivoEstrategico = $this->input->post('numero_objetivo');
	        if($numeroObjetivoEstrategico != ""){
	            $arrParam["numeroObjetivoEstrategico"] = $numeroObjetivoEstrategico; 
	        }
	    }
        if($_POST["numero_proyecto"]){
			$numeroProyecto = $this->input->post('numero_proyecto');
	        if($numeroProyecto != ""){
	            $arrParam["numeroProyecto"] = $numeroProyecto; 
	        }
	    }
        if($userRol == ID_ROL_SUPERVISOR || $userRol == ID_ROL_ENLACE){
            $arrParam["idDependencia"] = $idDependencia;  
        }
        $lista = $this->general_model->get_dependencia_full_by_filtro($arrParam);

		echo "<option value=''>Todas...</option>";
		if ($lista) {
			foreach ($lista as $fila) {
				echo "<option value='" . $fila["id_dependencia"] . "' >" . $fila["dependencia"] . "</option>";
			}
		}
    }

	/**
	 * Lista Numero de Actividaes filtrada por objetivos estrategicos
     * @since 17/06/2022
     * @author BMOTTAG
	 */
    public function numeroActividadesList() {
        header("Content-Type: text/plain; charset=utf-8"); //Para evitar problemas de acentos

		$userRol = $this->session->userdata("role");
		$idDependencia = $this->session->userdata("dependencia");

		$arrParam = array();
        if($_POST["numero_objetivo"]){
			$numeroObjetivoEstrategico = $this->input->post('numero_objetivo');
	        if($numeroObjetivoEstrategico != ""){
	            $arrParam["numeroObjetivoEstrategico"] = $numeroObjetivoEstrategico; 
	        }
	    }
        if($_POST["numero_proyecto"]){
			$numeroProyecto = $this->input->post('numero_proyecto');
	        if($numeroProyecto != ""){
	            $arrParam["numeroProyecto"] = $numeroProyecto; 
	        }
	    }
        if($_POST["id_dependencia"]){
			$idDependencia = $this->input->post('id_dependencia');
	        if($idDependencia != ""){
	            $arrParam["idDependencia"] = $idDependencia; 
	        }
	    }elseif($userRol == ID_ROL_SUPERVISOR || $userRol == ID_ROL_ENLACE){
            $arrParam["idDependencia"] = $idDependencia;  
        }
        $listaTodasActividades = $this->general_model->get_numero_actividades_full_by_dependencia($arrParam);

		echo "<option value=''>Todas...</option>";
		if ($listaTodasActividades) {
			foreach ($listaTodasActividades as $fila) {
				echo "<option value='" . $fila["numero_actividad"] . "' >" . $fila["numero_actividad"] . "</option>";
			}
		}			
    }




	
	
}