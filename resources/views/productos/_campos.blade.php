<label for="nombre">Nombre</label>
<input type="text" id="nombre" name="nombre" value="{{ old('nombre', $producto->nombre ?? '') }}" maxlength="150" required>
@error('nombre')
    <span class="error">{{ $message }}</span>
@enderror

<label for="categorias_id">Categoría</label>
<select id="categorias_id" name="categorias_id" required>
    <option value="">Selecciona una categoría</option>
    @foreach ($categorias as $categoria)
        <option value="{{ $categoria->id }}"
            {{ old('categorias_id', $producto->categorias_id ?? '') == $categoria->id ? 'selected' : '' }}>
            {{ $categoria->nombre }}
        </option>
    @endforeach
</select>
@error('categorias_id')
    <span class="error">{{ $message }}</span>
@enderror

<label for="precio_compra">Precio de compra</label>
<input type="number" id="precio_compra" name="precio_compra" value="{{ old('precio_compra', $producto->precio_compra ?? '') }}" min="0" step="0.01" required>
@error('precio_compra')
    <span class="error">{{ $message }}</span>
@enderror

<label for="precio_venta">Precio de venta</label>
<input type="number" id="precio_venta" name="precio_venta" value="{{ old('precio_venta', $producto->precio_venta ?? '') }}" min="0" step="0.01" required>
@error('precio_venta')
    <span class="error">{{ $message }}</span>
@enderror

<label for="stock">Stock actual</label>
<input type="number" id="stock" name="stock" value="{{ old('stock', $producto->stock ?? 0) }}" min="0" step="1" required>
@error('stock')
    <span class="error">{{ $message }}</span>
@enderror

<label for="stock_minimo">Stock mínimo</label>
<input type="number" id="stock_minimo" name="stock_minimo" value="{{ old('stock_minimo', $producto->stock_minimo ?? 0) }}" min="0" step="1" required>
@error('stock_minimo')
    <span class="error">{{ $message }}</span>
@enderror