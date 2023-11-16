<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (file_exists(SYSTEMPATH . 'Config/Routes.php')) {
    require SYSTEMPATH . 'Config/Routes.php';
}

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('HomeController');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(true);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
$routes->get('/', 'Home::index');

$routes->group('home', function ($routes) {
    $routes->get('/', 'Home::index');
    $routes->get('index', 'Home::index');
    #$routes->match(['get', 'post'], 'login', 'Home::index');
    $routes->post('login', 'Home::login');
    $routes->get('logout/(:any)', 'Home::logout/$1');
});

$routes->group('admin', function ($routes) {
    $routes->add('/', 'Admin::index');
    $routes->get('find_user', 'Admin::find_user');
    $routes->post('get_user', 'Admin::get_user');
    #$routes->match(['get', 'post'], 'get_user', 'Admin::get_user');
    $routes->get('get_user/(:any)', 'Admin::get_user/$1');
    $routes->post('import_user', 'Admin::import_user');
    $routes->get('show_user/(:any)', 'Admin::show_user/$1');
    $routes->get('list_perfil/(:any)', 'Admin::list_perfil/$1');
    $routes->post('set_perfil', 'Admin::set_perfil');
    $routes->get('del_perfil/(:any)', 'Admin::del_perfil/$1');
    $routes->match(['get', 'post'], 'disable_user/(:any)', 'Admin::disable_user/$1');
    $routes->match(['get', 'post'], 'enable_user/(:any)', 'Admin::enable_user/$1');
    $routes->add('teste', 'Admin::teste');
});

$routes->group('tabela', function ($routes) {
    $routes->match(['get', 'post'], 'list_tabela/(:any)', 'Tabela::list_tabela/$1');
    $routes->get('edit_item', 'Tabela::edit_item');
    $routes->post('manage_item', 'Tabela::manage_item');
    $routes->get('sort_item/(:any)/(:any)/(:any)', 'Tabela::sort_item/$1/$2/$3');
    $routes->get('sort_medicamento/(:any)', 'Tabela::sort_medicamento/$1');
});

$routes->group('paciente', function ($routes) {
    $routes->add('/', 'Paciente::find_paciente');
    $routes->get('find_paciente', 'Paciente::find_paciente');
    $routes->post('get_paciente', 'Paciente::get_paciente');
    $routes->get('show_paciente/(:any)', 'Paciente::show_paciente/$1');
    $routes->get('list_paciente/(:any)', 'Paciente::list_paciente/$1');
});

$routes->group('prescricao', function ($routes) {
    $routes->get('list_prescricao/(:any)', 'Prescricao::list_prescricao/$1');
    $routes->get('print_prescricao/(:any)', 'Prescricao::print_prescricao/$1');
    $routes->get('page_prescricao', 'Prescricao::page_prescricao');
    $routes->get('manage_prescricao/(:any)/(:any)', 'Prescricao::manage_prescricao/$1/$2');
    $routes->get('manage_medicamento/(:any)/(:any)', 'Prescricao::manage_medicamento/$1/$2');
});

$routes->group('migracao', function ($routes) {
    $routes->get('completa_tabela', 'Migracao::completa_tabela');
    $routes->get('calcula_tabela', 'Migracao::calcula_tabela');
});

/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
