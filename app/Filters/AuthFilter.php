<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use App\Models\UserModel;

class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {

        // Si no está autenticado, redirigir al login
        if (!session('user')) {
            return redirect()->to('/')->with('message', 'Por favor inicia sesión');
        }

        // Verificar en cada petición que la cuenta siga activa en la BD.
        // Si fue desactivada (o eliminada) mientras la sesión estaba abierta,
        // se cierra la sesión de inmediato sin esperar a un nuevo login.
        $currentUser = (new UserModel())->find(session('user')->id);

        if (!$currentUser || (int) $currentUser->active !== 1) {
            session()->destroy();
            return redirect()->to('/')->with('message', 'Tu cuenta ha sido desactivada.');
        }

        // Si no tiene permisos, redirigir a 404
        if($arguments){
            $rol = session('user')->rol;
            if(!in_array($rol, $arguments)){
                return redirect()->to('/404');
            }
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Lógica después de la solicitud (si es necesario)
    }
}