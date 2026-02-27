<?php

Route::group(['middleware' => ['auth','usuariodresses']], function (){

Route::get('dresses/empresa', function () {
    return view('indexdresses');
});

});

// O si ya tienes un resource para clientes, puedes usar:
Route::resource('clientes', ClienteController::class);
// Esto automáticamente crea la ruta 'clientes.update'

Route::get('dresses/usuarios', 'Sitedigitalweb\Dresses\Http\UsuariaController@index');
Route::get('dresses/crear-usuario', 'Sitedigitalweb\Dresses\Http\UsuariaController@crearusuario');
Route::get('dresses/editar/{id}', 'Sitedigitalweb\Dresses\Http\UsuariaController@editar');
Route::get('dresses/eliminar/{id}', 'Sitedigitalweb\Dresses\Http\UsuariaController@eliminar');
Route::post('dresses/crear-usuario', 'Sitedigitalweb\Dresses\Http\UsuariaController@crear');
Route::post('dresses/actualizar/{id}', 'Sitedigitalweb\Dresses\Http\UsuariaController@actualizar');

Route::get('dresses/clientes', 'Sitedigitalweb\Dresses\Http\UsuariaController@clientes');
Route::get('dresses/editar/cliente/{id}', 'Sitedigitalweb\Dresses\Http\UsuariaController@editarclientes');
Route::get('dresses/editar/clientepos/{id}', 'Sitedigitalweb\Dresses\Http\UsuariaController@editarclientespos');
Route::get('dresses/editar/orden/{id}', 'DigitalsiteSaaS\Dresses\Http\OrdenController@editarorden');
Route::post('dresses/dresses/eidtar-clienteweb/{id}', 'Sitedigitalweb\Dresses\Http\UsuariaController@editarclienteweb');



Route::post('dresses/dresses/eidtar-clientewebpos/{id}', 'DigitalsiteSaaS\Dresses\Http\UsuariaController@editarclientewebpos');

Route::get('dresses/editar/producto/{id}', 'Sitedigitalweb\Dresses\Http\UsuariaController@editarproductosweb');
Route::post('dresses/editar/producto/{id}', 'Sitedigitalweb\Dresses\Http\UsuariaController@editarproductoswebs');

Route::get('dresses/edit/store/{id}', 'Sitedigitalweb\Dresses\Http\UsuariaController@editstore');
Route::post('dresses/edit/store/{id}', 'Sitedigitalweb\Dresses\Http\UsuariaController@editstores');

Route::get('dresses/crear-cliente', 'Sitedigitalweb\Dresses\Http\UsuariaController@crearcliente');
Route::post('dresses/crear-cliente', 'Sitedigitalweb\Dresses\Http\UsuariaController@createcliente');

Route::get('dresses/factura/lista-facturas/{id}', 'DigitalsiteSaaS\Dresses\Http\UsuariaController@facturaempresa');

Route::get('dresses/factura/crear-producto', 'Sitedigitalweb\Dresses\Http\UsuariaController@createproducto');
Route::post('productos/creates', 'Sitedigitalweb\Dresses\Http\UsuariaController@creaproducts');

Route::get('dresses/intraday', 'DigitalsiteSaaS\Dresses\Http\IntradayController@index');
Route::get('dresses/intraday-create', [DigitalsiteSaaS\Dresses\Http\IntradayController::class, 'create']);
Route::post('/dresses-intraday', [DigitalsiteSaaS\Dresses\Http\IntradayController::class, 'store'])->name('dresses-intraday.store');
Route::get('dresses/report', [DigitalsiteSaaS\Dresses\Http\IntradayController::class, 'totalRegistros']);

Route::get('/dresses-intraday/{id}/edit', [DigitalsiteSaaS\Dresses\Http\IntradayController::class, 'edit'])
    ->name('dresses-intraday.edit');

Route::put('/dresses-intraday/{id}', [DigitalsiteSaaS\Dresses\Http\IntradayController::class, 'update'])
    ->name('dresses-intraday.update');

Route::patch(
    'dresses-intraday/{intraday}/time-out',
    [DigitalsiteSaaS\Dresses\Http\IntradayController::class, 'updateTimeOut']
)->name('dresses-intraday.update-time-out');

Route::get('dresses/factura/crear-facturacion/{id}', 'DigitalsiteSaaS\Dresses\Http\UsuariaController@crearfactura');
Route::post('dresses/factura/crear-factura', 'DigitalsiteSaaS\Dresses\Http\UsuariaController@createfactura');

Route::post('dresses/crear-impuesto', 'Sitedigitalweb\Dresses\Http\OrdenController@createimpuesto');

Route::get('Facturacione/{id}', 'DigitalsiteSaaS\Dresses\Http\UsuariaController@facturacione');
Route::get('Facturacione/{id}/ajax-subcat', 'DigitalsiteSaaS\Dresses\Http\UsuariaController@facturacioneajax');
Route::post('dresses/factura/creacion-producto', 'DigitalsiteSaaS\Dresses\Http\UsuariaController@creatproducto');
Route::get('dresses/factura/generar-factura/{id}', 'DigitalsiteSaaS\Dresses\Http\UsuariaController@pdf');
Route::post('dresses/factura/editar-empresa/{id}', 'DigitalsiteSaaS\Dresses\Http\UsuariaController@editarempresa');
Route::post('dresses/factura/actualizar-empresa/{id}', 'DigitalsiteSaaS\Dresses\Http\UsuariaController@update');
Route::post('dresses/factura/crear-empresa', 'Sitedigitalweb\Dresses\Http\UsuariaController@createempresa');
Route::get('dresses/factura/crearempresa', 'Sitedigitalweb\Dresses\Http\UsuariaController@crearempresaweb');
Route::get('dresses/factura/negocios', 'Sitedigitalweb\Dresses\Http\UsuariaController@negocios');

Route::post('/productos/pdf', [DigitalsiteSaaS\Dresses\Http\OrdenController::class, 'bulkFicha'])
    ->name('productos.pdf');


Route::get('dresses/specialorders', 'Sitedigitalweb\Dresses\Http\UsuariaController@special');
Route::get('dresses/layaway', 'Sitedigitalweb\Dresses\Http\UsuariaController@special');
Route::get('dresses/rentals', 'Sitedigitalweb\Dresses\Http\UsuariaController@rentals');

Route::get('dresses/impuestos', 'Sitedigitalweb\Dresses\Http\OrdenController@impuestos');

Route::get('dresses/specialorders/{id}', 'DigitalsiteSaaS\Dresses\Http\UsuariaController@specialedit');

Route::get('dresses/ver-ordenes/{id}', 'DigitalsiteSaaS\Dresses\Http\OrdenController@verordenes');

Route::get('dresses/ver-ordenes', 'Sitedigitalweb\Dresses\Http\OrdenController@verordenestotal');

Route::get('dresses/ver-taxes', 'Sitedigitalweb\Dresses\Http\OrdenController@vertaxes');
Route::get('dresses/crear-taxes', 'Sitedigitalweb\Dresses\Http\OrdenController@creartaxes');

Route::get('orden/delete/{id}', 'DigitalsiteSaaS\Dresses\Http\OrdenController@ordendelete');
Route::get('gestion/factura/eliminar-almacen/{id}', 'DigitalsiteSaaS\Dresses\Http\OrdenController@productdelete');

Route::get('gestion/factura/eliminar-taxes/{id}', 'DigitalsiteSaaS\Dresses\Http\OrdenController@impuestodelete');


Route::get('gestion/factura/eliminar-tienda/{id}', 'DigitalsiteSaaS\Dresses\Http\OrdenController@negociodelete');




Route::get('/dresses/search', [Sitedigitalweb\Dresses\Http\UsuariaController::class, 'search'])->name('dresses.search');

Route::get('/dresses/client', [Sitedigitalweb\Dresses\Http\UsuariaController::class, 'client'])->name('dresses.client');


Route::post('/dresses/venta', [DigitalsiteSaaS\Dresses\Http\UsuariaController::class, 'store'])->name('dresses.ventaa');


Route::post('/guardar-venta', [Sitedigitalweb\Dresses\Http\OrdenController::class, 'store'])->name('dresses.venta');
Route::get('/buscar-clientes', [DigitalsiteSaaS\Dresses\Http\OrdenController::class, 'searchClientes'])->name('dresses.clients');
Route::get('/buscar-productos', [DigitalsiteSaaS\Dresses\Http\OrdenController::class, 'searchProductos'])->name('dresses.searchs');
Route::post('/clientes', [Sitedigitalweb\Dresses\Http\ClienteController::class, 'store'])->name('clientes.store');

Route::get('/orders/{id}/edit', [Sitedigitalweb\Dresses\Http\OrdenController::class, 'edit'])->name('orders.edit');
Route::get('/ordersa/{id}/edit', [Sitedigitalweb\Dresses\Http\OrdenController::class, 'edit'])->name('orders.edita');
Route::put('/orders/{id}', [Sitedigitalweb\Dresses\Http\OrdenController::class, 'update'])->name('orders.update');
Route::put('/ordersa/{id}', [DigitalsiteSaaS\Dresses\Http\OrdenController::class, 'updatea'])->name('orders.updatea');
Route::get('/orders/{id}', [DigitalsiteSaaS\Dresses\Http\OrdenController::class, 'show'])->name('orders.show');


Route::get('/orders/{id}/pdf', [DigitalsiteSaaS\Dresses\Http\OrdenController::class, 'generatePDF'])->name('orders.pdf');

Route::get('/orders/{id}/print', [Sitedigitalweb\Dresses\Http\OrdenController::class, 'print'])->name('orders.print');

// Para visualizar en navegador
Route::get('/orders/{id}/view', [Sitedigitalweb\Dresses\Http\OrdenController::class, 'generatePDF'])->name('orders.view');

// Para descargar
Route::get('/orders/{id}/download', function($id) {
    return app()->call('Sitedigitalweb\Dresses\Http\OrdenController@generatePDF', ['id' => $id, 'download' => true]);
})->name('orders.download');

Route::get('/dresses/impuestos', function() {
    return \Sitedigitalweb\Dresses\Tenant\Impuesto::all();
})->name('dresses.impuestos');

