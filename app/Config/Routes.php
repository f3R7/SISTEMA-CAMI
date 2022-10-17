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
$routes->setDefaultController('Home');
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

$routes->post('/auth/login','Auth::login');
$routes->post('/auth/usucreate','Auth::create');
$routes->post('/auth/percreate','Auth::percreate');
$routes->get('/auth/listproductos','Auth::listproductos');
$routes->post('/Restauraciones/create', 'Restauraciones::create');
$routes->put('/Restauraciones/update', 'Restauraciones::update');
$routes->get('/auth/detalles/(:num)', 'Auth::detalleproducto/$1');

$routes->group('api', ['namespace' => 'App\Controllers\API', 'filter' => 'authFilter'], function($routes){

    $routes->get('personas', 'Personas::index');
    $routes->post('personas/create', 'Personas::create');
    $routes->get('personas/edit/(:num)', 'Personas::edit/$1');
    $routes->put('personas/update/(:num)', 'Personas::update/$1');
    $routes->put('personas/updateme/(:num)', 'Personas::updatemedico/$1');
    $routes->delete('personas/delete/(:num)', 'Personas::delete/$1');
    $routes->put('personas/updateadm/(:num)', 'Personas::updateadm/$1');

    
    $routes->get('usuarios/editperfil/(:num)', 'Usuarios::editperfil/$1');
    $routes->get('usuarios', 'Usuarios::index');
    $routes->post('usuarios/create', 'Usuarios::create');
    $routes->post('usuarios/upfile', 'Usuarios::upfile');
    $routes->get('usuarios/edit/(:num)', 'Usuarios::edit/$1');
    $routes->put('usuarios/desactivar/(:num)', 'Usuarios::desactivar/$1');
    $routes->put('usuarios/activar/(:num)', 'Usuarios::activar/$1');


    $routes->get('roles', 'Roles::index');

    $routes->get('marcas', 'Marcas::index');
    $routes->post('marcas/create', 'Marcas::create');
    $routes->get('marcas/edit/(:num)', 'Marcas::edit/$1');
    $routes->put('marcas/update/(:num)', 'Marcas::update/$1');
    $routes->put('marcas/desactivar/(:num)', 'Marcas::desactivar/$1');
    $routes->put('marcas/activar/(:num)', 'Marcas::activar/$1');

    $routes->get('categorias', 'Categorias::index');
    $routes->post('categorias/create', 'Categorias::create');
    $routes->get('categorias/edit/(:num)', 'Categorias::edit/$1');
    $routes->put('categorias/update/(:num)', 'Categorias::update/$1');
    $routes->put('categorias/desactivar/(:num)', 'Categorias::desactivar/$1');
    $routes->put('categorias/activar/(:num)', 'Categorias::activar/$1');

    $routes->get('productos', 'Productos::index');
    $routes->post('productos/create', 'Productos::create');
    $routes->get('productos/edit/(:num)', 'Productos::edit/$1');
    $routes->put('productos/update/(:num)', 'Productos::update/$1');
    $routes->put('productos/desactivar/(:num)', 'Productos::desactivar/$1');
    $routes->put('productos/activar/(:num)', 'Productos::activar/$1');
    $routes->post('productos/upfile', 'Productos::upfile');
    $routes->get('productos/listaproductos', 'Productos::listaproductos');


    $routes->get('ingresos', 'Ingresos::index');
    $routes->post('ingresos/create', 'Ingresos::create');


    /*

    $routes->get('detalleingreso', 'DetalleIngreso::index');
    $routes->post('detalleingreso', 'DetalleIngreso::create');

    $routes->post('ingresos/createdet', 'Ingresos::createdet');
    $routes->post('ingresos/pruebas', 'Ingresos::pruebas');
    */
});



if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
