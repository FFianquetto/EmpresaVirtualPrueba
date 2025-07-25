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
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <span class="card-title">{{ __('Mensajes Recibidos') }} ({{ $mensajesRecibidos->count() }})</span>
                </div>
                <div class="card-body">
                    @if($mensajesRecibidos->count() > 0)
                        <div class="list-group">
                            @foreach($mensajesRecibidos as $mensaje)
                                <div class="list-group-item">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">{{ $mensaje->emisor->nombre }}</h6>
                                        <small>{{ $mensaje->created_at->format('d/m/Y H:i') }}</small>
                                    </div>
                                    <p class="mb-1">{{ $mensaje->mensaje }}</p>
                                    @if($mensaje->link)
                                        <small><a href="{{ $mensaje->link }}" target="_blank">Link adjunto</a></small>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted">No tienes mensajes recibidos.</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <span class="card-title">{{ __('Mensajes Enviados') }} ({{ $mensajesEnviados->count() }})</span>
                </div>
                <div class="card-body">
                    @if($mensajesEnviados->count() > 0)
                        <div class="list-group">
                            @foreach($mensajesEnviados as $mensaje)
                                <div class="list-group-item">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">Para: {{ $mensaje->receptor->nombre }}</h6>
                                        <small>{{ $mensaje->created_at->format('d/m/Y H:i') }}</small>
                                    </div>
                                    <p class="mb-1">{{ $mensaje->mensaje }}</p>
                                    @if($mensaje->link)
                                        <small><a href="{{ $mensaje->link }}" target="_blank">Link adjunto</a></small>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted">No has enviado mensajes.</p>
                    @endif
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
                        <a href="{{ route('publicaciones.create') }}" class="btn btn-success btn-sm">
                            <i class="fa fa-plus"></i> {{ __('Nueva Publicación') }}
                        </a>
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
                            <p class="text-muted mb-3">No tienes publicaciones aún.</p>
                            <a href="{{ route('publicaciones.create') }}" class="btn btn-primary">
                                <i class="fa fa-plus"></i> {{ __('Crear tu primera publicación') }}
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 