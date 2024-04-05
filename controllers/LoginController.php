<?php
namespace Controllers;

use Classes\Email;
use Model\Usuario;
use MVC\Router;

class LoginController{

    public static function login(Router $router){
        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $auth = new Usuario($_POST);
            $alertas = $auth->validarLogin();

            if(empty($alertas)){
               //comprobar si existe el usuario
               $usuario = Usuario::where('email',$auth->email);
               if($usuario){
                    if($usuario->comprobarPasswordAndVerificado($auth->password)){
                        //autenticar usuario
                        session_start();
                        $_SESSION['id'] = $usuario->id;
                        $_SESSION['nombre'] = $usuario->nombre . " " . $usuario->apellido;
                        $_SESSION['email'] = $usuario->email;
                        $_SESSION['login'] = true;

                        //redireccionar
                        if($usuario->admin === '1'){
                            $_SESSION['admin'] = $usuario->admin ?? null;
                            header("Location: /admin");
                        }
                        else{header("Location: /cita");}
                    }
               }
               else{Usuario::setAlerta('error','El usuario ingresado NO EXISTE');}
            }
        }

        $alertas = Usuario::getAlertas();
        $router->render('auth/login',[
            'alertas' => $alertas
        ]);
    }



    public static function logout(Router $router){
        session_start();
        $_SESSION = [];
        header('Location: /');
    }


    public static function olvide(Router $router){
        $alertas=[];

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $auth = new Usuario($_POST);
            $alertas = $auth->validarEmail();
            if(empty($alertas)){
                $usuario = Usuario::where('email',$auth->email);
                if($usuario && $usuario->confirmado === '1'){
                    //generar token
                    $usuario->crearToken();
                    $usuario->guardar();
                    //enviar email 
                    $email = new Email($usuario->email,$usuario->nombre,$usuario->token);
                    $email->enviarInstrucciones();
                    //alerta
                    Usuario::setAlerta('exito','Sigue las instrucciones enviadas al correo para REESTABLECER CONTRASEÑA');
                }

                else{Usuario::setAlerta('error','El usuario NO EXISTE o NO ESTA CONFIRMADO');}
            }
        }
        $alertas = Usuario::getAlertas();
        $router->render('auth/olvide-password',[
            'alertas' => $alertas
        ]);
    }


    public static function recuperar(Router $router){
        $alertas = [];
        $error = false;
     
        $token = s($_GET['token']);
        
        //buscar usuario por token
        $usuario = Usuario::where('token', $token);
        if(empty($usuario)){
            Usuario::setAlerta('error','Token No Válido');
            $error = true;
        }
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            //leer nuevo password y guardar
            $password = new Usuario($_POST);
            $alertas = $password->validarPassword();
     
            if(empty($alertas)){
                $usuario->password = null;
                $usuario->password = $password->password;
                $usuario->hashPassword();
                $usuario->token = "";
                $resultado = $usuario->guardar();
                if($resultado) {
                    // Crear mensaje de exito
                    Usuario::setAlerta('exito', 'Password Actualizado Correctamente. Inicia Sesion');
                                    
                    // Redireccionar al login tras 3 segundos
                    header('Refresh: 3; url=/');
                }
            }
        }
     
        $alertas = Usuario::getAlertas();
        $router->render('auth/recuperar-password',[
            'alertas' => $alertas,
            'error' => $error
        ]);
    }



    public static function crear(Router $router){
        $usuario = new Usuario;
        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $usuario->sincronizar($_POST);
            $alertas = $usuario->validarNuevaCuenta();

            //revisar si alertas esta vacio
            if(empty($alertas)){
                //verificar si existe el usuario
                $resultado = $usuario->existeUsuario();
                if($resultado->num_rows){$alertas = Usuario::getAlertas();}
                else{
                    //hashear password
                    $usuario->hashPassword();
                    //generar token
                    $usuario->crearToken();
                    //enviar mail
                    $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
                    $email->enviarConfirmacion();

                    //crear el usuario
                    $resultado = $usuario->guardar();
                    if($resultado){header('Location: /mensaje');}
                }
            }

        }
        $router->render('auth/crear-cuenta',[
            'usuario' => $usuario,
            'alertas' =>$alertas
        ]);
    }




    public static function mensaje(Router $router){$router->render('auth/mensaje');}




    public static function confirmar(Router $router){
        $alertas = [];
        $token = s($_GET['token']);
        $usuario = Usuario::where('token',$token);

        if(empty($usuario)){Usuario::setAlerta('error','Token No Valido');}
        else{
            $usuario -> confirmado = "1";
            $usuario -> token = '';
            $usuario -> guardar();
            Usuario::setAlerta('exito','Cuenta Verificada Correctamente');
        }

        $alertas = Usuario::getAlertas();
        $router->render('auth/confirmar-cuenta',['alertas' => $alertas]);

    }
}