@extends('layouts.app')

@section('template_title')
    Servicios Publicados
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span id="card_title">
                                {{ __('Servicios Disponibles') }}
                            </span>

                            <div class="float-right">
                                @if(session('usuario_logueado'))
                                    <a href="{{ route('publicaciones.create') }}" class="btn btn-primary btn-sm float-right" data-placement="left">
                                      {{ __('Publicar Servicio') }}
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>

                    @if(session('error'))
                        <div class="alert alert-danger m-4">
                            <i class="fa fa-exclamation-circle"></i> {{ session('error') }}
                        </div>
                    @endif

                    @if(session('success'))
                        <div class="alert alert-success m-4">
                            <i class="fa fa-check-circle"></i> {{ session('success') }}
                        </div>
                    @endif

                    @if ($usuarioRegistrado)
                        <div class="alert alert-info m-4">
                            <p><strong>¡Bienvenido {{ $usuarioRegistrado }}!</strong> Publica aquí!</p>
                        </div>
                    @endif

                    <div class="card-body bg-white">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="thead">
                                    <tr>
                                        <th>Imagen</th>
                                        <th>Servicio</th>
                                        <th>Descripción</th>
                                        <th>Publicado por</th>
                                        <th>Contactar</th>
                                        <th>Fecha</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($publicaciones as $publicacione)
                                        @php
                                            $esPropiaPublicacion = $registroId && $publicacione->registro_id == $registroId;
                                        @endphp
                                        
                                        <tr>
                                            <td>
                                                @if($publicacione->imagen)
                                                    <img src="{{ $publicacione->imagen_url }}" alt="Imagen del servicio" class="img-thumbnail" style="max-width: 60px; max-height: 60px;">
                                                @else
                                                    <div class="bg-light text-muted d-flex align-items-center justify-content-center" style="width: 60px; height: 60px; font-size: 12px;">
                                                        Sin imagen
                                                    </div>
                                                @endif
                                                @if($publicacione->audio)
                                                    <div class="mt-1">
                                                        <i class="fa fa-volume-up text-info" title="Tiene audio"></i>
                                                    </div>
                                                @endif
                                            </td>
                                            <td>{{ $publicacione->titulo }}</td>
                                            
                                            <td>{{ Str::limit($publicacione->contenido, 50) }}</td>
                                            
                                            <td>{{ $publicacione->autor->nombre ?? 'Sin autor' }}</td>
                                            <td>
                                                @if($publicacione->autor && !$esPropiaPublicacion)
                                                    @if(session('usuario_logueado'))
                                                        <a href="{{ route('comentarios.create', ['emisor_id' => session('registro_id'), 'receptor_id' => $publicacione->autor->id, 'publicacion_id' => $publicacione->id]) }}" class="btn btn-sm btn-info">
                                                            <i class="fa fa-envelope"></i> Contactar
                                                        </a>
                                                    @else
                                                        <span class="text-muted">Logueate para contactar...</span>
                                                    @endif
                                                @elseif($esPropiaPublicacion)
                                                    <span class="text-muted">¡Tú eres el autor amigo!</span>
                                                @else
                                                    <span class="text-muted">No disponible</span>
                                                @endif
                                            </td>
                                            <td>{{ $publicacione->created_at->format('d/m/Y') }}</td>
                                            
                                            <td>
                                                <a class="btn btn-sm btn-primary" href="{{ route('publicaciones.show',$publicacione->id) }}"><i class="fa fa-fw fa-eye"></i> Ver</a>
                                                @if($esPropiaPublicacion)
                                                    <form action="{{ route('publicaciones.destroy',$publicacione->id) }}" method="POST" style="display: inline;">
                                                        <a class="btn btn-sm btn-success" href="{{ route('publicaciones.edit',$publicacione->id) }}"><i class="fa fa-fw fa-edit"></i> Editar</a>
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm" onclick="event.preventDefault(); confirm('¿Estás seguro de eliminar esta publicación?') ? this.closest('form').submit() : false;">
                                                            <i class="fa fa-fw fa-trash"></i> Eliminar
                                                        </button>
                                                    </form>
                                                @endif
                                            </td>
                                        </tr>


                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                
                {!! $publicaciones->withQueryString()->links() !!}
            </div>
        </div>
    </div>
@endsection
