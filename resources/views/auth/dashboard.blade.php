@extends('layouts.app')

@section('template_title')
    {{ __('Dashboard') }} - {{ $usuario->nombre }}
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <span id="card_title">
                            {{ __('Dashboard de') }} {{ $usuario->nombre }}
                        </span>
                        <div class="float-right">
                            <form action="{{ route('auth.logout') }}" method="POST" style="display: inline;">
                                @csrf
                                <button type="submit" class="btn btn-danger btn-sm">
                                    <i class="fa fa-sign-out"></i> {{ __('Cerrar Sesión') }}
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <span class="card-title">{{ __(' Chats recientes') }}</span>
                    </div>
                </div>
                <div class="card-body p-0">
                    @php
                        $usuarioId = session('registro_id');
                        
                        $conversaciones = \App\Models\Comentario::where(function($query) use ($usuarioId) {
                            $query->where('registro_id_emisor', $usuarioId)
                                  ->orWhere('registro_id_receptor', $usuarioId);
                        })
                        ->with(['emisor', 'receptor'])
                        ->orderBy('created_at', 'desc')
                        ->get()
                        ->groupBy(function($mensaje) use ($usuarioId) {
                            if ($mensaje->registro_id_emisor == $usuarioId) {
                                return $mensaje->registro_id_receptor;
                            } else {
                                return $mensaje->registro_id_emisor;
                            }
                        })
                        ->map(function($mensajes) {
                            return $mensajes->first();
                        })
                        ->sortByDesc('created_at')
                        ->take(3);
                    @endphp

                    @if($conversaciones->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($conversaciones as $ultimoMensaje)
                                @php
                                    $otroUsuario = $ultimoMensaje->registro_id_emisor == $usuarioId 
                                        ? $ultimoMensaje->receptor 
                                        : $ultimoMensaje->emisor;
                                @endphp
                                
                                <div class="list-group-item list-group-item-action p-3" style="border: none; border-bottom: 1px solid #dee2e6;">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0 me-3">
                                            <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                <i class="fa fa-user text-white" style="font-size: 0.9rem;"></i>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="d-flex justify-content-between align-items-start">
                                                <div>
                                                    <h6 class="mb-1 fw-bold" style="font-size: 0.9rem;">{{ $otroUsuario->nombre }}</h6>
                                                    <p class="mb-1 text-muted small">
                                                        @if(!empty($ultimoMensaje->mensaje))
                                                            @if($ultimoMensaje->registro_id_emisor == $usuarioId)
                                                                - {{ Str::limit($ultimoMensaje->mensaje, 30) }}
                                                            @else
                                                                {{ Str::limit($ultimoMensaje->mensaje, 30) }}
                                                            @endif
                                                        @else
                                                            <em class="text-muted">
                                                                @if($ultimoMensaje->imagen && $ultimoMensaje->audio)
                                                                    <i class="fa fa-image me-1"></i><i class="fa fa-music me-1"></i>Imagen y audio
                                                                @elseif($ultimoMensaje->imagen)
                                                                    <i class="fa fa-image me-1"></i>Imagen
                                                                @elseif($ultimoMensaje->audio)
                                                                    <i class="fa fa-music me-1"></i>Audio
                                                                @endif
                                                            </em>
                                                        @endif
                                                    </p>
                                                </div>
                                                <div class="text-end">
                                                    <small class="text-muted d-block">
                                                        {{ $ultimoMensaje->created_at->diffForHumans() }}
                                                    </small>
                                                    @if($ultimoMensaje->imagen || $ultimoMensaje->audio)
                                                        <div class="mt-1">
                                                            @if($ultimoMensaje->imagen)
                                                                <span class="badge bg-info me-1" style="font-size: 0.5rem;">
                                                                    <i class="fa fa-image"></i>
                                                                </span>
                                                            @endif
                                                            @if($ultimoMensaje->audio)
                                                                <span class="badge bg-warning me-1" style="font-size: 0.5rem;">
                                                                    <i class="fa fa-music"></i>
                                                                </span>
                                                            @endif
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mt-2">
                                        <a href="{{ route('comentarios.conversacion', [$usuarioId, $otroUsuario->id]) }}" 
                                           class="btn btn-outline-primary btn-sm w-100" style="font-size: 0.8rem;">
                                            <i class="fa fa-comments"></i> Abrir Chat
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                    @else
                        <div class="text-center py-4">
                            <div class="mb-2">
                                <i class="fa fa-comments fa-2x text-muted"></i>
                            </div>
                            <p class="text-muted mb-2" style="font-size: 0.9rem;">No tienes conversaciones</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <span class="card-title">{{ __('Acciones Rápidas') }}</span>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('comentarios.index') }}" class="btn btn-outline-primary">
                            <i class="fa fa-comments"></i> Ver todos los Chats
                        </a>
                        <a href="{{ route('publicaciones.create') }}" class="btn btn-success">
                            <i class="fa fa-plus"></i> Nueva Publicación
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <span class="card-title">{{ __('Mis Publicaciones') }} ({{ $publicacionesUsuario->count() }})</span>
                       
                    </div>
                </div>
                <div class="card-body">
                    @if($publicacionesUsuario->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="thead">
                                    <tr>
                                        <th>Servicio</th>
                                        <th>Descripción</th>
                                        <th>Fecha</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($publicacionesUsuario as $publicacion)
                                        <tr>
                                            <td>{{ $publicacion->titulo }}</td>
                                            <td>{{ Str::limit($publicacion->contenido, 50) }}</td>
                                            <td>{{ $publicacion->created_at->format('d/m/Y') }}</td>
                                            <td>
                                                <a class="btn btn-sm btn-primary" href="{{ route('publicaciones.show', $publicacion->id) }}">
                                                    <i class="fa fa-fw fa-eye"></i> Ver
                                                </a>
                                                <a class="btn btn-sm btn-success" href="{{ route('publicaciones.edit', $publicacion->id) }}">
                                                    <i class="fa fa-fw fa-edit"></i> Editar
                                                </a>
                                                <form action="{{ route('publicaciones.destroy', $publicacion->id) }}" method="POST" style="display: inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm" onclick="event.preventDefault(); confirm('¿Estás seguro de eliminar esta publicación?') ? this.closest('form').submit() : false;">
                                                        <i class="fa fa-fw fa-trash"></i> Eliminar
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <p class="text-muted mb-3">Sin publicaciones</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 