@extends('LayoutDresses.layout')

@section('ContenidoSite-01')

<!-- Formulario de filtro -->
<div class="card mb-4">
    <div class="card-body">
        <h5>Filtrar Reporte</h5>
        <form method="GET" action="{{ url()->current() }}" class="row g-3">
            <div class="col-md-3">
                <label for="usuario_id" class="form-label">Usuario:</label>
                <select class="form-control" id="usuario_id" name="usuario_id">
                    <option value="todos" {{ ($usuario_id ?? 'todos') == 'todos' ? 'selected' : '' }}>
                        Todos los usuarios
                    </option>
                    @if($usuarios && $usuarios->count() > 0)
                        @foreach($usuarios as $usuario)
                            <option value="{{ $usuario->id }}" {{ ($usuario_id ?? '') == $usuario->id ? 'selected' : '' }}>
                                {{ $usuario->name }}
                            </option>
                        @endforeach
                    @else
                        <option value="">No hay usuarios disponibles</option>
                    @endif
                </select>
            </div>
            
            <div class="col-md-3">
                <label for="fecha_desde" class="form-label">Fecha Desde:</label>
                <input type="date" 
                       class="form-control" 
                       id="fecha_desde" 
                       name="fecha_desde" 
                       value="{{ $fecha_desde ?? '' }}"
                       max="{{ $fecha_hasta ?? date('Y-m-d') }}">
            </div>
            
            <div class="col-md-3">
                <label for="fecha_hasta" class="form-label">Fecha Hasta:</label>
                <input type="date" 
                       class="form-control" 
                       id="fecha_hasta" 
                       name="fecha_hasta" 
                       value="{{ $fecha_hasta ?? '' }}"
                       min="{{ $fecha_desde ?? '' }}"
                       max="{{ date('Y-m-d') }}">
            </div>
            
            <div class="col-md-3 d-flex align-items-end">
                <div class="btn-group w-100">
                    <button type="submit" class="btn btn-primary">
                        <i class="feather icon-filter"></i> Filtrar
                    </button>
                    <a href="{{ url()->current() }}" class="btn btn-secondary">
                        <i class="feather icon-refresh-cw"></i> Resetear
                    </a>
                </div>
            </div>
        </form>
        
        <!-- Rangos rápidos -->
        <div class="mt-3">
            <small class="text-muted">Rangos rápidos:</small>
            <div class="btn-group mt-1" role="group">
                <button type="button" class="btn btn-sm btn-outline-primary btn-rango" data-dias="0">
                    Hoy
                </button>
                <button type="button" class="btn btn-sm btn-outline-primary btn-rango" data-dias="7">
                    Últimos 7 días
                </button>
                <button type="button" class="btn btn-sm btn-outline-primary btn-rango" data-dias="30">
                    Últimos 30 días
                </button>
                <button type="button" class="btn btn-sm btn-outline-primary btn-rango" data-dias="90">
                    Últimos 90 días
                </button>
            </div>
        </div>
        
        <!-- Información del filtro actual -->
        <div class="mt-3">
            <div class="alert alert-info">
                <i class="feather icon-info"></i>
                <strong>Filtro actual:</strong>
                
                @if($usuario_id && $usuario_id !== 'todos')
                    @php
                        $usuarioSeleccionado = $usuarios->firstWhere('id', $usuario_id);
                    @endphp
                    <strong>Usuario:</strong> {{ $usuarioSeleccionado->name ?? 'Desconocido' }} | 
                @else
                    <strong>Usuario:</strong> Todos | 
                @endif
                
                @if($fecha_desde && $fecha_hasta)
                    <strong>Período:</strong> {{ \Carbon\Carbon::parse($fecha_desde)->format('d/m/Y') }} 
                    al {{ \Carbon\Carbon::parse($fecha_hasta)->format('d/m/Y') }}
                @else
                    <strong>Período:</strong> Últimos 30 días 
                    ({{ \Carbon\Carbon::parse($fecha_desde)->format('d/m/Y') }} - 
                    {{ \Carbon\Carbon::parse($fecha_hasta)->format('d/m/Y') }})
                @endif
                
                @if($usuario_id && $usuario_id !== 'todos')
                    <br><small class="text-muted">Nota: Cuando se filtra por usuario específico, la tabla de actividad solo muestra ese usuario.</small>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Tarjetas de métricas -->
<div class="row">
    <div class="col-md-3 col-xl-3">
        <div class="card bg-c-blue order-card">
            <div class="card-body">
                <h6 class="m-b-20">Total Registros</h6>
                <h2 class="text-start"><span>{{ $total }}</span><i class="feather icon-shopping-cart float-end"></i></h2>
                <p class="m-b-0 text-end">Total registros<span class="float-start">351</span></p>
            </div>
        </div>
    </div>

    <div class="col-md-3 col-xl-3">
        <div class="card bg-c-green order-card">
            <div class="card-body">
                <h6 class="m-b-20">Compraron</h6>
                <h2 class="text-start"><span>{{ $compraron }}</span><i class="feather icon-shopping-cart float-end"></i></h2>
                <p class="m-b-0 text-end">Compras satisfactorias<span class="float-start">351</span></p>
            </div>
        </div>
    </div>

    <div class="col-md-3 col-xl-3">
        <div class="card bg-c-red order-card">
            <div class="card-body">
                <h6 class="m-b-20">No compraron</h6>
                <h2 class="text-start"><span>{{ $noCompraron }}</span><i class="feather icon-shopping-cart float-end"></i></h2>
                <p class="m-b-0 text-end">Compra declinadas<span class="float-start">351</span></p>
            </div>
        </div>
    </div>

    <div class="col-md-3 col-xl-3">
        <div class="card bg-c-yellow order-card">
            <div class="card-body">
                <h6 class="m-b-20">Porcentaje Conversión</h6>
                <h2 class="text-start"><span> {{ round($stats->conversion, 2) }} %</span><i class="feather icon-shopping-cart float-end"></i></h2>
                <p class="m-b-0 text-end">Conversión general<span class="float-start">351</span></p>
            </div>
        </div>
    </div>

    <div class="col-md-3 col-xl-3">
        <div class="card bg-c-purple order-card">
            <div class="card-body">
                <h6 class="m-b-20">Tiempo atención general</h6>
                @php
                $minutos = max(0, round($promedioMinutos));
                $horas = intdiv($minutos, 60);
                $mins  = $minutos % 60;
                @endphp
                <h2 class="text-start">
                    <span> {{ $horas }} {{ $horas === 1 ? 'hora' : 'horas' }}
                    {{ $mins }} {{ $mins === 1 ? 'minuto' : 'minutos' }}</span>
                    <i class="feather icon-shopping-cart float-end"></i>
                </h2>
                <p class="m-b-0 text-end">Tiempo general<span class="float-start">351</span></p>
            </div>
        </div>
    </div>
</div>

<!-- Tabla de actividad de usuarios -->
<div class="col-xl-12 col-md-12">
    <div class="card User-Activity table-card">
        <div class="card-header">
            <h5>Actividad de Usuarios</h5>
            @if($usuario_id && $usuario_id !== 'todos')
                <span class="badge bg-info">Filtrado por usuario específico</span>
            @endif
            <div class="card-header-right">
                <div class="btn-group card-option">
                    <button type="button" class="btn dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="feather icon-more-horizontal"></i>
                    </button>
                    <ul class="list-unstyled card-option dropdown-menu dropdown-menu-end">
                        <li class="dropdown-item full-card"><a href="#!"><span><i class="feather icon-maximize"></i>
                            maximize</span><span style="display:none"><i class="feather icon-minimize"></i>
                            Restore</span></a></li>
                        <li class="dropdown-item minimize-card"><a href="#!"><span><i class="feather icon-minus"></i> collapse</span><span style="display:none"><i class="feather icon-plus"></i>expand</span></a></li>
                        <li class="dropdown-item reload-card"><a href="#!"><i class="feather icon-refresh-cw"></i> reload</a></li>
                        <li class="dropdown-item close-card"><a href="#!"><i class="feather icon-trash"></i> remove</a></li>
                    </ul>
                </div>
            </div>
        </div>
        
        <div class="card-body p-0">
            <div class="table-responsive">
                <div class="activity-scroll ps ps--active-y" style="height:410px;position:relative;">
                    <table class="table table-hover m-0">
                        <thead>
                            <tr>
                                <th class="text-center">Image</th>
                                <th class="text-center">Usuario</th>
                                <th class="text-center">Total Registros</th>
                                <th class="text-center">Compraron</th>
                                <th class="text-center">Conversión</th>
                                <th class="text-center">Promedio Tiempo</th>
                                <th class="text-end"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($registros_users->count() > 0)
                                @foreach($registros_users as $item)
                                    @php
                                        $minutos = round($item->promedio_minutos ?? 0);
                                        $horas = intdiv($minutos, 60);
                                        $mins  = $minutos % 60;
                                    @endphp
                                    
                                    @php
                                        $conversion = round($item->conversion, 2);
                                    @endphp
                                    
                                    <tr>
                                        <td class="text-center">
                                            <img class="rounded-circle" style="width:40px;" src="assets/images/user/avatar-1.jpg" alt="activity-user">
                                        </td>
                                        <td class="text-center">{{ $item->usuario->name ?? 'Sin usuario' }}</td>
                                        <td class="text-center">{{ $item->total }}</td>
                                        <td class="text-center">{{ $item->compraron }}</td>
                                        <td class="text-center">
                                            @if($conversion >= 70)
                                                <label class="badge badge-light-success">
                                                    {{ $conversion }} %
                                                </label>
                                            @elseif($conversion >= 50 && $conversion <= 69)
                                                <label class="badge badge-light-warning">
                                                    {{ $conversion }} %
                                                </label>
                                            @else
                                                <label class="badge badge-light-danger">
                                                    {{ $conversion }} %
                                                </label>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            {{ $horas }} {{ $horas == 1 ? 'hora' : 'horas' }}
                                            {{ $mins }} {{ $mins == 1 ? 'minuto' : 'minutos' }}
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="7" class="text-center text-muted">
                                        <i class="feather icon-users"></i>
                                        No se encontraron registros con los filtros aplicados
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Sección de registros por mes y otras métricas -->
<div class="row mt-4">
    <div class="col-xl-4 col-md-4">
        <div class="card">
            <div class="card-header">
                <h5>Registros por Mes</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Año</th>
                                <th>Mes</th>
                                <th class="text-center">Total registros</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($registros->count() > 0)
                                @foreach($registros as $item)
                                    <tr>
                                        <td>{{ $item->anio }}</td>
                                        <td>
                                            {{ \Carbon\Carbon::create()->month($item->mes)->translatedFormat('F') }}
                                        </td>
                                        <td class="text-center">{{ $item->total }}</td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="3" class="text-center text-muted">
                                        No hay datos para mostrar
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-4 col-md-12">
        <div class="card proj-t-card">
            <div class="card-body">
                <div class="row align-items-center m-b-30">
                    <div class="col-auto">
                        <i class="far fa-calendar-check text-c-red f-30"></i>
                    </div>
                    <div class="col p-l-0">
                        <h6 class="m-b-5">Resumen del Filtro</h6>
                        <h6 class="m-b-0 text-c-red">{{ $total }} registros encontrados</h6>
                    </div>
                </div>
                <div class="row align-items-center text-center">
                    <div class="col">
                        <h6 class="m-b-0">{{ $compraron }}</h6>
                        <small>Compraron</small>
                    </div>
                    <div class="col"><i class="fas fa-exchange-alt text-c-red f-18"></i></div>
                    <div class="col">
                        <h6 class="m-b-0">{{ $noCompraron }}</h6>
                        <small>No compraron</small>
                    </div>
                </div>
                <h6 class="pt-badge bg-c-red">{{ round($stats->conversion, 2) }}% conversión</h6>
            </div>
        </div>
    </div>

    <div class="col-xl-4 col-md-12">
        <div class="card">
            <div class="card-header">
                <h5>Visitas por Horario</h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-md-4">
                        <div class="card border-0">
                            <div class="card-body">
                                <h5>🌅 Mañana</h5>
                                <h2 class="text-c-blue">{{ $visitas->manana ?? 0 }}</h2>
                                <small>6:00 - 11:59</small>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card border-0">
                            <div class="card-body">
                                <h5>🌞 Tarde</h5>
                                <h2 class="text-c-green">{{ $visitas->tarde ?? 0 }}</h2>
                                <small>12:00 - 17:59</small>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card border-0">
                            <div class="card-body">
                                <h5>🌙 Noche</h5>
                                <h2 class="text-c-purple">{{ $visitas->noche ?? 0 }}</h2>
                                <small>18:00 - 5:59</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Otras secciones de tu vista original -->
<div class="col-xl-4 col-md-12 mt-4">
    <div class="card proj-t-card">
        <div class="card-body">
            <div class="row align-items-center m-b-30">
                <div class="col-auto">
                    <i class="far fa-calendar-check text-c-red f-30"></i>
                </div>
                <div class="col p-l-0">
                    <h6 class="m-b-5">Ticket Answered</h6>
                    <h6 class="m-b-0 text-c-red">Live Update</h6>
                </div>
            </div>
            <div class="row align-items-center text-center">
                <div class="col">
                    <h6 class="m-b-0">327</h6>
                </div>
                <div class="col"><i class="fas fa-exchange-alt text-c-red f-18"></i></div>
                <div class="col">
                    <h6 class="m-b-0">10 Days</h6>
                </div>
            </div>
            <h6 class="pt-badge bg-c-red">53%</h6>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Botones de rangos rápidos
    document.querySelectorAll('.btn-rango').forEach(button => {
        button.addEventListener('click', function() {
            const dias = this.getAttribute('data-dias');
            const hoy = new Date();
            const fechaInicio = new Date();
            
            fechaInicio.setDate(hoy.getDate() - dias);
            
            // Formatear fechas para input type="date"
            const formatDate = (date) => {
                return date.toISOString().split('T')[0];
            };
            
            document.getElementById('fecha_desde').value = dias > 0 ? formatDate(fechaInicio) : formatDate(hoy);
            document.getElementById('fecha_hasta').value = formatDate(hoy);
            
            // Enviar el formulario automáticamente
            document.querySelector('form').submit();
        });
    });
    
    // Validar que fecha desde sea menor o igual que fecha hasta
    const fechaDesde = document.getElementById('fecha_desde');
    const fechaHasta = document.getElementById('fecha_hasta');
    
    if (fechaDesde && fechaHasta) {
        fechaDesde.addEventListener('change', function() {
            fechaHasta.min = this.value;
        });
        
        fechaHasta.addEventListener('change', function() {
            fechaDesde.max = this.value;
        });
    }
    
    // Opcional: Configurar fechas máximas/minimas iniciales
    if (fechaDesde && fechaDesde.value) {
        fechaHasta.min = fechaDesde.value;
    }
    
    if (fechaHasta && fechaHasta.value) {
        fechaDesde.max = fechaHasta.value;
    }
});
</script>
@endpush

@stop