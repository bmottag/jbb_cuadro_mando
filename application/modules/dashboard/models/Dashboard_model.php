<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	class Dashboard_model extends CI_Model {


		/**
		 * Guardar Actividades
		 * @since 15/04/2022
		 */
		public function guardarActividad() 
		{
				$idActividad = $this->input->post('hddId');
				$idCuadroBase = $this->input->post('hddIdCuadroBase');
				$idUser = $this->session->userdata("id");
		
				$data = array(
					'numero_actividad' => $this->input->post('numero_actividad'),
					'descripcion_actividad' => $this->input->post('descripcion'),
					'meta_plan_operativo_anual' => $this->input->post('meta_plan'),
					'unidad_medida' => $this->input->post('unidad_medida'),
					'nombre_indicador' => $this->input->post('nombre_indicador'),
					'tipo_indicador' => $this->input->post('tipo_indicador'),
					'ponderacion ' => $this->input->post('ponderacion'),
					'fecha_inicial' => $this->input->post('fecha_inicial'),
					'fecha_final' => $this->input->post('fecha_final'),
					'fk_id_proceso_calidad' => $this->input->post('proceso_calidad'),
					'fk_id_area_responsable' => $this->input->post('id_responsable')
				);	

				//revisar si es para adicionar o editar
				if ($idActividad == '') 
				{
					$data['fk_id_cuadro_base'] = $idCuadroBase;
					$query = $this->db->insert('actividades', $data);
					$idActividad = $this->db->insert_id();	
				} else {
					$this->db->where('id_actividad', $idActividad);
					$query = $this->db->update('actividades', $data);
				}
				if ($query) {
					return $idActividad;
				} else {
					return false;
				}
		}

		/**
		 * Adicionar registros de programacion por mes
	     * @since 23/04/2022
	     * @author BMOTTAG
		 */
		public function save_programa_actividad($idActividad) 
		{
			//add the new record
			$query = 1;
			$mesInicial = $this->input->post('fecha_inicial');
			$mesFinal = $this->input->post('fecha_final');
			$idUser = $this->session->userdata("id");

			for ($i = $mesInicial; $i <= $mesFinal; $i++) {
					$data = array(
						'fk_id_mes' => $i,
						'fk_id_actividad' => $idActividad,
						'fk_id_user' => $idUser,
						'fecha_creacion' => date("Y-m-d G:i:s")
					);	
					$query = $this->db->insert('actividad_ejecucion', $data);
			}

			if($query) {
				return true;
			} else{
				return false;
			}
		}	

		/**
		 * Guardar Ejecucion Actividades
		 * @since 17/04/2022
		 */
		public function guardarProgramado() 
		{
				$idActividad = $this->input->post('hddIdActividad');
				$idEjecucion = $this->input->post('hddId');
				$idUser = $this->session->userdata("id");
		
				$data = array(
					'programado' => $this->input->post('programado'),
					'fecha_creacion' => date("Y-m-d G:i:s")
				);	

				//revisar si es para adicionar o editar
				if ($idEjecucion == '') 
				{
					$data['fk_id_mes'] = $this->input->post('mes');
					$data['fk_id_actividad'] = $idActividad;
					$data['fk_id_user'] = $idUser;
					$query = $this->db->insert('actividad_ejecucion', $data);
				} else {
					$this->db->where('id_ejecucion_actividad', $idEjecucion);
					$query = $this->db->update(' actividad_ejecucion ', $data);
				}
				if ($query) {
					return true;
				} else {
					return false;
				}
		}

		/**
		 * Guardar Programacion Actividades
		 * @since 23/04/2022
		 */
		public function guardarProgramacion() 
		{
				//update states
				$query = 1;
				
				$datos = $this->input->post('form');
				if($datos) {
					$tot = count($datos['id']);
					for ($i = 0; $i < $tot; $i++) 
					{					
						$data = array(
							'programado' => $datos['programado'][$i],
							'fecha_actualizacion' => date("Y-m-d G:i:s")
						);
						$this->db->where('id_ejecucion_actividad', $datos['id'][$i]);
						$query = $this->db->update('actividad_ejecucion', $data);
					}
				}
				
				if ($query){
					return true;
				} else{
					return false;
				}
		}

		/**
		 * Guardar Ejecucion Actividades
		 * @since 17/04/2022
		 */
		public function guardarEjecucion() 
		{
				$idEjecucion = $this->input->post('hddId');
				$idUser = $this->session->userdata("id");
		
				$data = array(
					'fk_id_responsable' => $idUser,
					'ejecutado' => $this->input->post('ejecutado'),
					'descripcion_actividades' => $this->input->post('descripcion'),
					'evidencias' => $this->input->post('evidencia'),
					'fecha_actualizacion' => date("Y-m-d G:i:s")
				);	
				$this->db->where('id_ejecucion_actividad ', $idEjecucion);
				$query = $this->db->update(' actividad_ejecucion ', $data);

				if ($query) {
					return true;
				} else {
					return false;
				}
		}

		/**
		 * Guardar estado del Trimestre
		 * @since 17/04/2022
		 */
		public function guardarTrimestre($banderaActividad, $estadoActividad, $numeroActividad, $cumplimientoTrimestre, $avancePOA, $numeroTrimestre) 
		{	
				$data = array(
					'trimestre_' . $numeroTrimestre => $cumplimientoTrimestre,
					'estado_trimestre_' . $numeroTrimestre => $estadoActividad,
					'avance_poa' => $avancePOA
				);	

				//revisar si es para adicionar o editar
				if ($banderaActividad) 
				{
					$this->db->where('fk_numero_actividad', $numeroActividad);
					$query = $this->db->update('actividad_estado', $data);
				} else {
					$data['fk_id_actividad'] = $idActividad;
					$query = $this->db->insert('actividad_estado', $data);
				}
				if ($query) {
					return true;
				} else {
					return false;
				}
		}

		/**
		 * Add estado actividad
		 * @since 24/04/2022
		 */
		public function addHistorialActividad($arrData) 
		{
			$idUser = $this->session->userdata("id");
			
			$data = array(
				'fk_numero_actividad' => $arrData["numeroActividad"],
				'fk_id_usuario' => $idUser,
				'numero_trimestre' => $arrData["numeroTrimestre"],
				'fecha_cambio' => date("Y-m-d G:i:s"),
				'observacion' => $arrData["observacion"],
				'fk_id_estado' => $arrData["estado"]
			);
			
			$query = $this->db->insert('actividad_historial', $data);

			if ($query) {
				return true;
			} else {
				return false;
			}
		}

		/**
		 * Update estado de la actividad
		 * @since 24/12/2022
		 */
		public function updateEstadoActividad($arrData)
		{			
			$columna = 'estado_trimestre_' . $arrData["numeroTrimestre"];
			$data = array(
				$columna => $arrData["estado"]
			);			
			$this->db->where('fk_id_actividad', $arrData["idActividad"]);
			$query = $this->db->update('actividad_estado', $data);

			if ($query) {
				return true;
			} else {
				return false;
			}
		}

		/**
		 * Contar actividades por dependencia
		 * @author BMOTTAG
		 * @since  8/12/2016
		 */
		public function countActividades($arrData)
		{

				$sql = "SELECT count(id_actividad) CONTEO";
				$sql.= " FROM  actividades A";
				$sql.= " INNER JOIN cuadro_base C ON C.id_cuadro_base = A.fk_id_cuadro_base";
				$sql.= " INNER JOIN meta_proyecto_inversion M ON M.nu_meta_proyecto = C.fk_nu_meta_proyecto_inversion";
				$sql.= " WHERE 1=1 ";
				if (array_key_exists("idDependencia", $arrData)) {
					$sql.= " AND C.fk_id_dependencia = '". $arrData["idDependencia"]. "'";
					if (array_key_exists("vigencia", $arrData)) {
						$sql.= " AND M.vigencia_meta_proyecto = '". $arrData["vigencia"]. "'";
					}
				}


				$query = $this->db->query($sql);
				$row = $query->row();
				return $row->CONTEO;
		}

		/**
		 * Sumatoria avance POA
		 * @since 17/5/2022
		 */
		public function sumAvance($arrData) 
		{		
			$this->db->select_sum('avance_poa');
			$this->db->join('actividades A', 'A.numero_actividad = E.fk_numero_actividad', 'INNER');
			$this->db->join('cuadro_base C', 'C.id_cuadro_base = A.fk_id_cuadro_base', 'INNER');
			$this->db->join('meta_proyecto_inversion M', 'M.nu_meta_proyecto = C.fk_nu_meta_proyecto_inversion', 'INNER');
			if (array_key_exists("idDependencia", $arrData)) {
				$this->db->where('fk_id_dependencia', $arrData["idDependencia"]);
			}
			if (array_key_exists("vigencia", $arrData)) {
				$this->db->where('vigencia_meta_proyecto', $arrData["vigencia"]);
			}
			
			$query = $this->db->get('actividad_estado E');

			if ($query->num_rows() > 0) {
				return $query->row_array();
			} else {
				return false;
			}
		}
		
		
		
		
	    
	}