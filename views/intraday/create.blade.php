@extends ('LayoutDresses.layout')


 @section('ContenidoSite-01')
@if(auth()->user()->compania == 1)
<div class="container">
    <h3 class="mb-4">Agregar Registro – Dresses Intraday</h3>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('dresses-intraday.store') }}">
        @csrf

        <div class="row">
            <div class="col-md-4 mb-3">
                <label>Fecha</label>
                <input type="date" name="fecha" class="form-control">
            </div>

            <div class="col-md-4 mb-3">
                <label>Nombre *</label>
                <input type="text" name="nombre" class="form-control" required>
            </div>

            <div class="col-md-4 mb-3">
                <label>Apellido *</label>
                <input type="text" name="apellido" class="form-control" required>
            </div>

            <div class="col-md-4 mb-3">
                <label>Fecha Evento</label>
                <input type="date" name="fecha_evento" class="form-control">
            </div>

            <div class="col-md-4 mb-3">
                <label>Ciudad</label>
                <input type="text" name="ciudad" class="form-control">
            </div>

            <div class="col-md-4 mb-3">
                <label>Teléfono</label>
                <input type="text" name="telefono" class="form-control">
            </div>

            <div class="col-md-4 mb-3">
                <label>Referido</label>
                <input type="text" name="referido" class="form-control">
            </div>

            <div class="col-md-4 mb-3">
                <label>Time In</label>
                <input type="time" name="time_in" class="form-control">
            </div>
            <!--
            <div class="col-md-4 mb-3">
                <label>Time Out</label>
                <input type="time" name="time_out" class="form-control">
            </div>
            -->
            <div class="col-md-4 mb-3">
                <label>Usuario</label>
               <select name="usuario" class="form-control">
                    @foreach($usuario as $usuario)
                    <option value="{{$usuario->id}}">{{$usuario->name}}</option>
                    @endforeach
                    
                </select>
            </div>

            <div class="col-md-4 mb-3">
                <label>Follow</label>
                <select name="follow" class="form-control">
                    <option value="0">No</option>
                    <option value="1">Sí</option>
                </select>
            </div>

             <div class="col-md-4 mb-3">
                <label>Cita</label>
                <select name="cita" class="form-control">
                    <option value="0">No</option>
                    <option value="1">Sí</option>
                </select>
            </div>
            <div class="col-md-4 mb-3">
                <label>Compró</label>
                <select name="compro" class="form-control">
                    <option value="0">No</option>
                    <option value="1">Sí</option>
                </select>
            </div>

            <div class="col-md-12 mb-3">
                <label>Comentarios</label>
                <textarea name="comentarios" class="form-control" rows="3"></textarea>
            </div>
        </div>

        <button class="btn btn-primary">Guardar</button>
    </form>
</div>

@else
<h2>NO TIENES PERMISOS SUFICIENTES PARA ESTA SECCIÓN</h2>
@endif
@stop