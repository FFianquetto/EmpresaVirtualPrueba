@extends('layouts.app')

@section('template_title')
    Ver Mensaje
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-default">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span class="card-title">Detalles del Mensaje</span>
                            <div class="float-right">
                                <a class="btn btn-primary btn-sm" href="{{ route('comentarios.index') }}">
                                    <i class="fa fa-arrow-left"></i> Volver
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body bg-white">
                        
                        <div class="form-group mb-2 mb20">
                            <strong>De:</strong>
                            {{ $comentario->emisor->nombre ?? 'Sin emisor' }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Para:</strong>
                            {{ $comentario->receptor->nombre ?? 'Sin receptor' }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Mensaje:</strong>
                            <div class="alert alert-light border">
                                @if(!empty($comentario->mensaje))
                                    {{ $comentario->mensaje }}
                                @else
                                    <em class="text-muted">
                                        @if($comentario->imagen && $comentario->audio)
                                            Solo imagen y audio
                                        @elseif($comentario->imagen)
                                            Solo imagen
                                        @elseif($comentario->audio)
                                            Solo audio
                                        @endif
                                    </em>
                                @endif
                            </div>
                        </div>
                        
                        @if($comentario->imagen)
                            <div class="form-group mb-2 mb20">
                                <strong>Imagen adjunta:</strong>
                                <div class="mt-2">
                                    <img src="{{ $comentario->imagen_url }}" alt="Imagen adjunta" 
                                         class="img-fluid rounded" style="max-width: 300px; max-height: 300px;">
                                </div>
                            </div>
                        @endif
                        
                        @if($comentario->audio)
                            <div class="form-group mb-2 mb20">
                                <strong>Audio adjunto:</strong>
                                <div class="mt-2">
                                    <audio controls style="max-width: 100%;">
                                        <source src="{{ $comentario->audio_url }}" type="audio/mpeg">
                                        <source src="{{ $comentario->audio_url }}" type="audio/wav">
                                        <source src="{{ $comentario->audio_url }}" type="audio/ogg">
                                        Tu navegador no soporta el elemento de audio.
                                    </audio>
                                </div>
                            </div>
                        @endif
                        
                        <div class="form-group mb-2 mb20">
                            <strong>Fecha de envío:</strong>
                            {{ $comentario->created_at->format('d/m/Y H:i') }}
                        </div>

                        @php
                            $usuarioActual = session('registro_id');
                            $esReceptor = $usuarioActual == $comentario->registro_id_receptor;
                            $esEmisor = $usuarioActual == $comentario->registro_id_emisor;
                        @endphp

                        <div class="form-group mb-2 mb20">
                            @if($esReceptor)
                                <a href="{{ route('comentarios.create', ['emisor_id' => session('registro_id'), 'receptor_id' => $comentario->emisor->id]) }}" class="btn btn-primary">
                                    <i class="fa fa-reply"></i> Contestar a {{ $comentario->emisor->nombre }}
                                </a>
                            @endif
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
