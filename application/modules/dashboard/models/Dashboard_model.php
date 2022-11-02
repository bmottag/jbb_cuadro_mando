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
					'fk_id_area_responsable' => $this->input->post('id_responsable'),
					'fk_id_dependencia' => $this->input->post('id_dependencia'),
					'plan_archivos' => $this->input->post('plan_archivos'),
					'plan_adquisiciones' => $this->input->post('plan_adquisiciones'),
					'plan_vacantes' => $this->input->post('plan_vacantes'),
					'plan_recursos' => $this->input->post('plan_recursos'),
					'plan_talento' => $this->input->post('plan_talento'),
					'plan_capacitacion' => $this->input->post('plan_capacitacion'),
					'plan_incentivos' => $this->input->post('plan_incentivos'),
					'plan_trabajo' => $this->input->post('plan_trabajo'),
					'plan_anticorrupcion' => $this->input->post('plan_anticorrupcion'),
					'plan_tecnologia' => $this->input->post('plan_tecnologia'),
					'plan_riesgos' => $this->input->post('plan_riesgos'),
					'plan_informacion' => $this->input->post('plan_informacion'),
					'fk_id_indicador_pmr' => $this->input->post('id_indicador_pmr')
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
		public function save_programa_actividad($numeroActividad) 
		{
			//add the new record
			$query = 1;
			$mesInicial = $this->input->post('fecha_inicial');
			$mesFinal = $this->input->post('fecha_final');
			$idUser = $this->session->userdata("id");

			for ($i = $mesInicial; $i <= $mesFinal; $i++) {
					$data = array(
						'fk_id_mes' => $i,
						'fk_numero_actividad' => $numeroActividad,
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
				$numeroActividad = $this->input->post('hddNumeroActividad');
				$idEjecucion = $this->input->post('hddId');
				$idUser = $this->session->userdata("id");
		
				$data = array(
					'programado' => $this->input->post('programado'),
					'fecha_creacion' => date("Y-m-d G:i:s")
				);	

				//revisar si es para adicionar o editar
				if ($idEjecucion == '') 
				{
					$data['fk_numero_actividad'] = $numeroActividad;
					$data['fk_id_user'] = $idUser;
					$data['fk_id_mes'] = $this->input->post('mes');
					$query = $this->db->insert('actividad_ejecucion', $data);
				} else {
					$this->db->where('id_ejecucion_actividad', $idEjecucion);
					$query = $this->db->update(' actividad_ejecucion', $data);
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
							'ejecutado' => $datos['ejecutado'][$i],
							'descripcion_actividades' => $datos['descripcion'][$i],
							'evidencias' => $datos['evidencia'][$i],
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
		 * @since 18/06/2022
		 */
		public function guardarEjecucion() 
		{
				//update states
				$query = 1;
				$idUser = $this->session->userdata("id");
				
				$datos = $this->input->post('form');
				if($datos) {
					$tot = count($datos['id']);
					for ($i = 0; $i < $tot; $i++) 
					{					
						$data = array(
							'fk_id_responsable' => $idUser,
							'ejecutado' => $datos['ejecutado'][$i],
							'descripcion_actividades' => $datos['descripcion'][$i],
							'evidencias' => $datos['evidencia'][$i],
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
		 * Guardar estado del Trimestre
		 * @since 17/04/2022
		 */
		public function guardarTrimestre($banderaActividad, $estadoActividad, $numeroActividad, $cumplimientoTrimestre, $avancePOA, $numeroTrimestre) 
		{	
				$data = array(
					'estado_trimestre_' . $numeroTrimestre => $estadoActividad,
				);	

				//revisar si es para adicionar o editar
				if ($banderaActividad) 
				{
					$this->db->where('fk_numero_actividad', $numeroActividad);
					$query = $this->db->update('actividad_estado', $data);
				} else {
					$data['fk_numero_actividad'] = $numeroActividad;
					$query = $this->db->insert('actividad_estado', $data);
				}
				if ($query) {
					return true;
				} else {
					return false;
				}
		}


		
		
		
		
	    
	}