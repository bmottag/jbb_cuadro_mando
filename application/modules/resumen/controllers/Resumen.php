<?php
defined('BASEPATH') OR exit('No direct script access allowed');

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
				"vigencia" => date("Y")
			);
			$data['nroActividades'] = $this->general_model->countActividades($arrParam);

			$arrParam = array(
				"table" => "param_estados",
				"order" => "valor",
				"id" => "x"
			);
			$data['listaEstados'] = $this->general_model->get_basic_search($arrParam);

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
					"avancePOA" => $avancePOA
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
}