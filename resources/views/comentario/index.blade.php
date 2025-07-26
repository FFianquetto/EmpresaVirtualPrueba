@extends('layouts.app')

@section('template_title')
    Mis Conversaciones
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span id="card_title">
                                {{ __('Mis Conversaciones') }}
                            </span>
                        </div>
                    </div>
                    @if(session('success'))
                        <div class="alert alert-success m-4">
                            <i class="fa fa-check-circle"></i> {{ session('success') }}
                        </div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-danger m-4">
                            <i class="fa fa-exclamation-circle"></i> {{ session('error') }}
                        </div>
                    @endif
                    <div class="card-body bg-white p-0">
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
                            ->sortByDesc('created_at');
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
                                                <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                                    <i class="fa fa-user text-white"></i>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1">
                                                <div class="d-flex justify-content-between align-items-start">
                                                    <div>
                                                        <h6 class="mb-1 fw-bold">{{ $otroUsuario->nombre }}</h6>
                                                        <p class="mb-1 text-muted small">
                                                            @if(!empty($ultimoMensaje->mensaje))
                                                                @if($ultimoMensaje->registro_id_emisor == $usuarioId)
                                                                    - {{ Str::limit($ultimoMensaje->mensaje, 50) }}
                                                                @else
                                                                    {{ Str::limit($ultimoMensaje->mensaje, 50) }}
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
                                                                    <span class="badge bg-info me-1" style="font-size: 0.6rem;">
                                                                        <i class="fa fa-image"></i>
                                                                    </span>
                                                                @endif
                                                                @if($ultimoMensaje->audio)
                                                                    <span class="badge bg-warning me-1" style="font-size: 0.6rem;">
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
                                               class="btn btn-outline-primary btn-sm w-100">
                                                <i class="fa fa-comments"></i> Abrir Chat
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-5">
                                <div class="mb-3">
                                    <i class="fa fa-comments fa-3x text-muted"></i>
                                </div>
                                <h5 class="text-muted">No tienes conversaciones</h5>
                                <p class="text-muted">Inicia una nueva conversación para comenzar a chatear.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
