<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//require_once(FCPATH.'vendor/autoload.php');

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

            if($_POST && $_POST["id_dependencia"] != ""){
                $arrParam["idDependencia"] = $_POST["id_dependencia"];
            }
            $data['listaTodasActividades'] = $this->general_model->get_numero_actividades_full_by_dependencia($arrParam);

            if($_POST && $_POST["numero_actividad"] != ""){
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

			$data["view"] = "resumen_general";
			$this->load->view("layout_calendar", $data);
	}

	/**
	 * RESUMEN
	 * @since 24/06/2022
	 */
	public function enlace()
	{	
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

            if($_POST && $_POST["id_dependencia"] != ""){
                $arrParam["idDependencia"] = $_POST["id_dependencia"];
            }
            $data['listaTodasActividades'] = $this->general_model->get_numero_actividades_full_by_dependencia($arrParam);

            if($_POST && $_POST["numero_actividad"] != ""){
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
					"numeroActividad" => $lista["numero_actividad"],
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
						"numeroActividad" => $lista["numero_actividad"],
						"numeroTrimestre" => $numeroTrimestre
					);
					$sumaProgramadoTrimestreX = $this->general_model->sumarProgramado($arrParam);
					$sumaEjecutadoTrimestreX = $this->general_model->sumarEjecutado($arrParam);

					if($sumaProgramadoTrimestreX['programado'] > 0){
						$cumplimientoX = round($sumaEjecutadoTrimestreX['ejecutado'] / $sumaProgramadoTrimestreX['programado'] * 100, 2);
					}
				}

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

			endforeach;

			$data["result"] = true;
			$data["mensaje"] = $msj;
			$this->session->set_flashdata('retornoExito', $msj);
			echo json_encode($data);
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
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition:attachment;filename=cuadro_mando.xlsx');

		$arrParam = array();
		$listaActividades = $this->general_model->get_actividades_full($arrParam);

		$spreadsheet = new Spreadsheet();
		$spreadsheet->getActiveSheet()->setTitle('Consolidado General');
		$spreadsheet->getActiveSheet(0)->setCellValue('A2', 'PRUEBAS DE COBBINAR CELDAS');
		$spreadsheet->getActiveSheet()->mergeCells('A2:D2');

		$spreadsheet->getActiveSheet(0)
							->setCellValue('A3', 'Dependencia')
							->setCellValue('B3', 'Proyecto de Inversión')
							->setCellValue('C3', 'Meta proyecto de inversión')
							->setCellValue('D3', 'Presupuesto Meta Proyecto de Inversión o Funcionamiento')
							->setCellValue('E3', 'Propósito')
							->setCellValue('F3', 'Logro')
							->setCellValue('G3', 'Programa Estratégico')
							->setCellValue('H3', 'Meta PDD')
							->setCellValue('I3', 'Estrategias')
							->setCellValue('J3', 'Objetivo Estratégico')
							->setCellValue('K3', 'Proceso de Calidad')
							->setCellValue('L3', 'No. Actividad')
							->setCellValue('M3', 'Actividad')
							->setCellValue('N3', 'Meta Plan Operativo Anual')
							->setCellValue('O3', 'Unidad de Medida')
							->setCellValue('P3', 'Nombre del indicador')
							->setCellValue('Q3', 'Tipo de indicador')
							->setCellValue('R3', 'Responsable')
							->setCellValue('S3', 'Ponderación')
							->setCellValue('T3', 'Fecha inicial')
							->setCellValue('U3', 'Fecha Final')
							->setCellValue('V3', 'Enero')
							->setCellValue('W3', 'Febrero')
							->setCellValue('X3', 'Marzo')
							->setCellValue('Y3', 'Abril')
							->setCellValue('Z3', 'Mayo')
							->setCellValue('AA3', 'Junio')
							->setCellValue('AB3', 'Julio')
							->setCellValue('AC3', 'Agosto')
							->setCellValue('AD3', 'Septiembre')
							->setCellValue('AE3', 'Octubre')
							->setCellValue('AF3', 'Noviembre')
							->setCellValue('AG3', 'Diciembre')
							->setCellValue('AH3', 'Trimestre I')
							->setCellValue('AI3', 'Trimestre II')
							->setCellValue('AJ3', 'Trimestre III')
							->setCellValue('AK3', 'Trimestre IV')
							->setCellValue('AL3', 'Avance POA');

		$j=4;
		if($listaActividades){
			foreach ($listaActividades as $lista):
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
							->setCellValue('L'.$j, $lista['numero_actividad'])
							->setCellValue('M'.$j, $lista['descripcion_actividad'])
							->setCellValue('N'.$j, $lista['meta_plan_operativo_anual'])
							->setCellValue('O'.$j, $lista['unidad_medida'])
							->setCellValue('P'.$j, $lista['nombre_indicador'])
							->setCellValue('Q'.$j, $tipo_indicador)
							->setCellValue('R'.$j, $lista['area_responsable'])
							->setCellValue('S'.$j, $lista['ponderacion'] . '%')
							->setCellValue('T'.$j, $lista['mes_inicial'])
							->setCellValue('U'.$j, $lista['mes_final'])
							->setCellValue('V'.$j, $lista['mes_final'])
							->setCellValue('W'.$j, $lista['mes_final'])
							->setCellValue('X'.$j, $lista['mes_final'])
							->setCellValue('Y'.$j, $lista['mes_final'])
							->setCellValue('Z'.$j, $lista['mes_final'])
							->setCellValue('AA'.$j, $lista['mes_final'])
							->setCellValue('AB'.$j, $lista['mes_final'])
							->setCellValue('AC'.$j, $lista['mes_final'])
							->setCellValue('AD'.$j, $lista['mes_final'])
							->setCellValue('AE'.$j, $lista['mes_final'])
							->setCellValue('AF'.$j, $lista['mes_final'])
							->setCellValue('AG'.$j, $lista['mes_final'])
							->setCellValue('AH'.$j, $lista['trimestre_1'])
							->setCellValue('AI'.$j, $lista['trimestre_2'])
							->setCellValue('AJ'.$j, $lista['trimestre_3'])
							->setCellValue('AK'.$j, $lista['trimestre_4'])
							->setCellValue('AL'.$j, $lista['avance_poa']);
					$j++;
			endforeach;
		}

		// Set column widths							  
		$spreadsheet->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
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
		$spreadsheet->getActiveSheet()->getColumnDimension('Y')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('Z')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('AA')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('AB')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('AC')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('AD')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('AE')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('AF')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('AG')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('AH')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('AI')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('AJ')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('AK')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('AL')->setAutoSize(true);

/*
		// Set fonts	
		$spreadsheet->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
		$spreadsheet->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);
		$spreadsheet->getActiveSheet()->getStyle('A3:AL3')->getFont()->setBold(true);
		$spreadsheet->getActiveSheet()->getStyle('A3:AL3')->getFont()->getColor()->setARGB(Color::COLOR_WHITE);
 		$spreadsheet->getActiveSheet()->getStyle('A3:AL3')->getFill()->setFillType(Fill::FILL_SOLID);
		$spreadsheet->getActiveSheet()->getStyle('A3:AL3')->getFill()->getStartColor()->setARGB('86B659');

*/

		

/*

$spreadsheet->getActiveSheet()->getStyle('A3:AL3')->applyFromArray(
    [
        'borders' => [
            'top' => ['borderStyle' => Border::BORDER_MEDIUM],
            'left' => ['borderStyle' => Border::BORDER_MEDIUM],
            'right' => ['borderStyle' => Border::BORDER_MEDIUM],
            'bottom' => ['borderStyle' => Border::BORDER_MEDIUM],
        ],
    ]
);

*/
		$spreadsheet->createSheet();
		$spreadsheet->setActiveSheetIndex(1)->setCellValue('A1', 'Pruebas de hojas');
		$spreadsheet->getActiveSheet()->setTitle('Objetivo Estratégico');

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
}