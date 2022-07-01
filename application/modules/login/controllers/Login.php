<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {
	
    public function __construct() {
        parent::__construct();
        $this->load->model("login_model");
        $this->load->model("general_model");
		$this->load->helper("cookie");
    }

	/**
	 * Index Page for this controller.
	 * @param int $id: id del vehiculo encriptado para el hauling
	 */
	public function index($id = 'x')
	{
			$this->session->sess_destroy();
			$this->load->view('login');
	}
	
	public function validateUser()
	{
			$login = $this->security->xss_clean($this->input->post("inputLogin"));
			$passwd = $this->security->xss_clean($this->input->post("inputPassword"));
	
			//busco datos del usuario
			$arrParam = array(
				"table" => "usuarios",
				"order" => "id_user",
				"column" => "log_user",
				"id" => $login
			);
			$userExist = $this->general_model->get_basic_search($arrParam);

			if ($userExist)
			{
					$arrParam = array(
						"login" => $login,
						"passwd" => $passwd
					);
					$user = $this->login_model->validateLogin($arrParam); //brings user information from user table

					if(($user["valid"] == true)) 
					{
						$userRole = intval($user["role"]);
						//busco url del dashboard de acuerdo al rol del usuario
						$arrParam = array(
							"idRole" => $userRole
						);
						$rolInfo = $this->general_model->get_roles($arrParam);

						$sessionData = array(
							"auth" => "OK",
							"id" => $user["id"],
							"dashboardURL" => $rolInfo[0]['dashboard_url'],
							"firstname" => $user["firstname"],
							"lastname" => $user["lastname"],
							"name" => $user["firstname"] . ' ' . $user["lastname"],
							"dependencia" => $user["dependencia"],
							"logUser" => $user["logUser"],
							"state" => $user["state"],
							"role" => $user["role"],
							"photo" => $user["photo"]
						);
												
						$this->session->set_userdata($sessionData);
						//cookies
						set_cookie('user',$login, '350000'); 
						set_cookie('password',$passwd,'350000'); 
						
						$this->login_model->redireccionarUsuario();
					}else{					
						$data["msj"] = "<strong>" . $userExist[0]["first_name"] . "</strong> esa no es su contraseña.";
						$this->session->sess_destroy();
						$this->load->view('login', $data);
					}
			}else{
				$data["msj"] = "<strong>" . $login . "</strong> no esta registrado.";
				$this->session->sess_destroy();
				$this->load->view('login', $data);
			}
	}
	
	/**
	 * Form to ask for a new password
	 */
	public function recover()
	{
		$this->load->view("form_email");
	}
	
	/**
	 * Se valida correo, se envia correo con enlace para cambiar contraseña y se guarda llave en la base de datos
	 */	
	public function validateEmail()
	{
			$email = $this->security->xss_clean($this->input->post("email"));
			
			$this->load->model("general_model");
			//busco datos del usuario
			$arrParam = array(
				"table" => "usuarios",
				"order" => "id_user",
				"column" => "email",
				"id" => $email
			);
			$userExist = $this->general_model->get_basic_search($arrParam);
			
			if ($userExist)
			{
				$idUsuario = $userExist[0]['id_user'];
				
				//elimino datos anteriores de tabla recuperar
				$arrParam = array(
					"table" => "usuarios_llave_contraseña",
					"primaryKey" => "fk_id_user_ulc",
					"id" => $idUsuario
				);
				$this->general_model->deleteRecord($arrParam);
				
				//genero llave
				$llave = $this->randomText();

				//guardo llave en tabla recuperar
				$this->login_model->saveKey($idUsuario, $email, $llave);
				
				//envio correo con url para cambio de contraseña
				$this->email($llave);

				$data["msjSuccess"] = "Se envío correo a <strong>" . $email . "</strong> para recuperar la contraseña.";
				$this->load->view('form_email', $data);
				
			}else{
				$data["msj"] = "<strong>" . $email . "</strong> no existe.";
				$this->session->sess_destroy();
				$this->load->view('form_email', $data);
			}
	}
	
	//FUNCION PARA CREAR UNA CLAVE ALEATORIA
	function randomText()
	{ 		
			$str = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890";
			$key = "";
			//Reconstruimos la contraseña segun la longitud que se quiera
			for($i=0;$i<20;$i++) {
			  //obtenemos un caracter aleatorio escogido de la cadena de caracteres
			  $key .= substr($str,rand(0,62),1);
			}

			return $key; 
	} 
	
	/**
	 * Evio correo al usuario con llave para recuperar la contraseña
     * @since 25/11/2020
     * @author BMOTTAG
	 */
	public function email($key)
	{
			//busco informacion en la base de datos
			$arrParam = array("key" => $key);
			$information = $this->login_model->validateLoginKey($arrParam);//brings user information from user table
										
			$subjet = "Recuperar contraseña - JBB-APP";
			$user = $information["firstname"] . ' ' . $information["lastname"];
			$to = $information["email"];
							
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
			$msj = 'Sr.(a) ' . $user . ', ';
			$msj .= "<p>Siga el enlace para recuperar su contraseña:</p>";
			$msj .= "<a href='" . base_url("login/keyLogin/" . $key) . "'>Recuperar contraseña</a>";
									
			$mensaje = "<p>$msj</p>
						<p>Cordialmente,</p>
						<p><strong>$paramCompanyName</strong></p>";	

			require_once(APPPATH.'libraries/PHPMailer/class.phpmailer.php');
            $mail = new PHPMailer(true);

            $mail->IsSMTP(); // set mailer to use SMTP
            $mail->Host = $paramHost; // specif smtp server
            $mail->SMTPSecure= "tls"; // Used instead of TLS when only POP mail is selected
            $mail->Port = 587; // Used instead of 587 when only POP mail is selected
            $mail->SMTPAuth = true;
			$mail->Username = $paramUsername; // SMTP username
            $mail->Password = $paramPassword; // SMTP password
            $mail->FromName = $paramFromName;
            $mail->From = $paramUsername;
            $mail->AddAddress($to, 'Usuario JBB Cuadro de Mando');
            $mail->WordWrap = 50;
            $mail->CharSet = 'UTF-8';
            $mail->IsHTML(true); // set email format to HTML
            $mail->Subject = $paramCompanyName . ' - ' . $paramAPPName;
            $mail->Body = nl2br ($mensaje,false);
            $mail->Send();
			return true;
	}
	
	/**
	 * Login por medio de LLAVE de recuperacion de contraseña
	 * @param varchar $valor: llave de la tabla recuperar
	 */
	public function keyLogin($valor = 'x')
	{
			$arrParam = array("key" => $valor);
			$user = $this->login_model->validateLoginKey($arrParam);//brings user information from user table

			if (($user["valid"] == true)) 
			{
				$userRole = intval($user["role"]);
				//busco url del dashboard de acuerdo al rol del usuario
				$arrParam = array(
					"idRole" => $userRole
				);
				$rolInfo = $this->general_model->get_roles($arrParam);
				
				$sessionData = array(
					"auth" => "OK",
					"id" => $user["id"],
					"dashboardURL" => $rolInfo[0]['dashboard_url'],
					"firstname" => $user["firstname"],
					"lastname" => $user["lastname"],
					"name" => $user["firstname"] . ' ' . $user["lastname"],
					"dependencia" => $user["dependencia"],
					"logUser" => $user["logUser"],
					"state" => 66,
					"role" => $user["role"],
					"photo" => $user["photo"],
				);
				$this->session->set_userdata($sessionData);
				
				$this->login_model->redireccionarUsuario();			
			}else{					
				$data["msj"] = "<strong>Error</strong> datos incorrectos.";
				$this->load->view('login', $data);
			}
	}

	/**
	 * Form to search a equipment
	 */
	public function search_equipment()
	{
		$this->load->view("form_search_equipment");
	}
	
	
	
	
	
}
