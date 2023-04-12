<?php

namespace Controllers;

use MVC\Router;
use Classes\Email;
use Model\Usuario;

class LoginController{

    public static function login(Router $router){
        $alertas = [];

        $auth = new Usuario;

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $auth = new Usuario($_POST);

            $alertas = $auth->validarLogin();

            if(empty($alertas)){
                //comprobar que existe el usuario
                $usuario = Usuario::where('email', $auth->email);

                if($usuario){
                    //verificar password
                   if($usuario->comprobarPasswordAndVerificado($auth->password)){
                    //Autenticar el usuario
                    session_start();

                    $_SESSION['id'] = $usuario->id;
                    $_SESSION['nombre'] = $usuario->nombre . " " . $usuario->apellido ;
                    $_SESSION['email'] = $usuario->email;
                    $_SESSION['login'] = true;

                    //Redireccionar

                    if($usuario->admin === "1"){
                        $_SESSION['admin'] = $usuario->admin ?? null;

                        header('Location: /admin');
                    }else{
                        header('Location: /cita');
                    }

                    debuguear($_SESSION);
                   }; 
                }else{
                    Usuario::setAlerta('error', 'usuario no encontrado');
                }
            }
        }

        $alertas = Usuario::getAlertas();

        $router->render('auth/login', [
            'alertas' => $alertas,
            'auth' => $auth
        ]);
    }

    public static function logout(){
        session_start();

        $_SESSION = [];

        header('Location: /');
    }

    public static function olvide(Router $router){

        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $auth = new Usuario($_POST);
            $alertas = $auth->validarEmail();

            if(empty($alertas)){

                $usuario = Usuario::where('email', $auth->email);

                if($usuario && $usuario->confirmado === "1"){
                    //Generar un token para recuperar contrase
                    $usuario->crearToken();
                    $usuario->guardar();

                    //Enviar el email

                    $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
                    $email->enviarInstrucciones();

                    //Alerta de exito
                    Usuario::setAlerta('exito', 'revisa tu email');
                    
                }else{
                    Usuario::setAlerta('error', 'El usuario no existe o no está confirmado'); 
                }
            }
        }

        $alertas = Usuario::getAlertas();

        $router->render('auth/olvide-password', [
            'alertas' => $alertas
        ]);
    }

    public static function recuperar(Router $router){

        $alertas = [];
        $error = false;

        $token = s($_GET['token']);

        //buscar usuario por su token

        $usuario = Usuario::where('token', $token);

        if(empty($usuario)){
            Usuario::setAlerta('error', 'Token no válido');
            $error = true;
        }
        
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            //leer el nuevo password y guardarlo

            $password = new Usuario($_POST);
            $alertas = $password->validarPassword();

            if(empty($alertas)) {
                $usuario->password = null; //resetea el password del usuario

                $usuario->password = $password->password;
                $usuario->hashPassword(); //hasheamos el password
                $usuario->token = null; //volvemos a establecer el token como null

                $resultado = $usuario->guardar();
                if($resultado){
                    header('Location: /');
                }
            }
        }

        $alertas= Usuario::getAlertas();

        $router->render('auth/recuperar-password',[
            'alertas' => $alertas,
            'error' => $error
        ]);
    }

    public static function crear(Router $router){
        $usuario = new Usuario;

        //Alertas vacias
        $alertas = [];
        if($_SERVER['REQUEST_METHOD'] === 'POST'){

            $usuario->sincronizar($_POST);
            $alertas = $usuario->validarNuevaCuenta();
            
            //Revisar que alerta este vacio
            if(empty($alertas)){
                //verificar que el usuario ya no estaba registrado
                $resultado = $usuario->existeUsuario();

                if($resultado->num_rows){
                    $alertas = Usuario::getAlertas();
                }else{
                    //hashear contraseña
                    $usuario->hashPassword();

                    //generar un token único (verificación via email/sms)
                    $usuario->crearToken();

                    //enviar email
                    $email = new Email($usuario->nombre, $usuario->email, $usuario->token);

                    $email->enviarConfirmacion();

                    //crear el usuario
                    $resultado = $usuario->guardar();
                    if($resultado){
                        header('Location: /mensaje');
                    }

                    //debuguear($usuario);
                }
            }
        }
        $router->render('auth/crear-cuenta',[
            'usuario'=> $usuario,
            'alertas' => $alertas
        ]);
    }

    public static function mensaje(Router $router){
        $router->render('auth/mensaje');
    }

    public static function confirmar(Router $router){
        $alertas = [];

        $token = s($_GET['token']);

        $usuario = Usuario::where('token', $token);

        if(empty($usuario)){
            // Mostrar mensaje de error
            Usuario::setAlerta('error', 'Token no válido');
        }else{
            //modificar a usuario confirmado
            $usuario->confirmado = "1";
            $usuario->token=null;
            $usuario->guardar();
            Usuario::setAlerta('exito', 'Cuenta comprobada con éxito');
        }
        //Obtener alertas
        $alertas = Usuario::getAlertas();
        //Renderizar la vista
        $router->render('auth/confirmar-cuenta', [
            'alertas' => $alertas
        ]);
    }
}
