<?php

namespace Sitedigitalweb\Dresses\Tenant;

use Hyn\Tenancy\Traits\UsesTenantConnection;
use Illuminate\Database\Eloquent\Model;

class Orden extends Model
{
  use UsesTenantConnection;
    protected $table = 'dresses_ordens';
    
    protected $fillable = [
        'cliente_id',
        'fecha_compra',
        'fecha_compraO',
        'pickDate',
        'returnDate',
        'vendedor',
        'identidad',
        'observaciones',
        'subtotal',
        'impuesto_total',
        'total',
        'adelanto',
        'adelanto1',
        'adelanto2',
        'adelanto3',
        'user1',
        'user2',
        'user3',
        'date1',
        'date2',
        'date3',
        'method',
        'method1',
        'method2',
        'method3',
        'status',
        'prefijo',
        'monto_adeudado',

    ];

    protected $dates = ['fecha_compra','fecha_compraO','pickDate','returnDate'];

    // Relación con cliente
    public function cliente()
    {
        return $this->belongsTo(\Sitedigitalweb\Dresses\Tenant\Cliente::class, 'cliente_id');
    }

    public function vendedor()
{
    return $this->belongsTo(\Sitedigitalweb\Usuario\Tenant\Usuario::class, 'users');
}

public function vendedorRelacion()
    {
        return $this->belongsTo(\Sitedigitalweb\Usuario\Tenant\Usuario::class, 'vendedor'); // Cambia 'vendedor' por el nombre correcto del campo
    }

    // Relación muchos a muchos con productos
    public function productos()
    {
        return $this->belongsToMany(\Sitedigitalweb\Dresses\Tenant\Producto::class, 'dresses_orden_producto')
            ->withPivot([
                'cantidad',
                'talla',
                'color',
                'descuento',
                'impuesto',
                'precio_unitario',
                'total'
            ]);
    }
}