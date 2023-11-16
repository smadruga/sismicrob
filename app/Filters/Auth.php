<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class Auth implements FilterInterface {

    /**
     * Controle de sessão
     * Tempo atual: 2 horas (120 minutos)
     * Caso o tempo seja alterado aqui é necessário alterar a variável de ambiente
     * huap.session.expires no arquivo .env
     *
     * @param array|null $arguments
     *
     * @return mixed
     *
     */
    public function before(RequestInterface $request, $arguments = null)
    {

        $this->session = \Config\Services::session();

        if ( (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > env('huap.session.expires'))) || !isset($_SESSION['Sessao']))
            return redirect()->to('home/logout/timeout');

    }

    /**
     * We don't have anything to do here.
     *
     * @param array|null $arguments
     *
     * @return mixed
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // ...
    }

}
