<div class="row padding-1 p-1">
    <div class="col-md-12">
        
        @if(!isset($emisor) || !isset($receptor))
            <div class="form-group mb-2 mb20">
                <label for="registro_id_emisor" class="form-label">Remitente</label>
                <select name="registro_id_emisor" class="form-control @error('registro_id_emisor') is-invalid @enderror" id="registro_id_emisor">
                    <option value="">Selecciona el remitente</option>
                    @foreach(\App\Models\Registro::all() as $registro)
                        <option value="{{ $registro->id }}" {{ old('registro_id_emisor', $comentario?->registro_id_emisor) == $registro->id ? 'selected' : '' }}>
                            {{ $registro->nombre }} ({{ $registro->correo }})
                        </option>
                    @endforeach
                </select>
                {!! $errors->first('registro_id_emisor', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
            </div>
            
            <div class="form-group mb-2 mb20">
                <label for="registro_id_receptor" class="form-label">Destinatario</label>
                <select name="registro_id_receptor" class="form-control @error('registro_id_receptor') is-invalid @enderror" id="registro_id_receptor">
                    <option value="">Selecciona el destinatario</option>
                    @foreach(\App\Models\Registro::all() as $registro)
                        <option value="{{ $registro->id }}" {{ old('registro_id_receptor', $comentario?->registro_id_receptor) == $registro->id ? 'selected' : '' }}>
                            {{ $registro->nombre }} ({{ $registro->correo }})
                        </option>
                    @endforeach
                </select>
                {!! $errors->first('registro_id_receptor', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
            </div>
        @endif

        <div class="form-group mb-2 mb20">
            <label for="mensaje" class="form-label">Mensaje (opcional)</label>
            <textarea name="mensaje" class="form-control @error('mensaje') is-invalid @enderror" 
                      id="mensaje" rows="4" placeholder="Escribe tu mensaje aquí... (opcional)">{{ old('mensaje', $comentario?->mensaje) }}</textarea>
            {!! $errors->first('mensaje', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

        <div class="form-group mb-2 mb20">
            <label for="imagen" class="form-label">Imagen (opcional)</label>
            <input type="file" name="imagen" class="form-control @error('imagen') is-invalid @enderror" 
                   id="imagen" accept="image/*">
            <small class="form-text text-muted">Formatos permitidos: JPEG, PNG, JPG, GIF. Máximo 2MB.</small>
            {!! $errors->first('imagen', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

        <div class="form-group mb-2 mb20">
            <label for="audio" class="form-label">Audio (opcional)</label>
            <input type="file" name="audio" class="form-control @error('audio') is-invalid @enderror" 
                   id="audio" accept="audio/*">
            <small class="form-text text-muted">Formatos permitidos: MP3, WAV, OGG. Máximo 10MB.</small>
            {!! $errors->first('audio', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">
            @if(isset($emisor) && isset($receptor))
                Enviar Mensaje
            @else
                Enviar
            @endif
        </button>
    </div>
</div>