<label for="nombre">Nombre</label>
<input type="text" id="nombre" name="nombre" value="{{ old('nombre', $categoria->nombre ?? '') }}" maxlength="100" required>
@error('nombre')
    <span class="error">{{ $message }}</span>
@enderror