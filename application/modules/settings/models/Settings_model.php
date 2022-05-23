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
					'nombre_proyecto_inversion' => $this->input->post('proyecto'),
					'vigencia' => $this->input->post('vigencia')
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
		public function saveObjetivo() 
		{
				$idObjetivo= $this->input->post('hddId');
				
				$data = array(
					'objetivo_estrategico' => $this->input->post('objetivo_estrategico'),
					'descripcion_objetivo_estrategico' => $this->input->post('descripcion')
				);
				
				//revisar si es para adicionar o editar
				if ($idObjetivo == '') {
					$query = $this->db->insert('objetivos_estrategicos', $data);		
				} else {
					$this->db->where('id_objetivo_estrategico', $idObjetivo);
					$query = $this->db->update('objetivos_estrategicos ', $data);
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
				
				$data = array(
					'numero_meta_proyecto' => $this->input->post('numero_meta_proyecto'),
					'meta_proyecto' => $this->input->post('meta_proyecto'),
					'presupuesto_meta' => $this->input->post('presupuesto_meta'),
					'vigencia_meta_proyecto' => $this->input->post('vigencia'),
					'valor_meta_proyecto' => $this->input->post('valor_meta'),
					'unidad_meta_proyecto' => $this->input->post('unidad_meta')
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
		 * Add/Edit ESTRATEGIA
		 * @since 26/04/2022
		 */
		public function saveEstrategia() 
		{
				$idEstrategia = $this->input->post('hddId');
				
				$data = array(
					'fk_id_objetivo_estrategico' => $this->input->post('idObjetivo'),
					'numero_estrategia' => $this->input->post('numero_estrategia'),
					'estrategia' => $this->input->post('estrategia')
				);
				
				//revisar si es para adicionar o editar
				if ($idEstrategia == '') {
					$query = $this->db->insert('estrategias', $data);		
				} else {
					$this->db->where('id_estrategia', $idEstrategia);
					$query = $this->db->update('estrategias', $data);
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
				$numeroEstrategia = $this->input->post('hddNumeroEstrategia');
				$idCuadroBase = $this->input->post('hddIdCuadroBase');
		
				$data = array(
					'fk_numero_proyecto_inversion' => $this->input->post('id_proyecto_inversion'),
					'fk_nu_meta_proyecto_inversion' => $this->input->post('id_meta_proyecto_inversion'),
					'fk_numero_proposito' => $this->input->post('id_proposito'),
					'fk_numero_logro' => $this->input->post('id_logros'),
					'fk_numero_programa_estrategico' => $this->input->post('id_programa_estrategico'),
					'fk_numero_meta_pdd' => $this->input->post('id_meta_pdd'),
					'fk_numero_ods' => $this->input->post('id_ods'),
					'fk_id_dependencia ' => $this->input->post('id_dependencia')
				);

				//revisar si es para adicionar o editar
				if ($idCuadroBase == 'x') {
					$data['fk_numero_estrategia'] = $numeroEstrategia;
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
		
		
		
	    
	}