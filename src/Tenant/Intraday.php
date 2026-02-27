<?php

namespace DigitalsiteSaaS\Dresses\Tenant;

use Hyn\Tenancy\Traits\UsesTenantConnection;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;



class Intraday extends Model{

use UsesTenantConnection;

protected $table = 'dresses_intraday';

 protected $fillable = [
        'fecha','nombre','apellido','fecha_evento','ciudad','telefono',
        'follow','referido','ciya','time_in','time_out','time',
        'usuario_id','compro','comentarios'
    ];

public function getDuracionHumanaAttribute()
{
    if (!$this->time_in || !$this->time_out) {
        return null;
    }

    $inicio = Carbon::createFromFormat('H:i:s', $this->time_in);
    $fin    = Carbon::createFromFormat('H:i:s', $this->time_out);

    $minutosTotales = $inicio->diffInMinutes($fin);

    $horas   = intdiv($minutosTotales, 60);
    $minutos = $minutosTotales % 60;

    $texto = '';

    // Horas
    if ($horas > 0) {
        $texto .= $horas . ' ' . ($horas === 1 ? 'hora' : 'horas');
    } else {
        $texto .= '0 horas';
    }

    // Minutos
    $texto .= ' ' . $minutos . ' ' . ($minutos === 1 ? 'minuto' : 'minutos');

    return $texto;
}

public function usuario()
{
    return $this->belongsTo(\DigitalsiteSaaS\Usuario\Tenant\Usuario::class, 'usuario_id');
}


}