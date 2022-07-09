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
		if (array_key_exists("idDependencia", $arrData)) {
			$this->db->where('U.fk_id_dependencia_u', $arrData["idDependencia"]);
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
		 * Consulta lista de objetivos estrategicos
		 * @since 15/04/2022
		 */
		public function get_objetivos_estrategicos($arrData) 
		{		
				$this->db->select();
				$this->db->join('estrategias O', 'O.id_estrategia = E.fk_id_estrategia', 'INNER');
				if (array_key_exists("idObjetivoEstrategico", $arrData)) {
					$this->db->where('E.id_objetivo_estrategico', $arrData["idObjetivoEstrategico"]);
				}
				if (array_key_exists("numeroObjetivoEstrategico", $arrData)) {
					$this->db->where('E.numero_objetivo_estrategico like', $arrData["numeroObjetivoEstrategico"]);
				}
				if (array_key_exists("idEstrategia", $arrData)) {
					$this->db->where('E.fk_id_estrategia', $arrData["idEstrategia"]);
				}
				if (array_key_exists("filtroEstrategias", $arrData)) {
					$where = "E.id_objetivo_estrategico IN (" . $arrData["filtroEstrategias"] . ")";
					$this->db->where($where);
				}
				$this->db->order_by('numero_objetivo_estrategico', 'asc');
				$query = $this->db->get('objetivos_estrategicos E');
				if ($query->num_rows() > 0) {
					return $query->result_array();
				} else {
					return false;
				}
		}

		/**
		 * Consulta estrategias
		 * @since 07/07/2022
		 */
		public function get_estrategias($arrData) 
		{		
				$this->db->select();
				if (array_key_exists("idEstrategia", $arrData)) {
					$this->db->where('E.id_estrategia', $arrData["idEstrategia"]);
				}
				$this->db->order_by('estrategia', 'asc');
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
				if (array_key_exists("numeroObjetivoEstrategico", $arrData)) {
					$this->db->where('C.fk_numero_objetivo_estrategico like', $arrData["numeroObjetivoEstrategico"]);
				}
				if (array_key_exists("idEstrategia", $arrData)) {
					$this->db->where('C.fk_id_objetivo_estrategico', $arrData["idEstrategia"]);
				}
				if (array_key_exists("idMetaProyecto", $arrData)) {
					$this->db->where('M.id_meta_proyecto_inversion', $arrData["idMetaProyecto"]);
				}
				if (array_key_exists("idProposito", $arrData)) {
					$this->db->where('X.id_proposito', $arrData["idProposito"]);
				}
				if (array_key_exists("idLogro", $arrData)) {
					$this->db->where('L.id_logros', $arrData["idLogro"]);
				}
				if (array_key_exists("idPrograma", $arrData)) {
					$this->db->where('Y.id_programa_estrategico', $arrData["idPrograma"]);
				}
				if (array_key_exists("idMetaPDD", $arrData)) {
					$this->db->where('Z.id_meta_pdd', $arrData["idMetaPDD"]);
				}
				if (array_key_exists("idODS", $arrData)) {
					$this->db->where('O.id_ods', $arrData["idODS"]);
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
				$this->db->join('objetivos_estrategicos O', 'O.numero_objetivo_estrategico = E.fk_numero_objetivo_estrategico', 'INNER');			
				if (array_key_exists("idMetaObjetivoEstrategico", $arrData)) {
					$this->db->where('E.id_meta', $arrData["idMetaObjetivoEstrategico"]);
				}
				if (array_key_exists("numeroObjetivoEstrategico", $arrData)) {
					$this->db->where('E.fk_numero_objetivo_estrategico like', $arrData["numeroObjetivoEstrategico"]);
				}
				$query = $this->db->get('objetivos_estrategicos_metas E');
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
				$this->db->join('objetivos_estrategicos O', 'O.numero_objetivo_estrategico = E.fk_numero_objetivo_estrategico', 'INNER');			
				if (array_key_exists("idIndicadorObjetivoEstrategico", $arrData)) {
					$this->db->where('E.id_indicador', $arrData["idIndicadorObjetivoEstrategico"]);
				}
				if (array_key_exists("numeroObjetivoEstrategico", $arrData)) {
					$this->db->where('E.fk_numero_objetivo_estrategico like', $arrData["numeroObjetivoEstrategico"]);
				}
				$query = $this->db->get('objetivos_estrategicos_indicadores E');
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
				$this->db->join('objetivos_estrategicos O', 'O.numero_objetivo_estrategico = E.fk_numero_objetivo_estrategico', 'INNER');			
				if (array_key_exists("idResultadoObjetivoEstrategico", $arrData)) {
					$this->db->where('E.id_resultado', $arrData["idResultadoObjetivoEstrategico"]);
				}
				if (array_key_exists("numeroObjetivoEstrategico", $arrData)) {
					$this->db->where('E.fk_numero_objetivo_estrategico like', $arrData["numeroObjetivoEstrategico"]);
				}
				$query = $this->db->get('objetivos_estrategicos_resultados E');
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
				$this->db->select('A.*, D.dependencia, P.mes mes_inicial, X.mes mes_final, R.area_responsable responsable, E.trimestre_1, E.trimestre_2, E.trimestre_3, E.trimestre_4, E.avance_poa');
				$this->db->join('param_meses P', 'P.id_mes = A.fecha_inicial', 'INNER');
				$this->db->join('param_meses X', 'X.id_mes = A.fecha_final', 'INNER');
				$this->db->join('param_area_responsable R', 'R.id_area_responsable = A.fk_id_area_responsable', 'INNER');
				$this->db->join('cuadro_base C', 'C.id_cuadro_base = A.fk_id_cuadro_base', 'INNER');
				$this->db->join('param_dependencias D', 'D.id_dependencia = A.fk_id_dependencia', 'INNER');
				$this->db->join('actividad_estado E', 'E.fk_numero_actividad  = A.numero_actividad ', 'LEFT');

				if(array_key_exists("idActividad", $arrData)) {
					$this->db->where('A.id_actividad', $arrData["idActividad"]);
				}
				if(array_key_exists("numeroActividad", $arrData)) {
					$this->db->where('A.numero_actividad', $arrData["numeroActividad"]);
				}
				if(array_key_exists("idCuadroBase", $arrData)) {
					$this->db->where('A.fk_id_cuadro_base', $arrData["idCuadroBase"]);
				}
				if(array_key_exists("NOTidCuadroBase", $arrData)) {
					$this->db->where('A.fk_id_cuadro_base !=', $arrData["NOTidCuadroBase"]);
				}
				if (array_key_exists("idDependencia", $arrData)) {
					$this->db->where('A.fk_id_dependencia', $arrData["idDependencia"]);
				}
				if (array_key_exists("numeroObjetivoEstrategico", $arrData)) {
					$this->db->where('C.fk_numero_objetivo_estrategico like', $arrData["numeroObjetivoEstrategico"]);
				}
				if (array_key_exists("numeroProyecto", $arrData)) {
					$this->db->where('C.fk_numero_proyecto_inversion', $arrData["numeroProyecto"]);
				}

				$this->db->order_by('A.numero_actividad', 'asc');
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
				$this->db->order_by('H.numero_trimestre, H.id_historial ', 'desc');
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
				if (array_key_exists("filtroTrimestre", $arrData)) {
					$where = "P.numero_trimestre IN (" . $arrData["filtroTrimestre"] . ")";
					$this->db->where($where);
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
		public function get_objetivos_estrategicos_by_dependencia($arrData) 
		{					
				$this->db->select('id_objetivo_estrategico');
				$this->db->join('cuadro_base C', 'C.id_cuadro_base = A.fk_id_cuadro_base', 'INNER');
				$this->db->join('objetivos_estrategicos E', 'E.numero_objetivo_estrategico = C.fk_numero_objetivo_estrategico', 'INNER');
				if (array_key_exists("idDependencia", $arrData)) {
					$this->db->where('A.fk_id_dependencia', $arrData["idDependencia"]);
				}
				$this->db->group_by("E.id_objetivo_estrategico");
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
				if (array_key_exists("numeroObjetivoEstrategico", $arrData)) {
					$this->db->where('C.fk_numero_objetivo_estrategico like', $arrData["numeroObjetivoEstrategico"]);
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
				$this->db->select("A.*, D.dependencia, E.avance_poa, E.trimestre_1, E.trimestre_2, E.trimestre_3, E.trimestre_4, W.mes mes_inicial, K.mes mes_final, C.id_cuadro_base, fk_numero_objetivo_estrategico, CONCAT(numero_proyecto_inversion, ' ', nombre_proyecto_inversion) proyecto_inversion, CONCAT(ES.numero_objetivo_estrategico, ' ', ES.objetivo_estrategico) objetivo_estrategico, EG.estrategia, PR.proceso_calidad, meta_proyecto, presupuesto_meta, vigencia_meta_proyecto, CONCAT(numero_proposito, ' ', proposito) proposito, CONCAT(numero_logro, ' ', logro) logro, CONCAT(numero_programa_estrategico, ' ', programa_estrategico) programa, CONCAT(numero_meta_pdd, ' ', meta_pdd) meta_pdd, CONCAT(numero_ods, ' ', ods) ods, R.area_responsable");
				$this->db->join('actividad_estado E', 'E.fk_numero_actividad  = A.numero_actividad', 'LEFT');
				$this->db->join('param_proceso_calidad PR', 'PR.id_proceso_calidad = A.fk_id_proceso_calidad', 'INNER');
				$this->db->join('param_area_responsable R', 'R.id_area_responsable = A.fk_id_area_responsable', 'INNER');
				$this->db->join('param_dependencias D', 'D.id_dependencia = A.fk_id_dependencia', 'INNER');
				$this->db->join('param_meses W', 'W.id_mes = A.fecha_inicial', 'INNER');
				$this->db->join('param_meses K', 'K.id_mes = A.fecha_final', 'INNER');
				$this->db->join('cuadro_base C', 'C.id_cuadro_base = A.fk_id_cuadro_base', 'INNER');
				$this->db->join('proyecto_inversion P', 'P.numero_proyecto_inversion = C.fk_numero_proyecto_inversion', 'INNER');
				$this->db->join('objetivos_estrategicos ES', 'ES.numero_objetivo_estrategico = C.fk_numero_objetivo_estrategico', 'INNER');
				$this->db->join('estrategias EG', 'EG.id_estrategia = ES.fk_id_estrategia', 'INNER');
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
				if (array_key_exists("numeroObjetivoEstrategico", $arrData)) {
					$this->db->where('C.fk_numero_objetivo_estrategico like', $arrData["numeroObjetivoEstrategico"]);
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
		 * Consulta lista de actividades para una dependencia
		 * @since 09/06/2022
		 */
		public function get_actividades_full_by_dependencia($arrData) 
		{		
				$this->db->select("A.*, D.dependencia, E.avance_poa, E.estado_trimestre_1, E.estado_trimestre_2, E.estado_trimestre_3, E.estado_trimestre_4, W.mes mes_inicial, K.mes mes_final, C.id_cuadro_base, numero_objetivo_estrategico, objetivo_estrategico, CONCAT(numero_proyecto_inversion, ' ', nombre_proyecto_inversion) proyecto_inversion, meta_proyecto, vigencia_meta_proyecto, CONCAT(numero_proposito, ' ', proposito) proposito, CONCAT(numero_logro, ' ', logro) logro, CONCAT(numero_programa_estrategico, ' ', programa_estrategico) programa, CONCAT(numero_meta_pdd, ' ', meta_pdd) meta_pdd, CONCAT(numero_ods, ' ', ods) ods");
				$this->db->join('actividad_estado E', 'E.fk_numero_actividad  = A.numero_actividad ', 'LEFT');
				$this->db->join('param_dependencias D', 'D.id_dependencia = A.fk_id_dependencia', 'INNER');
				$this->db->join('param_meses W', 'W.id_mes = A.fecha_inicial', 'INNER');
				$this->db->join('param_meses K', 'K.id_mes = A.fecha_final', 'INNER');
				$this->db->join('cuadro_base C', 'C.id_cuadro_base = A.fk_id_cuadro_base', 'INNER');
				$this->db->join('proyecto_inversion P', 'P.numero_proyecto_inversion = C.fk_numero_proyecto_inversion', 'INNER');
				$this->db->join('objetivos_estrategicos ES', 'ES.numero_objetivo_estrategico = C.fk_numero_objetivo_estrategico', 'INNER');
				$this->db->join('meta_proyecto_inversion M', 'M.nu_meta_proyecto = C.fk_nu_meta_proyecto_inversion', 'INNER');
				$this->db->join('propositos X', 'X.numero_proposito = C.fk_numero_proposito', 'INNER');
				$this->db->join('logros L', 'L.numero_logro  = C.fk_numero_logro', 'INNER');
				$this->db->join('programa_estrategico Y', 'Y.numero_programa_estrategico = C.fk_numero_programa_estrategico', 'INNER');
				$this->db->join('meta_pdd Z', 'Z.numero_meta_pdd = C.fk_numero_meta_pdd', 'INNER');
				$this->db->join('ods O', 'O.numero_ods = C.fk_numero_ods', 'INNER');

				if (array_key_exists("idDependencia", $arrData)) {
					$this->db->where('A.fk_id_dependencia', $arrData["idDependencia"]);
				}
				if (array_key_exists("idEstrategia", $arrData)) {
					$this->db->where('ES.fk_id_estrategia', $arrData["idEstrategia"]);
				}
				if (array_key_exists("numeroObjetivoEstrategico", $arrData)) {
					$this->db->where('C.fk_numero_objetivo_estrategico like', $arrData["numeroObjetivoEstrategico"]);
				}
				if (array_key_exists("numeroProyecto", $arrData)) {
					$this->db->where('P.numero_proyecto_inversion', $arrData["numeroProyecto"]);
				}
				if (array_key_exists("numeroActividad", $arrData)) {
					$this->db->where('A.numero_actividad', $arrData["numeroActividad"]);
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
				$this->db->join('usuarios U', 'U.fk_id_dependencia_u = A.fk_id_dependencia', 'INNER');
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

	/**
	 * Lista de las dependencias del sistema
	 * @since 15/06/2022
	 */
	public function get_app_dependencias($arrData) 
	{		
		if (array_key_exists("idDependencia", $arrData)) {
			$this->db->where('id_dependencia', $arrData["idDependencia"]);
		}
		if (array_key_exists("filtro", $arrData)) {
			$values = array('1', '3');
			$this->db->where_not_in('id_dependencia', $values);
		}
		$this->db->order_by('dependencia', 'asc');
		$query = $this->db->get('param_dependencias');

		if ($query->num_rows() > 0) {
			return $query->result_array();
		} else {
			return false;
		}
	}

		/**
		 * Consulta lista de NUMEROS actividades para una dependencia
		 * @since 15/06/2022
		 */
		public function get_numero_actividades_full_by_dependencia($arrData) 
		{		
				$this->db->select("A.numero_actividad, numero_objetivo_estrategico, objetivo_estrategico");
				$this->db->join('cuadro_base C', 'C.id_cuadro_base = A.fk_id_cuadro_base', 'INNER');
				$this->db->join('objetivos_estrategicos ES', 'ES.numero_objetivo_estrategico = C.fk_numero_objetivo_estrategico', 'INNER');

				if (array_key_exists("idDependencia", $arrData)) {
					$this->db->where('A.fk_id_dependencia', $arrData["idDependencia"]);
				}
				if (array_key_exists("idEstrategia", $arrData)) {
					$this->db->where('ES.fk_id_estrategia', $arrData["idEstrategia"]);
				}
				if (array_key_exists("numeroObjetivoEstrategico", $arrData)) {
					$this->db->where('ES.numero_objetivo_estrategico like', $arrData["numeroObjetivoEstrategico"]);
				}
				if (array_key_exists("numeroProyecto", $arrData)) {
					$this->db->where('C.fk_numero_proyecto_inversion', $arrData["numeroProyecto"]);
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
		 * Consulta lista de NUMEROS actividades para una dependencia
		 * @since 15/06/2022
		 */
		public function get_numero_proyectos_full_by_dependencia($arrData) 
		{		
				$this->db->select("distinct(fk_numero_proyecto_inversion) numero_proyecto");
				$this->db->join('cuadro_base C', 'C.id_cuadro_base = A.fk_id_cuadro_base', 'INNER');
				$this->db->join('objetivos_estrategicos ES', 'ES.numero_objetivo_estrategico = C.fk_numero_objetivo_estrategico', 'INNER');

				if (array_key_exists("idDependencia", $arrData)) {
					$this->db->where('A.fk_id_dependencia', $arrData["idDependencia"]);
				}
				if (array_key_exists("idEstrategia", $arrData)) {
					$this->db->where('ES.fk_id_estrategia', $arrData["idEstrategia"]);
				}
				if (array_key_exists("numeroObjetivoEstrategico", $arrData)) {
					$this->db->where('ES.numero_objetivo_estrategico like', $arrData["numeroObjetivoEstrategico"]);
				}
				if (array_key_exists("numeroProyecto", $arrData)) {
					$this->db->where('C.fk_numero_proyecto_inversion', $arrData["numeroProyecto"]);
				}
				$this->db->order_by("fk_numero_proyecto_inversion", "ASC");
				$query = $this->db->get('actividades A');
				if ($query->num_rows() > 0) {
					return $query->result_array();
				} else {
					return false;
				}
		}

		/**
		 * Consulta lista de ESTRATEGIAS actividades para una dependencia
		 * @since 7/07/2022
		 */
		public function get_estrategias_full_by_dependencia($arrData) 
		{		
				$this->db->select("distinct(id_estrategia) id_estrategia, estrategia");
				$this->db->join('cuadro_base C', 'C.id_cuadro_base = A.fk_id_cuadro_base', 'INNER');
				$this->db->join('objetivos_estrategicos ES', 'ES.numero_objetivo_estrategico = C.fk_numero_objetivo_estrategico', 'INNER');
				$this->db->join('estrategias E', 'E.id_estrategia = ES.fk_id_estrategia', 'INNER');
				if (array_key_exists("idDependencia", $arrData)) {
					$this->db->where('A.fk_id_dependencia', $arrData["idDependencia"]);
				}
				if (array_key_exists("idEstrategia", $arrData)) {
					$this->db->where('ES.fk_id_estrategia', $arrData["idEstrategia"]);
				}
				$this->db->order_by("estrategia", "ASC");
				$query = $this->db->get('actividades A');
				if ($query->num_rows() > 0) {
					return $query->result_array();
				} else {
					return false;
				}
		}

		/**
		 * Consulta lista de dependencias
		 * @since 18/06/2022
		 */
		public function get_dependencia_full_by_filtro($arrData) 
		{		
				$this->db->select("distinct(id_dependencia), dependencia");
				$this->db->join('cuadro_base C', 'C.id_cuadro_base = A.fk_id_cuadro_base', 'INNER');
				$this->db->join('objetivos_estrategicos ES', 'ES.numero_objetivo_estrategico = C.fk_numero_objetivo_estrategico', 'INNER');
				$this->db->join('param_dependencias D', 'D.id_dependencia = A.fk_id_dependencia', 'INNER');

				if (array_key_exists("idDependencia", $arrData)) {
					$this->db->where('A.fk_id_dependencia', $arrData["idDependencia"]);
				}
				if (array_key_exists("idEstrategia", $arrData)) {
					$this->db->where('ES.fk_id_estrategia', $arrData["idEstrategia"]);
				}
				if (array_key_exists("numeroObjetivoEstrategico", $arrData)) {
					$this->db->where('ES.numero_objetivo_estrategico like', $arrData["numeroObjetivoEstrategico"]);
				}
				if (array_key_exists("numeroProyecto", $arrData)) {
					$this->db->where('C.fk_numero_proyecto_inversion', $arrData["numeroProyecto"]);
				}
				if (array_key_exists("filtro", $arrData)) {
					$values = array('1', '3');
					$this->db->where_not_in('D.id_dependencia', $values);
				}
				$this->db->order_by("dependencia", "ASC");
				$query = $this->db->get('actividades A');
				if ($query->num_rows() > 0) {
					return $query->result_array();
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
				$this->db->select('distinct(id_dependencia), dependencia');
				$this->db->join('param_dependencias D', 'D.id_dependencia = A.fk_id_dependencia', 'INNER');
				if (array_key_exists("idCuadroBase", $arrData)) {
					$this->db->where('A.fk_id_cuadro_base', $arrData["idCuadroBase"]);
				}
				$this->db->order_by('dependencia', 'asc');
				$query = $this->db->get('actividades A');
				if ($query->num_rows() > 0) {
					return $query->result_array();
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
				$sql.= " INNER JOIN objetivos_estrategicos E ON E.numero_objetivo_estrategico = C.fk_numero_objetivo_estrategico";
				$sql.= " WHERE 1=1 ";
				if (array_key_exists("idDependencia", $arrData)) {
					$sql.= " AND A.fk_id_dependencia = '". $arrData["idDependencia"]. "'";
					if (array_key_exists("vigencia", $arrData)) {
						$sql.= " AND M.vigencia_meta_proyecto = '". $arrData["vigencia"]. "'";
					}
				}
				if (array_key_exists("idEstrategia", $arrData)) {
					$sql.= " AND E.fk_id_estrategia = '". $arrData["idEstrategia"]. "'";
					if (array_key_exists("vigencia", $arrData)) {
						$sql.= " AND M.vigencia_meta_proyecto = '". $arrData["vigencia"]. "'";
					}
				}
				if (array_key_exists("numeroObjetivoEstrategico", $arrData)) {
					$sql.= " AND E.numero_objetivo_estrategico like'". $arrData["numeroObjetivoEstrategico"]. "'";
					if (array_key_exists("vigencia", $arrData)) {
						$sql.= " AND M.vigencia_meta_proyecto = '". $arrData["vigencia"]. "'";
					}
				}
				if (array_key_exists("numeroProyecto", $arrData)) {
					$sql.= " AND C.fk_numero_proyecto_inversion = '". $arrData["numeroProyecto"]. "'";
				}
				if(array_key_exists("numeroActividad", $arrData)) {
					$sql.= " AND A.numero_actividad = '". $arrData["numeroActividad"]. "'";
				}

				if (array_key_exists("planArchivos", $arrData)) {
					$sql.= " AND A.plan_archivos = 1";
				}
				if (array_key_exists("planAdquisiciones", $arrData)) {
					$sql.= " AND A.plan_adquisiciones = 1";
				}
				if (array_key_exists("planVacantes", $arrData)) {
					$sql.= " AND A.plan_vacantes = 1";
				}
				if (array_key_exists("planRecursos", $arrData)) {
					$sql.= " AND A.plan_recursos = 1";
				}
				if (array_key_exists("planTalento", $arrData)) {
					$sql.= " AND A.plan_talento = 1";
				}
				if (array_key_exists("planCapacitacion", $arrData)) {
					$sql.= " AND A.plan_capacitacion = 1";
				}
				if (array_key_exists("planIncentivos", $arrData)) {
					$sql.= " AND A.plan_incentivos = 1";
				}
				if (array_key_exists("planTrabajo", $arrData)) {
					$sql.= " AND A.plan_trabajo = 1";
				}
				if (array_key_exists("planAnticorrupcion", $arrData)) {
					$sql.= " AND A.plan_anticorrupcion = 1";
				}
				if (array_key_exists("planTecnologia", $arrData)) {
					$sql.= " AND A.plan_tecnologia = 1";
				}
				if (array_key_exists("planRiesgos", $arrData)) {
					$sql.= " AND A.plan_riesgos = 1";
				}
				if (array_key_exists("planInformacion", $arrData)) {
					$sql.= " AND A.plan_informacion = 1";
				}

				$query = $this->db->query($sql);
				$row = $query->row();
				return $row->CONTEO;
		}

		/**
		 * Contar actividades por estado por trimestre
		 * @author BMOTTAG
		 * @since  8/12/2016
		 */
		public function countActividadesEstado($arrData)
		{
				$sql = "SELECT count(id_estado_actividad) CONTEO";
				$sql.= " FROM  actividad_estado A";
				$sql.= " INNER JOIN actividades E ON E.numero_actividad = A.fk_numero_actividad";
				$sql.= " WHERE 1=1 ";
				if (array_key_exists("idDependencia", $arrData)) {
					$sql.= " AND E.fk_id_dependencia = '". $arrData["idDependencia"]. "'";
				}
				if (array_key_exists("idEstrategia", $arrData)) {
					$sql.= " AND E.fk_id_estrategia = '". $arrData["idEstrategia"]. "'";
					if (array_key_exists("vigencia", $arrData)) {
						$sql.= " AND M.vigencia_meta_proyecto = '". $arrData["vigencia"]. "'";
					}
				}
				if (array_key_exists("numeroTrimestre", $arrData) && array_key_exists("estadoTrimestre", $arrData) ) {
					$sql.= " AND A.estado_trimestre_" . $arrData["numeroTrimestre"] . " = '". $arrData["estadoTrimestre"]. "'";
				}

				$query = $this->db->query($sql);
				$row = $query->row();
				return $row->CONTEO;
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
		 * @since 24/04/2022
		 */
		public function updateEstadoActividad($arrData)
		{			
			$data = array(
				'estado_trimestre_' . $arrData["numeroTrimestre"] => $arrData["estado"]
			);	
			//si esta aprobado por planeacion, debo guardar los calculos
			if($arrData["estado"] == 5){
				$valorCumplimiento = "cumplimiento" . $arrData["numeroTrimestre"];
				$data = array(
					'trimestre_' . $arrData["numeroTrimestre"] => $arrData[$valorCumplimiento],
					'estado_trimestre_' . $arrData["numeroTrimestre"] => $arrData["estado"],
					'avance_poa' => $arrData["avancePOA"]
				);	
			}
			$this->db->where('fk_numero_actividad', $arrData["numeroActividad"]);
			$query = $this->db->update('actividad_estado', $data);

			if ($query) {
				return true;
			} else {
				return false;
			}
		}

		/**
		 * Update estado de la actividad
		 * @since 24/06/2022
		 */
		public function updateEstadoActividadTotales($arrData)
		{			
			$data = array(
				'estado_trimestre_' . $arrData["numeroTrimestre"] => $arrData["estado"],
				'trimestre_' . $arrData["numeroTrimestre"] => $arrData["cumplimientoX"],
				'estado_trimestre_' . $arrData["numeroTrimestre"] => $arrData["estado"],
				'avance_poa' => $arrData["avancePOA"],
				'cumplimiento' => $arrData["cumplimientoActual"],
				'mensaje_poa_trimestre_' . $arrData["numeroTrimestre"] => $arrData["observacion"]
			);	
			$this->db->where('fk_numero_actividad', $arrData["numeroActividad"]);
			$query = $this->db->update('actividad_estado', $data);

			if ($query) {
				return true;
			} else {
				return false;
			}
		}

		/**
		 * Update observacion de la actividad
		 * @since 24/06/2022
		 */
		public function updateObservacionActividadTotales($arrData)
		{			
			$data = array(
				'estado_trimestre_' . $arrData["numeroTrimestre"] => $arrData["estado"],
				'observacion_trimestre_' . $arrData["numeroTrimestre"] => $arrData["observacion"]
			);	
			$this->db->where('fk_numero_actividad', $arrData["numeroActividad"]);
			$query = $this->db->update('actividad_estado', $data);

			if ($query) {
				return true;
			} else {
				return false;
			}
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
			$this->db->join('objetivos_estrategicos X', 'X.numero_objetivo_estrategico = C.fk_numero_objetivo_estrategico', 'INNER');

			if (array_key_exists("idDependencia", $arrData)) {
				$this->db->where('fk_id_dependencia', $arrData["idDependencia"]);
			}
			if (array_key_exists("idObjetivo", $arrData)) {
				$this->db->where('fk_id_objetivo_estrategico', $arrData["idObjetivo"]);
			}
			if (array_key_exists("numeroObjetivoEstrategico", $arrData)) {
				$this->db->where('numero_objetivo_estrategico', $arrData["numeroObjetivoEstrategico"]);
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

		/**
		 * Sumatoria de Cumplimiento
		 * @since 27/6/2022
		 */
		public function sumCumplimiento($arrData) 
		{		
			$this->db->select_sum('cumplimiento');
			$this->db->join('actividades A', 'A.numero_actividad = E.fk_numero_actividad', 'INNER');
			$this->db->join('cuadro_base C', 'C.id_cuadro_base = A.fk_id_cuadro_base', 'INNER');
			$this->db->join('meta_proyecto_inversion M', 'M.nu_meta_proyecto = C.fk_nu_meta_proyecto_inversion', 'INNER');
			$this->db->join('objetivos_estrategicos X', 'X.numero_objetivo_estrategico = C.fk_numero_objetivo_estrategico', 'INNER');

			if (array_key_exists("idEstrategia", $arrData)) {
				$this->db->where('X.fk_id_estrategia', $arrData["idEstrategia"]);
			}
			if (array_key_exists("numeroObjetivoEstrategico", $arrData)) {
				$this->db->where('C.fk_numero_objetivo_estrategico like', $arrData["numeroObjetivoEstrategico"]);
			}
			if (array_key_exists("idDependencia", $arrData)) {
				$this->db->where('A.fk_id_dependencia', $arrData["idDependencia"]);
			}
			if (array_key_exists("planArchivos", $arrData)) {
				$this->db->where('A.plan_archivos', 1);
			}
			if (array_key_exists("planAdquisiciones", $arrData)) {
				$this->db->where('A.plan_adquisiciones', 1);
			}
			if (array_key_exists("planVacantes", $arrData)) {
				$this->db->where('A.plan_vacantes', 1);
			}
			if (array_key_exists("planRecursos", $arrData)) {
				$this->db->where('A.plan_recursos', 1);
			}
			if (array_key_exists("planTalento", $arrData)) {
				$this->db->where('A.plan_talento', 1);
			}
			if (array_key_exists("planCapacitacion", $arrData)) {
				$this->db->where('A.plan_capacitacion', 1);
			}
			if (array_key_exists("planIncentivos", $arrData)) {
				$this->db->where('A.plan_incentivos', 1);
			}
			if (array_key_exists("planTrabajo", $arrData)) {
				$this->db->where('A.plan_trabajo', 1);
			}
			if (array_key_exists("planAnticorrupcion", $arrData)) {
				$this->db->where('A.plan_anticorrupcion', 1);
			}
			if (array_key_exists("planTecnologia", $arrData)) {
				$this->db->where('A.plan_tecnologia', 1);
			}
			if (array_key_exists("planRiesgos", $arrData)) {
				$this->db->where('A.plan_riesgos', 1);
			}
			if (array_key_exists("planInformacion", $arrData)) {
				$this->db->where('A.plan_informacion', 1);
			}

			$query = $this->db->get('actividad_estado E');

			if ($query->num_rows() > 0) {
				return $query->row_array();
			} else {
				return false;
			}
		}


}