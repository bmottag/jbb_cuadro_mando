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

		$img1 = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
		$img1->setPath('images/logo_alcaldia.png');
		$img1->setCoordinates('A2');
		$img1->setOffsetX(20);
		$img1->setOffsetY(10);
		$img1->setWorksheet($spreadsheet->getActiveSheet());

		$img2 = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
		$img2->setPath('images/logo_bogota.png');
		$img2->setCoordinates('J2');
		$img2->setOffsetX(10);
		$img2->setOffsetY(10);
		$img2->setWorksheet($spreadsheet->getActiveSheet());

		$validation = $spreadsheet->getActiveSheet()->getCell('B8')->getDataValidation();
		$validation->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST);
		$validation->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_INFORMATION);
		$validation->setShowDropDown(true);
		$validation->setFormula1('"Oficina Jurídica,Oficina Asesora de Planeación,Subdirección Científica,Subdirección Técnica Operativa,Subdirección Educativa y Cultural,Secretaría General"');

		$spreadsheet->getActiveSheet()->setCellValue('B9','=IF(B8="","Seleccione la dependencia ↑",SUMIF(B14:B200,B8,BA14:BA200))');

		$spreadsheet->getActiveSheet()->getStyle("AH")->getNumberFormat()
					->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_PERCENTAGE_00);
		/*$spreadsheet->getActiveSheet()->getStyle("BK")->getNumberFormat()
					->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_PERCENTAGE_00);
		$spreadsheet->getActiveSheet()->getStyle("BN")->getNumberFormat()
					->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_PERCENTAGE_00);*/
		$spreadsheet->getActiveSheet()->getStyle("BP")->getNumberFormat()
					->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_PERCENTAGE_00);

		$spreadsheet->getActiveSheet()->mergeCells('A2:A6');

		$spreadsheet->getActiveSheet(0)->setCellValue('B2', 'MANUAL DE PROCESOS Y PROCEDIMIENTOS');
		$spreadsheet->getActiveSheet()->mergeCells('B2:I2');

		$spreadsheet->getActiveSheet(0)->setCellValue('B3', 'DYP - DIRECCIONAMIENTO Y PLANEACIÓN');
		$spreadsheet->getActiveSheet()->mergeCells('B3:I3');

		$spreadsheet->getActiveSheet(0)->setCellValue('B4', 'MATRIZ PLAN OPERATIVO ANUAL');
		$spreadsheet->getActiveSheet()->mergeCells('B4:I4');

		$spreadsheet->getActiveSheet(0)->setCellValue('B5', 'Código:');
		$spreadsheet->getActiveSheet()->mergeCells('B5:C5');

		$spreadsheet->getActiveSheet(0)->setCellValue('D5', 'Versión:');
		$spreadsheet->getActiveSheet()->mergeCells('D5:E5');

		$spreadsheet->getActiveSheet(0)->setCellValue('F5', 'Fecha:');
		$spreadsheet->getActiveSheet()->mergeCells('F5:G5');

		$spreadsheet->getActiveSheet(0)->setCellValue('H5', 'Página:');
		$spreadsheet->getActiveSheet()->mergeCells('H5:I5');

		$spreadsheet->getActiveSheet(0)->setCellValue('B6', 'DYP.PR.17.F.01');
		$spreadsheet->getActiveSheet()->mergeCells('B6:C6');

		$spreadsheet->getActiveSheet(0)->setCellValue('D6', '1');
		$spreadsheet->getActiveSheet()->mergeCells('D6:E6');

		$spreadsheet->getActiveSheet(0)->setCellValue('F6', '29/09/2022');
		$spreadsheet->getActiveSheet()->mergeCells('F6:G6');

		$spreadsheet->getActiveSheet(0)->setCellValue('H6', '1 de 6');
		$spreadsheet->getActiveSheet()->mergeCells('H6:I6');

		$spreadsheet->getActiveSheet()->mergeCells('J2:J6');

		$spreadsheet->getActiveSheet(0)->setCellValue('A8', 'Dependencia');

		$spreadsheet->getActiveSheet(0)->setCellValue('A9', 'Avance POA Anual');

		$spreadsheet->getActiveSheet(0)->setCellValue('E8', 'VIGENCIA: 2022');
		$spreadsheet->getActiveSheet()->mergeCells('E8:F8');

		$spreadsheet->getActiveSheet()->mergeCells('A11:A13');

		$spreadsheet->getActiveSheet()->mergeCells('B11:B13');

		$spreadsheet->getActiveSheet(0)->setCellValue('C11', 'Plan de Desarrollo Distrital');
		$spreadsheet->getActiveSheet()->mergeCells('C11:J12');

		$spreadsheet->getActiveSheet(0)->setCellValue('K11', 'Plan Estratégico');
		$spreadsheet->getActiveSheet()->mergeCells('K11:L12');

		$spreadsheet->getActiveSheet()->mergeCells('M11:M13');

		$spreadsheet->getActiveSheet()->mergeCells('N11:N13');

		$spreadsheet->getActiveSheet(0)->setCellValue('O11', 'Planes Institucionales');
		$spreadsheet->getActiveSheet()->mergeCells('O11:Z12');

		$spreadsheet->getActiveSheet(0)->setCellValue('AA11', 'Actividad');
		$spreadsheet->getActiveSheet()->mergeCells('AA11:AB12');

		$spreadsheet->getActiveSheet()->mergeCells('AC11:AC13');
		$spreadsheet->getActiveSheet()->mergeCells('AD11:AD13');
		$spreadsheet->getActiveSheet()->mergeCells('AE11:AE13');
		$spreadsheet->getActiveSheet()->mergeCells('AF11:AF13');
		$spreadsheet->getActiveSheet()->mergeCells('AG11:AG13');
		$spreadsheet->getActiveSheet()->mergeCells('AH11:AH13');

		$spreadsheet->getActiveSheet(0)->setCellValue('AI11', 'Duración');
		$spreadsheet->getActiveSheet()->mergeCells('AI11:AJ12');

		$spreadsheet->getActiveSheet(0)->setCellValue('AK11', 'Ejecución');
		$spreadsheet->getActiveSheet()->mergeCells('AK11:AV12');

		$spreadsheet->getActiveSheet(0)->setCellValue('AW11', 'Estado de la actividad');
		$spreadsheet->getActiveSheet()->mergeCells('AW11:AZ12');

		$spreadsheet->getActiveSheet()->mergeCells('BA11:BA13');

		$spreadsheet->getActiveSheet(0)->setCellValue('BB11', 'Descripción de actividades');
		$spreadsheet->getActiveSheet()->mergeCells('BB11:BE12');

		$spreadsheet->getActiveSheet(0)->setCellValue('BF11', 'Evidencias');
		$spreadsheet->getActiveSheet()->mergeCells('BF11:BI12');

		$spreadsheet->getActiveSheet(0)->setCellValue('BJ11', 'EVALUACIÓN CONTROL INTERNO');
		$spreadsheet->getActiveSheet()->mergeCells('BJ11:BQ11');

		$spreadsheet->getActiveSheet(0)->setCellValue('BJ12', 'Semestre I');
		$spreadsheet->getActiveSheet()->mergeCells('BJ12:BL12');

		$spreadsheet->getActiveSheet(0)->setCellValue('BM12', 'Semestre II');
		$spreadsheet->getActiveSheet()->mergeCells('BM12:BO12');

		$spreadsheet->getActiveSheet(0)->setCellValue('BP12', '% Avance Anual');
		$spreadsheet->getActiveSheet()->mergeCells('BP12:BP13');

		$spreadsheet->getActiveSheet(0)->setCellValue('BQ12', 'Total Evidenciado Evaluación');
		$spreadsheet->getActiveSheet()->mergeCells('BQ12:BQ13');

		$spreadsheet->getActiveSheet(0)
							->setCellValue('A11', 'Año')
							->setCellValue('B11', 'Dependencia')
							->setCellValue('C13', 'Proyecto de Inversión')
							->setCellValue('D13', 'Meta proyecto de inversión')
							->setCellValue('E13', 'Presupuesto Meta Proyecto de Inversión o Funcionamiento')
							->setCellValue('F13', 'Propósito')
							->setCellValue('G13', 'Logro')
							->setCellValue('H13', 'Programa Estratégico')
							->setCellValue('I13', 'Programa')
							->setCellValue('J13', 'Meta PDD')
							->setCellValue('K13', 'Estrategias')
							->setCellValue('L13', 'Objetivo Estratégico')
							->setCellValue('M11', 'Dimensiones MIPG')
							->setCellValue('N11', 'Proceso de Calidad')
							->setCellValue('O13', 'Plan Institucional de Archivos de la Entidad')
							->setCellValue('P13', 'Plan Anual de Adquisiciones')
							->setCellValue('Q13', 'Plan Anual de Vacantes')
							->setCellValue('R13', 'Plan de Previsión de Recursos Humanos')
							->setCellValue('S13', 'Plan Estratégico de Talento Humano')
							->setCellValue('T13', 'Plan Institucional de Capacitación')
							->setCellValue('U13', 'Plan de Incentivos Institucionales')
							->setCellValue('V13', 'Plan de Trabajo Anual en Seguridad y Salud en el Trabajo')
							->setCellValue('W13', 'Plan Anticorrupción y de Atención al Ciudadano')
							->setCellValue('X13', 'Plan Estratégico de Tecnologías de la Información y las Comunicaciones')
							->setCellValue('Y13', 'Plan de Tratamiento de Riesgos de Seguridad y Privacidad de la Información ')
							->setCellValue('Z13', 'Plan de Seguridad y Privacidad de la Información ')
							->setCellValue('AA13', 'No.')
							->setCellValue('AB13', 'Actividad')
							->setCellValue('AC11', 'Meta Plan Operativo Anual')
							->setCellValue('AD11', 'Unidad de Medida')
							->setCellValue('AE11', 'Nombre del indicador')
							->setCellValue('AF11', 'Tipo de indicador')
							->setCellValue('AG11', 'Responsable')
							->setCellValue('AH11', 'Ponderación')
							->setCellValue('AI13', 'Fecha inicial')
							->setCellValue('AJ13', 'Fecha Final')
							->setCellValue('AK13', 'Enero')
							->setCellValue('AL13', 'Febrero')
							->setCellValue('AM13', 'Marzo')
							->setCellValue('AN13', 'Abril')
							->setCellValue('AO13', 'Mayo')
							->setCellValue('AP13', 'Junio')
							->setCellValue('AQ13', 'Julio')
							->setCellValue('AR13', 'Agosto')
							->setCellValue('AS13', 'Septiembre')
							->setCellValue('AT13', 'Octubre')
							->setCellValue('AU13', 'Noviembre')
							->setCellValue('AV13', 'Diciembre')
							->setCellValue('AW13', 'Trimestre I')
							->setCellValue('AX13', 'Trimestre II')
							->setCellValue('AY13', 'Trimestre III')
							->setCellValue('AZ13', 'Trimestre IV')
							->setCellValue('BA11', 'Avance POA')
							->setCellValue('BB13', 'Trimestre I')
							->setCellValue('BC13', 'Trimestre II')
							->setCellValue('BD13', 'Trimestre III')
							->setCellValue('BE13', 'Trimestre IV')
							->setCellValue('BF13', 'Trimestre I')
							->setCellValue('BG13', 'Trimestre II')
							->setCellValue('BH13', 'Trimestre III')
							->setCellValue('BI13', 'Trimestre IV')
							->setCellValue('BJ13', 'Total Evidenciado Evaluación')
							->setCellValue('BK13', '% Cumplimiento Semestre')
							->setCellValue('BL13', 'Observaciones de la Evaluación')
							->setCellValue('BM13', 'Total Evidenciado Evaluación')
							->setCellValue('BN13', '% Cumplimiento Semestre')
							->setCellValue('BO13', 'Observaciones de la Evaluación');

		$j=14;
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

				$spreadsheet->getActiveSheet()->getStyle('A'.$j.':BU'.$j)->applyFromArray(
				    [
					    'alignment' => [
					        'wrapText' => TRUE
					    ]
				    ]
				);

				$spreadsheet->getActiveSheet()->getStyle('E' . $j)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_CURRENCY_USD_SIMPLE);
				$spreadsheet->getActiveSheet()->getStyle('AA' . $j)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
				$spreadsheet->getActiveSheet()
							->setCellValue('A'.$j, $lista['vigencia_meta_proyecto'])
							->setCellValue('B'.$j, $lista['dependencia'])
							->setCellValue('C'.$j, $lista['proyecto_inversion'])
							->setCellValue('D'.$j, $lista['meta_proyecto'])
							->setCellValue('E'.$j, $lista['presupuesto_meta'])
							->setCellValue('F'.$j, $lista['proposito'])
							->setCellValue('G'.$j, $lista['logro'])
							->setCellValue('H'.$j, $lista['programa_estrategico'])
							->setCellValue('I'.$j, $lista['programa'])
							->setCellValue('J'.$j, $lista['meta_pdd'])
							->setCellValue('K'.$j, $lista['estrategia'])
							->setCellValue('L'.$j, $lista['objetivo_estrategico'])
							->setCellValue('M'.$j, $lista['dimension'])
							->setCellValue('N'.$j, $lista['proceso_calidad'])
							->setCellValue('O'.$j, $plan_archivos)
							->setCellValue('P'.$j, $plan_adquisiciones)
							->setCellValue('Q'.$j, $plan_vacantes)
							->setCellValue('R'.$j, $plan_recursos)
							->setCellValue('S'.$j, $plan_talento)
							->setCellValue('T'.$j, $plan_capacitacion)
							->setCellValue('U'.$j, $plan_incentivos)
							->setCellValue('V'.$j, $plan_trabajo)
							->setCellValue('W'.$j, $plan_anticorrupcion)
							->setCellValue('X'.$j, $plan_tecnologia)
							->setCellValue('Y'.$j, $plan_riesgos)
							->setCellValue('Z'.$j, $plan_informacion)
							->setCellValue('AA'.$j, $lista['numero_actividad'])
							->setCellValue('AB'.$j, $lista['descripcion_actividad'])
							->setCellValue('AC'.$j, $lista['meta_plan_operativo_anual'])
							->setCellValue('AD'.$j, $lista['unidad_medida'])
							->setCellValue('AE'.$j, $lista['nombre_indicador'])
							->setCellValue('AF'.$j, $tipo_indicador)
							->setCellValue('AG'.$j, $lista['area_responsable'])
							->setCellValue('AH'.$j, $lista['ponderacion'] . '%')
							->setCellValue('AI'.$j, $lista['mes_inicial'])
							->setCellValue('AJ'.$j, $lista['mes_final']);

				foreach ($infoEjecucion as $ejecucion):
					if($ejecucion['fk_id_mes'] == 1){
						$spreadsheet->getActiveSheet()->setCellValue('AK'.$j, $ejecucion['ejecutado']);
						break;
					}
				endforeach;

				foreach ($infoEjecucion as $ejecucion):
					if($ejecucion['fk_id_mes'] == 2){
						$spreadsheet->getActiveSheet()->setCellValue('AL'.$j, $ejecucion['ejecutado']);
						break;
					}
				endforeach;

				foreach ($infoEjecucion as $ejecucion):
					if($ejecucion['fk_id_mes'] == 3){
						$spreadsheet->getActiveSheet()->setCellValue('AM'.$j, $ejecucion['ejecutado']);
						break;
					}
				endforeach;

				foreach ($infoEjecucion as $ejecucion):
					if($ejecucion['fk_id_mes'] == 4){
						$spreadsheet->getActiveSheet()->setCellValue('AN'.$j, $ejecucion['ejecutado']);
						break;
					}
				endforeach;

				foreach ($infoEjecucion as $ejecucion):
					if($ejecucion['fk_id_mes'] == 5){
						$spreadsheet->getActiveSheet()->setCellValue('AO'.$j, $ejecucion['ejecutado']);
						break;
					}
				endforeach;

				foreach ($infoEjecucion as $ejecucion):
					if($ejecucion['fk_id_mes'] == 6){
						$spreadsheet->getActiveSheet()->setCellValue('AP'.$j, $ejecucion['ejecutado']);
						break;
					}
				endforeach;

				foreach ($infoEjecucion as $ejecucion):
					if($ejecucion['fk_id_mes'] == 7){
						$spreadsheet->getActiveSheet()->setCellValue('AQ'.$j, $ejecucion['ejecutado']);
						break;
					}
				endforeach;

				foreach ($infoEjecucion as $ejecucion):
					if($ejecucion['fk_id_mes'] == 8){
						$spreadsheet->getActiveSheet()->setCellValue('AR'.$j, $ejecucion['ejecutado']);
						break;
					}
				endforeach;

				foreach ($infoEjecucion as $ejecucion):
					if($ejecucion['fk_id_mes'] == 9){
						$spreadsheet->getActiveSheet()->setCellValue('AS'.$j, $ejecucion['ejecutado']);
						break;
					}
				endforeach;

				foreach ($infoEjecucion as $ejecucion):
					if($ejecucion['fk_id_mes'] == 10){
						$spreadsheet->getActiveSheet()->setCellValue('AT'.$j, $ejecucion['ejecutado']);
						break;
					}
				endforeach;

				foreach ($infoEjecucion as $ejecucion):
					if($ejecucion['fk_id_mes'] == 11){
						$spreadsheet->getActiveSheet()->setCellValue('AU'.$j, $ejecucion['ejecutado']);
						break;
					}
				endforeach;

				foreach ($infoEjecucion as $ejecucion):
					if($ejecucion['fk_id_mes'] == 12){
						$spreadsheet->getActiveSheet()->setCellValue('AV'.$j, $ejecucion['ejecutado']);
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
							->setCellValue('AW'.$j, $trimestre_1)
							->setCellValue('AX'.$j, $trimestre_2)
							->setCellValue('AY'.$j, $trimestre_3)
							->setCellValue('AZ'.$j, $trimestre_4)
							->setCellValue('BA'.$j, $avance_poa)
							->setCellValue('BB'.$j, str_replace(array("<br>"),"\n",$lista['descripcion_actividad_trimestre_1']))
							->setCellValue('BC'.$j, str_replace(array("<br>"),"\n",$lista['descripcion_actividad_trimestre_2']))
							->setCellValue('BD'.$j, str_replace(array("<br>"),"\n",$lista['descripcion_actividad_trimestre_3']))
							->setCellValue('BE'.$j, str_replace(array("<br>"),"\n",$lista['descripcion_actividad_trimestre_4']))
							->setCellValue('BF'.$j, str_replace(array("<br>"),"\n",$lista['evidencias_trimestre_1']))
							->setCellValue('BG'.$j, str_replace(array("<br>"),"\n",$lista['evidencias_trimestre_2']))
							->setCellValue('BH'.$j, str_replace(array("<br>"),"\n",$lista['evidencias_trimestre_3']))
							->setCellValue('BI'.$j, str_replace(array("<br>"),"\n",$lista['evidencias_trimestre_4']));

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
							->setCellValue('BJ'.$j, $calificacio1)
							->setCellValue('BK'.$j, '=IFERROR(BJ'.$j.'/($AC$'.$j.'/2),"")')
							->setCellValue('BL'.$j, $observacion1)
							->setCellValue('BM'.$j, $calificacio2)
							->setCellValue('BN'.$j, '=IFERROR(BM'.$j.'/($AC$'.$j.'/2),"")')
							->setCellValue('BO'.$j, $observacion2)
							->setCellValue('BP'.$j, '=IFERROR((BJ'.$j.'+BM'.$j.')/$AC$'.$j.',"")')
							->setCellValue('BQ'.$j, '=IFERROR(BP'.$j.'*AH'.$j.',"")');

				$j++;
			endforeach;
		}

		// Set column widths
		$spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(20);
		$spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(40);
		$spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(40);
		$spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(40);
		$spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(30);
		$spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(35);
		$spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(40);
		$spreadsheet->getActiveSheet()->getColumnDimension('H')->setWidth(40);
		$spreadsheet->getActiveSheet()->getColumnDimension('I')->setWidth(40);
		$spreadsheet->getActiveSheet()->getColumnDimension('J')->setWidth(40);
		$spreadsheet->getActiveSheet()->getColumnDimension('K')->setWidth(20);
		$spreadsheet->getActiveSheet()->getColumnDimension('L')->setWidth(40);
		$spreadsheet->getActiveSheet()->getColumnDimension('M')->setWidth(30);
		$spreadsheet->getActiveSheet()->getColumnDimension('N')->setWidth(30);
		$spreadsheet->getActiveSheet()->getColumnDimension('O')->setWidth(5);
		$spreadsheet->getActiveSheet()->getColumnDimension('P')->setWidth(5);
		$spreadsheet->getActiveSheet()->getColumnDimension('Q')->setWidth(5);
		$spreadsheet->getActiveSheet()->getColumnDimension('R')->setWidth(5);
		$spreadsheet->getActiveSheet()->getColumnDimension('S')->setWidth(5);
		$spreadsheet->getActiveSheet()->getColumnDimension('T')->setWidth(5);
		$spreadsheet->getActiveSheet()->getColumnDimension('U')->setWidth(5);
		$spreadsheet->getActiveSheet()->getColumnDimension('V')->setWidth(5);
		$spreadsheet->getActiveSheet()->getColumnDimension('W')->setWidth(5);
		$spreadsheet->getActiveSheet()->getColumnDimension('X')->setWidth(5);
		$spreadsheet->getActiveSheet()->getColumnDimension('Y')->setWidth(5);
		$spreadsheet->getActiveSheet()->getColumnDimension('Z')->setWidth(5);
		$spreadsheet->getActiveSheet()->getColumnDimension('AA')->setWidth(5);
		$spreadsheet->getActiveSheet()->getColumnDimension('AB')->setWidth(40);
		$spreadsheet->getActiveSheet()->getColumnDimension('AC')->setWidth(20);
		$spreadsheet->getActiveSheet()->getColumnDimension('AD')->setWidth(20);
		$spreadsheet->getActiveSheet()->getColumnDimension('AE')->setWidth(40);
		$spreadsheet->getActiveSheet()->getColumnDimension('AF')->setWidth(20);
		$spreadsheet->getActiveSheet()->getColumnDimension('AG')->setWidth(30);
		$spreadsheet->getActiveSheet()->getColumnDimension('AH')->setWidth(20);
		$spreadsheet->getActiveSheet()->getColumnDimension('AI')->setWidth(20);
		$spreadsheet->getActiveSheet()->getColumnDimension('AJ')->setWidth(20);
		$spreadsheet->getActiveSheet()->getColumnDimension('AK')->setWidth(10);
		$spreadsheet->getActiveSheet()->getColumnDimension('AL')->setWidth(10);
		$spreadsheet->getActiveSheet()->getColumnDimension('AM')->setWidth(10);
		$spreadsheet->getActiveSheet()->getColumnDimension('AN')->setWidth(10);
		$spreadsheet->getActiveSheet()->getColumnDimension('AO')->setWidth(10);
		$spreadsheet->getActiveSheet()->getColumnDimension('AP')->setWidth(10);
		$spreadsheet->getActiveSheet()->getColumnDimension('AQ')->setWidth(10);
		$spreadsheet->getActiveSheet()->getColumnDimension('AR')->setWidth(10);
		$spreadsheet->getActiveSheet()->getColumnDimension('AS')->setWidth(10);
		$spreadsheet->getActiveSheet()->getColumnDimension('AT')->setWidth(10);
		$spreadsheet->getActiveSheet()->getColumnDimension('AU')->setWidth(10);
		$spreadsheet->getActiveSheet()->getColumnDimension('AV')->setWidth(10);
		$spreadsheet->getActiveSheet()->getColumnDimension('AW')->setWidth(20);
		$spreadsheet->getActiveSheet()->getColumnDimension('AX')->setWidth(20);
		$spreadsheet->getActiveSheet()->getColumnDimension('AY')->setWidth(20);
		$spreadsheet->getActiveSheet()->getColumnDimension('AZ')->setWidth(20);
		$spreadsheet->getActiveSheet()->getColumnDimension('BA')->setWidth(20);
		$spreadsheet->getActiveSheet()->getColumnDimension('BB')->setWidth(120);
		$spreadsheet->getActiveSheet()->getColumnDimension('BC')->setWidth(120);
		$spreadsheet->getActiveSheet()->getColumnDimension('BD')->setWidth(120);
		$spreadsheet->getActiveSheet()->getColumnDimension('BE')->setWidth(120);
		$spreadsheet->getActiveSheet()->getColumnDimension('BF')->setWidth(120);
		$spreadsheet->getActiveSheet()->getColumnDimension('BG')->setWidth(120);
		$spreadsheet->getActiveSheet()->getColumnDimension('BH')->setWidth(120);
		$spreadsheet->getActiveSheet()->getColumnDimension('BI')->setWidth(120);
		$spreadsheet->getActiveSheet()->getColumnDimension('BJ')->setWidth(40);
		$spreadsheet->getActiveSheet()->getColumnDimension('BK')->setWidth(40);
		$spreadsheet->getActiveSheet()->getColumnDimension('BL')->setWidth(80);
		$spreadsheet->getActiveSheet()->getColumnDimension('BM')->setWidth(40);
		$spreadsheet->getActiveSheet()->getColumnDimension('BN')->setWidth(40);
		$spreadsheet->getActiveSheet()->getColumnDimension('BO')->setWidth(80);
		$spreadsheet->getActiveSheet()->getColumnDimension('BP')->setWidth(40);
		$spreadsheet->getActiveSheet()->getColumnDimension('BQ')->setWidth(40);

		// Set fonts
		$spreadsheet->getActiveSheet()->getStyle('B2:I4')->getFont()->setSize(14);
		$spreadsheet->getActiveSheet()->getStyle('B5:I6')->getFont()->setSize(11);
		$spreadsheet->getActiveSheet()->getStyle('E8:F8')->getFont()->setSize(11);
		$spreadsheet->getActiveSheet()->getStyle('A8:B9')->getFont()->setSize(11);

		$spreadsheet->getActiveSheet()->getStyle('A11:BQ12')->getFont()->setSize(11);
		$spreadsheet->getActiveSheet()->getStyle('BM12:BQ12')->getFont()->setSize(11);
		$spreadsheet->getActiveSheet()->getStyle('A13:N13')->getFont()->setSize(11);
		$spreadsheet->getActiveSheet()->getStyle('O13:Z13')->getFont()->setSize(8);
		$spreadsheet->getActiveSheet()->getStyle('AA13:BQ13')->getFont()->setSize(11);

		$spreadsheet->getActiveSheet()->getStyle('B2:I4')->getFont()->setBold(true);

		$spreadsheet->getActiveSheet()->getStyle('A11:BQ12')->getFont()->setBold(true);

		$spreadsheet->getActiveSheet()->getStyle('A13:N13')->getFont()->setBold(true);
		$spreadsheet->getActiveSheet()->getStyle('AA13:AJ13')->getFont()->setBold(true);
		$spreadsheet->getActiveSheet()->getStyle('AW13:BQ13')->getFont()->setBold(true);

		$spreadsheet->getActiveSheet()->getStyle('B2:I6')->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE);
 		$spreadsheet->getActiveSheet()->getStyle('B2:I6')->getFill()->setFillType(Fill::FILL_SOLID);
		$spreadsheet->getActiveSheet()->getStyle('B2:I6')->getFill()->getStartColor()->setARGB('236e09');

		$spreadsheet->getActiveSheet()->getStyle('A8:B9')->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE);
 		$spreadsheet->getActiveSheet()->getStyle('A8:B9')->getFill()->setFillType(Fill::FILL_SOLID);
		$spreadsheet->getActiveSheet()->getStyle('A8:B9')->getFill()->getStartColor()->setARGB('236e09');

		$spreadsheet->getActiveSheet()->getStyle('A11:BQ12')->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE);
 		$spreadsheet->getActiveSheet()->getStyle('A11:BQ12')->getFill()->setFillType(Fill::FILL_SOLID);
		$spreadsheet->getActiveSheet()->getStyle('A11:BQ12')->getFill()->getStartColor()->setARGB('236e09');

		$spreadsheet->getActiveSheet()->getStyle('A12:BQ12')->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE);
 		$spreadsheet->getActiveSheet()->getStyle('A12:BQ12')->getFill()->setFillType(Fill::FILL_SOLID);
		$spreadsheet->getActiveSheet()->getStyle('A12:BQ12')->getFill()->getStartColor()->setARGB('236e09');

		$spreadsheet->getActiveSheet()->getStyle('A13:BQ13')->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE);
 		$spreadsheet->getActiveSheet()->getStyle('A13:BQ13')->getFill()->setFillType(Fill::FILL_SOLID);
		$spreadsheet->getActiveSheet()->getStyle('A13:BQ13')->getFill()->getStartColor()->setARGB('86B659');

		$spreadsheet->getActiveSheet()->getStyle('B2:I6')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
		$spreadsheet->getActiveSheet()->getStyle('B2:I6')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

		$spreadsheet->getActiveSheet()->getStyle('A8:B9')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
		$spreadsheet->getActiveSheet()->getStyle('A8:B9')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

		$spreadsheet->getActiveSheet()->getStyle('E8:F8')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
		$spreadsheet->getActiveSheet()->getStyle('E8:F8')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

		$spreadsheet->getActiveSheet()->getStyle('A11:BQ11')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
		$spreadsheet->getActiveSheet()->getStyle('A11:BQ11')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

		$spreadsheet->getActiveSheet()->getStyle('BJ12:BQ12')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
		$spreadsheet->getActiveSheet()->getStyle('BJ12:BQ12')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

		$spreadsheet->getActiveSheet()->getStyle('A13:N13')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
		$spreadsheet->getActiveSheet()->getStyle('A13:N13')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

		$spreadsheet->getActiveSheet()->getStyle('AA13:BQ13')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
		$spreadsheet->getActiveSheet()->getStyle('AA13:BQ13')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

		$spreadsheet->getActiveSheet()->getRowDimension('2')->setRowHeight(20);
		$spreadsheet->getActiveSheet()->getRowDimension('3')->setRowHeight(20);
		$spreadsheet->getActiveSheet()->getRowDimension('4')->setRowHeight(20);
		$spreadsheet->getActiveSheet()->getRowDimension('5')->setRowHeight(20);
		$spreadsheet->getActiveSheet()->getRowDimension('6')->setRowHeight(20);
		$spreadsheet->getActiveSheet()->getRowDimension('8')->setRowHeight(20);
		$spreadsheet->getActiveSheet()->getRowDimension('9')->setRowHeight(20);
		$spreadsheet->getActiveSheet()->getRowDimension('13')->setRowHeight(160);
		$spreadsheet->getActiveSheet()->getStyle('A2:J6')->applyFromArray(
		    [
		        'borders' => [
		            'allBorders' => ['borderStyle' => Border::BORDER_THIN],
		        ],
		    ]
		);

		$spreadsheet->getActiveSheet()->getStyle('A8:B9')->applyFromArray(
		    [
		        'borders' => [
		            'allBorders' => ['borderStyle' => Border::BORDER_THIN],
		        ],
		    ]
		);

		$spreadsheet->getActiveSheet()->getStyle('A11:BQ12')->applyFromArray(
		    [
		        'borders' => [
		            'allBorders' => ['borderStyle' => Border::BORDER_THIN],
		        ],
		    ]
		);

		$spreadsheet->getActiveSheet()->getStyle('A13:BQ13')->applyFromArray(
		    [
		        'borders' => [
		            'allBorders' => ['borderStyle' => Border::BORDER_THIN],
		        ],
		    ]
		);

		$spreadsheet->getActiveSheet()->getStyle('O13:Z13')->applyFromArray(
		    [
			    'alignment' => [
			        'textRotation' => 90,
			        'readOrder' => Alignment::READORDER_RTL,
			        'wrapText' => TRUE
			    ]
		    ]
		);

		$spreadsheet->getActiveSheet()->getStyle('AK13:AV13')->applyFromArray(
		    [
			    'alignment' => [
			        'textRotation' => 90,
			        'readOrder' => Alignment::READORDER_RTL,
			        'wrapText' => TRUE
			    ]
		    ]
		);

		$spreadsheet->getActiveSheet()->getStyle('A13:BQ13')->applyFromArray(
		    [
			    'alignment' => [
			        'wrapText' => TRUE
			    ]
		    ]
		);

		$spreadsheet->getActiveSheet()->getStyle('A11:BQ12')->applyFromArray(
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