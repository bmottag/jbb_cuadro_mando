<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Clase para consultas generales a una tabla
 */
class General_model extends CI_Model {

    /**
     * Consulta BASICA A UNA TABLA
     * @param $TABLA: nombre de la tabla
     * @param $ORDEN: orden por el que se quiere organizar los datos
     * @param $COLUMNA: nombre de la columna en la tabla para realizar un filtro (NO ES OBLIGATORIO)
     * @param $VALOR: valor de la columna para realizar un filtro (NO ES OBLIGATORIO)
     * @since 8/11/2016
     */
    public function get_basic_search($arrData) {
        if ($arrData["id"] != 'x')
            $this->db->where($arrData["column"], $arrData["id"]);
        $this->db->order_by($arrData["order"], "ASC");
        $query = $this->db->get($arrData["table"]);

        if ($query->num_rows() >= 1) {
            return $query->result_array();
        } else
            return false;
    }
	
	/**
	 * Delete Record
	 * @since 25/5/2017
	 */
	public function deleteRecord($arrDatos) 
	{
			$query = $this->db->delete($arrDatos ["table"], array($arrDatos ["primaryKey"] => $arrDatos ["id"]));
			if ($query) {
				return true;
			} else {
				return false;
			}
	}
	
	/**
	 * Update field in a table
	 * @since 11/12/2016
	 */
	public function updateRecord($arrDatos) {
		$data = array(
			$arrDatos ["column"] => $arrDatos ["value"]
		);
		$this->db->where($arrDatos ["primaryKey"], $arrDatos ["id"]);
		$query = $this->db->update($arrDatos ["table"], $data);
		if ($query) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Lista de menu
	 * Modules: MENU
	 * @since 30/3/2020
	 */
	public function get_menu($arrData) 
	{		
		if (array_key_exists("idMenu", $arrData)) {
			$this->db->where('id_menu', $arrData["idMenu"]);
		}
		if (array_key_exists("menuType", $arrData)) {
			$this->db->where('menu_type', $arrData["menuType"]);
		}
		if (array_key_exists("menuState", $arrData)) {
			$this->db->where('menu_state', $arrData["menuState"]);
		}
		if (array_key_exists("columnOrder", $arrData)) {
			$this->db->order_by($arrData["columnOrder"], 'asc');
		}else{
			$this->db->order_by('menu_order', 'asc');
		}
		
		$query = $this->db->get('param_menu');

		if ($query->num_rows() > 0) {
			return $query->result_array();
		} else {
			return false;
		}
	}	

	/**
	 * Lista de roles
	 * Modules: ROL
	 * @since 30/3/2020
	 */
	public function get_roles($arrData) 
	{		
		if (array_key_exists("filtro", $arrData)) {
			$this->db->where('id_role !=', 99);
		}
		if (array_key_exists("idRole", $arrData)) {
			$this->db->where('id_role', $arrData["idRole"]);
		}
		
		$this->db->order_by('role_name', 'asc');
		$query = $this->db->get('param_role');

		if ($query->num_rows() > 0) {
			return $query->result_array();
		} else {
			return false;
		}
	}
	
	/**
	 * User list
	 * @since 30/3/2020
	 */
	public function get_user($arrData) 
	{			
		$this->db->select();
		$this->db->join('param_role R', 'R.id_role = U.fk_id_user_role', 'INNER');
		$this->db->join('param_dependencias D', 'D.id_dependencia = U.fk_id_dependencia_u', 'INNER');
		if (array_key_exists("state", $arrData)) {
			$this->db->where('U.state', $arrData["state"]);
		}
		
		//list without inactive users
		if (array_key_exists("filtroState", $arrData)) {
			$this->db->where('U.state !=', 2);
		}
		
		if (array_key_exists("idUser", $arrData)) {
			$this->db->where('U.id_user', $arrData["idUser"]);
		}
		if (array_key_exists("idRole", $arrData)) {
			$this->db->where('U.fk_id_user_role', $arrData["idRole"]);
		}

		$this->db->order_by("first_name, last_name", "ASC");
		$query = $this->db->get("usuarios U");

		if ($query->num_rows() >= 1) {
			return $query->result_array();
		} else{
			return false;
		}
	}
	
	/**
	 * Lista de enlaces
	 * Modules: MENU
	 * @since 31/3/2020
	 */
	public function get_links($arrData) 
	{		
		$this->db->select();
		$this->db->join('param_menu M', 'M.id_menu = L.fk_id_menu', 'INNER');
		
		if (array_key_exists("idMenu", $arrData)) {
			$this->db->where('fk_id_menu', $arrData["idMenu"]);
		}
		if (array_key_exists("idLink", $arrData)) {
			$this->db->where('id_link', $arrData["idLink"]);
		}
		if (array_key_exists("linkType", $arrData)) {
			$this->db->where('link_type', $arrData["linkType"]);
		}			
		if (array_key_exists("linkState", $arrData)) {
			$this->db->where('link_state', $arrData["linkState"]);
		}
		
		$this->db->order_by('M.menu_order, L.order', 'asc');
		$query = $this->db->get('param_menu_links L');

		if ($query->num_rows() > 0) {
			return $query->result_array();
		} else {
			return false;
		}
	}
	
	/**
	 * Lista de permisos
	 * Modules: MENU
	 * @since 31/3/2020
	 */
	public function get_role_access($arrData) 
	{		
		$this->db->select('P.id_access, P.fk_id_menu, P.fk_id_link, P.fk_id_role, M.menu_name, M.menu_order, M.menu_type, L.link_name, L.link_url, L.order, L.link_icon, L.link_type, R.role_name, R.style');
		$this->db->join('param_menu M', 'M.id_menu = P.fk_id_menu', 'INNER');
		$this->db->join('param_menu_links L', 'L.id_link = P.fk_id_link', 'LEFT');
		$this->db->join('param_role R', 'R.id_role = P.fk_id_role', 'INNER');
		
		if (array_key_exists("idPermiso", $arrData)) {
			$this->db->where('id_access', $arrData["idPermiso"]);
		}
		if (array_key_exists("idMenu", $arrData)) {
			$this->db->where('P.fk_id_menu', $arrData["idMenu"]);
		}
		if (array_key_exists("idLink", $arrData)) {
			$this->db->where('P.fk_id_link', $arrData["idLink"]);
		}
		if (array_key_exists("idRole", $arrData)) {
			$this->db->where('P.fk_id_role', $arrData["idRole"]);
		}
		if (array_key_exists("menuType", $arrData)) {
			$this->db->where('M.menu_type', $arrData["menuType"]);
		}
		if (array_key_exists("linkState", $arrData)) {
			$this->db->where('L.link_state', $arrData["linkState"]);
		}
		if (array_key_exists("menuURL", $arrData)) {
			$this->db->where('M.menu_url', $arrData["menuURL"]);
		}
		if (array_key_exists("linkURL", $arrData)) {
			$this->db->where('L.link_url', $arrData["linkURL"]);
		}		
		
		$this->db->order_by('M.menu_order, L.order', 'asc');
		$query = $this->db->get('param_menu_access P');

		if ($query->num_rows() > 0) {
			return $query->result_array();
		} else {
			return false;
		}
	}
	
	/**
	 * menu list for a role
	 * Modules: MENU
	 * @since 2/4/2020
	 */
	public function get_role_menu($arrData) 
	{		
		$this->db->select('distinct(fk_id_menu), menu_url,menu_icon,menu_name,menu_order');
		$this->db->join('param_menu M', 'M.id_menu = P.fk_id_menu', 'INNER');

		if (array_key_exists("idRole", $arrData)) {
			$this->db->where('P.fk_id_role', $arrData["idRole"]);
		}
		if (array_key_exists("menuType", $arrData)) {
			$this->db->where('M.menu_type', $arrData["menuType"]);
		}
		if (array_key_exists("menuState", $arrData)) {
			$this->db->where('M.menu_state', $arrData["menuState"]);
		}
					
		//$this->db->group_by("P.fk_id_menu"); 
		$this->db->order_by('M.menu_order', 'asc');
		$query = $this->db->get('param_menu_access P');

		if ($query->num_rows() > 0) {
			return $query->result_array();
		} else {
			return false;
		}
	}
	
		/**
		 * Consulta lista de estrategias
		 * @since 15/04/2022
		 */
		public function get_estrategias($arrData) 
		{		
				$this->db->select();
				$this->db->join('objetivos_estrategicos O', 'O.id_objetivo_estrategico = E.fk_id_objetivo_estrategico', 'INNER');
				if (array_key_exists("idEstrategia", $arrData)) {
					$this->db->where('E.id_estrategia', $arrData["idEstrategia"]);
				}
				if (array_key_exists("numeroEstrategia", $arrData)) {
					$this->db->where('E.numero_estrategia like', $arrData["numeroEstrategia"]);
				}
				if (array_key_exists("filtroEstrategias", $arrData)) {
					$where = "E.id_estrategia IN (" . $arrData["filtroEstrategias"] . ")";
					$this->db->where($where);
				}
				$this->db->order_by('numero_estrategia', 'asc');
				$query = $this->db->get('estrategias E');
				if ($query->num_rows() > 0) {
					return $query->result_array();
				} else {
					return false;
				}
		}

		/**
		 * Consulta lista de cuadro de mando
		 * @since 15/04/2022
		 */
		public function get_lista_cuadro_mando($arrData) 
		{		
				$this->db->select("C.*, CONCAT(numero_proyecto_inversion, ' ', nombre_proyecto_inversion) proyecto_inversion, id_meta_proyecto_inversion, CONCAT(numero_meta_proyecto, ' ', meta_proyecto) meta_proyecto, presupuesto_meta, CONCAT(numero_proposito, ' ', proposito) proposito, CONCAT(numero_logro, ' ', logro) logro, CONCAT(numero_programa_estrategico, ' ', programa_estrategico) programa, CONCAT(numero_meta_pdd, ' ', meta_pdd) meta_pdd, CONCAT(numero_ods, ' ', ods) ods");				
				$this->db->join('proyecto_inversion P', 'P.numero_proyecto_inversion = C.fk_numero_proyecto_inversion', 'INNER');
				$this->db->join('meta_proyecto_inversion M', 'M.nu_meta_proyecto = C.fk_nu_meta_proyecto_inversion', 'INNER');
				$this->db->join('propositos X', 'X.numero_proposito = C.fk_numero_proposito', 'INNER');
				$this->db->join('logros L', 'L.numero_logro  = C.fk_numero_logro', 'INNER');
				$this->db->join('programa_estrategico Y', 'Y.numero_programa_estrategico = C.fk_numero_programa_estrategico', 'INNER');
				$this->db->join('meta_pdd Z', 'Z.numero_meta_pdd = C.fk_numero_meta_pdd', 'INNER');
				$this->db->join('ods O', 'O.numero_ods = C.fk_numero_ods', 'INNER');
				if (array_key_exists("idCuadroBase", $arrData)) {
					$this->db->where('C.id_cuadro_base', $arrData["idCuadroBase"]);
				}
				if (array_key_exists("filtroCuadroBase", $arrData)) {
					$where = "C.id_cuadro_base IN (" . $arrData["filtroCuadroBase"] . ")";
					$this->db->where($where);
				}
				if (array_key_exists("numeroEstrategia", $arrData)) {
					$this->db->where('C.fk_numero_estrategia like', $arrData["numeroEstrategia"]);
				}
				if (array_key_exists("idEstrategia", $arrData)) {
					$this->db->where('C.fk_id_estrategia', $arrData["idEstrategia"]);
				}
				if (array_key_exists("idMetaProyecto", $arrData)) {
					$this->db->where('C.fk_id_meta_proyecto_inversion', $arrData["idMetaProyecto"]);
				}
				if (array_key_exists("idProposito", $arrData)) {
					$this->db->where('C.fk_id_proposito', $arrData["idProposito"]);
				}
				if (array_key_exists("idLogro", $arrData)) {
					$this->db->where('C.fk_id_logro', $arrData["idLogro"]);
				}
				if (array_key_exists("idPrograma", $arrData)) {
					$this->db->where('C.fk_id_programa_estrategico', $arrData["idPrograma"]);
				}
				if (array_key_exists("idMetaPDD", $arrData)) {
					$this->db->where('C.fk_id_meta_pdd', $arrData["idMetaPDD"]);
				}
				if (array_key_exists("idODS", $arrData)) {
					$this->db->where('C.fk_id_ods', $arrData["idODS"]);
				}
				$query = $this->db->get('cuadro_base C');
				if ($query->num_rows() > 0) {
					return $query->result_array();
				} else {
					return false;
				}
		}

		/**
		 * Consulta lista de metas
		 * @since 15/04/2022
		 */
		public function get_lista_metas($arrData) 
		{		
				$this->db->select();				
				if (array_key_exists("idEstrategia", $arrData)) {
					$this->db->where('E.fk_id_estrategia', $arrData["idEstrategia"]);
				}
				$query = $this->db->get('estrategias_metas E');
				if ($query->num_rows() > 0) {
					return $query->result_array();
				} else {
					return false;
				}
		}

		/**
		 * Consulta lista de indicadores
		 * @since 15/04/2022
		 */
		public function get_lista_indicadores($arrData) 
		{		
				$this->db->select();				
				if (array_key_exists("idEstrategia", $arrData)) {
					$this->db->where('E.fk_id_estrategia', $arrData["idEstrategia"]);
				}
				$query = $this->db->get('estrategias_indicadores E');
				if ($query->num_rows() > 0) {
					return $query->result_array();
				} else {
					return false;
				}
		}

		/**
		 * Consulta lista de cuadro de resultados
		 * @since 15/04/2022
		 */
		public function get_lista_resultados($arrData) 
		{		
				$this->db->select();				
				if (array_key_exists("idEstrategia", $arrData)) {
					$this->db->where('E.fk_id_estrategia', $arrData["idEstrategia"]);
				}
				$query = $this->db->get('estrategias_resultados E');
				if ($query->num_rows() > 0) {
					return $query->result_array();
				} else {
					return false;
				}
		}

		/**
		 * Consulta lista de actividades
		 * @since 15/04/2022
		 */
		public function get_actividades($arrData) 
		{		
				$userRol = $this->session->userdata("role");
				$idUser = $this->session->userdata("id");
				$idDependencia = $this->session->userdata("dependencia");
							
				$this->db->select('A.*, P.mes mes_inicial, X.mes mes_final, R.area_responsable responsable');
				$this->db->join('param_meses P', 'P.id_mes = A.fecha_inicial', 'INNER');
				$this->db->join('param_meses X', 'X.id_mes = A.fecha_final', 'INNER');
				$this->db->join('param_area_responsable R', 'R.id_area_responsable = A.fk_id_area_responsable', 'INNER');
				$this->db->join('cuadro_base C', 'C.id_cuadro_base = A.fk_id_cuadro_base', 'INNER');

				if (array_key_exists("idActividad", $arrData)) {
					$this->db->where('A.id_actividad', $arrData["idActividad"]);
				}
				if (array_key_exists("numeroActividad", $arrData)) {
					$this->db->where('A.numero_actividad', $arrData["numeroActividad"]);
				}
				if (array_key_exists("idCuadroBase", $arrData)) {
					$this->db->where('A.fk_id_cuadro_base', $arrData["idCuadroBase"]);
				}
				$query = $this->db->get('actividades A');
				if ($query->num_rows() > 0) {
					return $query->result_array();
				} else {
					return false;
				}
		}

		/**
		 * Consulta informacion de la ejecucion de las actividades
		 * @since 17/04/2022
		 */
		public function get_ejecucion_actividades($arrData) 
		{		
				$this->db->select();
				$this->db->join('param_meses P', 'P.id_mes = E.fk_id_mes', 'INNER');
				if (array_key_exists("numeroActividad", $arrData)) {
					$this->db->where('E.fk_numero_actividad', $arrData["numeroActividad"]);
				}
				if (array_key_exists("idMes", $arrData)) {
					$this->db->where('E.fk_id_mes', $arrData["idMes"]);
				}
				if (array_key_exists("numeroTrimestre", $arrData)) {
					$this->db->where('P.numero_trimestre', $arrData["numeroTrimestre"]);
				}
				$this->db->order_by('E.fk_id_mes', 'asc');
				$query = $this->db->get('actividad_ejecucion E');
				if ($query->num_rows() > 0) {
					return $query->result_array();
				} else {
					return false;
				}
		}

		/**
		 * Consulta historial de la actividad
		 * @since 24/04/2022
		 */
		public function get_historial_actividad($arrData) 
		{		
				$this->db->select('H.*, U.first_name, P.estado, P.clase, P.icono');
				$this->db->join('param_estados P', 'P.valor = H.fk_id_estado', 'INNER');
				$this->db->join('usuarios U', 'U.id_user = H.fk_id_usuario', 'INNER');
				if (array_key_exists("numeroActividad", $arrData)) {
					$this->db->where('H.fk_numero_actividad', $arrData["numeroActividad"]);
				}
				if (array_key_exists("numeroTrimestre", $arrData)) {
					$this->db->where('H.numero_trimestre', $arrData["numeroTrimestre"]);
				}
				$this->db->order_by('H.id_historial ', 'desc');
				$query = $this->db->get('actividad_historial H');
				if ($query->num_rows() > 0) {
					return $query->result_array();
				} else {
					return false;
				}
		}


		/**
		 * Sumar programacion para una actividad
		 * @author BMOTTAG
		 * @since  17/04/2022
		 */
		public function sumarProgramado($arrData)
		{
				$this->db->select_sum('programado');
				$this->db->join('param_meses P', 'P.id_mes = E.fk_id_mes', 'INNER');
				$this->db->where('E.fk_numero_actividad', $arrData["numeroActividad"]);
				if (array_key_exists("numeroTrimestre", $arrData)) {
					$this->db->where('P.numero_trimestre', $arrData["numeroTrimestre"]);
				}
				$query = $this->db->get('actividad_ejecucion E');
				if ($query->num_rows() > 0) {
					return $query->row_array();
				} else {
					return false;
				}
		}

		/**
		 * Sumar ejecucion para una actividad
		 * @author BMOTTAG
		 * @since  17/04/2022
		 */
		public function sumarEjecutado($arrData)
		{
				$this->db->select_sum('ejecutado');
				$this->db->join('param_meses P', 'P.id_mes = E.fk_id_mes', 'INNER');
				$this->db->where('E.fk_numero_actividad', $arrData["numeroActividad"]);
				if (array_key_exists("numeroTrimestre", $arrData)) {
					$this->db->where('P.numero_trimestre', $arrData["numeroTrimestre"]);
				}
				$query = $this->db->get('actividad_ejecucion E');
				if ($query->num_rows() > 0) {
					return $query->row_array();
				} else {
					return false;
				}
		}

		/**
		 * Consulta lista de estratgias
		 * @since 23/04/2022
		 */
		public function get_estrategias_by_dependencia($arrData) 
		{					
				$this->db->select('id_estrategia');
				$this->db->join('cuadro_base C', 'C.id_cuadro_base = A.fk_id_cuadro_base', 'INNER');
				$this->db->join('cuadro_base_dependencias T', 'T.fk_id_cuadro_base = C.id_cuadro_base', 'INNER');
				$this->db->join('estrategias E', 'E.numero_estrategia = C.fk_numero_estrategia', 'INNER');
				if (array_key_exists("idDependencia", $arrData)) {
					$this->db->where('T.fk_id_dependencia', $arrData["idDependencia"]);
				}
				$this->db->group_by("E.id_estrategia");
				$query = $this->db->get('actividades A');
				if ($query->num_rows() > 0) {
					return $query->result_array();
				} else {
					return false;
				}
		}

		/**
		 * Consulta lista de cuadro bases
		 * @since 23/04/2022
		 */
		public function get_cuadro_base_by_responsable($arrData) 
		{		
				$userRol = $this->session->userdata("role");
				$idUser = $this->session->userdata("id");
				$idDependencia = $this->session->userdata("dependencia");
				
				$this->db->select('fk_id_cuadro_base');
				$this->db->join('cuadro_base C', 'C.id_cuadro_base = A.fk_id_cuadro_base', 'INNER');
				if($userRol == ID_ROL_SUPERVISOR){
					$this->db->where('C.fk_id_dependencia', $idDependencia);
				}
				if (array_key_exists("numeroEstrategia", $arrData)) {
					$this->db->where('C.fk_numero_estrategia like', $arrData["numeroEstrategia"]);
				}
				$this->db->group_by("A.fk_id_cuadro_base");
				$query = $this->db->get('actividades A');
				if ($query->num_rows() > 0) {
					return $query->result_array();
				} else {
					return false;
				}
		}

		/**
		 * Consulta informacion de los estados de las actividades
		 * @since 24/04/2022
		 */
		public function get_estados_actividades($arrData) 
		{		
				$this->db->select('E.*, P.estado primer_estado, P.clase primer_clase, X.estado segundo_estado, X.clase segundo_clase, Y.estado tercer_estado, Y.clase tercer_clase, Z.estado cuarta_estado, Z.clase cuarta_clase');
				$this->db->join('param_estados P', 'P.valor = E.estado_trimestre_1', 'INNER');
				$this->db->join('param_estados X', 'X.valor = E.estado_trimestre_2', 'INNER');
				$this->db->join('param_estados Y', 'Y.valor = E.estado_trimestre_3', 'INNER');
				$this->db->join('param_estados Z', 'Z.valor = E.estado_trimestre_4', 'INNER');
				if (array_key_exists("numeroActividad", $arrData)) {
					$this->db->where('E.fk_numero_actividad', $arrData["numeroActividad"]);
				}
				$this->db->order_by('E.fk_numero_actividad', 'asc');
				$query = $this->db->get('actividad_estado E');
				if ($query->num_rows() > 0) {
					return $query->result_array();
				} else {
					return false;
				}
		}

		/**
		 * Consulta lista de actividades
		 * @since 30/04/2022
		 */
		public function get_actividades_full($arrData) 
		{		
				$this->db->select("A.*, E.avance_poa, W.mes mes_inicial, K.mes mes_final, C.id_cuadro_base, fk_numero_estrategia, CONCAT(numero_proyecto_inversion, ' ', nombre_proyecto_inversion) proyecto_inversion, meta_proyecto, vigencia_meta_proyecto, CONCAT(numero_proposito, ' ', proposito) proposito, CONCAT(numero_logro, ' ', logro) logro, CONCAT(numero_programa_estrategico, ' ', programa_estrategico) programa, CONCAT(numero_meta_pdd, ' ', meta_pdd) meta_pdd, CONCAT(numero_ods, ' ', ods) ods");
				$this->db->join('actividad_estado E', 'E.fk_numero_actividad  = A.numero_actividad ', 'LEFT');
				$this->db->join('param_meses W', 'W.id_mes = A.fecha_inicial', 'INNER');
				$this->db->join('param_meses K', 'K.id_mes = A.fecha_final', 'INNER');
				$this->db->join('cuadro_base C', 'C.id_cuadro_base = A.fk_id_cuadro_base', 'INNER');
				$this->db->join('proyecto_inversion P', 'P.numero_proyecto_inversion = C.fk_numero_proyecto_inversion', 'INNER');
				$this->db->join('meta_proyecto_inversion M', 'M.nu_meta_proyecto = C.fk_nu_meta_proyecto_inversion', 'INNER');
				$this->db->join('propositos X', 'X.numero_proposito = C.fk_numero_proposito', 'INNER');
				$this->db->join('logros L', 'L.numero_logro  = C.fk_numero_logro', 'INNER');
				$this->db->join('programa_estrategico Y', 'Y.numero_programa_estrategico = C.fk_numero_programa_estrategico', 'INNER');
				$this->db->join('meta_pdd Z', 'Z.numero_meta_pdd = C.fk_numero_meta_pdd', 'INNER');
				$this->db->join('ods O', 'O.numero_ods = C.fk_numero_ods', 'INNER');

				if (array_key_exists("idActividad", $arrData)) {
					$this->db->where('A.id_actividad', $arrData["idActividad"]);
				}
				if (array_key_exists("idCuadroBase", $arrData)) {
					$this->db->where('A.fk_id_cuadro_base', $arrData["idCuadroBase"]);
				}
				if (array_key_exists("numeroEstrategia", $arrData)) {
					$this->db->where('C.fk_numero_estrategia like', $arrData["numeroEstrategia"]);
				}
				if (array_key_exists("filtroCuadroBase", $arrData)) {
					$where = "C.id_cuadro_base IN (" . $arrData["filtroCuadroBase"] . ")";
					$this->db->where($where);
				}
				$query = $this->db->get('actividades A');
				if ($query->num_rows() > 0) {
					return $query->result_array();
				} else {
					return false;
				}
		}

		/**
		 * Consulta lista de meta proyecto inversion
		 * @since 3/05/2022
		 */
		public function get_meta_proyecto($arrData) 
		{		
				$this->db->select();
				$this->db->join('proyecto_inversion P', 'P.numero_proyecto_inversion = M.fk_numero_proyecto', 'LEFT');
				if (array_key_exists("idMetaProyecto", $arrData)) {
					$this->db->where('M.id_meta_proyecto_inversion', $arrData["idMetaProyecto"]);
				}
				if (array_key_exists("numeroProyecto", $arrData)) {
					$this->db->where('M.fk_numero_proyecto', $arrData["numeroProyecto"]);
				}
				if (array_key_exists("vigencia", $arrData)) {
					$this->db->where('M.vigencia_meta_proyecto', $arrData["vigencia"]);
				}
				$this->db->order_by('numero_meta_proyecto', 'asc');
				$query = $this->db->get('meta_proyecto_inversion M');
				if ($query->num_rows() > 0) {
					return $query->result_array();
				} else {
					return false;
				}
		}

		/**
		 * Consulta sumatoria de presupuesto para actividad por meta proyecto
		 * @since 30/04/2022
		 */
		public function get_sumatoria_presupuesto($arrData) 
		{		
				$this->db->select("SUM(presupuesto_actividad) sumatoria");
				$this->db->join('cuadro_base C', 'C.id_cuadro_base = A.fk_id_cuadro_base', 'INNER');
				$this->db->join('meta_proyecto_inversion M', 'M.id_meta_proyecto_inversion = C.fk_id_meta_proyecto_inversion', 'INNER');

				if (array_key_exists("idMetaProyecto", $arrData)) {
					$this->db->where('M.id_meta_proyecto_inversion', $arrData["idMetaProyecto"]);
				}

				$query = $this->db->get('actividades A');
				if ($query->num_rows() > 0) {
					return $query->row_array();
				} else {
					return false;
				}
		}

		/**
		 * Consulta lista de dependencias para cuadro base
		 * @since 8/06/2022
		 */
		public function get_dependencias($arrData) 
		{		
				$this->db->select('dependencia');
				$this->db->join('param_dependencias D', 'D.id_dependencia = C.fk_id_dependencia', 'INNER');
				if (array_key_exists("idCuadroBase", $arrData)) {
					$this->db->where('C.fk_id_cuadro_base', $arrData["idCuadroBase"]);
				}
				$this->db->order_by('dependencia', 'asc');
				$query = $this->db->get('cuadro_base_dependencias C');
				if ($query->num_rows() > 0) {
					return $query->result_array();
				} else {
					return false;
				}
		}

		/**
		 * Consulta lista de actividades para una dependencia
		 * @since 09/06/2022
		 */
		public function get_actividades_full_by_dependencia($arrData) 
		{		
				$this->db->select("A.*, E.avance_poa, E.estado_trimestre_1, E.estado_trimestre_2, E.estado_trimestre_3, E.estado_trimestre_4, W.mes mes_inicial, K.mes mes_final, C.id_cuadro_base, numero_estrategia, estrategia, CONCAT(numero_proyecto_inversion, ' ', nombre_proyecto_inversion) proyecto_inversion, meta_proyecto, vigencia_meta_proyecto, CONCAT(numero_proposito, ' ', proposito) proposito, CONCAT(numero_logro, ' ', logro) logro, CONCAT(numero_programa_estrategico, ' ', programa_estrategico) programa, CONCAT(numero_meta_pdd, ' ', meta_pdd) meta_pdd, CONCAT(numero_ods, ' ', ods) ods");
				$this->db->join('actividad_estado E', 'E.fk_numero_actividad  = A.numero_actividad ', 'LEFT');
				$this->db->join('param_meses W', 'W.id_mes = A.fecha_inicial', 'INNER');
				$this->db->join('param_meses K', 'K.id_mes = A.fecha_final', 'INNER');
				$this->db->join('cuadro_base C', 'C.id_cuadro_base = A.fk_id_cuadro_base', 'INNER');
				$this->db->join('cuadro_base_dependencias T', 'T.fk_id_cuadro_base = C.id_cuadro_base', 'INNER');
				$this->db->join('proyecto_inversion P', 'P.numero_proyecto_inversion = C.fk_numero_proyecto_inversion', 'INNER');
				$this->db->join('estrategias ES', 'ES.numero_estrategia = C.fk_numero_estrategia', 'INNER');
				$this->db->join('meta_proyecto_inversion M', 'M.nu_meta_proyecto = C.fk_nu_meta_proyecto_inversion', 'INNER');
				$this->db->join('propositos X', 'X.numero_proposito = C.fk_numero_proposito', 'INNER');
				$this->db->join('logros L', 'L.numero_logro  = C.fk_numero_logro', 'INNER');
				$this->db->join('programa_estrategico Y', 'Y.numero_programa_estrategico = C.fk_numero_programa_estrategico', 'INNER');
				$this->db->join('meta_pdd Z', 'Z.numero_meta_pdd = C.fk_numero_meta_pdd', 'INNER');
				$this->db->join('ods O', 'O.numero_ods = C.fk_numero_ods', 'INNER');

				if (array_key_exists("idDependencia", $arrData)) {
					$this->db->where('T.fk_id_dependencia', $arrData["idDependencia"]);
				}
				if (array_key_exists("numeroEstrategia", $arrData)) {
					$this->db->where('C.fk_numero_estrategia like', $arrData["numeroEstrategia"]);
				}
				$this->db->order_by("A.numero_actividad", "ASC");
				$query = $this->db->get('actividades A');
				if ($query->num_rows() > 0) {
					return $query->result_array();
				} else {
					return false;
				}
		}

		/**
		 * Consulta lista de SUPERVISORES para una actividad
		 * @since 11/06/2022
		 */
		public function get_user_encargado_by_actividad($arrData) 
		{					
				$this->db->select('id_user');
				$this->db->join('cuadro_base C', 'C.id_cuadro_base = A.fk_id_cuadro_base', 'INNER');
				$this->db->join('cuadro_base_dependencias T', 'T.fk_id_cuadro_base = C.id_cuadro_base', 'INNER');
				$this->db->join('usuarios U', 'U.fk_id_dependencia_u = T.fk_id_dependencia', 'INNER');
				if (array_key_exists("idRol", $arrData)) {
					$this->db->where('U.fk_id_user_role', $arrData["idRol"]);
				}
				if (array_key_exists("numeroActividad", $arrData)) {
					$this->db->where('A.numero_actividad', $arrData["numeroActividad"]);
				}
				$query = $this->db->get('actividades A');
				if ($query->num_rows() > 0) {
					return $query->result_array();
				} else {
					return false;
				}
		}


}