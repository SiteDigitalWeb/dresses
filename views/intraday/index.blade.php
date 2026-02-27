@extends ('LayoutDresses.layout')


 @section('ContenidoSite-01')
@if(auth()->user()->compania == 1)
<div class="col-md-10 offset-md-1">
  <div class="container">
   <?php $status=Session::get('status'); ?>
   @if($status=='ok_create')
    <div class="alert alert-success">
     <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
     <strong>Usuario registrado con éxito</strong> CMS...
    </div>
   @endif

   @if($status=='ok_delete')
    <div class="alert alert-danger">
     <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
     <strong>Usuario eliminado con éxito</strong> CMS...
    </div>
   @endif

   @if($status=='ok_update')
    <div class="alert alert-warning">
     <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
     <strong>Usuario actualizado con éxito</strong> CMS...
    </div>
   @endif
  </div>
</div>


  <div class="content-header"> 
   <ul class="nav-horizontal text-center"> <a class="btn btn-primary waves-effect waves-light" href="/dresses/crear-usuario"><i class="fa fa-user-plus"></i> Create User</a>
   </ul>
  </div>

<div class="container">
 <div class="col-md-10 offset-md-1">
                           
  <div class="card">
   <div class="card-body">
    
    <h4 class="mt-0 header-title mb-4"><b>Registered Users</b></h4>
                                           
    <table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">

     <thead>
      <tr>
       <th class="text-center"><b>ID</b></th>
       <th class="text-center"><b>NAME</b></th>
       <th class="text-center"><b>EVENT DATE</b></th>
       <th class="text-center"><b>CIUDAD</b></th>
       <th class="text-center"><b>TIME IN</b></th>
       <th class="text-center"><b>TIME OUT</b></th>
       <th class="text-center"><b>TIME</b></th>
       <th class="text-center"><b>ACTIONS</b></th>
      </tr>
     </thead>
     
     <tbody>
      @foreach($intraday as $intraday)
      <tr>
       <td class="text-center">{{$intraday->id}}</td>
       <td class="text-center">{{$intraday->nombre}}</td>
       <td>{{$intraday->fecha_evento}}</td>
       <td>{{$intraday->ciudad}}</td>
       <td>{{$intraday->time_in}}</td>
       <td class="text-center">
    @if($intraday->time_out)
        {{ $intraday->time_out }}
    @else
        <input 
            type="time"
            class="form-control form-control-sm time-out-input"
            data-id="{{ $intraday->id }}"
            style="width:120px;margin:auto;"
        >
    @endif
</td>
       <td><span class="label label-success">{{ $intraday->duracion_humana }}
       </span></td>
       <td class="text-center">
       <div class="btn-group">
        <a href="<?=URL::to('dresses/editar/');?>/" style="padding: 1px;"><span  id="tip" data-toggle="tooltip" data-placement="top" title="Edit User" class="btn drp-icon btn-rounded btn-warning"><span class="fas fa-edit"></span></a>
        <!--
        <a href="<?=URL::to('dresses/eliminar/');?>/" style="padding: 1px; onclick="return confirm('¿Está seguro que desea eliminar el registro?')"><button class="btn drp-icon btn-rounded btn-danger" data-toggle="tooltip" data-placement="right" title="Delete User"><span class="fas fa-trash-alt"></span></button></a>
        -->
       </div>
       </td>
      </tr>
      @endforeach
     </tbody>
    
    </table>
            
   </div>
  </div>
 </div> <!-- end col -->
</div> <!-- end row -->

<script>
document.addEventListener('DOMContentLoaded', function () {

    document.querySelectorAll('.time-out-input').forEach(input => {
        input.addEventListener('change', function () {

            const intradayId = this.dataset.id;
            const timeOut = this.value;

            fetch(`/dresses-intraday/${intradayId}/time-out`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    time_out: timeOut
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    this.outerHTML = `<span class="badge bg-success">${timeOut}</span>`;
                }
            })
            .catch(() => {
                alert('Error guardando el Time Out');
            });

        });
    });

});
</script>
@else
<h2>NO TIENES PERMISOS SUFICIENTES PARA ESTA SECCIÓN</h2>
@endif
@stop