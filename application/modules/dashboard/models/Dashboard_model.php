<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	class Dashboard_model extends CI_Model {

		/**
		 * Guardar cuadro base
		 * @since 16/04/2022
		 */
		public function saveCuadroBase() 
		{
				$idEstrategia = $this->input->post('hddIdEstrategia');
		
				$data = array(
					'fk_id_proyecto_inversion' => $this->input->post('id_proyecto_inversion'),
					'fk_id_meta_proyecto_inversion' => $this->input->post('id_meta_proyecto_inversion'),
					'fk_id_proposito' => $this->input->post('id_proposito'),
					'fk_id_logro' => $this->input->post('id_logros'),
					'fk_id_programa_estrategico' => $this->input->post('id_programa_estrategico'),
					'fk_id_meta_pdd' => $this->input->post('id_meta_pdd'),
					'fk_id_ods' => $this->input->post('id_ods'),
					'fk_id_dependencia ' => $this->input->post('id_dependencia')
				);	

				$data['fk_id_estrategia'] = $idEstrategia;
				$query = $this->db->insert('cuadro_base', $data);

				if ($query) {
					return true;
				} else {
					return false;
				}
		}

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
					'fk_id_responsable' => $this->input->post('id_responsable'),
					'ponderacion ' => $this->input->post('ponderacion'),
					'fecha_inicial' => $this->input->post('fecha_inicial'),
					'fecha_final' => $this->input->post('fecha_final')
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
					$data['fk_id_mes'] = $i;
					$data['fk_id_actividad'] = $idActividad;
					$data['fk_id_user'] = $idUser;
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
		public function guardarTrimestre($estadoTrimestre, $idActividad, $cumplimientoTrimestre, $avancePOA, $numeroTrimestre) 
		{	
				$data = array(
					'trimestre_' . $numeroTrimestre => $cumplimientoTrimestre,
					'estado_trimestre_' . $numeroTrimestre => 1,
					'avance_poa' => $avancePOA
				);	

				//revisar si es para adicionar o editar
				if ($estadoTrimestre) 
				{
					$this->db->where('fk_id_actividad', $idActividad);
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
		
		
		
		
	    
	}