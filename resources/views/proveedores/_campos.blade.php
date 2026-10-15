<label for="nombre">Nombre</label>
<input type="text" id="nombre" name="nombre" value="{{ old('nombre', $proveedor->nombre ?? '') }}" maxlength="150" required>
@error('nombre')
    <span class="error">{{ $message }}</span>
@enderror

<label for="telefono">Teléfono (opcional)</label>
<input type="text" id="telefono" name="telefono" value="{{ old('telefono', $proveedor->telefono ?? '') }}" maxlength="30">
@error('telefono')
    <span class="error">{{ $message }}</span>
@enderror

<label for="direccion">Dirección (opcional)</label>
<input type="text" id="direccion" name="direccion" value="{{ old('direccion', $proveedor->direccion ?? '') }}" maxlength="200">
@error('direccion')
    <span class="error">{{ $message }}</span>
@enderror