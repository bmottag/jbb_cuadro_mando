<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Settings extends CI_Controller {
	
    public function __construct() {
        parent::__construct();
        $this->load->model("settings_model");
        $this->load->model("general_model");
		$this->load->helper('form');
    }
	
	/**
	 * users List
     * @since 15/12/2016
     * @author BMOTTAG
	 */
	public function users($state=1)
	{			
			$data['state'] = $state;
			
			if($state == 1){
				$arrParam = array("filtroState" => TRUE);
			}else{
				$arrParam = array("state" => $state);
			}
			
			$data['info'] = $this->general_model->get_user($arrParam);
			
			$data["view"] = 'users';
			$this->load->view("layout_calendar", $data);
	}
	
    /**
     * Cargo modal - formulario Users
     * @since 15/12/2016
     */
    public function cargarModalUsers() 
	{
			header("Content-Type: text/plain; charset=utf-8"); //Para evitar problemas de acentos
			
			$data['information'] = FALSE;
			$data["idEmployee"] = $this->input->post("idEmployee");	
			
			$arrParam = array("filtro" => TRUE);
			$data['roles'] = $this->general_model->get_roles($arrParam);

			$arrParam = array(
				"table" => "param_dependencias",
				"order" => "dependencia",
				"id" => "x"
			);
			$data['dependencias'] = $this->general_model->get_basic_search($arrParam);

			$arrParam = array(
				"table" => "param_area_responsable",
				"order" => "area_responsable",
				"id" => "x"
			);
			$data['lista_area_responsable'] = $this->general_model->get_basic_search($arrParam);

			if ($data["idEmployee"] != 'x') {
				$arrParam = array(
					"table" => "usuarios",
					"order" => "id_user",
					"column" => "id_user",
					"id" => $data["idEmployee"]
				);
				$data['information'] = $this->general_model->get_basic_search($arrParam);
			}
			
			$this->load->view("users_modal", $data);
    }
	
	/**
	 * Update User
     * @since 15/12/2016
     * @author BMOTTAG
	 */
	public function save_user()
	{			
			header('Content-Type: application/json');
			$data = array();
			
			$idUser = $this->input->post('hddId');

			$msj = "Se adicionó un nuevo Usuario!";
			if ($idUser != '') {
				$msj = "Se actualizó el Usuario!";
			}			

			$log_user = $this->input->post('user');
			$email_user = $this->input->post('email');
			
			$result_user = false;
			$result_email = false;
			
			//verificar si ya existe el usuario
			$arrParam = array(
				"idUser" => $idUser,
				"column" => "log_user",
				"value" => $log_user
			);
			$result_user = $this->settings_model->verifyUser($arrParam);
			
			//verificar si ya existe el correo
			$arrParam = array(
				"idUser" => $idUser,
				"column" => "email",
				"value" => $email_user
			);
			$result_email = $this->settings_model->verifyUser($arrParam);

			$data["state"] = $this->input->post('state');
			if ($idUser == '') {
				$data["state"] = 1;//para el direccionamiento del JS, cuando es usuario nuevo no se envia state
			}

			if ($result_user || $result_email)
			{
				$data["result"] = "error";
				if($result_user)
				{
					$data["mensaje"] = " Error. El Usuario ya existe.";
					$this->session->set_flashdata('retornoError', '<strong>Error!!!</strong> El Usuario ya existe.');
				}
				if($result_email)
				{
					$data["mensaje"] = " Error. El correo ya existe.";
					$this->session->set_flashdata('retornoError', '<strong>Error!!!</strong> El correo ya existe.');
				}
				if($result_user && $result_email)
				{
					$data["mensaje"] = " Error. El Usuario y el Correo ya existen.";
					$this->session->set_flashdata('retornoError', '<strong>Error!!!</strong> El Usuario y el Correo ya existen.');
				}
			} else {
					if ($this->settings_model->saveUser()) {
						$data["result"] = true;					
						$this->session->set_flashdata('retornoExito', '<strong>Correcto!</strong> ' . $msj);
					} else {
						$data["result"] = "error";					
						$this->session->set_flashdata('retornoError', '<strong>Error!</strong> Ask for help');
					}
			}

			echo json_encode($data);
    }
	
	/**
	 * Reset employee password
	 * Reset the password to '123456'
	 * And change the status to '0' to changue de password 
     * @since 11/1/2017
     * @author BMOTTAG
	 */
	public function resetPassword($idUser)
	{
			if ($this->settings_model->resetEmployeePassword($idUser)) {
				$this->session->set_flashdata('retornoExito', '<strong>Correcto!</strong> You have reset the Employee pasword to: 123456');
			} else {
				$this->session->set_flashdata('retornoError', '<strong>Error!</strong> Ask for help');
			}
			
			redirect("/settings/employee/",'refresh');
	}	

	/**
	 * Change password
     * @since 15/4/2017
     * @author BMOTTAG
	 */
	public function change_password($idUser)
	{
			if (empty($idUser)) {
				show_error('ERROR!!! - You are in the wrong place. The ID USER is missing.');
			}
			
			$arrParam = array(
				"table" => "usuarios",
				"order" => "id_user",
				"column" => "id_user",
				"id" => $idUser
			);
			$data['information'] = $this->general_model->get_basic_search($arrParam);
		
			$data["view"] = "form_password";
			$this->load->view("layout", $data);
	}
	
	/**
	 * Update user´s password
	 */
	public function update_password()
	{
			$data = array();			
			
			$newPassword = $this->input->post("inputPassword");
			$confirm = $this->input->post("inputConfirm");
			$userState = $this->input->post("hddState");
			
			//Para redireccionar el usuario
			if($userState!=2){
				$userState = 1;
			}
			
			$passwd = str_replace(array("<",">","[","]","*","^","-","'","="),"",$newPassword); 
			
			$data['linkBack'] = "settings/users/" . $userState;
			$data['titulo'] = "<i class='fa fa-unlock fa-fw'></i>CAMBIAR CONTRASEÑA";
			
			if($newPassword == $confirm)
			{					
					if ($this->settings_model->updatePassword()) {
						$data['msj'] = 'Se actualizó la contraseña del usuario.';
						$data['msj'] .= '<br>';
						$data['msj'] .= '<br><strong>Nombre Usuario: </strong>' . $this->input->post('hddUser');
						$data['msj'] .= '<br><strong>Contraseña: </strong>' . $passwd;
						$data['clase'] = 'alert-success';
					}else{
						$data['msj'] = '<strong>Error!!!</strong> Ask for help.';
						$data['clase'] = 'alert-danger';
					}
			}else{
				//definir mensaje de error
				echo "pailas no son iguales";
			}
						
			$data["view"] = "template/answer";
			$this->load->view("layout", $data);
	}
	
	/**
	 * Lista de proyectos
     * @since 15/04/2022
     * @author BMOTTAG
	 */
	public function proyectos()
	{
			$arrParam = array(
				"table" => "proyecto_inversion",
				"order" => "id_proyecto_inversion",
				"id" => "x"
			);
			$data['info'] = $this->general_model->get_basic_search($arrParam);
			
			$data["view"] = 'proyectos';
			$this->load->view("layout_calendar", $data);
	}
	
    /**
     * Cargo modal - formulario proyectos
     * @since 15/04/2022
     */
    public function cargarModalProyecto() 
	{
			header("Content-Type: text/plain; charset=utf-8"); //Para evitar problemas de acentos
			
			$data['information'] = FALSE;
			$data["idProyecto"] = $this->input->post("idProyecto");	
			
			if ($data["idProyecto"] != 'x') {
				$arrParam = array(
					"table" => "proyecto_inversion",
					"order" => "numero_proyecto_inversion",
					"column" => "id_proyecto_inversion",
					"id" => $data["idProyecto"]
				);
				$data['information'] = $this->general_model->get_basic_search($arrParam);
			}
			
			$this->load->view("proyectos_modal", $data);
    }
	
	/**
	 * Ingresar/Actualizar proyectos
     * @since 15/04/2022
     * @author BMOTTAG
	 */
	public function save_proyecto()
	{			
			header('Content-Type: application/json');
			$data = array();
			
			$idProyecto = $this->input->post('hddId');
			
			$msj = "Se adicionó el Proyecto de Inversión!";
			if ($idProyecto != '') {
				$msj = "Se actualizó el Proyecto de Inversión!";
			}

			if ($idProyecto = $this->settings_model->saveProyecto()) {
				$data["result"] = true;				
				$this->session->set_flashdata('retornoExito', '<strong>Correcto!</strong> ' . $msj);
			} else {
				$data["result"] = "error";			
				$this->session->set_flashdata('retornoError', '<strong>Error!</strong> Ask for help');
			}

			echo json_encode($data);	
    }

	/**
	 * Lista de estrategias
     * @since 15/04/2022
     * @author BMOTTAG
	 */
	public function estrategias()
	{
			$arrParam = array(
				"table" => "estrategias",
				"order" => "id_estrategia",
				"id" => "x"
			);
			$data['info'] = $this->general_model->get_basic_search($arrParam);
			
			$data["view"] = 'estrategias';
			$this->load->view("layout_calendar", $data);
	}
	
    /**
     * Cargo modal - formulario estrategias
     * @since 15/04/2022
     */
    public function cargarModalEstrategias() 
	{
			header("Content-Type: text/plain; charset=utf-8"); //Para evitar problemas de acentos
			
			$data['information'] = FALSE;
			$data["idEstrategia"] = $this->input->post("idEstrategia");	
			
			if ($data["idEstrategia"] != 'x') {
				$arrParam = array(
					"table" => "estrategias",
					"order" => "estrategia",
					"column" => "id_estrategia",
					"id" => $data["idEstrategia"]
				);
				$data['information'] = $this->general_model->get_basic_search($arrParam);
			}
			
			$this->load->view("estrategias_modal", $data);
    }
	
	/**
	 * Ingresar/Actualizar estrategias
     * @since 15/04/2022
     * @author BMOTTAG
	 */
	public function save_estrategias()
	{			
			header('Content-Type: application/json');
			$data = array();
			
			$idEstrategia = $this->input->post('hddId');
			
			$msj = "Se adicionó la Estrategia!";
			if ($idEstrategia != '') {
				$msj = "Se actualizó la Estrategia!";
			}

			if ($idEstrategia = $this->settings_model->saveEstrategia()) {
				$data["result"] = true;				
				$this->session->set_flashdata('retornoExito', '<strong>Correcto!</strong> ' . $msj);
			} else {
				$data["result"] = "error";			
				$this->session->set_flashdata('retornoError', '<strong>Error!</strong> Ask for help');
			}

			echo json_encode($data);	
    }

	/**
	 * Lista de propositos
     * @since 15/04/2022
     * @author BMOTTAG
	 */
	public function propositos()
	{
			$arrParam = array(
				"table" => " propositos",
				"order" => "numero_proposito",
				"id" => "x"
			);
			$data['info'] = $this->general_model->get_basic_search($arrParam);
			
			$data["view"] = 'propositos';
			$this->load->view("layout_calendar", $data);
	}
	
    /**
     * Cargo modal - formulario propositos
     * @since 15/04/2022
     */
    public function cargarModalPropositos() 
	{
			header("Content-Type: text/plain; charset=utf-8"); //Para evitar problemas de acentos
			
			$data['information'] = FALSE;
			$data["idProposito"] = $this->input->post("idProposito");	
			
			if ($data["idProposito"] != 'x') {
				$arrParam = array(
					"table" => "propositos",
					"order" => "numero_proposito",
					"column" => "id_proposito",
					"id" => $data["idProposito"]
				);
				$data['information'] = $this->general_model->get_basic_search($arrParam);
			}
			
			$this->load->view("propositos_modal", $data);
    }
	
	/**
	 * Ingresar/Actualizar propositos
     * @since 15/04/2022
     * @author BMOTTAG
	 */
	public function save_propositos()
	{			
			header('Content-Type: application/json');
			$data = array();
			
			$idProposito = $this->input->post('hddId');
			
			$msj = "Se adicionó el Propósito!";
			if ($idProposito != '') {
				$msj = "Se actualizó el Propósito!";
			}

			if ($idProposito = $this->settings_model->saveProposito()) {
				$data["result"] = true;				
				$this->session->set_flashdata('retornoExito', '<strong>Correcto!</strong> ' . $msj);
			} else {
				$data["result"] = "error";			
				$this->session->set_flashdata('retornoError', '<strong>Error!</strong> Ask for help');
			}

			echo json_encode($data);	
    }

	/**
	 * Lista de logros
     * @since 15/04/2022
     * @author BMOTTAG
	 */
	public function logros()
	{
			$arrParam = array(
				"table" => " logros",
				"order" => "numero_logro",
				"id" => "x"
			);
			$data['info'] = $this->general_model->get_basic_search($arrParam);
			
			$data["view"] = 'logros';
			$this->load->view("layout_calendar", $data);
	}
	
    /**
     * Cargo modal - formulario logros
     * @since 15/04/2022
     */
    public function cargarModalLogros() 
	{
			header("Content-Type: text/plain; charset=utf-8"); //Para evitar problemas de acentos
			
			$data['information'] = FALSE;
			$data["idLogro"] = $this->input->post("idLogro");	
			
			if ($data["idLogro"] != 'x') {
				$arrParam = array(
					"table" => " logros",
					"order" => "numero_logro",
					"column" => "id_logros",
					"id" => $data["idLogro"]
				);
				$data['information'] = $this->general_model->get_basic_search($arrParam);
			}
			
			$this->load->view("logros_modal", $data);
    }
	
	/**
	 * Ingresar/Actualizar logros
     * @since 15/04/2022
     * @author BMOTTAG
	 */
	public function save_logros()
	{			
			header('Content-Type: application/json');
			$data = array();
			
			$idLogro = $this->input->post('hddId');
			
			$msj = "Se adicionó el Logro!";
			if ($idLogro != '') {
				$msj = "Se actualizó el Logro!";
			}

			if ($idLogro = $this->settings_model->saveLogro()) {
				$data["result"] = true;				
				$this->session->set_flashdata('retornoExito', '<strong>Correcto!</strong> ' . $msj);
			} else {
				$data["result"] = "error";			
				$this->session->set_flashdata('retornoError', '<strong>Error!</strong> Ask for help');
			}

			echo json_encode($data);	
    }

	/**
	 * Lista de _programas
     * @since 15/04/2022
     * @author BMOTTAG
	 */
	public function programas()
	{
			$arrParam = array(
				"table" => " programa_estrategico",
				"order" => "numero_programa_estrategico",
				"id" => "x"
			);
			$data['info'] = $this->general_model->get_basic_search($arrParam);
			
			$data["view"] = 'programa';
			$this->load->view("layout_calendar", $data);
	}
	
    /**
     * Cargo modal - formulario _programas
     * @since 15/04/2022
     */
    public function cargarModalProgramas() 
	{
			header("Content-Type: text/plain; charset=utf-8"); //Para evitar problemas de acentos
			
			$data['information'] = FALSE;
			$data["idPrograma"] = $this->input->post("idPrograma");	
			
			if ($data["idPrograma"] != 'x') {
				$arrParam = array(
					"table" => "programa_estrategico",
					"order" => "numero_programa_estrategico",
					"column" => "id_programa_estrategico",
					"id" => $data["idPrograma"]
				);
				$data['information'] = $this->general_model->get_basic_search($arrParam);
			}
			
			$this->load->view("programa_modal", $data);
    }
	
	/**
	 * Ingresar/Actualizar _programas
     * @since 15/04/2022
     * @author BMOTTAG
	 */
	public function save_programas()
	{			
			header('Content-Type: application/json');
			$data = array();
			
			$idPrograma = $this->input->post('hddId');
			
			$msj = "Se adicionó el Programa Estratégico!";
			if ($idPrograma != '') {
				$msj = "Se actualizó el Programa Estratégico!";
			}

			if ($idPrograma = $this->settings_model->saveProgramas()) {
				$data["result"] = true;				
				$this->session->set_flashdata('retornoExito', '<strong>Correcto!</strong> ' . $msj);
			} else {
				$data["result"] = "error";			
				$this->session->set_flashdata('retornoError', '<strong>Error!</strong> Ask for help');
			}

			echo json_encode($data);	
    }

	/**
	 * Lista de _metas_pdd
     * @since 15/04/2022
     * @author BMOTTAG
	 */
	public function metas_pdd()
	{
			$arrParam = array(
				"table" => "meta_pdd",
				"order" => "numero_meta_pdd",
				"id" => "x"
			);
			$data['info'] = $this->general_model->get_basic_search($arrParam);
			
			$data["view"] = 'metas_pdd';
			$this->load->view("layout_calendar", $data);
	}
	
    /**
     * Cargo modal - formulario _metas_pdd
     * @since 15/04/2022
     */
    public function cargarModalMetasPDD() 
	{
			header("Content-Type: text/plain; charset=utf-8"); //Para evitar problemas de acentos
			
			$data['information'] = FALSE;
			$data["idMetaPDD"] = $this->input->post("idMetaPDD");	
			
			if ($data["idMetaPDD"] != 'x') {
				$arrParam = array(
					"table" => "meta_pdd",
					"order" => "numero_meta_pdd",
					"column" => "id_meta_pdd",
					"id" => $data["idMetaPDD"]
				);
				$data['information'] = $this->general_model->get_basic_search($arrParam);
			}
			
			$this->load->view("metas_pdd_modal", $data);
    }
	
	/**
	 * Ingresar/Actualizar _metas_pdd
     * @since 15/04/2022
     * @author BMOTTAG
	 */
	public function save_metas_pdd()
	{			
			header('Content-Type: application/json');
			$data = array();
			
			$idMetaPDD = $this->input->post('hddId');
			
			$msj = "Se adicionó la Meta PDD!";
			if ($idMetaPDD != '') {
				$msj = "Se actualizó la Meta PDD!";
			}

			if ($idMetaPDD = $this->settings_model->saveMetasPDD()) {
				$data["result"] = true;				
				$this->session->set_flashdata('retornoExito', '<strong>Correcto!</strong> ' . $msj);
			} else {
				$data["result"] = "error";			
				$this->session->set_flashdata('retornoError', '<strong>Error!</strong> Ask for help');
			}

			echo json_encode($data);	
    }

	/**
	 * Lista de ODS
     * @since 16/04/2022
     * @author BMOTTAG
	 */
	public function ods()
	{
			$arrParam = array(
				"table" => "ods",
				"order" => "numero_ods",
				"id" => "x"
			);
			$data['info'] = $this->general_model->get_basic_search($arrParam);
			
			$data["view"] = 'ods';
			$this->load->view("layout_calendar", $data);
	}
	
    /**
     * Cargo modal - formulario ODS
     * @since 16/04/2022
     */
    public function cargarModalODS() 
	{
			header("Content-Type: text/plain; charset=utf-8"); //Para evitar problemas de acentos
			
			$data['information'] = FALSE;
			$data["idODS"] = $this->input->post("idODS");	
			
			if ($data["idODS"] != 'x') {
				$arrParam = array(
					"table" => "ods",
					"order" => "numero_ods",
					"column" => "id_ods",
					"id" => $data["idODS"]
				);
				$data['information'] = $this->general_model->get_basic_search($arrParam);
			}
			
			$this->load->view("ods_modal", $data);
    }
	
	/**
	 * Ingresar/Actualizar ODS
     * @since 16/04/2022
     * @author BMOTTAG
	 */
	public function save_ods()
	{			
			header('Content-Type: application/json');
			$data = array();
			
			$idODS = $this->input->post('hddId');
			
			$msj = "Se adicionó la ODS!";
			if ($idODS != '') {
				$msj = "Se actualizó la ODS!";
			}

			if ($idODS = $this->settings_model->saveODS()) {
				$data["result"] = true;				
				$this->session->set_flashdata('retornoExito', '<strong>Correcto!</strong> ' . $msj);
			} else {
				$data["result"] = "error";			
				$this->session->set_flashdata('retornoError', '<strong>Error!</strong> Ask for help');
			}

			echo json_encode($data);	
    }

	/**
	 * Lista de metas_proyectos
     * @since 16/04/2022
     * @author BMOTTAG
	 */
	public function metas_proyectos($vigencia=2022)
	{
			$data['vigencia']  = $vigencia;
			$arrParam = array('vigencia'=>$vigencia);
			$data['info'] = $this->general_model->get_meta_proyecto($arrParam);
			
			$data["view"] = 'meta_proyectos';
			$this->load->view("layout_calendar", $data);
	}
	
    /**
     * Cargo modal - formulario metas_proyectos
     * @since 16/04/2022
     */
    public function cargarModalMetasProyectos() 
	{
			header("Content-Type: text/plain; charset=utf-8"); //Para evitar problemas de acentos
			
			$data['information'] = FALSE;
			$data["idMetaProyecto"] = $this->input->post("idMetaProyecto");	

			$arrParam = array(
				"table" => "proyecto_inversion",
				"order" => "id_proyecto_inversion",
				"id" => "x"
			);
			$data['listaProyectos'] = $this->general_model->get_basic_search($arrParam);
			$arrParam = array(
				"table" => "param_tipologia_anualidad",
				"order" => "id_tipologia",
				"id" => "x"
			);
			$data['listaTipologia'] = $this->general_model->get_basic_search($arrParam);
			
			if ($data["idMetaProyecto"] != 'x') {
				$arrParam = array(
					"idMetaProyecto" => $data["idMetaProyecto"]
				);
				$data['information'] = $this->general_model->get_meta_proyecto($arrParam);
			}
			
			$this->load->view("meta_proyectos_modal", $data);
    }
	
	/**
	 * Ingresar/Actualizar metas_proyectos
     * @since 16/04/2022
     * @author BMOTTAG
	 */
	public function save_metas_proyectos()
	{			
			header('Content-Type: application/json');
			$data = array();
			
			$idMetaProyecto = $this->input->post('hddId');
			
			$msj = "Se adicionó la Meta Proyecto de Inversión!";
			if ($idMetaProyecto != '') {
				$msj = "Se actualizó la la Meta Proyecto de Inversión!";
			}

			if ($idMetaProyecto = $this->settings_model->saveMetasProyectos()) {
				$data["result"] = true;				
				$this->session->set_flashdata('retornoExito', '<strong>Correcto!</strong> ' . $msj);
			} else {
				$data["result"] = "error";			
				$this->session->set_flashdata('retornoError', '<strong>Error!</strong> Ask for help');
			}

			echo json_encode($data);	
    }

	/**
	 * Evio de correo
     * @since 11/3/2021
     * @author BMOTTAG
	 */
	public function email($idUsuario)
	{
			$arrParam = array('idUser' => $idUsuario);
			$infoUsuario = $this->general_model->get_user($arrParam);
			$to = $infoUsuario[0]['email'];

			//reiniciar primero la contraseña del usuario a Jardin2021 y estado colocarlo en cero
			$arrParam['passwd'] = 'Jardin2022';
			$resetPassword = $this->settings_model->resetEmployeePassword($arrParam);

			//busco datos parametricos de configuracion para envio de correo
			$arrParam2 = array(
				"table" => "parametros",
				"order" => "id_parametro",
				"id" => "x"
			);
			$parametric = $this->general_model->get_basic_search($arrParam2);

			$paramHost = $parametric[0]["parametro_valor"];
			$paramUsername = $parametric[1]["parametro_valor"];
			$paramPassword = $parametric[2]["parametro_valor"];
			$paramFromName = $parametric[3]["parametro_valor"];
			$paramCompanyName = $parametric[4]["parametro_valor"];
			$paramAPPName = $parametric[5]["parametro_valor"];

			//mensaje del correo
			$msj = '<p>Sr.(a) ' . $infoUsuario[0]['first_name'] . ' se activo su ingreso a la plataforma de Programa Institucional - Cuadro de Mando del ' . $paramCompanyName . ',';
			$msj .= ' siga el enlace con las credenciales para acceder.</p>';
			$msj .= '<p>Recuerde cambiar su contraseña para activar su cuenta.</p>';
			$msj .= '<p><strong>Enlace: </strong>' . base_url();
			$msj .= '<br><strong>Usuario: </strong>' . $infoUsuario[0]['log_user'];
			$msj .= '<br><strong>Contraseña: </strong>' . $arrParam['passwd'];
									
			$mensaje = "<p>$msj</p>
						<p>Cordialmente,</p>
						<p><strong>$paramCompanyName</strong></p>";		

			require_once(APPPATH.'libraries/PHPMailer/class.phpmailer.php');
            $mail = new PHPMailer(true);

            try {
                    $mail->IsSMTP(); // set mailer to use SMTP
                    $mail->Host = $paramHost; // specif smtp server
                    $mail->SMTPSecure= "tls"; // Used instead of TLS when only POP mail is selected
                    $mail->Port = 587; // Used instead of 587 when only POP mail is selected
                    $mail->SMTPAuth = true;
					$mail->Username = $paramUsername; // SMTP username
                    $mail->Password = $paramPassword; // SMTP password
                    $mail->FromName = $paramFromName;
                    $mail->From = $paramUsername;
                    $mail->AddAddress($to, 'Usuario JBB Bienes');
                    $mail->WordWrap = 50;
                    $mail->CharSet = 'UTF-8';
                    $mail->IsHTML(true); // set email format to HTML
                    $mail->Subject = $paramCompanyName . ' - ' . $paramAPPName;

                    $mail->Body = nl2br ($mensaje,false);

                    $data['linkBack'] = "settings/users";
					$data['titulo'] = "<i class='fa fa-unlock fa-fw'></i>CAMBIAR CONTRASEÑA";

                    if($mail->Send())
                    {
						$data['msj'] = 'Se actualizó la contraseña del usuario.';
						$data['msj'] .= '<br>';
						$data['msj'] .= '<br><strong>Nombre Usuario: </strong>' . $infoUsuario[0]['first_name'];
						$data['msj'] .= '<br><strong>Contraseña: </strong>' . $arrParam['passwd'];
						$data['msj'] .= '<br><br><p>La información con los datos de ingreso fue enviada al correo electrónico del usuario, quien debe cambiar la contraseña para activar la cuenta.</p>';
						$data['clase'] = 'alert-success';

                        $this->session->set_flashdata('retorno_exito', 'Creaci&oacute;n de usuario exitosa!. La informaci&oacute;n para activar su cuenta fu&eacute; enviada al correo registrado, recuerde aceptar los t&eacute;rminos y condiciones y cambiar su contrase&ntilde;a');
                        //redirect(base_url(), 'refresh');
                        //exit;

                    }else{
						$data['msj'] = 'Se actualizó la contraseña del usuario, sin embargo no se pudo enviar el correo electrónico.';
						$data['msj'] .= '<br>';
						$data['msj'] .= '<br><strong>Nombre Usuario: </strong>' . $infoUsuario[0]['first_name'];
						$data['msj'] .= '<br><strong>Contraseña: </strong>' . $arrParam['passwd'];
						$data['clase'] = 'alert-success';

                        $this->session->set_flashdata('retorno_error', 'Se creo la persona, sin embargo no se pudo enviar el correo electr&oacute;nico');
                       // redirect(base_url(), 'refresh');
                       //exit;

                    }

					$data["view"] = "template/answer";
					$this->load->view("layout", $data);

                }catch (Exception $e){
                                print_r($e->getMessage());
                                        exit;
                }

	}

	/**
	 * Genera todas las imagenes de QR de os equipos
     * @since 20/3/2021
     * @author BMOTTAG
	 */
	public function generarImagenesQREquipos()
	{
				//primero eliminar imagenes de QR
				$files = glob('images/equipos/QR/*.png'); //obtenemos todos los nombres de los ficheros

				foreach($files as $file){
				    if(is_file($file))
				    unlink($file); //elimino el fichero
				}

				//informacion equipos
				$arrParam = array('estadoEquipo' => 1);	
				$infoEquipos = $this->general_model->get_equipos_info($arrParam);

				$this->load->library('ciqrcode');

				$tot = count($infoEquipos);
				for ($i = 0; $i < $tot; $i++) 
				{
					//INCIO - genero imagen con la libreria y la subo 
					$valorQRcode = base_url('login/index/' . $infoEquipos[$i]['qr_code_encryption']);
					$rutaImagen = $infoEquipos[$i]['qr_code_img'];
					
					$params['data'] = $valorQRcode;
					$params['level'] = 'H';
					$params['size'] = 10;
					$params['savename'] = FCPATH.$rutaImagen;
									
					$this->ciqrcode->generate($params);
					//FIN - genero imagen con la libreria y la subo
				}
				
				$this->session->set_flashdata('retornoExito', '<strong>Correcto!</strong> Se actualizarón las imagenes de QR de los equipos');
				
				redirect("/equipos",'refresh');
	}

	/**
	 * objetivos_estrategicos
     * @since 26/04/2022
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
     * Cargo modal - formulario objetivos_estrategicos
     * @since 26/04/2022
     */
    public function cargarModalObjetivosEstrategicos() 
	{
			header("Content-Type: text/plain; charset=utf-8"); //Para evitar problemas de acentos
			
			$data['information'] = FALSE;
			$data["idObjetivoEstrategico"] = $this->input->post("idObjetivoEstrategico");	
	
			$arrParam = array(
				"table" => "estrategias",
				"order" => "id_estrategia",
				"id" => "x"
			);
			$data['estrategias'] = $this->general_model->get_basic_search($arrParam);

			if ($data["idObjetivoEstrategico"] != 'x') {
				$arrParam = array(
					"idObjetivoEstrategico" => $data["idObjetivoEstrategico"]
				);
				$data['information'] = $this->general_model->get_objetivos_estrategicos($arrParam);
			}			
			$this->load->view("objetivos_estrategicos_modal", $data);
    }
	
	/**
	 * Update objetivos_estrategicos
     * @since 26/04/2022
     * @author BMOTTAG
	 */
	public function save_objetivos_estrategicos()
	{			
			header('Content-Type: application/json');
			$data = array();
			
			$idObjetivoEstrategico = $this->input->post('hddId');

			$msj = "Se adicionó el Objetivo Estratégico!";
			if ($idObjetivoEstrategico != '') {
				$msj = "Se actualizó el Objetivo Estratégico!";
			}			

			if ($idObjetivoEstrategico = $this->settings_model->saveObjetivo()) {
				$data["result"] = true;				
				$this->session->set_flashdata('retornoExito', '<strong>Correcto!</strong> ' . $msj);
			} else {
				$data["result"] = "error";			
				$this->session->set_flashdata('retornoError', '<strong>Error!</strong> Ask for help');
			}

			echo json_encode($data);
    }

	/**
	 * Delete ODS
     * @since 26/4/2022
	 */
	public function delete_ods()
	{			
			header('Content-Type: application/json');
			$data = array();
			
			$idODS = $this->input->post('identificador');

			$arrParam = array(
				"idODS" => $idODS
			);
			$infoCuadroBase = $this->general_model->get_lista_cuadro_mando($arrParam);

			if($infoCuadroBase){
					$data["result"] = "error";
					$data["mensaje"] = "Error!!! No se puede eliminar porque la ODS ya esta relacionada en un Plan de Desarrollo Distrital.";
					$this->session->set_flashdata('retornoError', '<strong>Error!</strong> Ask for help');
			}else{
				$arrParam = array(
					"table" => "ods",
					"primaryKey" => "id_ods",
					"id" => $idODS
				);
				
				if ($this->general_model->deleteRecord($arrParam)) 
				{
					$data["result"] = true;
					$this->session->set_flashdata('retornoExito', '<strong>Correcto!</strong> Se eliminó la ODS.');
				} else {
					$data["result"] = "error";
					$data["mensaje"] = "Error!!! Ask for help.";
					$this->session->set_flashdata('retornoError', '<strong>Error!</strong> Ask for help');
				}
			}
			
			echo json_encode($data);
    }

	/**
	 * Delete meta pdd
     * @since 26/4/2022
	 */
	public function delete_meta_pdd()
	{			
			header('Content-Type: application/json');
			$data = array();
			
			$idMetaPDD = $this->input->post('identificador');

			$arrParam = array(
				"idMetaPDD" => $idMetaPDD
			);
			$infoCuadroBase = $this->general_model->get_lista_cuadro_mando($arrParam);

			if($infoCuadroBase){
					$data["result"] = "error";
					$data["mensaje"] = "Error!!! No se puede eliminar porque la Meta PDD ya esta relacionada en un Plan de Desarrollo Distrital.";
					$this->session->set_flashdata('retornoError', '<strong>Error!</strong> Ask for help');
			}else{
				$arrParam = array(
					"table" => "meta_pdd",
					"primaryKey" => "id_meta_pdd",
					"id" => $idMetaPDD
				);
				
				if ($this->general_model->deleteRecord($arrParam)) 
				{
					$data["result"] = true;
					$this->session->set_flashdata('retornoExito', '<strong>Correcto!</strong> Se eliminó la Meta PDD.');
				} else {
					$data["result"] = "error";
					$data["mensaje"] = "Error!!! Ask for help.";
					$this->session->set_flashdata('retornoError', '<strong>Error!</strong> Ask for help');
				}
			}
			
			echo json_encode($data);
    }

	/**
	 * Delete programa
     * @since 26/4/2022
	 */
	public function delete_programa()
	{			
			header('Content-Type: application/json');
			$data = array();
			
			$idPrograma = $this->input->post('identificador');

			$arrParam = array(
				"idPrograma" => $idPrograma
			);
			$infoCuadroBase = $this->general_model->get_lista_cuadro_mando($arrParam);

			if($infoCuadroBase){
					$data["result"] = "error";
					$data["mensaje"] = "Error!!! No se puede eliminar porque el Programa Estratégico ya esta relacionada en un Plan de Desarrollo Distrital.";
					$this->session->set_flashdata('retornoError', '<strong>Error!</strong> Ask for help');
			}else{
				$arrParam = array(
					"table" => "programa_estrategico",
					"primaryKey" => "id_programa_estrategico",
					"id" => $idPrograma
				);
				
				if ($this->general_model->deleteRecord($arrParam)) 
				{
					$data["result"] = true;
					$this->session->set_flashdata('retornoExito', '<strong>Correcto!</strong> Se eliminó el Programa Estratégico.');
				} else {
					$data["result"] = "error";
					$data["mensaje"] = "Error!!! Ask for help.";
					$this->session->set_flashdata('retornoError', '<strong>Error!</strong> Ask for help');
				}
			}
			
			echo json_encode($data);
    }

	/**
	 * Delete logro
     * @since 26/4/2022
	 */
	public function delete_logro()
	{			
			header('Content-Type: application/json');
			$data = array();
			
			$idLogro = $this->input->post('identificador');

			$arrParam = array(
				"idLogro" => $idLogro
			);
			$infoCuadroBase = $this->general_model->get_lista_cuadro_mando($arrParam);

			if($infoCuadroBase){
					$data["result"] = "error";
					$data["mensaje"] = "Error!!! No se puede eliminar porque el Logro ya esta relacionada en un Plan de Desarrollo Distrital.";
					$this->session->set_flashdata('retornoError', '<strong>Error!</strong> Ask for help');
			}else{
				$arrParam = array(
					"table" => "logros",
					"primaryKey" => "id_logros",
					"id" => $idLogro
				);
				
				if ($this->general_model->deleteRecord($arrParam)) 
				{
					$data["result"] = true;
					$this->session->set_flashdata('retornoExito', '<strong>Correcto!</strong> Se eliminó el Logro.');
				} else {
					$data["result"] = "error";
					$data["mensaje"] = "Error!!! Ask for help.";
					$this->session->set_flashdata('retornoError', '<strong>Error!</strong> Ask for help');
				}
			}
			
			echo json_encode($data);
    }

	/**
	 * Delete proposito
     * @since 26/4/2022
	 */
	public function delete_proposito()
	{			
			header('Content-Type: application/json');
			$data = array();
			
			$idProposito = $this->input->post('identificador');

			$arrParam = array(
				"idProposito" => $idProposito
			);
			$infoCuadroBase = $this->general_model->get_lista_cuadro_mando($arrParam);

			if($infoCuadroBase){
					$data["result"] = "error";
					$data["mensaje"] = "Error!!! No se puede eliminar porque el Propósito ya esta relacionada en un Plan de Desarrollo Distrital.";
					$this->session->set_flashdata('retornoError', '<strong>Error!</strong> Ask for help');
			}else{
				$arrParam = array(
					"table" => "propositos",
					"primaryKey" => "id_proposito",
					"id" => $idProposito
				);
				
				if ($this->general_model->deleteRecord($arrParam)) 
				{
					$data["result"] = true;
					$this->session->set_flashdata('retornoExito', '<strong>Correcto!</strong> Se eliminó el Propósito.');
				} else {
					$data["result"] = "error";
					$data["mensaje"] = "Error!!! Ask for help.";
					$this->session->set_flashdata('retornoError', '<strong>Error!</strong> Ask for help');
				}
			}
			
			echo json_encode($data);
    }

	/**
	 * Delete meta proyecto inversion
     * @since 26/4/2022
	 */
	public function delete_meta_proyecto()
	{			
			header('Content-Type: application/json');
			$data = array();
			
			$idMetaProyecto = $this->input->post('identificador');

			$arrParam = array(
				"idMetaProyecto" => $idMetaProyecto
			);
			$infoCuadroBase = $this->general_model->get_lista_cuadro_mando($arrParam);

			if($infoCuadroBase){
					$data["result"] = "error";
					$data["mensaje"] = "Error!!! No se puede eliminar porque la Meta Proyecto Inversión ya esta relacionada en un Plan de Desarrollo Distrital.";
					$this->session->set_flashdata('retornoError', '<strong>Error!</strong> Ask for help');
			}else{
				$arrParam = array(
					"table" => "meta_proyecto_inversion",
					"primaryKey" => "id_meta_proyecto_inversion",
					"id" => $idMetaProyecto
				);
				
				if ($this->general_model->deleteRecord($arrParam)) 
				{
					$data["result"] = true;
					$this->session->set_flashdata('retornoExito', '<strong>Correcto!</strong> Se eliminó el Propósito.');
				} else {
					$data["result"] = "error";
					$data["mensaje"] = "Error!!! Ask for help.";
					$this->session->set_flashdata('retornoError', '<strong>Error!</strong> Ask for help');
				}
			}
			
			echo json_encode($data);
    }

	/**
	 * PLAN ESTRATEGICO
	 * @since 30/04/2022
	 */
	public function plan_estrategico()
	{				
			$arrParam = array();
			$data['listaObjetivosEstrategicos'] = $this->general_model->get_objetivos_estrategicos($arrParam);

			$arrParam = array(
				"table" => "estrategias",
				"order" => "estrategia",
				"id" => "x"
			);
			$data['listaEstrategias'] = $this->general_model->get_basic_search($arrParam);

			$data["view"] = "plan_estrategico";
			$this->load->view("layout_calendar", $data);
	}

    /**
     * Cargo modal - formulario cuadro base
     * @since 16/04/2022
     */
    public function cargarModalCuadroBase() 
	{
			header("Content-Type: text/plain; charset=utf-8"); //Para evitar problemas de acentos
			
			$data['information'] = FALSE;
			$data["numeroObjetivoEstrategico"] = $this->input->post("numeroObjetivoEstrategico");
			$data["idCuadroBase"] = $this->input->post("idCuadroBase");

			if ($data["idCuadroBase"] != 'x') {
				$arrParam = array("idCuadroBase" => $data["idCuadroBase"]);
				$data['information'] = $this->general_model->get_lista_cuadro_mando($arrParam);//info bloques
				
				$data["numeroObjetivoEstrategico"] = $data['information'][0]['fk_numero_objetivo_estrategico'];
			}

			$arrParam = array("numeroObjetivoEstrategico" => $data["numeroObjetivoEstrategico"]);
			$data['infoObjetivoEstrategico'] = $this->general_model->get_objetivos_estrategicos($arrParam);

			$arrParam = array(
				"table" => "proyecto_inversion",
				"order" => "id_proyecto_inversion",
				"id" => "x"
			);
			$data['listaProyectos'] = $this->general_model->get_basic_search($arrParam);

			$arrParam = array(
				"table" => "meta_proyecto_inversion",
				"order" => "numero_meta_proyecto",
				"id" => "x"
			);
			$data['listaMetasProyectos'] = $this->general_model->get_basic_search($arrParam);

			$arrParam = array(
				"table" => " propositos",
				"order" => "numero_proposito",
				"id" => "x"
			);
			$data['listaPropositos'] = $this->general_model->get_basic_search($arrParam);

			$arrParam = array(
				"table" => " logros",
				"order" => "numero_logro",
				"id" => "x"
			);
			$data['listaLogros'] = $this->general_model->get_basic_search($arrParam);

			$arrParam = array(
				"table" => " programa",
				"order" => "numero_programa",
				"id" => "x"
			);
			$data['listaProgramaSEGPLAN'] = $this->general_model->get_basic_search($arrParam);

			$arrParam = array(
				"table" => " programa_estrategico",
				"order" => "numero_programa_estrategico",
				"id" => "x"
			);
			$data['listaPrograma'] = $this->general_model->get_basic_search($arrParam);

			$arrParam = array(
				"table" => "meta_pdd",
				"order" => "numero_meta_pdd",
				"id" => "x"
			);
			$data['listaMetasPDD'] = $this->general_model->get_basic_search($arrParam);

			$arrParam = array(
				"table" => "ods",
				"order" => "numero_ods",
				"id" => "x"
			);
			$data['listaODS'] = $this->general_model->get_basic_search($arrParam);

			$arrParam = array(
				"table" => "param_dimensiones_mipg",
				"order" => "id_dimension",
				"id" => "x"
			);
			$data['listaDimensionesMIPG'] = $this->general_model->get_basic_search($arrParam);
						
			$this->load->view("plan_estrategico_modal", $data);
    }
	
	/**
	 * Ingresar/Actualizar cuadro base
     * @since 16/04/2022
     * @author BMOTTAG
	 */
	public function save_cuadro_base()
	{			
			header('Content-Type: application/json');
			$data = array();
			
			$idCuadroBase = $this->input->post('hddIdCuadroBase');
		
			$msj = "Se adicionó la información!";
			if ($idCuadroBase != 'x') {
				$msj = "Se actualizó la información!";
			}

			if ($this->settings_model->savePlanEstrategico()) {
				$data["result"] = true;				
				$this->session->set_flashdata('retornoExito', '<strong>Correcto!</strong> ' . $msj);
			} else {
				$data["result"] = "error";			
				$this->session->set_flashdata('retornoError', '<strong>Error!</strong> Ask for help');
			}

			echo json_encode($data);	
    }

	/**
	 * Delete Plan Estrategico
     * @since 26/4/2022
	 */
	public function delete_plan_estrategico()
	{			
			header('Content-Type: application/json');
			$data = array();
			
			$idCuadroBase = $this->input->post('identificador');

			$arrParam = array(
				"idCuadroBase" => $idCuadroBase
			);
			$infoActividades = $this->general_model->get_actividades($arrParam);

			if($infoActividades){
					$data["result"] = "error";
					$data["mensaje"] = "Error!!! No se puede eliminar porque el Plan de Desarrollo Distrital ya tiene asignas Actividades.";
					$this->session->set_flashdata('retornoError', '<strong>Error!</strong> Ask for help');
			}else{
				$arrParam = array(
					"table" => "cuadro_base ",
					"primaryKey" => "id_cuadro_base",
					"id" => $idCuadroBase
				);
				
				if ($this->general_model->deleteRecord($arrParam)) 
				{
					$data["result"] = true;
					$this->session->set_flashdata('retornoExito', '<strong>Correcto!</strong> Se eliminó el Plan de Desarrollo Distrital.');
				} else {
					$data["result"] = "error";
					$data["mensaje"] = "Error!!! Ask for help.";
					$this->session->set_flashdata('retornoError', '<strong>Error!</strong> Ask for help');
				}
			}
			
			echo json_encode($data);
    }

	/**
	 * Lista de propositos
     * @since 1/06/2022
     * @author BMOTTAG
	 */
	public function area_responsable()
	{
			$arrParam = array(
				"table" => "param_area_responsable",
				"order" => "area_responsable ",
				"id" => "x"
			);
			$data['info'] = $this->general_model->get_basic_search($arrParam);
			
			$data["view"] = 'area_responsable';
			$this->load->view("layout_calendar", $data);
	}
	
    /**
     * Cargo modal - formulario propositos
     * @since 1/06/2022
     */
    public function cargarModalAreaResponsable() 
	{
			header("Content-Type: text/plain; charset=utf-8"); //Para evitar problemas de acentos
			
			$data['information'] = FALSE;
			$data["idAreaResponsable"] = $this->input->post("idAreaResponsable");	
			
			if ($data["idAreaResponsable"] != 'x') {
				$arrParam = array(
					"table" => "param_area_responsable",
					"order" => "area_responsable",
					"column" => "id_area_responsable",
					"id" => $data["idAreaResponsable"]
				);
				$data['information'] = $this->general_model->get_basic_search($arrParam);
			}
			
			$this->load->view("area_responsable_modal", $data);
    }
	
	/**
	 * Ingresar/Actualizar propositos
     * @since 1/06/2022
     * @author BMOTTAG
	 */
	public function save_area_responsable()
	{			
			header('Content-Type: application/json');
			$data = array();
			
			$idAreaResponsable = $this->input->post('hddId');
			
			$msj = "Se adicionó el Área Responsable!";
			if ($idAreaResponsable != '') {
				$msj = "Se actualizó el Área Responsable!";
			}

			if ($idAreaResponsable = $this->settings_model->saveAreaResponsable()) {
				$data["result"] = true;				
				$this->session->set_flashdata('retornoExito', '<strong>Correcto!</strong> ' . $msj);
			} else {
				$data["result"] = "error";			
				$this->session->set_flashdata('retornoError', '<strong>Error!</strong> Ask for help');
			}

			echo json_encode($data);	
    }

	/**
	 * Lista Meta Proyecto filtrada por Proyecto de Inversión
     * @since 08/06/2022
     * @author BMOTTAG
	 */
    public function metaProyectoList() {
        header("Content-Type: text/plain; charset=utf-8"); //Para evitar problemas de acentos
		$numeroProyecto = $this->input->post('numeroProyecto');

		$arrParam = array(
			"numeroProyecto" => $numeroProyecto,
			"vigencia" => date('Y')
		);
		$lista= $this->general_model->get_meta_proyecto($arrParam);

		echo "<option value=''>Seleccione...</option>";
		if ($lista) {
			foreach ($lista as $fila) {
				echo "<option value='" . $fila["nu_meta_proyecto"] . "' >" . $fila["vigencia"] . '-' . $fila["numero_meta_proyecto"] . ' ' . $fila["meta_proyecto"] . "</option>";
			}
		}			

    }

	/**
	 * metas_objetivos_estrategicos
     * @since 20/06/2022
     * @author BMOTTAG
	 */
	public function metas_objetivos_estrategicos()
	{			
			$arrParam = array();
			$data['info'] = $this->general_model->get_lista_metas($arrParam);
			
			$data["view"] = 'metas_objetivos_estrategicos';
			$this->load->view("layout_calendar", $data);
	}
	
    /**
     * Cargo modal - formulario metas_objetivos_estrategicos
     * @since 20/06/2022
     */
    public function cargarModalMetasObjetivosEstrategicos() 
	{
			header("Content-Type: text/plain; charset=utf-8"); //Para evitar problemas de acentos
			
			$data['information'] = FALSE;
			$data["idMetaObjetivoEstrategico"] = $this->input->post("idMetaObjetivoEstrategico");	
	
			$arrParam = array();
			$data['listaObjetivosEstrategicos'] = $this->general_model->get_objetivos_estrategicos($arrParam);

			if ($data["idMetaObjetivoEstrategico"] != 'x') {
				$arrParam = array(
					"idMetaObjetivoEstrategico" => $data["idMetaObjetivoEstrategico"]
				);
				$data['information'] = $this->general_model->get_lista_metas($arrParam);
			}			
			$this->load->view("metas_objetivos_estrategicos_modal", $data);
    }
	
	/**
	 * Update mestas_objetivos_estrategicos
     * @since 20/04/2022
     * @author BMOTTAG
	 */
	public function save_metas_objetivos_estrategicos()
	{			
			header('Content-Type: application/json');
			$data = array();
			
			$idMetaObjetivoEstrategico = $this->input->post('hddId');

			$msj = "Se adicionó la Meta del Objetivo Estratégico!";
			if ($idMetaObjetivoEstrategico != '') {
				$msj = "Se actualizó la Meta del Objetivo Estratégico!";
			}			

			if ($idMetaObjetivoEstrategico = $this->settings_model->saveMetaObjetivo()) {
				$data["result"] = true;				
				$this->session->set_flashdata('retornoExito', '<strong>Correcto!</strong> ' . $msj);
			} else {
				$data["result"] = "error";			
				$this->session->set_flashdata('retornoError', '<strong>Error!</strong> Ask for help');
			}

			echo json_encode($data);
    }

	/**
	 * Formulario para eliminar registros de la base de datos
     * @since 20/06/2022
	 */
	public function atencion_eliminar()
	{
			$userRol = $this->session->role;
			if ($userRol != 99 &&  $userRol != 1) { 
				show_error('ERROR!!! - You are in the wrong place.');	
			}
		
			$data["view"] = 'eliminar_db';
			$this->load->view("layout_calendar", $data);
	}

	/**
	 * Eliminar registros de la base de datos
	 * @since 20/06/2022
	 */
	public function eliminar_db()
	{
			header('Content-Type: application/json');
			$data = array();

			if ($this->settings_model->eliminarRegistrosActividades()) {
				
				$data["msj"] = "las tablas actividades, actividad_historial, actividad_estado, actividad_ejecucion, actividades";
				
				$data["result"] = true;
				$data["mensaje"] = "Se eliminaron los registros.";
				$this->session->set_flashdata('retornoExito', 'Se eliminó los registros de ' . $data["msj"]);
			}else{
				$data["result"] = "error";
				$data["mensaje"] = "Error!!! Contactarse con el Administrador.";
				$this->session->set_flashdata('retornoError', '<strong>Error!!!</strong> Contactarse con el Administrador');
			}

			echo json_encode($data);
	}

	/**
	 * Eliminar metas objetivos estrategicos
	 * @since 20/06/2022
	 */
	public function eliminar_metas_objetivos_db()
	{
			header('Content-Type: application/json');
			$data = array();

			if ($this->settings_model->eliminarMetasObjetivos()) {
				
				$data["msj"] = " la tabla objetivos_estrategicos_metas.";

				$data["result"] = true;
				$data["mensaje"] = "Se eliminaron los registros.";
				$this->session->set_flashdata('retornoExito', 'Se eliminó los registros de ' . $data["msj"]);
			}else{
				$data["result"] = "error";
				$data["mensaje"] = "Error!!! Contactarse con el Administrador.";
				$this->session->set_flashdata('retornoError', '<strong>Error!!!</strong> Contactarse con el Administrador');
			}

			echo json_encode($data);
	}

	/**
	 * Eliminar INDICADORES objetivos estrategicos
	 * @since 20/06/2022
	 */
	public function eliminar_indicadores_objetivos_db()
	{
			header('Content-Type: application/json');
			$data = array();

			if ($this->settings_model->eliminarIndicadoresObjetivos()) {
				
				$data["msj"] = " la tabla objetivos_estrategicos_indicadores.";

				$data["result"] = true;
				$data["mensaje"] = "Se eliminaron los registros.";
				$this->session->set_flashdata('retornoExito', 'Se eliminó los registros de ' . $data["msj"]);
			}else{
				$data["result"] = "error";
				$data["mensaje"] = "Error!!! Contactarse con el Administrador.";
				$this->session->set_flashdata('retornoError', '<strong>Error!!!</strong> Contactarse con el Administrador');
			}

			echo json_encode($data);
	}

	/**
	 * Eliminar RESULTADOS objetivos estrategicos
	 * @since 20/06/2022
	 */
	public function eliminar_resultados_objetivos_db()
	{
			header('Content-Type: application/json');
			$data = array();

			if ($this->settings_model->eliminarResultadosObjetivos()) {
				
				$data["msj"] = " la tabla objetivos_estrategicos_resultados.";

				$data["result"] = true;
				$data["mensaje"] = "Se eliminaron los registros.";
				$this->session->set_flashdata('retornoExito', 'Se eliminó los registros de ' . $data["msj"]);
			}else{
				$data["result"] = "error";
				$data["mensaje"] = "Error!!! Contactarse con el Administrador.";
				$this->session->set_flashdata('retornoError', '<strong>Error!!!</strong> Contactarse con el Administrador');
			}

			echo json_encode($data);
	}

	/**
	 * Cargar la informacion
     * @since 20/06/2022
	 */
	public function subir_archivo($model, $error="", $success="")
	{		
			$data["error"] = $error;
			$data["success"] = $success;
			$data["view"] = "cargar_informacion";
			$data["model"] = $model;
			$this->load->view("layout_calendar", $data);
	}

	/**
	 *Cargue de archivo
     * @since 20/06/2022
	 */
	public function do_upload($model)
	{		
            $config['upload_path'] = './tmp/';
            $config['overwrite'] = true;
            $config['allowed_types'] = 'csv';
            $config['max_size'] = '5000';
            $config['file_name'] = $model . '.csv';

            $this->load->library('upload', $config);
            $bandera = false;
            if (!$this->upload->do_upload()) {
                $error = $this->upload->display_errors();
                $msgError = html_escape(substr($error, 3, -4));
                $this->subir_archivo($msgError);
            }else {
                $file_info = $this->upload->data();
                $data = array('upload_data' => $this->upload->data());

                $archivo = $file_info['file_name'];

				$registros = array();
				if (($fichero = fopen(FCPATH . 'tmp/' . $archivo, "a+")) !== FALSE) {
					// Lee los nombres de los campos
					$nombres_campos = fgetcsv($fichero, 0, ";");
					$num_campos = count($nombres_campos);
					// Lee los registros

					while (($datos = fgetcsv($fichero, 0, ";")) !== FALSE) {
						// Crea un array asociativo con los nombres y valores de los campos
						for ($icampo = 0; $icampo < $num_campos; $icampo++) {
							$registro[$nombres_campos[$icampo]] = utf8_encode($datos[$icampo]);
						}
						// Añade el registro leido al array de registros
						$registros[] = $registro;
					}
					fclose($fichero);

					$x=0;
					$errores = array();
					$bandera = false;
					foreach ($registros as $lista) {
						$x++;
						if ($this->settings_model->$model($lista)) {
							if($model == "cargar_actividades"){
								//cargo registros en la tabla de estado actividad
								$this->settings_model->cargar_actividades_estados($lista);
							}
						}else{
							$errores["numero_registro"] = $x;
							$bandera = true;
						}
					}
				}
            }
			
			$vista = $model;

			$success = 'El archivo se cargó correctamente.';

			if($bandera){
				$registros = implode(",", $errores["numero_registro"]);
				$success = 'El archivo se cargó pero hay errores en los siguientes registros:::' . $registros;
			}
			$this->subir_archivo($vista,'', $success);
    }

	/**
	 * Indicadores_objetivos_estrategicos
     * @since 20/06/2022
     * @author BMOTTAG
	 */
	public function indicadores_objetivos_estrategicos()
	{			
			$arrParam = array();
			$data['info'] = $this->general_model->get_lista_indicadores($arrParam);
			
			$data["view"] = 'indicadores_objetivos_estrategicos';
			$this->load->view("layout_calendar", $data);
	}
	
    /**
     * Cargo modal - formulario Indicadores_objetivos_estrategicos
     * @since 20/06/2022
     */
    public function cargarModalIndicadoresObjetivosEstrategicos() 
	{
			header("Content-Type: text/plain; charset=utf-8"); //Para evitar problemas de acentos
			
			$data['information'] = FALSE;
			$data["idIndicadorObjetivoEstrategico"] = $this->input->post("idIndicadorObjetivoEstrategico");	
	
			$arrParam = array();
			$data['listaObjetivosEstrategicos'] = $this->general_model->get_objetivos_estrategicos($arrParam);

			if ($data["idIndicadorObjetivoEstrategico"] != 'x') {
				$arrParam = array(
					"idIndicadorObjetivoEstrategico" => $data["idIndicadorObjetivoEstrategico"]
				);
				$data['information'] = $this->general_model->get_lista_indicadores($arrParam);
			}			
			$this->load->view("indicadores_objetivos_estrategicos_modal", $data);
    }
	
	/**
	 * Update indicadores_objetivos_estrategicos
     * @since 20/04/2022
     * @author BMOTTAG
	 */
	public function save_indicadores_objetivos_estrategicos()
	{			
			header('Content-Type: application/json');
			$data = array();
			
			$idIndicadorObjetivoEstrategico = $this->input->post('hddId');

			$msj = "Se adicionó el Indicador del Objetivo Estratégico!";
			if ($idIndicadorObjetivoEstrategico != '') {
				$msj = "Se actualizó el Indicador del Objetivo Estratégico!";
			}			

			if ($idIndicadorObjetivoEstrategico = $this->settings_model->saveIndicadorObjetivo()) {
				$data["result"] = true;				
				$this->session->set_flashdata('retornoExito', '<strong>Correcto!</strong> ' . $msj);
			} else {
				$data["result"] = "error";			
				$this->session->set_flashdata('retornoError', '<strong>Error!</strong> Ask for help');
			}

			echo json_encode($data);
    }

	/**
	 * resultados_objetivos_estrategicos
     * @since 20/06/2022
     * @author BMOTTAG
	 */
	public function resultados_objetivos_estrategicos()
	{			
			$arrParam = array();
			$data['info'] = $this->general_model->get_lista_resultados($arrParam);
			
			$data["view"] = 'resultados_objetivos_estrategicos';
			$this->load->view("layout_calendar", $data);
	}
	
    /**
     * Cargo modal - formulario resultados_objetivos_estrategicos
     * @since 20/06/2022
     */
    public function cargarModalResultadosObjetivosEstrategicos() 
	{
			header("Content-Type: text/plain; charset=utf-8"); //Para evitar problemas de acentos
			
			$data['information'] = FALSE;
			$data["idResultadoObjetivoEstrategico"] = $this->input->post("idResultadoObjetivoEstrategico");	
	
			$arrParam = array();
			$data['listaObjetivosEstrategicos'] = $this->general_model->get_objetivos_estrategicos($arrParam);

			if ($data["idResultadoObjetivoEstrategico"] != 'x') {
				$arrParam = array(
					"idResultadoObjetivoEstrategico" => $data["idResultadoObjetivoEstrategico"]
				);
				$data['information'] = $this->general_model->get_lista_resultados($arrParam);
			}			
			$this->load->view("resultados_objetivos_estrategicos_modal", $data);
    }
	
	/**
	 * Update resultados_objetivos_estrategicos
     * @since 20/04/2022
     * @author BMOTTAG
	 */
	public function save_resultados_objetivos_estrategicos()
	{			
			header('Content-Type: application/json');
			$data = array();
			
			$idResultadoObjetivoEstrategico = $this->input->post('hddId');

			$msj = "Se adicionó el Resultado del Objetivo Estratégico!";
			if ($idResultadoObjetivoEstrategico != '') {
				$msj = "Se actualizó el Resultado del Objetivo Estratégico!";
			}			

			if ($idResultadoObjetivoEstrategico = $this->settings_model->saveResultadoObjetivo()) {
				$data["result"] = true;				
				$this->session->set_flashdata('retornoExito', '<strong>Correcto!</strong> ' . $msj);
			} else {
				$data["result"] = "error";			
				$this->session->set_flashdata('retornoError', '<strong>Error!</strong> Ask for help');
			}

			echo json_encode($data);
    }
	
    /**
     * Cargo modal - formulario para importar una actividad de otro cuadro base
     * @since 23/06/2022
     */
    public function cargarModalImportarActividad() 
	{
			header("Content-Type: text/plain; charset=utf-8"); //Para evitar problemas de acentos
			
			$data['information'] = FALSE;
			$data["numeroObjetivoEstrategico"] = $this->input->post("numeroObjetivoEstrategico");
			$data["idCuadroBase"] = $this->input->post("idCuadroBase");

			if ($data["idCuadroBase"] != 'x') {
				$arrParam = array("idCuadroBase" => $data["idCuadroBase"]);
				$data['information'] = $this->general_model->get_lista_cuadro_mando($arrParam);//info bloques
				
				$data["numeroObjetivoEstrategico"] = $data['information'][0]['fk_numero_objetivo_estrategico'];
			}

			$arrParam = array("numeroObjetivoEstrategico" => $data["numeroObjetivoEstrategico"]);
			$data['infoObjetivoEstrategico'] = $this->general_model->get_objetivos_estrategicos($arrParam);

			$arrParam = array(
				"NOTidCuadroBase" => $data["idCuadroBase"]
			);
			$data['listaActividades'] = $this->general_model->get_actividades($arrParam);

			$this->load->view("importar_actividdades_modal", $data);
    }

	/**
	 * Importar Actividad
     * @since 24/06/2022
     * @author BMOTTAG
	 */
	public function save_importar_actividad()
	{			
			header('Content-Type: application/json');
			$data = array();
			
			$msj = "Se importó la Actividad al Plan Estratégico!";

			if ($this->settings_model->saveImportarActividad()) {
				/*
FALTA GUARDA EL CAMBIO PARA UNA AUDITORIA 
				*/
				$data["result"] = true;				
				$this->session->set_flashdata('retornoExito', '<strong>Correcto!</strong> ' . $msj);
			} else {
				$data["result"] = "error";			
				$this->session->set_flashdata('retornoError', '<strong>Error!</strong> Ask for help');
			}

			echo json_encode($data);	
    }

	/**
	 * Lista de propositos X vigencia
     * @since 16/04/2022
     * @author BMOTTAG
	 */
	public function propositos_x_vigencia($vigencia=2022)
	{
			$data['vigencia']  = $vigencia;
			$arrParam = array('vigencia'=>$vigencia);
			$data['info'] = $this->general_model->get_propositos_x_vigencia($arrParam);
			
			$data["view"] = 'propositos_x_vigencia';
			$this->load->view("layout_calendar", $data);
	}
	
    /**
     * Cargo modal - formulario porpositos
     * @since 16/04/2022
     */
    public function cargarModalPropositosXVigencia() 
	{
			header("Content-Type: text/plain; charset=utf-8"); //Para evitar problemas de acentos
			
			$data['information'] = FALSE;
			$data["idPropositoVigencia"] = $this->input->post("idPropositoVigencia");	

			$arrParam = array(
				"table" => " propositos",
				"order" => "numero_proposito",
				"id" => "x"
			);
			$data['listaProposito'] = $this->general_model->get_basic_search($arrParam);
			
			if ($data["idPropositoVigencia"] != 'x') {
				$arrParam = array(
					"idPropositoVigencia" => $data["idPropositoVigencia"]
				);
				$data['information'] = $this->general_model->get_propositos_x_vigencia($arrParam);
			}
			
			$this->load->view("propositos_x_vigencia_modal", $data);
    }
	
	/**
	 * Ingresar/Actualizar metas_proyectos
     * @since 16/04/2022
     * @author BMOTTAG
	 */
	public function save_propositos_x_vigencia()
	{			
			header('Content-Type: application/json');
			$data = array();
			
			$idPropositoVigencia = $this->input->post('hddId');
			
			$msj = "Se adicionó el Registro!";
			if ($idPropositoVigencia != '') {
				$msj = "Se actualizó Registro!";
			}

			if ($idPropositoVigencia = $this->settings_model->savePropositosXVigencia()) {
				$data["result"] = true;				
				$this->session->set_flashdata('retornoExito', '<strong>Correcto!</strong> ' . $msj);
			} else {
				$data["result"] = "error";			
				$this->session->set_flashdata('retornoError', '<strong>Error!</strong> Ask for help');
			}

			echo json_encode($data);	
    }

	/**
	 * Fechas limite de registro de ejecución
     * @since 18/08/2022
     * @author BMOTTAG
	 */
	public function fechas_limite()
	{
			$arrParam = array(
				"table" => "param_fechas_limites",
				"order" => "id_fecha",
				"id" => "x"
			);
			$data['info'] = $this->general_model->get_basic_search($arrParam);
			
			$data["view"] = 'fechas';
			$this->load->view("layout_calendar", $data);
	}

    /**
     * Cargo modal - formulario fechas limite registro ejecución
     * @since 15/04/2022
     */
    public function cargarModalFechas() 
	{
			header("Content-Type: text/plain; charset=utf-8"); //Para evitar problemas de acentos
			
			$data['information'] = FALSE;
			$idFecha = $this->input->post("idFecha");	
			
			$arrParam = array(
				"table" => "param_fechas_limites",
				"order" => "id_fecha",
				"column" => "id_fecha",
				"id" => $idFecha
			);
			$data['information'] = $this->general_model->get_basic_search($arrParam);

			$this->load->view("fechas_modal", $data);
    }

	/**
	 * Actualizar fechas
     * @since 18/07/2022
     * @author BMOTTAG
	 */
	public function save_fechas()
	{			
			header('Content-Type: application/json');
			$data = array();
			
			$idFecha = $this->input->post('hddId');
			$fecha = $this->input->post('fecha');
			
			$msj = "Se actualizó la información!";

			$arrParam = array(
				"table" => "param_fechas_limites",
				"primaryKey" => "id_fecha",
				"id" => $idFecha,
				"column" => "fecha",
				"value" => $fecha
			);

			if($this->general_model->updateRecord($arrParam)){
				$data["result"] = true;				
				$this->session->set_flashdata('retornoExito', '<strong>Correcto!</strong> ' . $msj);
			} else {
				$data["result"] = "error";			
				$this->session->set_flashdata('retornoError', '<strong>Error!</strong> Ask for help');
			}

			echo json_encode($data);	
    }

	/**
	 * Lista de PROYECETO X vigencia
     * @since 24/07/2022
     * @author BMOTTAG
	 */
	public function proyectos_x_vigencia($vigencia='x')
	{
			$data['vigencia']  = $vigencia;
			if($vigencia == 'x'){
				$data['vigencia']  = date('Y');
			}

			$arrParam = array('vigencia'=>$data['vigencia']);
			$data['info'] = $this->general_model->get_proyectos_x_vigencia($arrParam);
	
			$data["view"] = 'proyectos_x_vigencia';
			$this->load->view("layout_calendar", $data);
	}
	
    /**
     * Cargo modal - formulario proyectos
     * @since 24/07/2022
     */
    public function cargarModalProyectosXVigencia() 
	{
			header("Content-Type: text/plain; charset=utf-8"); //Para evitar problemas de acentos
			
			$data['information'] = FALSE;
			$data["idProyectoVigencia"] = $this->input->post("idProyectoVigencia");	

			$arrParam = array(
				"table" => " proyecto_inversion",
				"order" => "numero_proyecto_inversion",
				"id" => "x"
			);
			$data['listaProyecto'] = $this->general_model->get_basic_search($arrParam);
			
			if ($data["idProyectoVigencia"] != 'x') {
				$arrParam = array(
					"idProyectoVigencia" => $data["idProyectoVigencia"]
				);
				$data['information'] = $this->general_model->get_proyectos_x_vigencia($arrParam);
			}
			
			$this->load->view("proyectos_x_vigencia_modal", $data);
    }
	
	/**
	 * Ingresar/Actualizar proyectos X vigencia
     * @since 24/07/2022
     * @author BMOTTAG
	 */
	public function save_proyectos_x_vigencia()
	{			
			header('Content-Type: application/json');
			$data = array();
			
			$idProyectoVigencia = $this->input->post('hddId');
			
			$msj = "Se adicionó el Registro!";
			if ($idProyectoVigencia != '') {
				$msj = "Se actualizó Registro!";
			}

			if ($idProyectoVigencia = $this->settings_model->saveProyectosXVigencia()) {
				$data["result"] = true;				
				$this->session->set_flashdata('retornoExito', '<strong>Correcto!</strong> ' . $msj);
			} else {
				$data["result"] = "error";			
				$this->session->set_flashdata('retornoError', '<strong>Error!</strong> Ask for help');
			}

			echo json_encode($data);	
    }	

	/**
	 * Lista de metas_pdd X vigencia
     * @since 24/07/2022
     * @author BMOTTAG
	 */
	public function metas_pdd_x_vigencia($vigencia='x')
	{
			$data['vigencia']  = $vigencia;
			if($vigencia == 'x'){
				$data['vigencia']  = date('Y');
			}

			$arrParam = array('vigencia'=>$data['vigencia']);
			$data['info'] = $this->general_model->get_metas_pdd_x_vigencia($arrParam);
	
			$data["view"] = 'metas_pdd_x_vigencia';
			$this->load->view("layout_calendar", $data);
	}
	
    /**
     * Cargo modal - formulario metas_pdd
     * @since 24/07/2022
     */
    public function cargarModalMetasPDDXVigencia() 
	{
			header("Content-Type: text/plain; charset=utf-8"); //Para evitar problemas de acentos
			
			$data['information'] = FALSE;
			$data["idMetaPDDVigencia"] = $this->input->post("idMetaPDDVigencia");

			$arrParam = array(
				"table" => "meta_pdd",
				"order" => "numero_meta_pdd",
				"id" => "x"
			);
			$data['listaMetasPDD'] = $this->general_model->get_basic_search($arrParam);
	
			if ($data["idMetaPDDVigencia"] != 'x') {
				$arrParam = array(
					"idMetaPDDVigencia" => $data["idMetaPDDVigencia"]
				);
				$data['information'] = $this->general_model->get_metas_pdd_x_vigencia($arrParam);
			}
			
			$this->load->view("metas_pdd_x_vigencia_modal", $data);
    }
	
	/**
	 * Ingresar/Actualizar metas_pdd X vigencia
     * @since 24/07/2022
     * @author BMOTTAG
	 */
	public function save_metas_pdd_x_vigencia()
	{			
			header('Content-Type: application/json');
			$data = array();
			
			$idMetaPDDVigencia = $this->input->post('hddId');
			
			$msj = "Se adicionó el Registro!";
			if ($idMetaPDDVigencia != '') {
				$msj = "Se actualizó Registro!";
			}

			if ($idMetaPDDVigencia = $this->settings_model->saveMetasPDDXVigencia()) {
				$data["result"] = true;				
				$this->session->set_flashdata('retornoExito', '<strong>Correcto!</strong> ' . $msj);
			} else {
				$data["result"] = "error";			
				$this->session->set_flashdata('retornoError', '<strong>Error!</strong> Ask for help');
			}

			echo json_encode($data);	
    }

	/**
	 * Lista de programas segplan
     * @since 24/07/2022
     * @author BMOTTAG
	 */
	public function programas_sp()
	{
			$arrParam = array(
				"table" => "programa",
				"order" => "numero_programa",
				"id" => "x"
			);
			$data['info'] = $this->general_model->get_basic_search($arrParam);
			
			$data["view"] = 'programa_sp';
			$this->load->view("layout_calendar", $data);
	}
	
    /**
     * Cargo modal - formulario programas segplan
     * @since 24/07/2022
     */
    public function cargarModalProgramaSP() 
	{
			header("Content-Type: text/plain; charset=utf-8"); //Para evitar problemas de acentos
			
			$data['information'] = FALSE;
			$data["idPrograma"] = $this->input->post("idPrograma");	
			
			if ($data["idPrograma"] != 'x') {
				$arrParam = array(
					"table" => "programa",
					"order" => "numero_programa",
					"column" => "id_programa",
					"id" => $data["idPrograma"]
				);
				$data['information'] = $this->general_model->get_basic_search($arrParam);
			}
			
			$this->load->view("programa_sp_modal", $data);
    }
	
	/**
	 * Ingresar/Actualizar programas segplan
     * @since 24/07/2022
     * @author BMOTTAG
	 */
	public function save_programa_sp()
	{			
			header('Content-Type: application/json');
			$data = array();
			
			$idPrograma = $this->input->post('hddId');
			
			$msj = "Se adicionó el Programa!";
			if ($idPrograma != '') {
				$msj = "Se actualizó el Programa!";
			}

			if ($idPrograma = $this->settings_model->saveProgramaSP()) {
				$data["result"] = true;				
				$this->session->set_flashdata('retornoExito', '<strong>Correcto!</strong> ' . $msj);
			} else {
				$data["result"] = "error";			
				$this->session->set_flashdata('retornoError', '<strong>Error!</strong> Ask for help');
			}

			echo json_encode($data);	
    }

	/**
	 * Lista de programas segplan X vigencia
     * @since 24/07/2022
     * @author BMOTTAG
	 */
	public function programa_sp_x_vigencia($vigencia='x')
	{
			$data['vigencia']  = $vigencia;
			if($vigencia == 'x'){
				$data['vigencia']  = date('Y');
			}

			$arrParam = array('vigencia'=>$data['vigencia']);
			$data['info'] = $this->general_model->get_programa_sp_x_vigencia($arrParam);
	
			$data["view"] = 'programa_sp_x_vigencia';
			$this->load->view("layout_calendar", $data);
	}
	
    /**
     * Cargo modal - formulario programas segplan
     * @since 24/07/2022
     */
    public function cargarModalProgramaSPXVigencia() 
	{
			header("Content-Type: text/plain; charset=utf-8"); //Para evitar problemas de acentos
			
			$data['information'] = FALSE;
			$data["idProgramaSPVigencia"] = $this->input->post("idProgramaSPVigencia");

			$arrParam = array(
				"table" => "programa",
				"order" => "numero_programa",
				"id" => "x"
			);
			$data['listaProgramas'] = $this->general_model->get_basic_search($arrParam);
	
			if ($data["idProgramaSPVigencia"] != 'x') {
				$arrParam = array(
					"idProgramaSPVigencia" => $data["idProgramaSPVigencia"]
				);
				$data['information'] = $this->general_model->get_programa_sp_x_vigencia($arrParam);
			}
			
			$this->load->view("programa_sp_x_vigencia_modal", $data);
    }
	
	/**
	 * Ingresar/Actualizar programas segplan X vigencia
     * @since 24/07/2022
     * @author BMOTTAG
	 */
	public function save_programa_sp_x_vigencia()
	{			
			header('Content-Type: application/json');
			$data = array();
			
			$idProgramaSPVigencia = $this->input->post('hddId');
			
			$msj = "Se adicionó el Registro!";
			if ($idProgramaSPVigencia != '') {
				$msj = "Se actualizó Registro!";
			}

			if ($idProgramaSPVigencia = $this->settings_model->saveProgramasSPXVigencia()) {
				$data["result"] = true;				
				$this->session->set_flashdata('retornoExito', '<strong>Correcto!</strong> ' . $msj);
			} else {
				$data["result"] = "error";			
				$this->session->set_flashdata('retornoError', '<strong>Error!</strong> Ask for help');
			}

			echo json_encode($data);	
    }

	/**
	 * Lista de indicadores segplan
     * @since 27/07/2022
     * @author BMOTTAG
	 */
	public function indicadores_sp()
	{
			$arrParam = array(
				"table" => "indicadores",
				"order" => "numero_indicador",
				"id" => "x"
			);
			$data['info'] = $this->general_model->get_basic_search($arrParam);
			
			$data["view"] = 'indicadores_sp';
			$this->load->view("layout_calendar", $data);
	}
	
    /**
     * Cargo modal - formulario indicadores segplan
     * @since 27/07/2022
     */
    public function cargarModalIndicadoresSP() 
	{
			header("Content-Type: text/plain; charset=utf-8"); //Para evitar problemas de acentos
			
			$data['information'] = FALSE;
			$data["idIndicador"] = $this->input->post("idIndicador");	
			
			if ($data["idIndicador"] != 'x') {
				$arrParam = array(
					"table" => "indicadores",
					"order" => "numero_indicador",
					"column" => "id_indicador_sp",
					"id" => $data["idIndicador"]
				);
				$data['information'] = $this->general_model->get_basic_search($arrParam);
			}
			
			$this->load->view("indicadores_sp_modal", $data);
    }
	
	/**
	 * Ingresar/Actualizar indicadores segplan
     * @since 27/07/2022
     * @author BMOTTAG
	 */
	public function save_indicador_sp()
	{			
			header('Content-Type: application/json');
			$data = array();
			
			$idIndicador = $this->input->post('hddId');
			
			$msj = "Se adicionó el Indicador!";
			if ($idIndicador != '') {
				$msj = "Se actualizó el Indicador!";
			}

			if ($idIndicador = $this->settings_model->saveIndicadorSP()) {
				$data["result"] = true;				
				$this->session->set_flashdata('retornoExito', '<strong>Correcto!</strong> ' . $msj);
			} else {
				$data["result"] = "error";			
				$this->session->set_flashdata('retornoError', '<strong>Error!</strong> Ask for help');
			}

			echo json_encode($data);	
    }

	/**
	 * Lista de programas indicadores X vigencia
     * @since 27/07/2022
     * @author BMOTTAG
	 */
	public function indicador_sp_x_vigencia($vigencia='x')
	{
			$data['vigencia']  = $vigencia;
			if($vigencia == 'x'){
				$data['vigencia']  = date('Y');
			}

			$arrParam = array('vigencia'=>$data['vigencia']);
			$data['info'] = $this->general_model->get_indicador_sp_x_vigencia($arrParam);
	
			$data["view"] = 'indicadores_sp_x_vigencia';
			$this->load->view("layout_calendar", $data);
	}
	
    /**
     * Cargo modal - formulario indicadores segplan
     * @since 27/07/2022
     */
    public function cargarModalIndicadorSPXVigencia() 
	{
			header("Content-Type: text/plain; charset=utf-8"); //Para evitar problemas de acentos
			
			$data['information'] = FALSE;
			$data["idIndicadorSPVigencia"] = $this->input->post("idIndicadorSPVigencia");

			$arrParam = array(
				"table" => "indicadores",
				"order" => "numero_indicador",
				"id" => "x"
			);
			$data['listaIndicadores'] = $this->general_model->get_basic_search($arrParam);
	
			if ($data["idIndicadorSPVigencia"] != 'x') {
				$arrParam = array(
					"idIndicadorSPVigencia" => $data["idIndicadorSPVigencia"]
				);
				$data['information'] = $this->general_model->get_indicador_sp_x_vigencia($arrParam);
			}
			
			$this->load->view("indicadores_sp_x_vigencia_modal", $data);
    }
	
	/**
	 * Ingresar/Actualizar indicadores segplan X vigencia
     * @since 27/07/2022
     * @author BMOTTAG
	 */
	public function save_indicadores_sp_x_vigencia()
	{			
			header('Content-Type: application/json');
			$data = array();
			
			$idIndicadorSPVigencia = $this->input->post('hddId');
			
			$msj = "Se adicionó el Registro!";
			if ($idIndicadorSPVigencia != '') {
				$msj = "Se actualizó Registro!";
			}

			if ($idIndicadorSPVigencia = $this->settings_model->saveIndicadorSPXVigencia()) {
				$data["result"] = true;				
				$this->session->set_flashdata('retornoExito', '<strong>Correcto!</strong> ' . $msj);
			} else {
				$data["result"] = "error";			
				$this->session->set_flashdata('retornoError', '<strong>Error!</strong> Ask for help');
			}

			echo json_encode($data);	
    }

	/**
	 * Lista de indicadores PMR
     * @since 28/07/2022
     * @author BMOTTAG
	 */
	public function indicadores_pmr()
	{
			$arrParam = array(
				"table" => "indicadores_pmr",
				"order" => "numero_indicador_pmr",
				"id" => "x"
			);
			$data['info'] = $this->general_model->get_basic_search($arrParam);
			
			$data["view"] = 'indicadores_pmr';
			$this->load->view("layout_calendar", $data);
	}
	
    /**
     * Cargo modal - formulario indicadores PMR
     * @since 28/07/2022
     */
    public function cargarModalIndicadoresPMR() 
	{
			header("Content-Type: text/plain; charset=utf-8"); //Para evitar problemas de acentos
			
			$data['information'] = FALSE;
			$data["idIndicador"] = $this->input->post("idIndicador");	
			
			if ($data["idIndicador"] != 'x') {
				$arrParam = array(
					"table" => "indicadores_pmr",
					"order" => "numero_indicador_pmr",
					"column" => "id_indicador_pmr",
					"id" => $data["idIndicador"]
				);
				$data['information'] = $this->general_model->get_basic_search($arrParam);
			}
			
			$this->load->view("indicadores_pmr_modal", $data);
    }
	
	/**
	 * Ingresar/Actualizar indicadores PMR
     * @since 28/07/2022
     * @author BMOTTAG
	 */
	public function save_indicador_pmr()
	{			
			header('Content-Type: application/json');
			$data = array();
			
			$idIndicador = $this->input->post('hddId');
			
			$msj = "Se adicionó el Indicador!";
			if ($idIndicador != '') {
				$msj = "Se actualizó el Indicador!";
			}

			if ($idIndicador = $this->settings_model->saveIndicadorPMR()) {
				$data["result"] = true;				
				$this->session->set_flashdata('retornoExito', '<strong>Correcto!</strong> ' . $msj);
			} else {
				$data["result"] = "error";			
				$this->session->set_flashdata('retornoError', '<strong>Error!</strong> Ask for help');
			}

			echo json_encode($data);	
    }

    /**
	 * TABLERO PMR
	 * @since 31/10/2022
	 * @author AOCUBILLOSA
	 */
	public function tablero_pmr()
	{
			$arrParam = array();
			$data['info'] = $this->settings_model->get_tablero_pmr($arrParam);
			$data["view"] = "tablero_pmr";
			$this->load->view("layout_calendar", $data);
	}

    /**
     * Cargo modal - tablero PMR
     * @since 31/10/2022
     * @author AOCUBILLOSA
     */
    public function cargarModalTableroPMR()
	{
			header("Content-Type: text/plain; charset=utf-8"); //Para evitar problemas de acentos
			
			$data['information'] = FALSE;
			$data["idPMR"] = $this->input->post("idPMR");
			if ($data["idPMR"] != 'x') {
				$arrParam = array("id_pmr" => $data["idPMR"]);
				$data['information'] = $this->settings_model->get_tablero_pmr($arrParam);
			}

			$arrParam = array(
				"table" => "param_objetivos_pmr",
				"order" => "numero_objetivo_pmr",
				"id" => "x"
			);
			$data['listaObjetivoPMR'] = $this->general_model->get_basic_search($arrParam);

			$arrParam = array(
				"table" => "param_elementos_pep_pmr",
				"order" => "id_elemento_pep_pmr",
				"id" => "x"
			);
			$data['listaElementoPEP'] = $this->general_model->get_basic_search($arrParam);

			$arrParam = array(
				"table" => "param_productos_pmr",
				"order" => "numero_producto_pmr",
				"id" => "x"
			);
			$data['listaProductoPMR'] = $this->general_model->get_basic_search($arrParam);

			$arrParam = array(
				"table" => "proyecto_inversion",
				"order" => "numero_proyecto_inversion",
				"id" => "x"
			);
			$data['listaProyectoInversion'] = $this->general_model->get_basic_search($arrParam);

			$arrParam = array(
				"table" => "indicadores_pmr",
				"order" => "numero_indicador_pmr",
				"id" => "x"
			);
			$data['listaIndicadorPMR'] = $this->general_model->get_basic_search($arrParam);

			$arrParam = array(
				"table" => "param_unidad_medida_pmr",
				"order" => "id_unidad_medida_pmr",
				"id" => "x"
			);
			$data['listaUnidadMedida'] = $this->general_model->get_basic_search($arrParam);

			$arrParam = array(
				"table" => "param_naturaleza_pmr",
				"order" => "id_naturaleza_pmr",
				"id" => "x"
			);
			$data['listaNaturalezaPMR'] = $this->general_model->get_basic_search($arrParam);

			$arrParam = array(
				"table" => "param_periodicidad_pmr",
				"order" => "id_periodicidad_pmr",
				"id" => "x"
			);
			$data['listaPeriodicidadPMR'] = $this->general_model->get_basic_search($arrParam);

			$this->load->view("tablero_pmr_modal", $data);
    }
	
	/**
	 * Ingresar/Actualizar tablero PMR
     * @since 31/10/2022
     * @author AOCUBILLOSA
	 */
	public function save_tablero_pmr()
	{			
			header('Content-Type: application/json');
			$data = array();
			$idPMR = $this->input->post('hddIdPMR');
			$msj = "Se adicionó la información!";
			if ($idPMR != 'x') {
				$msj = "Se actualizó la información!";
			}
			if ($this->settings_model->saveTableroPMR()) {
				$data["result"] = true;				
				$this->session->set_flashdata('retornoExito', '<strong>Correcto!</strong> ' . $msj);
			} else {
				$data["result"] = "error";			
				$this->session->set_flashdata('retornoError', '<strong>Error!</strong> Ask for help');
			}
			echo json_encode($data);
    }

	/**
	 * Delete tablero PMR
     * @since 31/10/2022
     * @author AOCUBILLOSA
	 */
	public function delete_tablero_pmr()
	{			
			header('Content-Type: application/json');
			$data = array();
			$idPMR = $this->input->post('identificador');
			$arrParam = array(
				"table" => "tablero_pmr ",
				"primaryKey" => "id_pmr",
				"id" => $idPMR
			);
			if ($this->general_model->deleteRecord($arrParam)) {
				$data["result"] = true;
				$this->session->set_flashdata('retornoExito', '<strong>Correcto!</strong> Se eliminó el indicador del tablero PMR.');
			} else {
				$data["result"] = "error";
				$data["mensaje"] = "Error!!! Ask for help.";
				$this->session->set_flashdata('retornoError', '<strong>Error!</strong> Ask for help');
			}
			echo json_encode($data);
    }
	
}