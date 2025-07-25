<div class="row padding-1 p-1">
    <div class="col-md-12">
        
        <div class="form-group mb-2 mb20">
            <label for="titulo" class="form-label">Nombre del Servicio</label>
            <input type="text" name="titulo" class="form-control @error('titulo') is-invalid @enderror" value="{{ old('titulo', $publicacione?->titulo) }}" id="titulo" placeholder="">
            {!! $errors->first('titulo', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        
        <div class="form-group mb-2 mb20">
            <label for="contenido" class="form-label">Descripción del Servicio</label>
            <textarea name="contenido" class="form-control @error('contenido') is-invalid @enderror" id="contenido" rows="5" placeholder="Describe tu servicioio">{{ old('contenido', $publicacione?->contenido) }}</textarea>
            {!! $errors->first('contenido', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

        <div class="form-group mb-2 mb20">
            <label for="imagen" class="form-label">Imagen </label>
            <input type="file" name="imagen" class="form-control @error('imagen') is-invalid @enderror" id="imagen" accept="image/*">
            <small class="form-text text-muted">Imagen: JPEG, PNG, JPG - Max 2MB</small>
            {!! $errors->first('imagen', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
            
            @if(isset($publicacione) && $publicacione->imagen)
                <div class="mt-2">
                    <img src="{{ $publicacione->imagen_url }}" alt="Imagen actual" class="img-thumbnail" style="max-width: 200px;">
                    <small class="d-block text-muted">Imagen actual</small>
                </div>
            @endif
        </div>

        <div class="form-group mb-2 mb20">
            <label for="audio" class="form-label">Audio</label>
            <input type="file" name="audio" class="form-control @error('audio') is-invalid @enderror" id="audio" accept="audio/*">
            <small class="form-text text-muted">Audio: MP3, WAV, OGG - Max 10MB</small>
            {!! $errors->first('audio', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
            
            @if(isset($publicacione) && $publicacione->audio)
                <div class="mt-2">
                    <audio controls style="max-width: 100%;">
                        <source src="{{ $publicacione->audio_url }}" type="audio/mpeg">
                        El navegador no soporta audio.
                    </audio>
                    <small class="d-block text-muted">Audio actual</small>
                </div>
            @endif
        </div>

        @if(isset($registroId))
            <input type="hidden" name="registro_id" value="{{ $registroId }}">
        @endif

    </div>
    
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">Confirmar</button>
    </div>
</div>