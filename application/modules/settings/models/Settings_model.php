<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	class Settings_model extends CI_Model {

	    
		/**
		 * Verify if the user already exist by the social insurance number
		 * @author BMOTTAG
		 * @since  8/11/2016
		 * @review 10/12/2020
		 */
		public function verifyUser($arrData) 
		{
				if (array_key_exists("idUser", $arrData)) {
					$this->db->where('id_user !=', $arrData["idUser"]);
				}			

				$this->db->where($arrData["column"], $arrData["value"]);
				$query = $this->db->get("usuarios");

				if ($query->num_rows() >= 1) {
					return true;
				} else{ return false; }
		}
		
		/**
		 * Add/Edit USER
		 * @since 8/11/2016
		 */
		public function saveUser() 
		{
				$idUser = $this->input->post('hddId');
				
				$data = array(
					'first_name' => $this->input->post('firstName'),
					'last_name' => $this->input->post('lastName'),
					'log_user' => $this->input->post('user'),
					'movil' => $this->input->post('movilNumber'),
					'email' => $this->input->post('email'),
					'fk_id_user_role' => $this->input->post('id_role'),
					'fk_id_dependencia_u' => $this->input->post('idDependencia')
				);	

				//revisar si es para adicionar o editar
				if ($idUser == '') {
					$data['state'] = 0;//si es para adicionar se coloca estado inicial como usuario nuevo
					$data['password'] = 'be52d7c1a5e18013492be5fd8ff5f898';//Jardin2021
					$query = $this->db->insert('usuarios', $data);
				} else {
					$data['state'] = $this->input->post('state');
					$this->db->where('id_user', $idUser);
					$query = $this->db->update('usuarios', $data);
				}
				if ($query) {
					return true;
				} else {
					return false;
				}
		}
		
	    /**
	     * Reset user´s password
	     * @author BMOTTAG
	     * @since  20/3/2021
	     */
	    public function resetEmployeePassword($arrData)
		{
				$passwd = md5($arrData['passwd']);
				$data = array(
					'password' => $passwd,
					'state' => 0
				);
				$this->db->where('id_user', $arrData['idUser']);
				$query = $this->db->update('usuarios', $data);

				if ($query) {
					return true;
				} else {
					return false;
				}
	    }

	    /**
	     * Update user´s password
	     * @author BMOTTAG
	     * @since  8/11/2016
	     */
	    public function updatePassword()
		{
				$idUser = $this->input->post("hddId");
				$newPassword = $this->input->post("inputPassword");
				$passwd = str_replace(array("<",">","[","]","*","^","-","'","="),"",$newPassword); 
				$passwd = md5($passwd);
				
				$data = array(
					'password' => $passwd
				);

				$this->db->where('id_user', $idUser);
				$query = $this->db->update('usuarios', $data);

				if ($query) {
					return true;
				} else {
					return false;
				}
	    }
		
		/**
		 * Add/Edit PROYECTO
		 * @since 15/04/2022
		 */
		public function saveProyecto() 
		{
				$idProyecto = $this->input->post('hddId');
				
				$data = array(
					'numero_proyecto_inversion' => $this->input->post('numero_proyecto_inversion'),
					'nombre_proyecto_inversion' => $this->input->post('proyecto')
				);
				
				//revisar si es para adicionar o editar
				if ($idProyecto == '') {
					$query = $this->db->insert('proyecto_inversion', $data);		
				} else {
					$this->db->where('id_proyecto_inversion', $idProyecto);
					$query = $this->db->update('proyecto_inversion', $data);
				}
				if ($query) {
					return true;
				} else {
					return false;
				}
		}

		/**
		 * Add/Edit OBJETIVO ESTRATEGICO
		 * @since 15/04/2022
		 */
		public function saveEstrategia() 
		{
				$idObjetivo= $this->input->post('hddId');
				
				$data = array(
					'estrategia' => $this->input->post('estrategia'),
					'descripcion_estrategia' => $this->input->post('descripcion')
				);
				
				//revisar si es para adicionar o editar
				if ($idObjetivo == '') {
					$query = $this->db->insert('estrategias', $data);		
				} else {
					$this->db->where('id_estrategia', $idObjetivo);
					$query = $this->db->update('estrategias', $data);
				}
				if ($query) {
					return true;
				} else {
					return false;
				}
		}

		/**
		 * Add/Edit PORPOSITOS
		 * @since 15/04/2022
		 */
		public function saveProposito() 
		{
				$idProposito = $this->input->post('hddId');
				
				$data = array(
					'numero_proposito' => $this->input->post('numero_proposito'),
					'proposito' => $this->input->post('proposito')
				);
				
				//revisar si es para adicionar o editar
				if ($idProposito == '') {
					$query = $this->db->insert('propositos', $data);		
				} else {
					$this->db->where('id_proposito', $idProposito);
					$query = $this->db->update('propositos', $data);
				}
				if ($query) {
					return true;
				} else {
					return false;
				}
		}

		/**
		 * Add/Edit OBJETIVO ESTRATEGICO
		 * @since 15/04/2022
		 */
		public function saveLogro() 
		{
				$idLogro= $this->input->post('hddId');
				
				$data = array(
					'numero_logro' => $this->input->post('numero_logro'),
					'logro' => $this->input->post('logro')
				);
				
				//revisar si es para adicionar o editar
				if ($idLogro == '') {
					$query = $this->db->insert('logros', $data);		
				} else {
					$this->db->where('id_logros', $idLogro);
					$query = $this->db->update('logros', $data);
				}
				if ($query) {
					return true;
				} else {
					return false;
				}
		}

		/**
		 * Add/Edit OBJETIVO ESTRATEGICO
		 * @since 15/04/2022
		 */
		public function saveProgramas() 
		{
				$idPrograma= $this->input->post('hddId');
				
				$data = array(
					'numero_programa_estrategico' => $this->input->post('numero_programa_estrategico'),
					'programa_estrategico' => $this->input->post('programa_estrategico')
				);
				
				//revisar si es para adicionar o editar
				if ($idPrograma == '') {
					$query = $this->db->insert('programa_estrategico', $data);		
				} else {
					$this->db->where('id_programa_estrategico', $idPrograma);
					$query = $this->db->update('programa_estrategico', $data);
				}
				if ($query) {
					return true;
				} else {
					return false;
				}
		}

		/**
		 * Add/Edit OBJETIVO ESTRATEGICO
		 * @since 15/04/2022
		 */
		public function saveMetasPDD() 
		{
				$idObjetivo= $this->input->post('hddId');
				
				$data = array(
					'numero_meta_pdd' => $this->input->post('numero_meta_pdd'),
					'meta_pdd' => $this->input->post('meta_pdd')
				);
				
				//revisar si es para adicionar o editar
				if ($idObjetivo == '') {
					$query = $this->db->insert('meta_pdd ', $data);		
				} else {
					$this->db->where('id_meta_pdd', $idObjetivo);
					$query = $this->db->update('meta_pdd ', $data);
				}
				if ($query) {
					return true;
				} else {
					return false;
				}
		}

		/**
		 * Add/Edit ODS
		 * @since 16/04/2022
		 */
		public function saveODS() 
		{
				$idODS= $this->input->post('hddId');
				
				$data = array(
					'numero_ods' => $this->input->post('numero_ods'),
					'ods' => $this->input->post('ods')
				);
				
				//revisar si es para adicionar o editar
				if ($idODS == '') {
					$query = $this->db->insert('ods ', $data);		
				} else {
					$this->db->where('id_ods', $idODS);
					$query = $this->db->update('ods ', $data);
				}
				if ($query) {
					return true;
				} else {
					return false;
				}
		}

		/**
		 * Add/Edit ODS
		 * @since 16/04/2022
		 */
		public function saveMetasProyectos() 
		{
				$idMetaProyecto= $this->input->post('hddId');
				$vigencia= $this->input->post('vigencia');
				$numeroProyecto= $this->input->post('numeroProyecto');
				$numero_meta_proyecto= $this->input->post('numero_meta_proyecto');
				$nu_meta_proyecto = $vigencia . "-" . $numeroProyecto . "-" . $numero_meta_proyecto;
				
				$data = array(
					'numero_meta_proyecto' => $numero_meta_proyecto,
					'nu_meta_proyecto' => $nu_meta_proyecto,
					'fk_numero_proyecto' => $numeroProyecto,
					'meta_proyecto' => $this->input->post('meta_proyecto'),
					'presupuesto_meta' => $this->input->post('presupuesto_meta'),
					'vigencia_meta_proyecto' => $vigencia,
					'programado_meta_proyecto' => $this->input->post('programado_meta_proyecto'),
					'unidad_meta_proyecto' => $this->input->post('unidad_meta'),
					'fk_id_tipologia' => $this->input->post('id_tipologia')
				);
				
				//revisar si es para adicionar o editar
				if ($idMetaProyecto == '') {
					$query = $this->db->insert('meta_proyecto_inversion ', $data);		
				} else {
					$this->db->where('id_meta_proyecto_inversion', $idMetaProyecto);
					$query = $this->db->update('meta_proyecto_inversion', $data);
				}
				if ($query) {
					return true;
				} else {
					return false;
				}
		}

		/**
		 * Add/Edit objetivos_estrategicos
		 * @since 26/04/2022
		 */
		public function saveObjetivo() 
		{
				$idObjetivoEstrategico = $this->input->post('hddId');
				
				$data = array(
					'fk_id_estrategia' => $this->input->post('idEstrategia'),
					'numero_objetivo_estrategico' => $this->input->post('numero_objetivo_estrategico'),
					'objetivo_estrategico' => $this->input->post('objetivo_estrategico')
				);
				
				//revisar si es para adicionar o editar
				if ($idObjetivoEstrategico == '') {
					$query = $this->db->insert('objetivos_estrategicos', $data);		
				} else {
					$this->db->where('id_objetivo_estrategico', $idObjetivoEstrategico);
					$query = $this->db->update('objetivos_estrategicos', $data);
				}
				if ($query) {
					return true;
				} else {
					return false;
				}
		}

		/**
		 * Guardar cuadro base
		 * @since 16/04/2022
		 */
		public function savePlanEstrategico() 
		{
				$numeroObjetivoEstrategico = $this->input->post('hddObjetivoEstrategico');
				$idCuadroBase = $this->input->post('hddIdCuadroBase');
		
				$data = array(
					'fk_numero_proyecto_inversion' => $this->input->post('id_proyecto_inversion'),
					'fk_nu_meta_proyecto_inversion' => $this->input->post('id_meta_proyecto_inversion'),
					'fk_numero_proposito' => $this->input->post('id_proposito'),
					'fk_numero_logro' => $this->input->post('id_logros'),
					'fk_numero_programa' => $this->input->post('id_programa_sp'),
					'fk_numero_programa_estrategico' => $this->input->post('id_programa_estrategico'),
					'fk_numero_meta_pdd' => $this->input->post('id_meta_pdd'),
					'fk_numero_ods' => $this->input->post('id_ods'),
					'fk_id_dimension' => $this->input->post('id_dimension')
				);

				//revisar si es para adicionar o editar
				if ($idCuadroBase == 'x') {
					$data['fk_numero_objetivo_estrategico'] = $numeroObjetivoEstrategico;
					$query = $this->db->insert('cuadro_base', $data);
				} else {
					$this->db->where('id_cuadro_base', $idCuadroBase);
					$query = $this->db->update('cuadro_base', $data);
				}

				if ($query) {
					return true;
				} else {
					return false;
				}
		}

		/**
		 * Add/Edit AREA RESPINSABLE
		 * @since 1/06/2022
		 */
		public function saveAreaResponsable() 
		{
				$idAreaResponsable = $this->input->post('hddId');
				
				$data = array(
					'area_responsable' => $this->input->post('area_responsable')
				);
				
				//revisar si es para adicionar o editar
				if ($idAreaResponsable == '') {
					$query = $this->db->insert('param_area_responsable', $data);		
				} else {
					$this->db->where('id_area_responsable', $idAreaResponsable);
					$query = $this->db->update('param_area_responsable', $data);
				}
				if ($query) {
					return true;
				} else {
					return false;
				}
		}

		/**
		 * Add/Edit META objetivos_estrategicos
		 * @since 20/06/2022
		 */
		public function saveMetaObjetivo() 
		{
				$idMetaObjetivoEstrategico = $this->input->post('hddId');
				
				$data = array(
					'fk_numero_objetivo_estrategico' => $this->input->post('numeroObjetivoEstrategico'),
					'meta' => $this->input->post('meta')
				);
				
				//revisar si es para adicionar o editar
				if ($idMetaObjetivoEstrategico == '') {
					$query = $this->db->insert('objetivos_estrategicos_metas', $data);		
				} else {
					$this->db->where('id_meta', $idMetaObjetivoEstrategico);
					$query = $this->db->update('objetivos_estrategicos_metas', $data);
				}
				if ($query) {
					return true;
				} else {
					return false;
				}
		}

		/**
		 * Add/Edit INDICADORE objetivos_estrategicos
		 * @since 20/06/2022
		 */
		public function saveIndicadorObjetivo() 
		{
				$idIndicadorObjetivoEstrategico = $this->input->post('hddId');
				
				$data = array(
					'fk_numero_objetivo_estrategico' => $this->input->post('numeroObjetivoEstrategico'),
					'indicador' => $this->input->post('indicador')
				);
				
				//revisar si es para adicionar o editar
				if ($idIndicadorObjetivoEstrategico == '') {
					$query = $this->db->insert('objetivos_estrategicos_indicadores', $data);		
				} else {
					$this->db->where('id_indicador', $idIndicadorObjetivoEstrategico);
					$query = $this->db->update('objetivos_estrategicos_indicadores', $data);
				}
				if ($query) {
					return true;
				} else {
					return false;
				}
		}

		/**
		 * Add/Edit RESULTADO objetivos_estrategicos
		 * @since 20/06/2022
		 */
		public function saveResultadoObjetivo() 
		{
				$idResultadoObjetivoEstrategico = $this->input->post('hddId');
				
				$data = array(
					'fk_numero_objetivo_estrategico' => $this->input->post('numeroObjetivoEstrategico'),
					'resultado' => $this->input->post('resultado')
				);
				
				//revisar si es para adicionar o editar
				if ($idResultadoObjetivoEstrategico == '') {
					$query = $this->db->insert('objetivos_estrategicos_resultados', $data);		
				} else {
					$this->db->where('id_resultado', $idResultadoObjetivoEstrategico);
					$query = $this->db->update('objetivos_estrategicos_resultados', $data);
				}
				if ($query) {
					return true;
				} else {
					return false;
				}
		}

		/**
		 * Eliminar registros de la tabla actividad_historial,actividad_estado, actividad_ejecucion, actividades
		 * @since  20/06/2022
		 */
		public function eliminarRegistrosActividades()
		{
				$sql = "TRUNCATE actividad_historial;";
				$query = $this->db->query($sql);
			
				$sql = "TRUNCATE actividad_estado";
				$query = $this->db->query($sql);

				$sql = "TRUNCATE actividad_ejecucion";
				$query = $this->db->query($sql);

				$sql = "DELETE FROM actividades";
				$query = $this->db->query($sql);
				
				$sql = "ALTER TABLE actividades AUTO_INCREMENT=1";
				$query = $this->db->query($sql);

				if ($query) {
					return true;
				} else {
					return false;
				}
		}

		/**
		 * Eliminar registros de la tabla objetivos_estrategicos_metas
		 * @since  20/06/2022
		 */
		public function eliminarMetasObjetivos()
		{
				$sql = "TRUNCATE objetivos_estrategicos_metas";
				$query = $this->db->query($sql);
				
				if ($query) {
					return true;
				} else {
					return false;
				}
		}

		/**
		 * Eliminar registros de la tabla eliminarIndicadoresObjetivos
		 * @since  20/06/2022
		 */
		public function eliminarIndicadoresObjetivos()
		{
				$sql = "TRUNCATE objetivos_estrategicos_indicadores";
				$query = $this->db->query($sql);
				
				if ($query) {
					return true;
				} else {
					return false;
				}
		}

		/**
		 * Eliminar registros de la tabla objetivos_estrategicos_resultados
		 * @since  20/06/2022
		 */
		public function eliminarResultadosObjetivos()
		{
				$sql = "TRUNCATE objetivos_estrategicos_resultados";
				$query = $this->db->query($sql);
				
				if ($query) {
					return true;
				} else {
					return false;
				}
		}

		/**
		 * Cargar informacion 
		 * @since 14/8/2017
		 */
		public function cargar_actividades($lista) 
		{
				$query = $this->db->insert('actividades', $lista);

				if ($query) {
					return true;
				} else {
					return false;
				}
		}

		/**
		 * Cargar el mensaje de POA a la tabla de actividad estado
		 * @since 30/6/2022
		 */
		public function cargar_mensaje_poa($lista) 
		{
				$data = array(
					'mensaje_poa_trimestre_1' => $lista["mensaje_poa"]
				);
				$this->db->where('fk_numero_actividad', $lista["numero_actividad"]);
				$query = $this->db->update('actividad_estado', $data);

				if ($query) {
					return true;
				} else {
					return false;
				}
		}

		/**
		 * Cargar Plan Institucional
		 * @since 11/7/2022
		 */
		public function cargar_plan_institucional($lista) 
		{
				$data = array(
					'plan_archivos' => $lista["plan_archivos"],
					'plan_adquisiciones' => $lista["plan_adquisiciones"],
					'plan_vacantes' => $lista["plan_vacantes"],
					'plan_recursos' => $lista["plan_recursos"],
					'plan_talento' => $lista["plan_talento"],
					'plan_capacitacion' => $lista["plan_capacitacion"],
					'plan_incentivos' => $lista["plan_incentivos"],
					'plan_trabajo' => $lista["plan_trabajo"],
					'plan_anticorrupcion' => $lista["plan_anticorrupcion"],
					'plan_tecnologia' => $lista["plan_tecnologia"],
					'plan_riesgos' => $lista["plan_riesgos"],
					'plan_informacion' => $lista["plan_informacion"]
				);
				$this->db->where('numero_actividad', $lista["numero_actividad"]);
				$query = $this->db->update('actividades', $data);

				if ($query) {
					return true;
				} else {
					return false;
				}
		}

		/**
		 * Cargar informacion 
		 * @since 14/8/2017
		 */
		public function cargar_actividades_estados($lista) 
		{
				$data = array(
					'fk_numero_actividad' => $lista["numero_actividad"],
					'estado_trimestre_1' => 0,
					'estado_trimestre_2' => 0,
					'estado_trimestre_3' => 0,
					'estado_trimestre_4' => 0
				);

				$query = $this->db->insert('actividad_estado', $data);
				if ($query) {
					return true;
				} else {
					return false;
				}
		}

		/**
		 * Cargar informacion 
		 * @since 14/8/2017
		 */
		public function cargar_actividades_ejecucion($lista) 
		{
				$query = $this->db->insert('actividad_ejecucion', $lista);

				if ($query) {
					return true;
				} else {
					return false;
				}
		}

		/**
		 * Cargar informacion 
		 * @since 14/8/2017
		 */
		public function cargar_metas_objetivos_estrategicos($lista) 
		{
				$query = $this->db->insert('objetivos_estrategicos_metas', $lista);
				if ($query) {
					return true;
				} else {
					return false;
				}
		}

		/**
		 * Cargar informacion 
		 * @since 14/8/2017
		 */
		public function cargar_indicadores_objetivos_estrategicos($lista) 
		{
				$query = $this->db->insert('objetivos_estrategicos_indicadores', $lista);
				if ($query) {
					return true;
				} else {
					return false;
				}
		}

		/**
		 * Cargar informacion 
		 * @since 14/8/2017
		 */
		public function cargar_resultados_objetivos_estrategicos($lista) 
		{
				$query = $this->db->insert('objetivos_estrategicos_resultados', $lista);
				if ($query) {
					return true;
				} else {
					return false;
				}
		}

		/**
		 * IMPORTAR ACTIVIDAD
		 * @since 24/06/2022
		 */
		public function saveImportarActividad() 
		{
				$idActividad = $this->input->post('id_actividad');
				$data = array(
					'fk_id_cuadro_base' => $this->input->post('hddIdCuadroBase')
				);
				$this->db->where('id_actividad', $idActividad);
				$query = $this->db->update('actividades', $data);

				if ($query) {
					return true;
				} else {
					return false;
				}
		}

		/**
		 * Add/Edit Proposito
		 * @since 24/07/2022
		 */
		public function savePropositosXVigencia() 
		{
				$idPropositoVigencia= $this->input->post('hddId');
				$vigencia= $this->input->post('vigencia');
				$numeroProposito= $this->input->post('proposito');
				$nu_proposito_vigencia = $vigencia . "-" . $numeroProposito;
				
				$data = array(
					'nu_proposito_vigencia' => $nu_proposito_vigencia,
					'fk_numero_proposito' => $numeroProposito,
					'vigencia_proposito' => $vigencia,
					'recurso_programado_proposito' => $this->input->post('recurso_programado_proposito')
				);
				
				//revisar si es para adicionar o editar
				if ($idPropositoVigencia == '') {
					$query = $this->db->insert('proposito_x_vigencia ', $data);		
				} else {
					$this->db->where('id_proposito_vigencia', $idPropositoVigencia);
					$query = $this->db->update('proposito_x_vigencia', $data);
				}
				if ($query) {
					return true;
				} else {
					return false;
				}
		}

		/**
		 * Add/Edit Proyecto Vigencia
		 * @since 24/07/2022
		 */
		public function saveProyectosXVigencia() 
		{
				$idProyectoVigencia= $this->input->post('hddId');
				$vigencia= $this->input->post('vigencia');
				$numeroProyecto= $this->input->post('proyecto');
				$nu_proyecto_vigencia = $vigencia . "-" . $numeroProyecto;
				
				$data = array(
					'nu_proyecto_vigencia' => $nu_proyecto_vigencia,
					'fk_numero_proyecto_inversion' => $numeroProyecto,
					'vigencia_proyecto' => $vigencia,
					'recurso_programado_proyecto' => $this->input->post('recurso_programado_proyecto')
				);
				
				//revisar si es para adicionar o editar
				if ($idProyectoVigencia == '') {
					$query = $this->db->insert('proyecto_inversion_x_vigencia', $data);		
				} else {
					$this->db->where('id_proyecto_vigencia', $idProyectoVigencia);
					$query = $this->db->update('proyecto_inversion_x_vigencia', $data);
				}
				if ($query) {
					return true;
				} else {
					return false;
				}
		}

		/**
		 * Add/Edit Meta PDD Vigencia
		 * @since 24/07/2022
		 */
		public function saveMetasPDDXVigencia() 
		{
				$idMetaPDDVigencia= $this->input->post('hddId');
				$vigencia= $this->input->post('vigencia');
				$numeroMetaPDD= $this->input->post('metaPDD');
				$nu_meta_pdd_vigencia = $vigencia . "-" . $numeroMetaPDD;
				
				$data = array(
					'nu_meta_pdd_vigencia ' => $nu_meta_pdd_vigencia,
					'fk_numero_meta_pdd' => $numeroMetaPDD,
					'vigencia_meta_pdd' => $vigencia,
					'recurso_programado_meta_pdd' => $this->input->post('recurso_programado_meta_pdd')
				);
				
				//revisar si es para adicionar o editar
				if ($idMetaPDDVigencia == '') {
					$query = $this->db->insert('meta_pdd_x_vigencia', $data);		
				} else {
					$this->db->where('id_meta_pdd_vigencia', $idMetaPDDVigencia);
					$query = $this->db->update('meta_pdd_x_vigencia', $data);
				}
				if ($query) {
					return true;
				} else {
					return false;
				}
		}

		/**
		 * Add/Edit PROGRAMA SEGPLAN
		 * @since 24/74/2022
		 */
		public function saveProgramaSP() 
		{
				$idPrograma = $this->input->post('hddId');
				
				$data = array(
					'numero_programa' => $this->input->post('numero_programa'),
					'programa' => $this->input->post('programa')
				);
				
				//revisar si es para adicionar o editar
				if ($idPrograma == '') {
					$query = $this->db->insert('programa', $data);		
				} else {
					$this->db->where('id_programa', $idPrograma);
					$query = $this->db->update('programa', $data);
				}
				if ($query) {
					return true;
				} else {
					return false;
				}
		}

		/**
		 * Add/Edit Meta PDD Vigencia
		 * @since 24/07/2022
		 */
		public function saveProgramasSPXVigencia() 
		{
				$idProgramaSPVigencia= $this->input->post('hddId');
				$vigencia= $this->input->post('vigencia');
				$numeroProgramaSP= $this->input->post('programa');
				$nu_programa_sp_vigencia = $vigencia . "-" . $numeroProgramaSP;
				
				$data = array(
					'nu_programa_vigencia' => $nu_programa_sp_vigencia,
					'fk_numero_programa' => $numeroProgramaSP,
					'vigencia_programa' => $vigencia,
					'recurso_programado_programa' => $this->input->post('recurso_programado_programa')
				);
				
				//revisar si es para adicionar o editar
				if ($idProgramaSPVigencia == '') {
					$query = $this->db->insert('programa_x_vigencia', $data);		
				} else {
					$this->db->where('id_programa_vigencia', $idProgramaSPVigencia);
					$query = $this->db->update('programa_x_vigencia', $data);
				}
				if ($query) {
					return true;
				} else {
					return false;
				}
		}

		/**
		 * Add/Edit INDICADOR SEGPLAN
		 * @since 26/74/2022
		 */
		public function saveIndicadorSP() 
		{
				$idIndicador = $this->input->post('hddId');
		
				$data = array(
					'numero_indicador' => $this->input->post('numero_indicador'),
					'indicador_sp' => $this->input->post('indicador_sp')
				);
				
				//revisar si es para adicionar o editar
				if ($idIndicador == '') {
					$query = $this->db->insert('indicadores', $data);		
				} else {
					$this->db->where('id_indicador_sp', $idIndicador);
					$query = $this->db->update('indicadores', $data);
				}
				if ($query) {
					return true;
				} else {
					return false;
				}
		}

		/**
		 * Add/Edit INDICADOR Vigencia
		 * @since 26/07/2022
		 */
		public function saveIndicadorSPXVigencia() 
		{
				$idIndicadorSPVigencia= $this->input->post('hddId');
				$vigencia= $this->input->post('vigencia');
				$numeroIndicadorSP= $this->input->post('indicador');
				$nu_indicador_sp_vigencia = $vigencia . "-" . $numeroIndicadorSP;
				
				$data = array(
					'nu_indicador_vigencia' => $nu_indicador_sp_vigencia,
					'fk_numero_indicador' => $numeroIndicadorSP,
					'vigencia_indicador' => $vigencia,
					'programado_indicador_pdd' => $this->input->post('programado_indicador_pdd'),
					'programado_indicador_real' => $this->input->post('programado_indicador_real')
				);
				
				//revisar si es para adicionar o editar
				if ($idIndicadorSPVigencia == '') {
					$query = $this->db->insert('indicadores_x_vigencia', $data);		
				} else {
					$this->db->where('id_indicador_vigencia', $idIndicadorSPVigencia);
					$query = $this->db->update('indicadores_x_vigencia', $data);
				}
				if ($query) {
					return true;
				} else {
					return false;
				}
		}
		
		/**
		 * Add/Edit INDICADOR PMR
		 * @since 28/74/2022
		 */
		public function saveIndicadorPMR() 
		{
				$idIndicador = $this->input->post('hddId');
		
				$data = array(
					'numero_indicador_pmr' => $this->input->post('numero_indicador'),
					'indicador_pmr' => $this->input->post('indicador_pmr')
				);
				
				//revisar si es para adicionar o editar
				if ($idIndicador == '') {
					$query = $this->db->insert('indicadores_pmr', $data);		
				} else {
					$this->db->where('id_indicador_pmr', $idIndicador);
					$query = $this->db->update('indicadores_pmr', $data);
				}
				if ($query) {
					return true;
				} else {
					return false;
				}
		}
		
		/**
		 * Consulta lista tablero PMR
		 * @since 31/10/2022
		 * @author AOCUBILLOSA
		 */
		public function get_tablero_pmr($arrData) {
				$this->db->select();
				$this->db->join('param_objetivos_pmr O', 'O.numero_objetivo_pmr = T.fk_numero_objetivo_pmr', 'INNER');
				$this->db->join('param_elementos_pep_pmr E', 'E.id_elemento_pep_pmr = T.fk_id_elemento_pep_pmr', 'INNER');
				$this->db->join('param_productos_pmr P', 'P.numero_producto_pmr = T.fk_numero_producto_pmr', 'INNER');
				$this->db->join('proyecto_inversion PI', 'PI.numero_proyecto_inversion = T.fk_numero_proyecto_inversion', 'INNER');
				$this->db->join('indicadores_pmr I', 'I.numero_indicador_pmr = T.fk_numero_indicador_pmr', 'INNER');
				$this->db->join('param_unidad_medida_pmr U', 'U.id_unidad_medida_pmr = T.fk_id_unidad_medida_pmr', 'INNER');
				$this->db->join('param_naturaleza_pmr N', 'N.id_naturaleza_pmr = T.fk_id_naturaleza_pmr', 'INNER');
				$this->db->join('param_periodicidad_pmr PP', 'PP.id_periodicidad_pmr = T.fk_id_periodicidad_pmr', 'INNER');
				if (array_key_exists("id_pmr", $arrData)) {
					$this->db->where('T.id_pmr', $arrData['id_pmr']);
				}
				$this->db->order_by('fk_numero_indicador_pmr', 'asc');
				$query = $this->db->get('tablero_pmr T');
				if ($query->num_rows() > 0) {
					return $query->result_array();
				} else {
					return false;
				}
		}

		/**
		 * Consulta lista de actividades por indicador PMR
		 * @since 31/10/2022
		 * @author AOCUBILLOSA
		 */
		public function get_actividades($indicador_pmr) {		
				$this->db->select('numero_actividad');
				$this->db->where('fk_numero_indicador_pmr', $indicador_pmr);
				$this->db->order_by('numero_actividad', 'asc');
				$query = $this->db->get('actividades');
				if ($query->num_rows() > 0) {
					return $query->result_array();
				} else {
					return false;
				}
		}

		/**
		 * Sumar metas de las actividades por indicador PMR
		 * @author AOCUBILLOSA
		 * @since  31/10/2022
		 */
		public function sumatoria_metas($indicador_pmr)
		{
				$this->db->select_sum('meta_plan_operativo_anual');
				$this->db->where('fk_numero_indicador_pmr', $indicador_pmr);
				$this->db->order_by('fk_numero_indicador_pmr', 'asc');
				$query = $this->db->get('actividades');
				if ($query->num_rows() > 0) {
					return $query->result_array();
				} else {
					return false;
				}
		}

		/**
		 * Sumar avance de las actividades por indicador PMR y mes
		 * @author AOCUBILLOSA
		 * @since  31/10/2022
		 */
		public function sumatoria_mes($indicador_pmr, $mes)
		{
				$this->db->select_sum('ejecutado');
				$this->db->join('actividad_ejecucion E', 'A.numero_actividad = E.fk_numero_actividad', 'INNER');
				$this->db->where('fk_numero_indicador_pmr', $indicador_pmr);
				$this->db->where('fk_id_mes', $mes);
				$this->db->order_by('fk_numero_indicador_pmr', 'asc');
				$query = $this->db->get('actividades A');
				if ($query->num_rows() > 0) {
					return $query->result_array();
				} else {
					return false;
				}
		}

		/**
		 * Guardar tablero PMR
		 * @author AOCUBILLOSA
		 * @since 31/10/2022
		 */
		public function saveTableroPMR() 
		{
				$idPMR = $this->input->post('hddIdPMR');
				$data = array(
					'fk_numero_objetivo_pmr' => $this->input->post('id_objetivo_pmr'),
					'fk_id_elemento_pep_pmr' => $this->input->post('id_elemento_pep_pmr'),
					'fk_numero_producto_pmr' => $this->input->post('id_producto_pmr'),
					'fk_numero_proyecto_inversion' => $this->input->post('id_proyecto_inversion'),
					'fk_numero_indicador_pmr' => $this->input->post('id_indicador_pmr'),
					'fk_id_unidad_medida_pmr' => $this->input->post('id_unidad_medida_pmr'),
					'fk_id_naturaleza_pmr' => $this->input->post('id_naturaleza_pmr'),
					'fk_id_periodicidad_pmr' => $this->input->post('id_periodicidad_pmr')
				);
				//revisar si es para adicionar o editar
				if ($idPMR == 'x') {
					$query = $this->db->insert('tablero_pmr', $data);
				} else {
					$this->db->where('id_pmr', $idPMR);
					$query = $this->db->update('tablero_pmr', $data);
				}
				if ($query) {
					return true;
				} else {
					return false;
				}
		}
	    
	}