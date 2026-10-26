<div class="campo-filtro">
    <label for="desde">Desde</label>
    <input type="date" id="desde" name="desde" value="{{ $desde->toDateString() }}">
</div>

<div class="campo-filtro">
    <label for="hasta">Hasta</label>
    <input type="date" id="hasta" name="hasta" value="{{ $hasta->toDateString() }}">
</div>