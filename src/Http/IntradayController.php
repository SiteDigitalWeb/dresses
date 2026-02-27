<?php

namespace DigitalsiteSaaS\Dresses\Http;

use Illuminate\Http\Request;
use DigitalsiteSaaS\Dresses\Tenant\Cliente;
use DigitalsiteSaaS\Dresses\Intraday;
use App\Http\Controllers\Controller;
use DB;

class IntradayController extends Controller{
 
 protected $tenantName = null;

 public function __construct(){
  $this->middleware('auth');
   $hostname = app(\Hyn\Tenancy\Environment::class)->hostname();
   if ($hostname){
   $fqdn = $hostname->fqdn;
   $this->tenantName = explode(".", $fqdn)[0];
  }
 }

 public function create()
 {
  if(!$this->tenantName){
   $usuario = Usuario::all();
  }else{
   $usuario = \DigitalsiteSaaS\Usuario\Tenant\Usuario::all();
  }
  return view('dresses::intraday.create')->with('usuario',$usuario);
 }

 public function updateTimeOut(Request $request, $id)
{
    $request->validate([
        'time_out' => 'required|date_format:H:i'
    ]);

    $model = !$this->tenantName
        ? \DigitalsiteSaaS\Dresses\Intraday::findOrFail($id)
        : \DigitalsiteSaaS\Dresses\Tenant\Intraday::findOrFail($id);

    $model->update([
        'time_out' => $request->time_out
    ]);

    return response()->json([
        'success' => true,
        'time_out' => $model->time_out
    ]);
}


 public function index()
  {
   if(!$this->tenantName){
    $intraday = Intraday::all();
   }else{
    $intraday = \DigitalsiteSaaS\Dresses\Tenant\Intraday::all();
   }
   $website = app(\Hyn\Tenancy\Environment::class)->website();
   return view('dresses::intraday.index')->with('intraday',$intraday);
  }

  public function store(Request $request)
{
    $request->validate([
        'nombre'       => 'required|string',
        'apellido'       => 'required|string',
        'telefono'       => 'nullable|string',
        'fecha'          => 'nullable|date',
        'fecha_evento'   => 'nullable|date',
    ]);

    $data = [
        'fecha'         => $request->fecha,
        'nombre'        => $request->nombre,
        'apellido'      => $request->apellido,
        'fecha_evento'  => $request->fecha_evento,
        'ciudad'        => $request->ciudad,
        'telefono'      => $request->telefono,
        'follow'        => $request->follow ?? 0,
        'referido'      => $request->referido,
        'cita'          => $request->cita,
        'time_in'       => $request->time_in,
        'time_out'      => $request->time_out,
        'time'          => $request->time,
        'usuario_id'    => $request->usuario,
        'compro'        => $request->compro ?? 0,
        'comentarios'   => $request->comentarios,
    ];

    if (!$this->tenantName) {
        // 🌍 Modo central
        Intraday::create($data);
    } else {
        // 🏢 Modo tenant
        \DigitalsiteSaaS\Dresses\Tenant\Intraday::create($data);
    }

    return redirect()->back()->with('success', 'Registro guardado correctamente ✅');
} 


  public function edit($id)
    {
        if (!$this->tenantName) {
         $intraday = Intraday::findOrFail($id);
        } else {
         $intraday = \DigitalsiteSaaS\Dresses\Tenant\Intraday::findOrFail($id);
        }

        if(!$this->tenantName){
         $usuarios = Usuario::all();
        }else{
         $usuarios = \DigitalsiteSaaS\Usuario\Tenant\Usuario::all();
        }

        return view('dresses::intraday.edit', compact('intraday', 'usuarios'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre'        => 'required|string',
            'apellido'      => 'required|string',
            'telefono'      => 'nullable|string',
            'fecha'         => 'nullable|date',
            'fecha_evento'  => 'nullable|date',
        ]);

        $data = [
            'fecha'         => $request->fecha,
            'nombre'        => $request->nombre,
            'apellido'      => $request->apellido,
            'fecha_evento'  => $request->fecha_evento,
            'ciudad'        => $request->ciudad,
            'telefono'      => $request->telefono,
            'referido'      => $request->referido,
            'time_in'       => $request->time_in,
            'time_out'      => $request->time_out,
            'usuario_id'    => $request->usuario,
            'follow'        => $request->follow ?? 0,
            'cita'          => $request->cita ?? 0,
            'compro'        => $request->compro ?? 0,
            'comentarios'   => $request->comentarios,
        ];

        if (!$this->tenantName) {
            Intraday::where('id', $id)->update($data);
        } else {
            \DigitalsiteSaaS\Dresses\Tenant\Intraday::where('id', $id)->update($data);
        }

        return redirect()
            ->back()
            ->with('success', 'Registro actualizado correctamente ✅');
    }

    
    public function totalRegistros(Request $request)
    {
        // Obtener fechas del request o usar últimos 30 días por defecto
        $fecha_desde = $request->input('fecha_desde');
        $fecha_hasta = $request->input('fecha_hasta');
        $usuario_id = $request->input('usuario_id');
        
        // Si no hay fechas especificadas, usar últimos 30 días por defecto
        if (!$fecha_desde && !$fecha_hasta) {
            $fecha_hasta = now()->format('Y-m-d');
            $fecha_desde = now()->subDays(30)->format('Y-m-d');
        }
        
        // Función para aplicar filtros a cualquier consulta
        $applyFilters = function($query) use ($fecha_desde, $fecha_hasta, $usuario_id) {
            // Aplicar filtro de fechas
            if ($fecha_desde && $fecha_hasta) {
                $query->whereBetween('created_at', [
                    $fecha_desde . ' 00:00:00',
                    $fecha_hasta . ' 23:59:59'
                ]);
            } elseif ($fecha_desde) {
                $query->where('created_at', '>=', $fecha_desde . ' 00:00:00');
            } elseif ($fecha_hasta) {
                $query->where('created_at', '<=', $fecha_hasta . ' 23:59:59');
            }
            
            // Aplicar filtro por usuario si se seleccionó
            if ($usuario_id && $usuario_id !== 'todos') {
                $query->where('usuario_id', $usuario_id);
            }
            
            return $query;
        };

        // Obtener lista de usuarios de manera diferente
        $usuarios = [];
        
        if (!$this->tenantName) {
            // 🌍 Central - Obtener usuarios que tienen registros en Intraday
            $usuarios = \App\Models\User::whereIn('id', function($query) {
                    $query->select('usuario_id')
                          ->from('intradays') // Ajusta el nombre de la tabla si es diferente
                          ->whereNotNull('usuario_id')
                          ->groupBy('usuario_id');
                })
                ->select('id', 'name')
                ->orderBy('name')
                ->get();
        } else {
            // 🏢 Tenant - Obtener usuarios que tienen registros en Intraday
            // Primero necesito saber el nombre exacto de tu modelo de usuarios tenant
            // Voy a usar dos posibles opciones:
            
            try {
                // Opción 1: Si el modelo se llama Usuario
                $usuarios = \DigitalsiteSaaS\Usuario\Tenant\Usuario::whereIn('id', function($query) {
                        $query->select('usuario_id')
                              ->from('intradays') // Ajusta el nombre de la tabla si es diferente
                              ->whereNotNull('usuario_id')
                              ->groupBy('usuario_id');
                    })
                    ->select('id', 'name')
                    ->orderBy('name')
                    ->get();
            } catch (\Exception $e) {
                // Opción 2: Si no funciona, obtenemos los usuarios directamente de los registros Intraday
                $usuariosIds = [];
                
                if (!$this->tenantName) {
                    $usuariosIds = Intraday::whereNotNull('usuario_id')
                        ->distinct('usuario_id')
                        ->pluck('usuario_id')
                        ->toArray();
                } else {
                    $usuariosIds = \DigitalsiteSaaS\Dresses\Tenant\Intraday::whereNotNull('usuario_id')
                        ->distinct('usuario_id')
                        ->pluck('usuario_id')
                        ->toArray();
                }
                
                // Ahora obtenemos los usuarios basados en los IDs
                $usuarios = \DigitalsiteSaaS\Usuario\Tenant\Usuario::whereIn('id', $usuariosIds)
                    ->select('id', 'name')
                    ->orderBy('name')
                    ->get();
            }
        }

        // 🌍 Central
        if (!$this->tenantName) {
            // Total registros con filtro
            $totalQuery = Intraday::query();
            $applyFilters($totalQuery);
            $total = $totalQuery->count();
            
            // Compraron con filtro
            $compraronQuery = Intraday::where('compro', 1);
            $applyFilters($compraronQuery);
            $compraron = $compraronQuery->count();
            
            // No compraron con filtro
            $noCompraronQuery = Intraday::where('compro', 0);
            $applyFilters($noCompraronQuery);
            $noCompraron = $noCompraronQuery->count();
            
            // Registros por mes con filtro
            $registrosQuery = Intraday::select(
                    DB::raw('YEAR(created_at) as anio'),
                    DB::raw('MONTH(created_at) as mes'),
                    DB::raw('COUNT(*) as total')
                );
            $applyFilters($registrosQuery);
            $registros = $registrosQuery->groupBy('anio', 'mes')
                ->orderBy('anio')
                ->orderBy('mes')
                ->get();
            
            // Select para usuarios
            $selectRaw = "
                COUNT(*) as total,
                SUM(CASE 
                    WHEN compro = 1 THEN 1 
                    ELSE 0 
                END) as compraron,
                (
                    SUM(CASE WHEN compro = 1 THEN 1 ELSE 0 END) / COUNT(*)
                ) * 100 as conversion,
                AVG(
                    CASE
                        WHEN time_in IS NOT NULL 
                             AND time_out IS NOT NULL 
                             AND time_out >= time_in
                        THEN TIMESTAMPDIFF(MINUTE, time_in, time_out)
                        WHEN time_in IS NOT NULL 
                             AND time_out IS NOT NULL 
                             AND time_out < time_in
                        THEN TIMESTAMPDIFF(MINUTE, time_in, time_out) + 1440
                        ELSE NULL
                    END
                ) as promedio_minutos
            ";
            
            // Para la tabla de usuarios, si hay filtro por usuario específico, solo mostrar ese
            $registros_usersQuery = Intraday::select(
                    'usuario_id',
                    DB::raw($selectRaw)
                );
            
            // Si hay filtro por usuario, no agrupar, mostrar solo ese usuario
            if ($usuario_id && $usuario_id !== 'todos') {
                $registros_usersQuery->where('usuario_id', $usuario_id);
                $registros_users = $registros_usersQuery->groupBy('usuario_id')
                    ->with(['usuario' => function($query) {
                        $query->select('id', 'name');
                    }])
                    ->orderByDesc('total')
                    ->get();
            } else {
                // Si no hay filtro, mostrar todos agrupados
                $applyFilters($registros_usersQuery);
                $registros_users = $registros_usersQuery->groupBy('usuario_id')
                    ->with(['usuario' => function($query) {
                        $query->select('id', 'name');
                    }])
                    ->orderByDesc('total')
                    ->get();
            }
            
            // Promedio minutos con filtro
            $promedioQuery = Intraday::whereNotNull('time_in')
                ->whereNotNull('time_out');
            $applyFilters($promedioQuery);
            $promedioMinutos = $promedioQuery->select(DB::raw("
                AVG(
                    CASE
                        WHEN time_out >= time_in
                        THEN TIMESTAMPDIFF(MINUTE, time_in, time_out)
                        ELSE TIMESTAMPDIFF(MINUTE, time_in, time_out) + 1440
                    END
                ) as promedio
            "))
            ->value('promedio');
            
            // Stats generales con filtro
            $statsQuery = Intraday::select(DB::raw("
                COUNT(*) as total,
                SUM(CASE WHEN compro = 1 THEN 1 ELSE 0 END) as compraron,
                (
                    SUM(CASE WHEN compro = 1 THEN 1 ELSE 0 END) / NULLIF(COUNT(*),0)
                ) * 100 as conversion
            "));
            $applyFilters($statsQuery);
            $stats = $statsQuery->first();
            
            // Visitas por horario con filtro
            $visitasQuery = Intraday::select(DB::raw("
                SUM(CASE 
                    WHEN HOUR(created_at) BETWEEN 6 AND 11 THEN 1 
                    ELSE 0 
                END) as manana,
                SUM(CASE 
                    WHEN HOUR(created_at) BETWEEN 12 AND 17 THEN 1 
                    ELSE 0 
                END) as tarde,
                SUM(CASE 
                    WHEN HOUR(created_at) >= 18 
                      OR HOUR(created_at) <= 5 THEN 1 
                    ELSE 0 
                END) as noche
            "));
            $applyFilters($visitasQuery);
            $visitas = $visitasQuery->first();
            
        } else {
            // 🏢 Tenant - Misma lógica pero con el modelo tenant
            // Total registros con filtro
            $totalQuery = \DigitalsiteSaaS\Dresses\Tenant\Intraday::query();
            $applyFilters($totalQuery);
            $total = $totalQuery->count();
            
            // Compraron con filtro
            $compraronQuery = \DigitalsiteSaaS\Dresses\Tenant\Intraday::where('compro', 1);
            $applyFilters($compraronQuery);
            $compraron = $compraronQuery->count();
            
            // No compraron con filtro
            $noCompraronQuery = \DigitalsiteSaaS\Dresses\Tenant\Intraday::where('compro', 0);
            $applyFilters($noCompraronQuery);
            $noCompraron = $noCompraronQuery->count();
            
            // Registros por mes con filtro
            $registrosQuery = \DigitalsiteSaaS\Dresses\Tenant\Intraday::select(
                    DB::raw('YEAR(created_at) as anio'),
                    DB::raw('MONTH(created_at) as mes'),
                    DB::raw('COUNT(*) as total')
                );
            $applyFilters($registrosQuery);
            $registros = $registrosQuery->groupBy('anio', 'mes')
                ->orderBy('anio')
                ->orderBy('mes')
                ->get();
            
            $selectRaw = "
                COUNT(*) as total,
                SUM(CASE 
                    WHEN compro = 1 THEN 1 
                    ELSE 0 
                END) as compraron,
                (
                    SUM(CASE WHEN compro = 1 THEN 1 ELSE 0 END) / COUNT(*)
                ) * 100 as conversion,
                AVG(
                    CASE
                        WHEN time_in IS NOT NULL 
                             AND time_out IS NOT NULL 
                             AND time_out >= time_in
                        THEN TIMESTAMPDIFF(MINUTE, time_in, time_out)
                        WHEN time_in IS NOT NULL 
                             AND time_out IS NOT NULL 
                             AND time_out < time_in
                        THEN TIMESTAMPDIFF(MINUTE, time_in, time_out) + 1440
                        ELSE NULL
                    END
                ) as promedio_minutos
            ";
            
            // Para la tabla de usuarios, si hay filtro por usuario específico, solo mostrar ese
            $registros_usersQuery = \DigitalsiteSaaS\Dresses\Tenant\Intraday::select(
                    'usuario_id',
                    DB::raw($selectRaw)
                );
            
            // Si hay filtro por usuario, no agrupar, mostrar solo ese usuario
            if ($usuario_id && $usuario_id !== 'todos') {
                $registros_usersQuery->where('usuario_id', $usuario_id);
                $registros_users = $registros_usersQuery->groupBy('usuario_id')
                    ->with(['usuario' => function($query) {
                        $query->select('id', 'name');
                    }])
                    ->orderByDesc('total')
                    ->get();
            } else {
                // Si no hay filtro, mostrar todos agrupados
                $applyFilters($registros_usersQuery);
                $registros_users = $registros_usersQuery->groupBy('usuario_id')
                    ->with(['usuario' => function($query) {
                        $query->select('id', 'name');
                    }])
                    ->orderByDesc('total')
                    ->get();
            }
            
            // Promedio minutos con filtro
            $promedioQuery = \DigitalsiteSaaS\Dresses\Tenant\Intraday::whereNotNull('time_in')
                ->whereNotNull('time_out');
            $applyFilters($promedioQuery);
            $promedioMinutos = $promedioQuery->select(DB::raw("
                AVG(
                    CASE
                        WHEN time_out >= time_in
                        THEN TIMESTAMPDIFF(MINUTE, time_in, time_out)
                        ELSE TIMESTAMPDIFF(MINUTE, time_in, time_out) + 1440
                    END
                ) as promedio
            "))
            ->value('promedio');
            
            // Stats generales con filtro
            $statsQuery = \DigitalsiteSaaS\Dresses\Tenant\Intraday::select(DB::raw("
                COUNT(*) as total,
                SUM(CASE WHEN compro = 1 THEN 1 ELSE 0 END) as compraron,
                (
                    SUM(CASE WHEN compro = 1 THEN 1 ELSE 0 END) / NULLIF(COUNT(*),0)
                ) * 100 as conversion
            "));
            $applyFilters($statsQuery);
            $stats = $statsQuery->first();
            
            // Visitas por horario con filtro
            $visitasQuery = \DigitalsiteSaaS\Dresses\Tenant\Intraday::select(DB::raw("
                SUM(CASE 
                    WHEN HOUR(created_at) BETWEEN 6 AND 11 THEN 1 
                    ELSE 0 
                END) as manana,
                SUM(CASE 
                    WHEN HOUR(created_at) BETWEEN 12 AND 17 THEN 1 
                    ELSE 0 
                END) as tarde,
                SUM(CASE 
                    WHEN HOUR(created_at) >= 18 
                      OR HOUR(created_at) <= 5 THEN 1 
                    ELSE 0 
                END) as noche
            "));
            $applyFilters($visitasQuery);
            $visitas = $visitasQuery->first();
        }

        return view('dresses::reportes.registros', compact(
            'total', 'compraron', 'noCompraron', 'registros', 
            'registros_users', 'promedioMinutos', 'visitas', 'stats',
            'fecha_desde', 'fecha_hasta', 'usuario_id', 'usuarios'
        ));
    }
}

