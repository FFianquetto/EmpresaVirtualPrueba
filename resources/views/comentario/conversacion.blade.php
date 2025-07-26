@extends('layouts.app')

@section('template_title')
    Conversación con {{ $otroUsuarioData->nombre }}
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span id="card_title">
                                <i class="fa fa-comments"></i> Conversación con {{ $otroUsuarioData->nombre }}
                            </span>

                            <div class="float-right">
                                <a href="{{ route('comentarios.index') }}" class="btn btn-secondary btn-sm">
                                    <i class="fa fa-arrow-left"></i> Volver
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card-body bg-white p-0">
                        @if(session('success'))
                            <div class="alert alert-success m-3">
                                <i class="fa fa-check-circle"></i> {{ session('success') }}
                            </div>
                        @endif
                        
                        @if($conversacion->count() > 0)
                            <div class="chat-container" id="chatContainer" style="height: 400px; overflow-y: auto; padding: 20px;">
                                @foreach($conversacion as $mensaje)
                                    @php
                                        $esMio = $mensaje->registro_id_emisor == $usuarioActual;
                                    @endphp
                                    
                                    <div class="mb-3 {{ $esMio ? 'text-end' : 'text-start' }}">
                                        <div class="d-inline-block {{ $esMio ? 'bg-primary text-white' : 'bg-light' }} p-3 rounded" style="max-width: 70%;">
                                            <div class="small mb-1 {{ $esMio ? 'text-white-50' : 'text-muted' }}">
                                                <strong>{{ $mensaje->emisor->nombre }}</strong>
                                                <span class="ms-2">{{ $mensaje->created_at->format('d/m/Y H:i') }}</span>
                                            </div>
                                            <div class="mb-2">
                                                @if(!empty($mensaje->mensaje))
                                                    {{ $mensaje->mensaje }}
                                                @endif
                                            </div>
                                            
                                            @if($mensaje->imagen)
                                                <div class="mb-2">
                                                    <img src="{{ $mensaje->imagen_url }}" alt="Imagen adjunta" 
                                                         class="img-fluid rounded" style="max-width: 200px; max-height: 200px;">
                                                </div>
                                            @endif
                                            
                                            @if($mensaje->audio)
                                                <div class="mb-2">
                                                    <audio controls style="max-width: 100%;">
                                                        <source src="{{ $mensaje->audio_url }}" type="audio/mpeg">
                                                        <source src="{{ $mensaje->audio_url }}" type="audio/wav">
                                                        <source src="{{ $mensaje->audio_url }}" type="audio/ogg">
                                                        Tu navegador no soporta el elemento de audio.
                                                    </audio>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="alert alert-info text-center m-3">
                                <i class="fa fa-info-circle"></i> No hay mensajes en esta conversación.
                                <br>
                                <small>Escribe un mensaje abajo para comenzar la conversación.</small>
                            </div>
                        @endif
                        
                        <div class="chat-input-container" style="border-top: 1px solid #dee2e6; padding: 20px; background-color: #f8f9fa;">
                            <form action="{{ route('comentarios.store') }}" method="POST" enctype="multipart/form-data" id="chatForm">
                                @csrf
                                <input type="hidden" name="emisor_id" value="{{ $usuarioActual }}">
                                <input type="hidden" name="receptor_id" value="{{ $otroUsuarioData->id }}">
                                
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="form-group mb-2">
                                            <textarea name="mensaje" class="form-control @error('mensaje') is-invalid @enderror" 
                                                      id="mensaje" rows="2" placeholder="Mensaje"></textarea>
                                            {!! $errors->first('mensaje', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="d-flex gap-2">
                                            <div class="form-group mb-2 flex-grow-1">
                                                <label for="imagen" class="btn btn-outline-secondary btn-sm w-100 mb-1" style="cursor: pointer;">
                                                    <i class="fa fa-image"></i> Imagen
                                                </label>
                                                <input type="file" name="imagen" class="form-control @error('imagen') is-invalid @enderror" 
                                                       id="imagen" accept="image/*" style="display: none;">
                                                {!! $errors->first('imagen', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                                            </div>
                                            
                                            <div class="form-group mb-2 flex-grow-1">
                                                <label for="audio" class="btn btn-outline-secondary btn-sm w-100 mb-1" style="cursor: pointer;">
                                                    <i class="fa fa-music"></i> Audio
                                                </label>
                                                <input type="file" name="audio" class="form-control @error('audio') is-invalid @enderror" 
                                                       id="audio" accept="audio/*" style="display: none;">
                                                {!! $errors->first('audio', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                                            </div>
                                            
                                            <button type="submit" class="btn btn-primary btn-sm">
                                                <i class="fa fa-paper-plane"></i> Enviar
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                
                                <div id="selectedFiles" class="mt-2" style="display: none;">
                                    <small class="text-muted">
                                        <i class="fa fa-paperclip"></i> Archivos seleccionados: <span id="fileNames"></span>
                                    </small>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function scrollToBottom() {
            const container = document.getElementById('chatContainer');
            if (container) {
                container.scrollTop = container.scrollHeight;
            }
        }
        
        window.onload = function() {
            scrollToBottom();
        };
        
        document.getElementById('imagen').addEventListener('change', function() {
            updateSelectedFiles();
        });
        
        document.getElementById('audio').addEventListener('change', function() {
            updateSelectedFiles();
        });
        
        function updateSelectedFiles() {
            const imagenFile = document.getElementById('imagen').files[0];
            const audioFile = document.getElementById('audio').files[0];
            const selectedFilesDiv = document.getElementById('selectedFiles');
            const fileNamesSpan = document.getElementById('fileNames');
            
            let files = [];
            if (imagenFile) files.push(imagenFile.name);
            if (audioFile) files.push(audioFile.name);
            
            if (files.length > 0) {
                fileNamesSpan.textContent = files.join(', ');
                selectedFilesDiv.style.display = 'block';
            } else {
                selectedFilesDiv.style.display = 'none';
            }
        }
        
        document.getElementById('chatForm').addEventListener('submit', function() {
            setTimeout(scrollToBottom, 1000);
        });
    </script>
@endsection 