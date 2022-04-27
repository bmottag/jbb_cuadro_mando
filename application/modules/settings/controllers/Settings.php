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
			
			$data['linkBack'] = "settings/employee/" . $userState;
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
	 * Lista de objetivos_estrategicos
     * @since 15/04/2022
     * @author BMOTTAG
	 */
	public function objetivos_estrategicos()
	{
			$arrParam = array(
				"table" => "objetivos_estrategicos",
				"order" => "id_objetivo_estrategico",
				"id" => "x"
			);
			$data['info'] = $this->general_model->get_basic_search($arrParam);
			
			$data["view"] = 'objetivos_estrategicos';
			$this->load->view("layout_calendar", $data);
	}
	
    /**
     * Cargo modal - formulario objetivos_estrategicos
     * @since 15/04/2022
     */
    public function cargarModalObjetivosEstrategicos() 
	{
			header("Content-Type: text/plain; charset=utf-8"); //Para evitar problemas de acentos
			
			$data['information'] = FALSE;
			$data["idObjetivo"] = $this->input->post("idObjetivo");	
			
			if ($data["idObjetivo"] != 'x') {
				$arrParam = array(
					"table" => "objetivos_estrategicos",
					"order" => "objetivo_estrategico",
					"column" => "id_objetivo_estrategico",
					"id" => $data["idObjetivo"]
				);
				$data['information'] = $this->general_model->get_basic_search($arrParam);
			}
			
			$this->load->view("objetivos_estrategicos_modal", $data);
    }
	
	/**
	 * Ingresar/Actualizar objetivos_estrategicos
     * @since 15/04/2022
     * @author BMOTTAG
	 */
	public function save_objetivos_estrategicos()
	{			
			header('Content-Type: application/json');
			$data = array();
			
			$idObjetivo = $this->input->post('hddId');
			
			$msj = "Se adicionó el Objetivo Estratégico!";
			if ($idObjetivo != '') {
				$msj = "Se actualizó el Objetivo Estratégico!";
			}

			if ($idObjetivo = $this->settings_model->saveObjetivo()) {
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
				"table" => " meta_pdd",
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
	public function metas_proyectos()
	{
			$arrParam = array(
				"table" => "meta_proyecto_inversion",
				"order" => "numero_meta_proyecto",
				"id" => "x"
			);
			$data['info'] = $this->general_model->get_basic_search($arrParam);
			
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
			
			if ($data["idMetaProyecto"] != 'x') {
				$arrParam = array(
					"table" => "meta_proyecto_inversion",
					"order" => "numero_meta_proyecto",
					"column" => "id_meta_proyecto_inversion",
					"id" => $data["idMetaProyecto"]
				);
				$data['information'] = $this->general_model->get_basic_search($arrParam);
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
			
			$msj = "Se adicionó la ODS!";
			if ($idMetaProyecto != '') {
				$msj = "Se actualizó la ODS!";
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
			$arrParam['passwd'] = 'Jardin2021';
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
			$msj = '<p>Sr.(a) ' . $infoUsuario[0]['first_name'] . ' se activo su ingreso a la plataforma de Gestión y mantenimiento de bienes del ' . $paramCompanyName . ',';
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
	 * Estrategias
     * @since 26/04/2022
     * @author BMOTTAG
	 */
	public function estrategias()
	{			
			$arrParam = array();
			$data['info'] = $this->general_model->get_estrategias($arrParam);
			
			$data["view"] = 'estrategias';
			$this->load->view("layout_calendar", $data);
	}
	
    /**
     * Cargo modal - formulario estrategias
     * @since 26/04/2022
     */
    public function cargarModalEstrategias() 
	{
			header("Content-Type: text/plain; charset=utf-8"); //Para evitar problemas de acentos
			
			$data['information'] = FALSE;
			$data["idEstrategia"] = $this->input->post("idEstrategia");	
	
			$arrParam = array(
				"table" => "objetivos_estrategicos",
				"order" => "id_objetivo_estrategico",
				"id" => "x"
			);
			$data['objetivos'] = $this->general_model->get_basic_search($arrParam);

			if ($data["idEstrategia"] != 'x') {
				$arrParam = array(
					"idEstrategia" => $data["idEstrategia"]
				);
				$data['information'] = $this->general_model->get_estrategias($arrParam);
			}			
			$this->load->view("estrategias_modal", $data);
    }
	
	/**
	 * Update estrategias
     * @since 26/04/2022
     * @author BMOTTAG
	 */
	public function save_estrategias()
	{			
			header('Content-Type: application/json');
			$data = array();
			
			$idEstrategia = $this->input->post('hddId');

			$msj = "Se adicionó la Estretegia!";
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
	

	
}