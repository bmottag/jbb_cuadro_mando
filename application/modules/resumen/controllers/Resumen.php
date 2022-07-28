<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once(FCPATH.'vendor/autoload.php');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;


class Resumen extends CI_Controller {
	
    public function __construct() {
        parent::__construct();
        $this->load->model("resumen_model");
        $this->load->model("general_model");
		$this->load->helper('form');
    }
	
	/**
	 * RESUMEN
	 * @since 24/06/2022
	 */
	public function index()
	{	
			if($_GET)
			{								
				$arrParam = array(
					"numero_objetivo" => $_GET["numero_objetivo"],
					"numero_proyecto" => $_GET["numero_proyecto"],
					"id_dependencia" => $_GET["id_dependencia"],
					"numero_actividad" => $_GET["numero_actividad"]
				);
				$this->general_model->saveInfoGoBack($arrParam);
			}

			$arrParam = array(
				"table" => "param_estados",
				"order" => "valor",
				"id" => "x"
			);
			$data['listaEstados'] = $this->general_model->get_basic_search($arrParam);

			//INICIO LISTAS PARA FILTROS
			$arrParam = array();
			$data['listaNumeroObjetivoEstrategicos'] = $this->general_model->get_objetivos_estrategicos($arrParam);

	        $arrParam = array();
	        if($_GET && $_GET["numero_objetivo"] != ""){
	            $arrParam = array(
	                "numeroObjetivoEstrategico" => $_GET["numero_objetivo"]
	            );  
	        }
	        $data['listaProyectos'] = $this->general_model->get_numero_proyectos_full_by_dependencia($arrParam);

            if($_GET && $_GET["numero_proyecto"] != ""){
                $arrParam["numeroProyecto"] = $_GET["numero_proyecto"];
            }
			$data['listaNumeroDependencia'] = $this->general_model->get_dependencia_full_by_filtro($arrParam);

            if($_GET && $_GET["id_dependencia"] != ""){
                $arrParam["idDependencia"] = $_GET["id_dependencia"];
            }
            $data['listaTodasActividades'] = $this->general_model->get_numero_actividades_full_by_dependencia($arrParam);

            if($_GET && $_GET["numero_actividad"] != ""){
                $arrParam["numeroActividad"] = $_GET["numero_actividad"];
            }
			$data['listaActividades'] = $this->general_model->get_actividades($arrParam);

			$arrParam["vigencia"] = date("Y");
			$data['nroActividades'] = $this->general_model->countActividades($arrParam);          
	        //FIN LISTAS PARA FILTROS

			//NO INICIADA
			$arrParam2 = array(
				"numeroTrimestre" => 1,
				"estadoTrimestre" => 0,
				"vigencia" => date("Y")
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
				"vigencia" => date("Y")
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
				"vigencia" => date("Y")
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
				"vigencia" => date("Y")
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
				"vigencia" => date("Y")
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
				"vigencia" => date("Y")
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
				"vigencia" => date("Y")
			);
			$data['nroActividadesPrimerTrimestreRechazadaPlaneacion'] = $this->general_model->countActividadesEstado($arrParam2);

			$arrParam2["numeroTrimestre"] = 2;
			$data['nroActividadesSegundoTrimestreRechazadaPlaneacion'] = $this->general_model->countActividadesEstado($arrParam2);
			$arrParam2["numeroTrimestre"] = 3;
			$data['nroActividadesTercerTrimestreRechazadaPlaneacion'] = $this->general_model->countActividadesEstado($arrParam2);
			$arrParam2["numeroTrimestre"] = 4;
			$data['nroActividadesCuartoTrimestreRechazadaPlaneacion'] = $this->general_model->countActividadesEstado($arrParam2);

			//INCUMPLIDAS
			$arrParam2 = array(
				"numeroTrimestre" => 1,
				"estadoTrimestre" => 7,
				"vigencia" => date("Y")
			);
			$data['nroActividadesPrimerTrimestreIncumplidas'] = $this->general_model->countActividadesEstado($arrParam2);

			$arrParam2["numeroTrimestre"] = 2;
			$data['nroActividadesSegundoTrimestreIncumplidas'] = $this->general_model->countActividadesEstado($arrParam2);
			$arrParam2["numeroTrimestre"] = 3;
			$data['nroActividadesTercerTrimestreIncumplidas'] = $this->general_model->countActividadesEstado($arrParam2);
			$arrParam2["numeroTrimestre"] = 4;
			$data['nroActividadesCuartoTrimestreIncumplidas'] = $this->general_model->countActividadesEstado($arrParam2);

			$data["view"] = "resumen_general";
			$this->load->view("layout_calendar", $data);
	}

	/**
	 * EVALUACION
	 * @since 14/07/2022
	 */
	public function evaluacion()
	{	
			$data["retornoExito"] = false;

	        if($_POST){

	        	if(isset($_POST["btnPrimerSemestre"])){
	        		$numeroSemestre = 1;
	        		$estado = $_POST["estado1"];
	        	}else{
	        		$numeroSemestre = 2;
	        		$estado = $_POST["estado2"];
	        	}
				$arrParam = array(
					"numeroSemestre" => $numeroSemestre,
					"estado" => $estado
				);
				if($this->general_model->updatePublicacionActividades($arrParam)){
					if($estado == 0){
						$data["retornoExito"] = "Se publicó la información.";
					}else{
						$data["retornoExito"] = "Se despublicó la información.";
					}			
				}
	        }

			$arrParam = array(
				"evaluacionFlag" => true,
			);
			$data['listaActividades'] = $this->general_model->get_actividades($arrParam);

			$data["view"] = "evaluacion_oci";
			$this->load->view("layout_calendar", $data);
	}

	/**
	 * RESUMEN
	 * @since 24/06/2022
	 */
	public function enlace()
	{
			if($_GET)
			{								
				$arrParam = array(
					"numero_objetivo" => $_GET["numero_objetivo"],
					"numero_proyecto" => $_GET["numero_proyecto"],
					"id_dependencia" => $_GET["id_dependencia"],
					"numero_actividad" => $_GET["numero_actividad"]
				);
				$this->general_model->saveInfoGoBack($arrParam);
			}
		
			//INICIO LISTAS PARA FILTROS
			$idDependencia = $this->session->userdata("dependencia");
			$arrParam = array(
				"idDependencia" => $idDependencia,
				"vigencia" => date("Y")
			);
			$filtroObjetivosEstrategicos = $this->general_model->get_objetivos_estrategicos_by_dependencia($arrParam);
			$valor = '';
			if($filtroObjetivosEstrategicos){
				$tot = count($filtroObjetivosEstrategicos);
				if($tot > 0){
					for ($i = 0; $i < $tot; $i++) {
						$valor = $valor . $filtroObjetivosEstrategicos[$i]['id_objetivo_estrategico'];
						if($i != ($tot-1)){
							$valor .= ",";
						}
					}
				}else{
					$valor = false;
				}
			}
			$arrParam = array("filtroEstrategias" => $valor);
			$data['listaNumeroObjetivoEstrategicos'] = $this->general_model->get_objetivos_estrategicos($arrParam);

	        $arrParam = array();
	        if($_GET && $_GET["numero_objetivo"] != ""){
	            $arrParam = array(
	                "numeroObjetivoEstrategico" => $_GET["numero_objetivo"]
	            );  
	        }
	        $arrParam["idDependencia"] = $idDependencia;
	        $data['listaProyectos'] = $this->general_model->get_numero_proyectos_full_by_dependencia($arrParam);

            if($_GET && $_GET["numero_proyecto"] != ""){
                $arrParam["numeroProyecto"] = $_GET["numero_proyecto"];
            }
			$data['listaNumeroDependencia'] = $this->general_model->get_dependencia_full_by_filtro($arrParam);

            if($_GET && $_GET["id_dependencia"] != ""){
                $arrParam["idDependencia"] = $_GET["id_dependencia"];
            }
            $data['listaTodasActividades'] = $this->general_model->get_numero_actividades_full_by_dependencia($arrParam);

            if($_GET && $_GET["numero_actividad"] != ""){
                $arrParam["numeroActividad"] = $this->input->post('numero_actividad');
            }
			$data['listaActividades'] = $this->general_model->get_actividades($arrParam);

			$arrParam["vigencia"] = date("Y");
			$data['nroActividades'] = $this->general_model->countActividades($arrParam);          
	        //FIN LISTAS PARA FILTROS

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

			//INCUMPLIDAS
			$arrParam2 = array(
				"numeroTrimestre" => 1,
				"estadoTrimestre" => 7,
				"vigencia" => date("Y"),
				"idDependencia" => $idDependencia
			);
			$data['nroActividadesPrimerTrimestreIncumplidas'] = $this->general_model->countActividadesEstado($arrParam2);

			$arrParam2["numeroTrimestre"] = 2;
			$data['nroActividadesSegundoTrimestreIncumplidas'] = $this->general_model->countActividadesEstado($arrParam2);
			$arrParam2["numeroTrimestre"] = 3;
			$data['nroActividadesTercerTrimestreIncumplidas'] = $this->general_model->countActividadesEstado($arrParam2);
			$arrParam2["numeroTrimestre"] = 4;
			$data['nroActividadesCuartoTrimestreIncumplidas'] = $this->general_model->countActividadesEstado($arrParam2);

			$data["view"] = "resumen_general";
			$this->load->view("layout_calendar", $data);
	}

	/**
	 * Save estado de la actividad
     * @since 24/06/2022
     * @author BMOTTAG
	 */
	public function save_estado_actividad()
	{			
			header('Content-Type: application/json');
			$numeroTrimestre = $this->input->post('trimestre');
			$idEstado = $this->input->post('valorEstado');
			$observacion = $this->input->post('observacion');
			$msj = "Se cambio el estado de las actividades para el <b>Trimestre " . $numeroTrimestre .  "</b>.";
			if($idEstado==99){
				$msj = "Se realizó el cálculo de valores para el <b>Trimestre " . $numeroTrimestre .  "</b>.";
			}
			
			$arrParam = array();
			$listadoActividades = $this->general_model->get_actividades($arrParam);

			foreach ($listadoActividades as $lista):
				$cumplimientoX = 0;
				$avancePOA = 0;
				$cumplimientoActual = 0;
				$ponderacion = $lista['ponderacion'];
				//INICIO --- DEBO TENER EN CUENTA EL TRIMESTRE DE LOS DEMAS QUE ESTAN EN 5
				$arrParam = array("numeroActividad" => $lista["numero_actividad"]);
				$estadoActividad = $this->general_model->get_estados_actividades($arrParam);

				$estadoTrimestre1 = $estadoActividad[0]["estado_trimestre_1"];
				$estadoTrimestre2 = $estadoActividad[0]["estado_trimestre_2"];
				$estadoTrimestre3 = $estadoActividad[0]["estado_trimestre_3"];
				$estadoTrimestre4 = $estadoActividad[0]["estado_trimestre_4"];

				$incluirTrimestre = 0;//trimestres a incluir para el calculo del avance POA
				if($idEstado==99){
					if($estadoTrimestre1 == 5 || $estadoTrimestre1 == 7 ){
						$incluirTrimestre = $incluirTrimestre . "," . 1;
					}
					if($estadoTrimestre2 == 5 || $estadoTrimestre2 == 7 ){
						$incluirTrimestre = $incluirTrimestre . "," . 2;
					}
					if($estadoTrimestre3 == 5 || $estadoTrimestre3 == 7 ){
						$incluirTrimestre = $incluirTrimestre . "," . 3;
					}
					if($estadoTrimestre4 == 5 || $estadoTrimestre4 == 7 ){
						$incluirTrimestre = $incluirTrimestre . "," . 4;
					}
				}else{
					if(($numeroTrimestre != 1 && ($estadoTrimestre1 == 5 || $estadoTrimestre1 == 7) ) || ($numeroTrimestre == 1 && ($idEstado == 5 || $idEstado == 7) ) ){
						$incluirTrimestre = $incluirTrimestre . "," . 1;
					}
					if(($numeroTrimestre != 2 && ($estadoTrimestre2 == 5 || $estadoTrimestre2 == 7) ) || ($numeroTrimestre == 2 && ($idEstado == 5 || $idEstado == 7) ) ){
						$incluirTrimestre = $incluirTrimestre . "," . 2;
					}
					if(($numeroTrimestre != 3 && ($estadoTrimestre3 == 5 || $estadoTrimestre3 == 7) ) || ($numeroTrimestre == 3 && ($idEstado == 5 || $idEstado == 7) ) ){
						$incluirTrimestre = $incluirTrimestre . "," . 3;
					}
					if(($numeroTrimestre != 4 && ($estadoTrimestre4 == 5 || $estadoTrimestre4 == 7) ) || ($numeroTrimestre == 4 && ($idEstado == 5 || $idEstado == 7) ) ){
						$incluirTrimestre = $incluirTrimestre . "," . 4;
					}				
				}

				$arrParam = array(
					"numeroActividad" => $lista["numero_actividad"],
					"filtroTrimestre" => $incluirTrimestre
				);
				$sumaEjecutado = $this->general_model->sumarEjecutado($arrParam);	
				//FIN --- DEBO TENER EN CUENTA EL TRIMESTRE DE LOS DEMAS QUE ESTAN EN 5

				$sumaProgramado = $this->general_model->sumarProgramado($arrParam);
				if($sumaProgramado['programado'] > 0 && $sumaEjecutado){
					$avancePOA = round(($sumaEjecutado['ejecutado']/$sumaProgramado['programado']) * $ponderacion,3);
				}
				if($sumaProgramado['programado'] > 0 && $sumaEjecutado){
					$cumplimientoActual = round(($sumaEjecutado['ejecutado']/$sumaProgramado['programado']) * 100,3);
				}

				if($idEstado==99){
					switch ($numeroTrimestre) {
						case 1:
							$estadoRevisar = $estadoTrimestre1;
							break;
						case 2:
							$estadoRevisar = $estadoTrimestre2;
							break;
						case 3:
							$estadoRevisar = $estadoTrimestre3;
							break;
						case 4:
							$estadoRevisar = $estadoTrimestre4;
							break;
					}


					if( $estadoRevisar == 5 || $estadoRevisar == 7 ) {
						$arrParam = array(
							"numeroActividad" => $lista["numero_actividad"],
							"numeroTrimestre" => $numeroTrimestre
						);
						$sumaProgramadoTrimestreX = $this->general_model->sumarProgramado($arrParam);
						$sumaEjecutadoTrimestreX = $this->general_model->sumarEjecutado($arrParam);

						if($sumaProgramadoTrimestreX['programado'] > 0){
							$cumplimientoX = round($sumaEjecutadoTrimestreX['ejecutado'] / $sumaProgramadoTrimestreX['programado'] * 100,3);
						}
					}
				}else{
					if($idEstado == 5){
						$arrParam = array(
							"numeroActividad" => $lista["numero_actividad"],
							"numeroTrimestre" => $numeroTrimestre
						);
						$sumaProgramadoTrimestreX = $this->general_model->sumarProgramado($arrParam);
						$sumaEjecutadoTrimestreX = $this->general_model->sumarEjecutado($arrParam);

						if($sumaProgramadoTrimestreX['programado'] > 0){
							$cumplimientoX = round($sumaEjecutadoTrimestreX['ejecutado'] / $sumaProgramadoTrimestreX['programado'] * 100,3);
						}
					}					
				}


				if($idEstado==99){
					$arrParam = array(
						"numeroActividad" => $lista["numero_actividad"],
						"numeroTrimestre" => $numeroTrimestre,
						"cumplimientoX" => $cumplimientoX,
						"avancePOA" => $avancePOA,
						"cumplimientoActual" => $cumplimientoActual
					);
					$this->general_model->updateCalculosActividadTotales($arrParam);
				}else{
					$arrParam = array(
						"numeroActividad" => $lista["numero_actividad"],
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
						$this->general_model->updateEstadoActividadTotales($arrParam);
					}					
				}

			endforeach;

			$data["result"] = true;
			$data["mensaje"] = $msj;
			$this->session->set_flashdata('retornoExito', $msj);
			echo json_encode($data);
    }

	/**
	 * Save estado de la actividad
     * @since 24/06/2022
     * @author BMOTTAG
	 */
	public function save_evidencias()
	{			
			$numeroTrimestre = $this->input->post('trimestre');

			$arrParam = array();
			$listadoActividades = $this->general_model->get_actividades($arrParam);

			foreach ($listadoActividades as $lista):
				$arrParam = array("numeroActividad" => $lista["numero_actividad"]);
				$arrParam = array(
					"numeroActividad" => $lista["numero_actividad"],
					"numeroTrimestre" => $numeroTrimestre
				);
				$infoEjecucion = $this->general_model->get_ejecucion_actividades($arrParam);

				$descripcion_actividad = "";
				$evidencia = "";
				$z=0;
				if($infoEjecucion){
					foreach ($infoEjecucion as $valores):	
						if($z != 0){
							if($valores['descripcion_actividades'] != ""){
								$descripcion_actividad .= "<br>";
							}
							if($valores['evidencias'] != ""){
								$evidencia .= "<br>";
							}
						}
						$descripcion_actividad .= $valores['descripcion_actividades'];
						$evidencia .= $valores['evidencias'];
						$z++;
					endforeach;

					$arrParam = array(
						"numeroActividad" => $lista["numero_actividad"],
						"numeroTrimestre" => $numeroTrimestre,
						"descripcion_actividad" => $descripcion_actividad,
						"evidencia" => $evidencia
					);
					$this->general_model->updateEvidencias($arrParam);
				}
			endforeach;

			echo "Termino ejecucion";
    }

	/**
	 * objetivos_estrategicos
     * @since 26/06/2022
     * @author BMOTTAG
	 */
	public function objetivos_estrategicos()
	{			
			$arrParam = array();
			$data['info'] = $this->general_model->get_objetivos_estrategicos($arrParam);
			
			$data["view"] = 'objetivos_estrategicos';
			$this->load->view("layout_calendar", $data);
	}

	/**
	 * planes institucionaels
     * @since 27/06/2022
     * @author BMOTTAG
	 */
	public function planes_institucionales()
	{				
			$data["view"] = 'planes_institucionales';
			$this->load->view("layout_calendar", $data);
	}

	/**
	 * RESUMEN
	 * @since 24/06/2022
	 */
	public function reporte()
	{	
		$fechaActual = date('Y-m-d');
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition:attachment;filename=consolidado_POA_'.$fechaActual.'.xlsx');

		$arrParam = array();
		$listaActividades = $this->general_model->get_actividades_full($arrParam);

		$spreadsheet = new Spreadsheet();
		$spreadsheet->getActiveSheet()->setTitle('Consolidado');

		$spreadsheet->getActiveSheet()->mergeCells('A1:A3');

		$spreadsheet->getActiveSheet(0)->setCellValue('B1', 'Plan de Desarrollo Distrital');
		$spreadsheet->getActiveSheet()->mergeCells('B1:H2');


		$spreadsheet->getActiveSheet(0)->setCellValue('I1', 'Plan Estratégico');
		$spreadsheet->getActiveSheet()->mergeCells('I1:J2');

		$spreadsheet->getActiveSheet()->mergeCells('K1:K3');

		$spreadsheet->getActiveSheet(0)->setCellValue('L1', 'Planes Institucionales');
		$spreadsheet->getActiveSheet()->mergeCells('L1:W2');

		$spreadsheet->getActiveSheet(0)->setCellValue('X1', 'Actividad');
		$spreadsheet->getActiveSheet()->mergeCells('X1:Y2');

		$spreadsheet->getActiveSheet()->mergeCells('Z1:Z3');
		$spreadsheet->getActiveSheet()->mergeCells('AA1:AA3');
		$spreadsheet->getActiveSheet()->mergeCells('AB1:AB3');
		$spreadsheet->getActiveSheet()->mergeCells('AC1:AC3');
		$spreadsheet->getActiveSheet()->mergeCells('AD1:AD3');
		$spreadsheet->getActiveSheet()->mergeCells('AE1:AE3');

		$spreadsheet->getActiveSheet(0)->setCellValue('AF1', 'Duración');
		$spreadsheet->getActiveSheet()->mergeCells('AF1:AG2');

		$spreadsheet->getActiveSheet(0)->setCellValue('AH1', 'Ejecución');
		$spreadsheet->getActiveSheet()->mergeCells('AH1:AS2');

		$spreadsheet->getActiveSheet(0)->setCellValue('AT1', 'Estado de la actividad');
		$spreadsheet->getActiveSheet()->mergeCells('AT1:AW2');

		$spreadsheet->getActiveSheet()->mergeCells('AX1:AX3');

		$spreadsheet->getActiveSheet(0)->setCellValue('AY1', 'Descripción de actividades');
		$spreadsheet->getActiveSheet()->mergeCells('AY1:BB2');

		$spreadsheet->getActiveSheet(0)->setCellValue('BC1', 'Evidencias');
		$spreadsheet->getActiveSheet()->mergeCells('BC1:BF2');

		$spreadsheet->getActiveSheet(0)->setCellValue('BG1', 'Observaciones POA');
		$spreadsheet->getActiveSheet()->mergeCells('BG1:BJ2');

		$spreadsheet->getActiveSheet(0)->setCellValue('BK1', 'EVALUACIÓN CONTROL INTERNO');
		$spreadsheet->getActiveSheet()->mergeCells('BK1:BN1');

		$spreadsheet->getActiveSheet(0)->setCellValue('BK2', 'Semestre I');
		$spreadsheet->getActiveSheet()->mergeCells('BK2:BL2');

		$spreadsheet->getActiveSheet(0)->setCellValue('BM2', 'Semestre I');
		$spreadsheet->getActiveSheet()->mergeCells('BM2:BN2');

		$spreadsheet->getActiveSheet(0)->setCellValue('BO1', 'Programación');
		$spreadsheet->getActiveSheet()->mergeCells('BO1:BZ2');

		$spreadsheet->getActiveSheet(0)
							->setCellValue('A1', 'Dependencia')
							->setCellValue('B3', 'Proyecto de Inversión')
							->setCellValue('C3', 'Meta proyecto de inversión')
							->setCellValue('D3', 'Presupuesto Meta Proyecto de Inversión o Funcionamiento')
							->setCellValue('E3', 'Propósito')
							->setCellValue('F3', 'Logro')
							->setCellValue('G3', 'Programa Estratégico')
							->setCellValue('H3', 'Meta PDD')
							->setCellValue('I3', 'Estrategias')
							->setCellValue('J3', 'Objetivo Estratégico')
							->setCellValue('K1', 'Proceso de Calidad')
							->setCellValue('L3', 'Plan Institucional de Archivos de la Entidad')
							->setCellValue('M3', 'Plan Anual de Adquisiciones')
							->setCellValue('N3', 'Plan Anual de Vacantes')
							->setCellValue('O3', 'Plan de Previsión de Recursos Humanos')
							->setCellValue('P3', 'Plan Estratégico de Talento Humano')
							->setCellValue('Q3', 'Plan Institucional de Capacitación')
							->setCellValue('R3', 'Plan de Incentivos Institucionales')
							->setCellValue('S3', 'Plan de Trabajo Anual en Seguridad y Salud en el Trabajo')
							->setCellValue('T3', 'Plan Anticorrupción y de Atención al Ciudadano')
							->setCellValue('U3', 'Plan Estratégico de Tecnologías de la Información y las Comunicaciones')
							->setCellValue('V3', 'Plan de Tratamiento de Riesgos de Seguridad y Privacidad de la Información ')
							->setCellValue('W3', 'Plan de Seguridad y Privacidad de la Información ')
							->setCellValue('X3', 'No.')
							->setCellValue('Y3', 'Actividad')
							->setCellValue('Z1', 'Meta Plan Operativo Anual')
							->setCellValue('AA1', 'Unidad de Medida')
							->setCellValue('AB1', 'Nombre del indicador')
							->setCellValue('AC1', 'Tipo de indicador')
							->setCellValue('AD1', 'Responsable')
							->setCellValue('AE1', 'Ponderación')
							->setCellValue('AF3', 'Fecha inicial')
							->setCellValue('AG3', 'Fecha Final')
							->setCellValue('AH3', 'Enero')
							->setCellValue('AI3', 'Febrero')
							->setCellValue('AJ3', 'Marzo')
							->setCellValue('AK3', 'Abril')
							->setCellValue('AL3', 'Mayo')
							->setCellValue('AM3', 'Junio')
							->setCellValue('AN3', 'Julio')
							->setCellValue('AO3', 'Agosto')
							->setCellValue('AP3', 'Septiembre')
							->setCellValue('AQ3', 'Octubre')
							->setCellValue('AR3', 'Noviembre')
							->setCellValue('AS3', 'Diciembre')
							->setCellValue('AT3', 'Trimestre I')
							->setCellValue('AU3', 'Trimestre II')
							->setCellValue('AV3', 'Trimestre III')
							->setCellValue('AW3', 'Trimestre IV')
							->setCellValue('AX1', 'Avance POA')
							->setCellValue('AY3', 'Trimestre I')
							->setCellValue('AZ3', 'Trimestre II')
							->setCellValue('BA3', 'Trimestre III')
							->setCellValue('BB3', 'Trimestre IV')
							->setCellValue('BC3', 'Trimestre I')
							->setCellValue('BD3', 'Trimestre II')
							->setCellValue('BE3', 'Trimestre III')
							->setCellValue('BF3', 'Trimestre IV')
							->setCellValue('BG3', 'Trimestre I')
							->setCellValue('BH3', 'Trimestre II')
							->setCellValue('BI3', 'Trimestre III')
							->setCellValue('BJ3', 'Trimestre IV')
							->setCellValue('BK3', 'Calificación Semestre')
							->setCellValue('BL3', 'Observaciones de la Evaluación')
							->setCellValue('BM3', 'Calificación Semestre')
							->setCellValue('BN3', 'Observaciones de la Evaluación')
							->setCellValue('BO3', 'Enero')
							->setCellValue('BP3', 'Febrero')
							->setCellValue('BQ3', 'Marzo')
							->setCellValue('BR3', 'Abril')
							->setCellValue('BS3', 'Mayo')
							->setCellValue('BT3', 'Junio')
							->setCellValue('BU3', 'Julio')
							->setCellValue('BV3', 'Agosto')
							->setCellValue('BW3', 'Septiembre')
							->setCellValue('BX3', 'Octubre')
							->setCellValue('BY3', 'Noviembre')
							->setCellValue('BZ3', 'Diciembre');

		$j=4;
		if($listaActividades){
			foreach ($listaActividades as $lista):
				$arrParam = array("numeroActividad" => $lista['numero_actividad']);
				$infoEjecucion = $this->general_model->get_ejecucion_actividades($arrParam);
				switch ($lista['tipo_indicador']) {
					case 1:
						$tipo_indicador = 'Eficacia';
						break;
					case 2:
						$tipo_indicador = 'Eficiencia';
						break;
					case 3:
						$tipo_indicador = 'Efectividad';
						break;
				}

				$plan_archivos = $lista['plan_archivos'] == 1?"Si":"N/A";
				$plan_adquisiciones = $lista['plan_adquisiciones'] == 1?"Si":"N/A";
				$plan_vacantes = $lista['plan_vacantes'] == 1?"Si":"N/A";
				$plan_recursos = $lista['plan_recursos'] == 1?"Si":"N/A";
				$plan_talento = $lista['plan_talento'] == 1?"Si":"N/A";
				$plan_capacitacion = $lista['plan_capacitacion'] == 1?"Si":"N/A";
				$plan_incentivos = $lista['plan_incentivos'] == 1?"Si":"N/A";
				$plan_trabajo = $lista['plan_trabajo'] == 1?"Si":"N/A";
				$plan_anticorrupcion = $lista['plan_anticorrupcion'] == 1?"Si":"N/A";
				$plan_tecnologia = $lista['plan_tecnologia'] == 1?"Si":"N/A";
				$plan_riesgos = $lista['plan_riesgos'] == 1?"Si":"N/A";
				$plan_informacion = $lista['plan_informacion'] == 1?"Si":"N/A";

				$spreadsheet->getActiveSheet()->getStyle('A'.$j.':BN'.$j)->applyFromArray(
				    [
					    'alignment' => [
					        'wrapText' => TRUE
					    ]
				    ]
				);

				$spreadsheet->getActiveSheet()->getStyle('D' . $j)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_CURRENCY_USD_SIMPLE);
				$spreadsheet->getActiveSheet()->getStyle('S' . $j)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
				$spreadsheet->getActiveSheet()
							->setCellValue('A'.$j, $lista['dependencia'])
							->setCellValue('B'.$j, $lista['proyecto_inversion'])
							->setCellValue('C'.$j, $lista['meta_proyecto'])
							->setCellValue('D'.$j, $lista['presupuesto_meta'])
							->setCellValue('E'.$j, $lista['proposito'])
							->setCellValue('F'.$j, $lista['logro'])
							->setCellValue('G'.$j, $lista['programa'])
							->setCellValue('H'.$j, $lista['meta_pdd'])
							->setCellValue('I'.$j, $lista['estrategia'])
							->setCellValue('J'.$j, $lista['objetivo_estrategico'])
							->setCellValue('K'.$j, $lista['proceso_calidad'])
							->setCellValue('L'.$j, $plan_archivos)
							->setCellValue('M'.$j, $plan_adquisiciones)
							->setCellValue('N'.$j, $plan_vacantes)
							->setCellValue('O'.$j, $plan_recursos)
							->setCellValue('P'.$j, $plan_talento)
							->setCellValue('Q'.$j, $plan_capacitacion)
							->setCellValue('R'.$j, $plan_incentivos)
							->setCellValue('S'.$j, $plan_trabajo)
							->setCellValue('T'.$j, $plan_anticorrupcion)
							->setCellValue('U'.$j, $plan_tecnologia)
							->setCellValue('V'.$j, $plan_riesgos)
							->setCellValue('W'.$j, $plan_informacion)
							->setCellValue('X'.$j, $lista['numero_actividad'])
							->setCellValue('Y'.$j, $lista['descripcion_actividad'])
							->setCellValue('Z'.$j, $lista['meta_plan_operativo_anual'])
							->setCellValue('AA'.$j, $lista['unidad_medida'])
							->setCellValue('AB'.$j, $lista['nombre_indicador'])
							->setCellValue('AC'.$j, $tipo_indicador)
							->setCellValue('AD'.$j, $lista['area_responsable'])
							->setCellValue('AE'.$j, $lista['ponderacion'] . '%')
							->setCellValue('AF'.$j, $lista['mes_inicial'])
							->setCellValue('AG'.$j, $lista['mes_final']);

				foreach ($infoEjecucion as $ejecucion):
					if($ejecucion['fk_id_mes'] == 1){
						$spreadsheet->getActiveSheet()->setCellValue('AH'.$j, $ejecucion['ejecutado']);
						$spreadsheet->getActiveSheet()->setCellValue('BO'.$j, $ejecucion['programado']);
						break;
					}
				endforeach;

				foreach ($infoEjecucion as $ejecucion):
					if($ejecucion['fk_id_mes'] == 2){
						$spreadsheet->getActiveSheet()->setCellValue('AI'.$j, $ejecucion['ejecutado']);
						$spreadsheet->getActiveSheet()->setCellValue('BP'.$j, $ejecucion['programado']);
						break;
					}
				endforeach;

				foreach ($infoEjecucion as $ejecucion):
					if($ejecucion['fk_id_mes'] == 3){
						$spreadsheet->getActiveSheet()->setCellValue('AJ'.$j, $ejecucion['ejecutado']);
						$spreadsheet->getActiveSheet()->setCellValue('BQ'.$j, $ejecucion['programado']);
						break;
					}
				endforeach;

				foreach ($infoEjecucion as $ejecucion):
					if($ejecucion['fk_id_mes'] == 4){
						$spreadsheet->getActiveSheet()->setCellValue('AK'.$j, $ejecucion['ejecutado']);
						$spreadsheet->getActiveSheet()->setCellValue('BR'.$j, $ejecucion['programado']);
						break;
					}
				endforeach;

				foreach ($infoEjecucion as $ejecucion):
					if($ejecucion['fk_id_mes'] == 5){
						$spreadsheet->getActiveSheet()->setCellValue('AL'.$j, $ejecucion['ejecutado']);
						$spreadsheet->getActiveSheet()->setCellValue('BS'.$j, $ejecucion['programado']);
						break;
					}
				endforeach;

				foreach ($infoEjecucion as $ejecucion):
					if($ejecucion['fk_id_mes'] == 6){
						$spreadsheet->getActiveSheet()->setCellValue('AM'.$j, $ejecucion['ejecutado']);
						$spreadsheet->getActiveSheet()->setCellValue('BT'.$j, $ejecucion['programado']);
						break;
					}
				endforeach;

				foreach ($infoEjecucion as $ejecucion):
					if($ejecucion['fk_id_mes'] == 7){
						$spreadsheet->getActiveSheet()->setCellValue('AN'.$j, $ejecucion['ejecutado']);
						$spreadsheet->getActiveSheet()->setCellValue('BU'.$j, $ejecucion['programado']);
						break;
					}
				endforeach;

				foreach ($infoEjecucion as $ejecucion):
					if($ejecucion['fk_id_mes'] == 8){
						$spreadsheet->getActiveSheet()->setCellValue('AO'.$j, $ejecucion['ejecutado']);
						$spreadsheet->getActiveSheet()->setCellValue('BV'.$j, $ejecucion['programado']);
						break;
					}
				endforeach;

				foreach ($infoEjecucion as $ejecucion):
					if($ejecucion['fk_id_mes'] == 9){
						$spreadsheet->getActiveSheet()->setCellValue('AP'.$j, $ejecucion['ejecutado']);
						$spreadsheet->getActiveSheet()->setCellValue('BW'.$j, $ejecucion['programado']);
						break;
					}
				endforeach;

				foreach ($infoEjecucion as $ejecucion):
					if($ejecucion['fk_id_mes'] == 10){
						$spreadsheet->getActiveSheet()->setCellValue('AQ'.$j, $ejecucion['ejecutado']);
						$spreadsheet->getActiveSheet()->setCellValue('BX'.$j, $ejecucion['programado']);
						break;
					}
				endforeach;

				foreach ($infoEjecucion as $ejecucion):
					if($ejecucion['fk_id_mes'] == 11){
						$spreadsheet->getActiveSheet()->setCellValue('AR'.$j, $ejecucion['ejecutado']);
						$spreadsheet->getActiveSheet()->setCellValue('BY'.$j, $ejecucion['programado']);
						break;
					}
				endforeach;

				foreach ($infoEjecucion as $ejecucion):
					if($ejecucion['fk_id_mes'] == 12){
						$spreadsheet->getActiveSheet()->setCellValue('AS'.$j, $ejecucion['ejecutado']);
						$spreadsheet->getActiveSheet()->setCellValue('BZ'.$j, $ejecucion['programado']);
						break;
					}
				endforeach;

				$trimestre_1 = $lista['trimestre_1'];
				$trimestre_2 = $lista['trimestre_2'];
				$trimestre_3 = $lista['trimestre_3'];
				$trimestre_4 = $lista['trimestre_4'];
				$avance_poa = $lista['avance_poa'];
				
				$trimestre_1 = floatval($trimestre_1);
				$trimestre_2 = floatval($trimestre_2);
				$trimestre_3 = floatval($trimestre_3);
				$trimestre_4 = floatval($trimestre_4);
				$avance_poa = floatval($avance_poa);
				
				$spreadsheet->getActiveSheet()
							->setCellValue('AT'.$j, $trimestre_1)
							->setCellValue('AU'.$j, $trimestre_2)
							->setCellValue('AV'.$j, $trimestre_3)
							->setCellValue('AW'.$j, $trimestre_4)
							->setCellValue('AX'.$j, $avance_poa)
							->setCellValue('AY'.$j, str_replace(array("<br>"),"\n",$lista['descripcion_actividad_trimestre_1']))
							->setCellValue('AZ'.$j, str_replace(array("<br>"),"\n",$lista['descripcion_actividad_trimestre_2']))
							->setCellValue('BA'.$j, str_replace(array("<br>"),"\n",$lista['descripcion_actividad_trimestre_3']))
							->setCellValue('BB'.$j, str_replace(array("<br>"),"\n",$lista['descripcion_actividad_trimestre_4']))
							->setCellValue('BC'.$j, str_replace(array("<br>"),"\n",$lista['evidencias_trimestre_1']))
							->setCellValue('BD'.$j, str_replace(array("<br>"),"\n",$lista['evidencias_trimestre_2']))
							->setCellValue('BE'.$j, str_replace(array("<br>"),"\n",$lista['evidencias_trimestre_3']))
							->setCellValue('BF'.$j, str_replace(array("<br>"),"\n",$lista['evidencias_trimestre_4']))
							->setCellValue('BG'.$j, str_replace(array("<br>"),"\n",$lista['mensaje_poa_trimestre_1']))
							->setCellValue('BH'.$j, str_replace(array("<br>"),"\n",$lista['mensaje_poa_trimestre_2']))
							->setCellValue('BI'.$j, str_replace(array("<br>"),"\n",$lista['mensaje_poa_trimestre_3']))
							->setCellValue('BJ'.$j, str_replace(array("<br>"),"\n",$lista['mensaje_poa_trimestre_4']));

				$calificacio1 = '';
				$observacion1 = '';
				$calificacio2 = '';
				$observacion2 = '';
				if($lista['publicar_calificacion_1'] == 1){
					$calificacio1 = $lista['calificacion_semestre_1'];
					$observacion1 = $lista['observacion_semestre_1'];
				}
				if($lista['publicar_calificacion_2'] == 1){
					$calificacio2 = $lista['calificacion_semestre_2'];
					$observacion2 = $lista['observacion_semestre_2'];
				}

				$spreadsheet->getActiveSheet()
							->setCellValue('BK'.$j, $calificacio1)
							->setCellValue('BL'.$j, $observacion1)
							->setCellValue('BM'.$j, $calificacio2)
							->setCellValue('BN'.$j, $observacion2);

				$j++;
			endforeach;
		}

		// Set column widths							  
		$spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(40);
		$spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(40);
		$spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(40);
		$spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(30);
		$spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(35);
		$spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(40);
		$spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(40);
		$spreadsheet->getActiveSheet()->getColumnDimension('H')->setWidth(40);
		$spreadsheet->getActiveSheet()->getColumnDimension('I')->setWidth(20);
		$spreadsheet->getActiveSheet()->getColumnDimension('J')->setWidth(40);
		$spreadsheet->getActiveSheet()->getColumnDimension('K')->setWidth(30);
		$spreadsheet->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('M')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('N')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('O')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('P')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('Q')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('R')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('S')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('T')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('U')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('V')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('W')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('X')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('Y')->setWidth(40);
		$spreadsheet->getActiveSheet()->getColumnDimension('Z')->setWidth(20);
		$spreadsheet->getActiveSheet()->getColumnDimension('AA')->setWidth(20);
		$spreadsheet->getActiveSheet()->getColumnDimension('AB')->setWidth(40);
		$spreadsheet->getActiveSheet()->getColumnDimension('AC')->setWidth(20);
		$spreadsheet->getActiveSheet()->getColumnDimension('AD')->setWidth(30);
		$spreadsheet->getActiveSheet()->getColumnDimension('AE')->setWidth(20);
		$spreadsheet->getActiveSheet()->getColumnDimension('AF')->setWidth(20);
		$spreadsheet->getActiveSheet()->getColumnDimension('AG')->setWidth(20);
		$spreadsheet->getActiveSheet()->getColumnDimension('AH')->setWidth(10);
		$spreadsheet->getActiveSheet()->getColumnDimension('AI')->setWidth(10);
		$spreadsheet->getActiveSheet()->getColumnDimension('AJ')->setWidth(10);
		$spreadsheet->getActiveSheet()->getColumnDimension('AK')->setWidth(10);
		$spreadsheet->getActiveSheet()->getColumnDimension('AL')->setWidth(10);
		$spreadsheet->getActiveSheet()->getColumnDimension('AM')->setWidth(10);
		$spreadsheet->getActiveSheet()->getColumnDimension('AN')->setWidth(10);
		$spreadsheet->getActiveSheet()->getColumnDimension('AO')->setWidth(10);
		$spreadsheet->getActiveSheet()->getColumnDimension('AP')->setWidth(10);
		$spreadsheet->getActiveSheet()->getColumnDimension('AQ')->setWidth(10);
		$spreadsheet->getActiveSheet()->getColumnDimension('AR')->setWidth(10);
		$spreadsheet->getActiveSheet()->getColumnDimension('AS')->setWidth(10);
		$spreadsheet->getActiveSheet()->getColumnDimension('AT')->setWidth(20);
		$spreadsheet->getActiveSheet()->getColumnDimension('AU')->setWidth(20);
		$spreadsheet->getActiveSheet()->getColumnDimension('AV')->setWidth(20);
		$spreadsheet->getActiveSheet()->getColumnDimension('AW')->setWidth(20);
		$spreadsheet->getActiveSheet()->getColumnDimension('AX')->setWidth(20);
		$spreadsheet->getActiveSheet()->getColumnDimension('AY')->setWidth(120);
		$spreadsheet->getActiveSheet()->getColumnDimension('AZ')->setWidth(120);
		$spreadsheet->getActiveSheet()->getColumnDimension('BA')->setWidth(120);
		$spreadsheet->getActiveSheet()->getColumnDimension('BB')->setWidth(120);
		$spreadsheet->getActiveSheet()->getColumnDimension('BC')->setWidth(120);
		$spreadsheet->getActiveSheet()->getColumnDimension('BD')->setWidth(120);
		$spreadsheet->getActiveSheet()->getColumnDimension('BE')->setWidth(120);
		$spreadsheet->getActiveSheet()->getColumnDimension('BF')->setWidth(120);
		$spreadsheet->getActiveSheet()->getColumnDimension('BG')->setWidth(120);
		$spreadsheet->getActiveSheet()->getColumnDimension('BH')->setWidth(120);
		$spreadsheet->getActiveSheet()->getColumnDimension('BI')->setWidth(120);
		$spreadsheet->getActiveSheet()->getColumnDimension('BJ')->setWidth(120);
		$spreadsheet->getActiveSheet()->getColumnDimension('BK')->setWidth(40);
		$spreadsheet->getActiveSheet()->getColumnDimension('BL')->setWidth(80);
		$spreadsheet->getActiveSheet()->getColumnDimension('BM')->setWidth(40);
		$spreadsheet->getActiveSheet()->getColumnDimension('BN')->setWidth(80);


		// Set fonts
		$spreadsheet->getActiveSheet()->getStyle('A1:BN2')->getFont()->setSize(11);
		$spreadsheet->getActiveSheet()->getStyle('BJ2:BN2')->getFont()->setSize(11);
		$spreadsheet->getActiveSheet()->getStyle('A3:K3')->getFont()->setSize(11);
		$spreadsheet->getActiveSheet()->getStyle('L3:W3')->getFont()->setSize(8);
		$spreadsheet->getActiveSheet()->getStyle('X3:BN3')->getFont()->setSize(11);

		$spreadsheet->getActiveSheet()->getStyle('A1:BN2')->getFont()->setBold(true);

		$spreadsheet->getActiveSheet()->getStyle('A3:K3')->getFont()->setBold(true);
		$spreadsheet->getActiveSheet()->getStyle('X3:AG3')->getFont()->setBold(true);
		$spreadsheet->getActiveSheet()->getStyle('AT3:BN3')->getFont()->setBold(true);

		$spreadsheet->getActiveSheet()->getStyle('A1:BN2')->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE);
 		$spreadsheet->getActiveSheet()->getStyle('A1:BN2')->getFill()->setFillType(Fill::FILL_SOLID);
		$spreadsheet->getActiveSheet()->getStyle('A1:BN2')->getFill()->getStartColor()->setARGB('236e09');

		$spreadsheet->getActiveSheet()->getStyle('A2:BN2')->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE);
 		$spreadsheet->getActiveSheet()->getStyle('A2:BN2')->getFill()->setFillType(Fill::FILL_SOLID);
		$spreadsheet->getActiveSheet()->getStyle('A2:BN2')->getFill()->getStartColor()->setARGB('236e09');

		$spreadsheet->getActiveSheet()->getStyle('A3:BN3')->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE);
 		$spreadsheet->getActiveSheet()->getStyle('A3:BN3')->getFill()->setFillType(Fill::FILL_SOLID);
		$spreadsheet->getActiveSheet()->getStyle('A3:BN3')->getFill()->getStartColor()->setARGB('86B659');

		$spreadsheet->getActiveSheet()->getStyle('A1:BN1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
		$spreadsheet->getActiveSheet()->getStyle('A1:BN1')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

		$spreadsheet->getActiveSheet()->getStyle('BK2:BN2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
		$spreadsheet->getActiveSheet()->getStyle('BK2:BN2')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

		$spreadsheet->getActiveSheet()->getStyle('A3:K3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
		$spreadsheet->getActiveSheet()->getStyle('A3:K3')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

		$spreadsheet->getActiveSheet()->getStyle('X3:BN3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
		$spreadsheet->getActiveSheet()->getStyle('X3:BN3')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

		$spreadsheet->getActiveSheet()->getRowDimension('1')->setRowHeight(35);
		$spreadsheet->getActiveSheet()->getRowDimension('3')->setRowHeight(75);
		$spreadsheet->getActiveSheet()->getStyle('A1:BN2')->applyFromArray(
		    [
		        'borders' => [
		            'allBorders' => ['borderStyle' => Border::BORDER_THIN],
		        ],
		    ]
		);

		$spreadsheet->getActiveSheet()->getStyle('A3:BN3')->applyFromArray(
		    [
		        'borders' => [
		            'allBorders' => ['borderStyle' => Border::BORDER_THIN],
		        ],
		    ]
		);

		$spreadsheet->getActiveSheet()->getStyle('L3:W3')->applyFromArray(
		    [
			    'alignment' => [
			        'textRotation' => 90,
			        'readOrder' => Alignment::READORDER_RTL,
			        'wrapText' => TRUE
			    ]
		    ]
		);

		$spreadsheet->getActiveSheet()->getStyle('AH3:AS3')->applyFromArray(
		    [
			    'alignment' => [
			        'textRotation' => 90,
			        'readOrder' => Alignment::READORDER_RTL,
			        'wrapText' => TRUE
			    ]
		    ]
		);

		$spreadsheet->getActiveSheet()->getStyle('A3:BN3')->applyFromArray(
		    [
			    'alignment' => [
			        'wrapText' => TRUE
			    ]
		    ]
		);

		$spreadsheet->getActiveSheet()->getStyle('A1:BN2')->applyFromArray(
		    [
			    'alignment' => [
			        'wrapText' => TRUE
			    ]
		    ]
		);

		/**
		 * AVANCE DEPENDENICIAS
		 */
		$spreadsheet->createSheet();
		$spreadsheet->setActiveSheetIndex(1);
		$spreadsheet->getActiveSheet()->setTitle('Avance Dependencias');

		$spreadsheet->getActiveSheet()
							->setCellValue('A1', 'Dependencia')
							->setCellValue('B1', 'No. Actividades')
							->setCellValue('C1', 'Avance Plan Estratégico');

		$arrParam = array(
			"filtro" => true
		);
		$listaDependencia = $this->general_model->get_app_dependencias($arrParam);

		if($listaDependencia){
			$j=2;
	        foreach ($listaDependencia as $lista):
	            $arrParam = array(
	                "idDependencia" => $lista["id_dependencia"],
	                "vigencia" => date("Y")
	            );
	            $nroActividades = $this->general_model->countActividades($arrParam);
	            $avance = $this->general_model->sumAvance($arrParam);
	            $avancePOA = number_format($avance["avance_poa"],3);
	            if(!$avancePOA){
	                $avancePOA = 0;
	            }
				$spreadsheet->getActiveSheet()
							->setCellValue('A'.$j, $lista['dependencia'])
							->setCellValue('B'.$j, $nroActividades)
							->setCellValue('C'.$j, $avancePOA . '%');

				$spreadsheet->getActiveSheet()->getStyle('A'.$j.':C'.$j)->applyFromArray(
				    [
				        'borders' => [
				            'allBorders' => ['borderStyle' => Border::BORDER_THIN],
				        ],
				    ]
				);
				$j++;
	        endforeach;
		}

		$spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(40);
		$spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(30);
		$spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(30);

		// Set fonts
		$spreadsheet->getActiveSheet()->getStyle('A1:C1')->getFont()->setSize(11);
		$spreadsheet->getActiveSheet()->getStyle('A1:C1')->getFont()->setBold(true);

		$spreadsheet->getActiveSheet()->getStyle('A1:C1')->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE);
 		$spreadsheet->getActiveSheet()->getStyle('A1:C1')->getFill()->setFillType(Fill::FILL_SOLID);
		$spreadsheet->getActiveSheet()->getStyle('A1:C1')->getFill()->getStartColor()->setARGB('236e09');

		$spreadsheet->getActiveSheet()->getStyle('A1:C1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
		$spreadsheet->getActiveSheet()->getStyle('A1:C1')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

		$spreadsheet->getActiveSheet()->getRowDimension('1')->setRowHeight(40);
		$spreadsheet->getActiveSheet()->getStyle('A1:C1')->applyFromArray(
		    [
		        'borders' => [
		            'allBorders' => ['borderStyle' => Border::BORDER_THIN],
		        ],
		    ]
		);

		/**
		 * AVANCE ESTRATEGIAS
		 */
		$spreadsheet->createSheet();
		$spreadsheet->setActiveSheetIndex(2);
		$spreadsheet->getActiveSheet()->setTitle('Avance Estrategias');

		$spreadsheet->getActiveSheet()
							->setCellValue('A1', 'Estrategia')
							->setCellValue('B1', 'No. Actividades')
							->setCellValue('C1', 'Avance');

		$arrParam = array();
		$listaEstrategias = $this->general_model->get_estrategias($arrParam);

		if($listaEstrategias){
			$j=2;
	        foreach ($listaEstrategias as $lista):
                $arrParam = array(
                    "idEstrategia" => $lista["id_estrategia"],
                    "vigencia" => date("Y")
                );
                $nroActividades = $this->general_model->countActividades($arrParam);
                $cumplimiento = $this->general_model->sumCumplimiento($arrParam);
                $promedioCumplimiento = 0;
                if($nroActividades){
                    $promedioCumplimiento = number_format($cumplimiento["cumplimiento"]/$nroActividades,3);
                }
                if(!$promedioCumplimiento){
                    $promedioCumplimiento = 0;
                }
				$spreadsheet->getActiveSheet()
							->setCellValue('A'.$j, $lista['estrategia'])
							->setCellValue('B'.$j, $nroActividades)
							->setCellValue('C'.$j, $promedioCumplimiento . '%');

				$spreadsheet->getActiveSheet()->getStyle('A'.$j.':C'.$j)->applyFromArray(
				    [
				        'borders' => [
				            'allBorders' => ['borderStyle' => Border::BORDER_THIN],
				        ],
				    ]
				);
				$j++;
	        endforeach;
		}

		$spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(40);
		$spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(30);
		$spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(30);

		// Set fonts
		$spreadsheet->getActiveSheet()->getStyle('A1:C1')->getFont()->setSize(11);
		$spreadsheet->getActiveSheet()->getStyle('A1:C1')->getFont()->setBold(true);

		$spreadsheet->getActiveSheet()->getStyle('A1:C1')->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE);
 		$spreadsheet->getActiveSheet()->getStyle('A1:C1')->getFill()->setFillType(Fill::FILL_SOLID);
		$spreadsheet->getActiveSheet()->getStyle('A1:C1')->getFill()->getStartColor()->setARGB('236e09');

		$spreadsheet->getActiveSheet()->getStyle('A1:C1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
		$spreadsheet->getActiveSheet()->getStyle('A1:C1')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

		$spreadsheet->getActiveSheet()->getRowDimension('1')->setRowHeight(40);
		$spreadsheet->getActiveSheet()->getStyle('A1:C1')->applyFromArray(
		    [
		        'borders' => [
		            'allBorders' => ['borderStyle' => Border::BORDER_THIN],
		        ],
		    ]
		);

		/**
		 * PROMEDIO CUMPLIMIENTO OBJETIVOS ESTRATEGICOS
		 */
		$spreadsheet->createSheet();
		$spreadsheet->setActiveSheetIndex(3);
		$spreadsheet->getActiveSheet()->setTitle('Cump. Objetivos Estrategicos');

		$spreadsheet->getActiveSheet()
							->setCellValue('A1', 'Objetivo Estratégico')
							->setCellValue('B1', 'No. Actividades')
							->setCellValue('C1', 'Promedio de Cumplimiento');

		$arrParam = array();
		$info = $this->general_model->get_objetivos_estrategicos($arrParam);

		if($info){
			$j=2;
	        foreach ($info as $lista):
                $arrParam = array(
                    "numeroObjetivoEstrategico" => $lista["numero_objetivo_estrategico"],
                    "vigencia" => date("Y")
                );
                $nroActividades = $this->general_model->countActividades($arrParam);
				$cumplimiento = $this->general_model->sumCumplimiento($arrParam);
                $promedioCumplimiento = 0;
                if($nroActividades){
                    $promedioCumplimiento = number_format($cumplimiento["cumplimiento"]/$nroActividades,3);
                }
                             
                if(!$promedioCumplimiento){
                    $promedioCumplimiento = 0;
                }
				$spreadsheet->getActiveSheet()
							->setCellValue('A'.$j, $lista['numero_objetivo_estrategico'] . ' ' . $lista['objetivo_estrategico'])
							->setCellValue('B'.$j, $nroActividades)
							->setCellValue('C'.$j, $promedioCumplimiento . '%');

				$spreadsheet->getActiveSheet()->getStyle('A'.$j.':C'.$j)->applyFromArray(
				    [
				        'borders' => [
				            'allBorders' => ['borderStyle' => Border::BORDER_THIN],
				        ],
					    'alignment' => [
					        'wrapText' => TRUE
					    ]
				    ]
				);
				$j++;
	        endforeach;
		}

		$spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(70);
		$spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(30);
		$spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(30);

		// Set fonts
		$spreadsheet->getActiveSheet()->getStyle('A1:C1')->getFont()->setSize(11);
		$spreadsheet->getActiveSheet()->getStyle('A1:C1')->getFont()->setBold(true);

		$spreadsheet->getActiveSheet()->getStyle('A1:C1')->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE);
 		$spreadsheet->getActiveSheet()->getStyle('A1:C1')->getFill()->setFillType(Fill::FILL_SOLID);
		$spreadsheet->getActiveSheet()->getStyle('A1:C1')->getFill()->getStartColor()->setARGB('236e09');

		$spreadsheet->getActiveSheet()->getStyle('A1:C1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
		$spreadsheet->getActiveSheet()->getStyle('A1:C1')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

		$spreadsheet->getActiveSheet()->getRowDimension('1')->setRowHeight(40);
		$spreadsheet->getActiveSheet()->getStyle('A1:C1')->applyFromArray(
		    [
		        'borders' => [
		            'allBorders' => ['borderStyle' => Border::BORDER_THIN],
		        ],
		    ]
		);
		
		$spreadsheet->setActiveSheetIndex(0);

		$writer = new Xlsx($spreadsheet);
		$writer->save('php://output');
	}

    /**
     * Cargo modal - Listado comentarios POA
     * @since 11/07/2022
     */
    public function cargarModalComentariosPOA() 
	{
			header("Content-Type: text/plain; charset=utf-8"); //Para evitar problemas de acentos
			
			$data["numeroActividad"] = $this->input->post("numeroActividad");

			$arrParam = array(
				"numeroActividad" => $data["numeroActividad"],
				"filtroEstado" => "5,6"
			);
            $data['information'] = $this->general_model->get_historial_actividad($arrParam);
			$this->load->view("comentarios_poa_modal", $data);
    }

    /**
     * Cargo modal - Fprmulario de evaluación
     * @since 14/07/2022
     */
    public function cargarModalEvaluacionOCI() 
	{
			header("Content-Type: text/plain; charset=utf-8"); //Para evitar problemas de acentos
			
			$data["numeroActividad"] = $this->input->post("numeroActividad");
			$data["numeroSemestre"] = $this->input->post("numeroSemestre");

			$arrParam = array("numeroActividad" => $data["numeroActividad"]);
			$data['infoActividad'] = $this->general_model->get_actividades_full($arrParam);
			
			$arrParam["numeroSemestre"] = $data["numeroSemestre"];
            $data['information'] = $this->general_model->get_evaluacion_oci($arrParam);
			$this->load->view("evaluacion_modal", $data);
    }

	/**
	 * Guardar evaluación
	 * @since 14/07/2022
     * @author BMOTTAG
	 */
	public function guardar_evaluacion()
	{			
			header('Content-Type: application/json');
			$data = array();
			$numeroActividad = $this->input->post('hddId');
			$numeroSemestre = $this->input->post('numeroSemestre');
			$msj = "Se guardo la información!";

			$arrParam = array(
				"numeroActividad" => $numeroActividad,
				"numeroSemestre" => $numeroSemestre,
				"observacion" => $this->input->post('observacion'),
				"calificacion" => $this->input->post('calificacion'),
				"comentario" => $this->input->post('comentario')
			);
			if ($this->general_model->updateEvaluacionOCI($arrParam)) 
			{	
				//actualizo el estado del trimestre de la actividad
				$this->general_model->addEvaluacionOCI($arrParam);
				$data["result"] = true;		
				$this->session->set_flashdata('retornoExito', '<strong>Correcto!</strong> ' . $msj);
			} else {
				$data["result"] = "error";
				$this->session->set_flashdata('retornoError', '<strong>Error!</strong> Ask for help');
			}
		
			echo json_encode($data);
    }
}