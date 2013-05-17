<?php
class CLASS_SESSION
{
	private $message = '';	
	private $vlc_logged_in;

	function CLASS_SESSION()
	{ //constructor
							
	
	}
	function MTD_START()
	{
		//echo "CHKSESSION-> ingreso en start<br>";
		$this->vlc_logged_in=false;
		$resultado=false;
                //FN_NET_LOGGER("Session Start ",5);
		if (! isset ( $_SESSION['msisdn'] ))
		{ //fills session with empty values
			//echo "CHKSESSION-> seteo default <br>";
                        FN_NET_LOGGER("Session Start>  session por defecto ",5);
			$this->set_session_defaults ();
		}
		
		if (isset ( $_SESSION['logged_in']))
		{ //already logged in
                            if ($_SESSION['logged_in'] == true)
                            {
                                FN_NET_LOGGER("Session Start>  Chequeo de sesion",5);
				$this->vlc_logged_in= $this->check_session ();
                            }
				//echo "CHKSESSION-> chequeo sesion con resultado: $resultado <br>";
		}                			
		$resultado = $this->vlc_logged_in;
		//echo "EL RESULTADO ES: $resultado";		
		return $resultado;		
	}
	
	function check_session()
	{
		//echo "<BR>USER:".$_SESSION['user']." -  TOKEN:".$_SESSION['token']." - logged in:".$_SESSION['logged_in']." - Alias:".$_SESSION['alias']." - UID:".$_SESSION['uid']." - Nombre Completo:".$_SESSION['vs_nombre_usuario'];
		$msisdn = $_SESSION['msisdn'];
		$token  = $_SESSION['token'];
		$session= session_id ();
		$sql_chk_session = "SELECT msisdn,token FROM usuarios WHERE msisdn = $msisdn AND token='$token'";
                FN_NET_LOGGER("Check Session > msisdn: $msisdn  token:$token",5);
		//echo "<br>SQL CHECK SESSION: $sql_chk_session";
		$vlf_db_datos = FN_RUN_QUERY ( $sql_chk_session, 2 );

		if ($vlf_db_datos)
		{
			if (($vlf_db_datos[0][0] == $msisdn) && ($vlf_db_datos[0][1] == $token))
			{
				//---RECHECK PASSWD
                                FN_NET_LOGGER("Check Session > msisdn: $msisdn > Usuario Valido ",5);
				return true;
			}
			else
			{
				//echo "LOGOUT";
                                FN_NET_LOGGER("Check Session > msisdn: $msisdn > Usuario invalido ",5);
				$this->logout ();
				return false;
			}
		}
		else
		{
			$this->logout();
			return false;
		}
	}
	
	function check_login($vp_msisdn, $vp_pin)
	{
                global $vg_db_conexion;
                $vl_pin =$pin = crypt($vp_pin,"aa");
                $nickname="";
                $sql_check_login="SELECT
                                    u.msisdn,
                                    u.pin,
                                    dp.nickname
                                  FROM usuarios  as u
                                  LEFT JOIN usuarios_datos_personales as dp ON(u.msisdn = dp.msisdn)
                                  WHERE u.msisdn='$vp_msisdn'
                                  AND pin='$vl_pin' limit 1";

                $arreglo_datos= FN_RUN_QUERY($sql_check_login,3,$vg_db_conexion);
                FN_NET_LOGGER("Checklogin > msisdn: $vp_msisdn , pin:$vl_pin",5);
                if ($arreglo_datos[0][0] ==  $vp_msisdn)
                {
                        FN_NET_LOGGER("Checklogin > msisdn: $vp_msisdn , pin:$vl_pin => Usuario valido");
                        $this->set_session ( $arreglo_datos, true );
                        return true;
                }
                else
                {
                        $this->failed = true;
                        FN_NET_LOGGER("Checklogin > msisdn: $vp_msisdn , pin:$vl_pin => Usuario invalido");
                        $this->logout ();
                        $this->message .= 'Usuario invalido';
                        return false;
                }

	}
	function set_session($vp_db_datos, $init = true)
	{
                global $vg_db_conexion;
		FN_NET_LOGGER("set_session  => asignando datos de la session");
		$msisdn     = $vp_db_datos[0][0];
                $nickname   = $vp_db_datos[0][2];
		//90 days for the cookie
		$vlf_estado_query = false;
		setcookie ( "msisdn", htmlspecialchars ( $vp_db_datos[0][0] ), time () + 1200 );
		session_register ( "msisdn" );
		if ($init)
		{			
			
			$newtoken = $this->token (); // generate a new token
                        FN_NET_LOGGER("set_session  => Actualizando datos token");
			$sql_update_session = "UPDATE usuarios SET  token='{$newtoken}'  WHERE msisdn='$msisdn'";
			$vlf_estado_query = FN_RUN_NONQUERY ( $sql_update_session,$vg_db_conexion );
                        FN_NET_LOGGER("set_session  => asignacion de token al usuario: $msisdn token:$newtoken ");
		}
		setcookie ( "msisdn", htmlspecialchars($msisdn  ), time () + 1200 );
                setcookie ( "nickname", htmlspecialchars($nickname  ), time () + 1200 );		
		setcookie ( "logged_in", true, time () + 1200 );				
		session_register ( "msisdn" );
		session_register ( "nickname" );
		session_register ( "token" );
		session_register ( "logged_in" );		
		$_SESSION['msisdn'] = htmlspecialchars ($msisdn);
		$_SESSION['nickname'] = htmlspecialchars ($nickname);		
		$_SESSION['token'] = $newtoken;
		$_SESSION['logged_in'] = true;

                FN_NET_LOGGER("set_session  => asignacion de variables de sesion, usuario: $msisdn, nickname: $nickname, token: $newtoken, logged_in");
	}

	function logout()
	{
                FN_NET_LOGGER("loggout => asignando por default session",5);
		$this->set_session_defaults ();
		return true;
	}
	
	
	function check_remembered($cookie)
	{
		$vlf_sql = "";
		//$vlf_db_datos= new array();
		$serializedArray = $cookie;
		$serializedArray = stripslashes ( $serializedArray );
		list ( $username, $token ) = unserialize ( $serializedArray );
		FN_NET_LOGGER("Check Remembered => asignacion de variables de sesion, usuario: $msisdn, nickname: $nickname, token: $newtoken, logged_in");
		if (empty ( $username ) or empty ( $token ))
		{
			return;
		}
		else
		{
			$username = $username;
			$token = $token;
			$ip = $_SERVER['REMOTE_ADDR'];
			$vlf_sql = "SELECT username,passwd,session,token,ip FROM usuarios WHERE username = '{$username}' AND token ='{$token}' AND ip = '{$ip}'";
			$vlf_db_datos = FN_RUN_QUERY ( $vl_sql, 5 );
			
			if ($vlf_db_datos[0][0] == $username)
			{
				$this->set_session ( $vlf_db_datos, false, false );
			}
			else
			{
				$this->set_session ( $vlf_db_datos, true, true );
			}
		}
	}
	
	function token()
	{
		// generate a random token
                $seed="";
		for($i = 1; $i < 33; $i ++)
		{
			$seed .= chr ( rand ( 0, 255 ) );
		}
		return md5 ( $seed );
	}
	
	
	function set_session_defaults()
	{
                if (isset($_SESSION['logged_in']))
                {
                    unset ( $_SESSION['logged_in'] );
                }
                if (isset($_SESSION['msisdn']))
                {
                    unset ( $_SESSION['msisdn'] );
                }
                if (isset($_SESSION['token']))
                {
                    unset ( $_SESSION['token'] );
                }
                if (isset($_SESSION['nickname']))
                {
                    unset ( $_SESSION['nickname'] );
                }
                if (isset($_SESSION['tonos_escuchados']))
                {
                    unset ( $_SESSION['tonos_escuchados'] );
                }
		$_SESSION['logged_in'] = false;		
		$_SESSION['msisdn'] = '';
		$_SESSION['token'] = '';
		$_SESSION['nickname'] = '';
		$_SESSION['tonos_escuchados'] = '';                
                FN_NET_LOGGER("session-default => asignando por default session",5);
	}
	function fn_clean_id($_var_clean_id)
	{
		$ID_PATTERN = "[0-9]";
		if (is_numeric ( $_var_clean_id ))
		{
			return $_var_clean_id;
		}
		else
		{
			return null;
		}
	}

}
?>
