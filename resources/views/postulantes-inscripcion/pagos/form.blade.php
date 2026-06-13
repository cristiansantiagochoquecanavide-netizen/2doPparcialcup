<!-- Parcial CU8: campos obligatorios del registro de pago. -->
<div class="mb-3">
    <label class="form-label">Postulante</label>
    <select name="id_postulante" class="form-select" required>
        <option value="">Seleccione...</option>
        @foreach($postulantes as $postulante)
            <option value="{{ $postulante->id_postulante }}" @selected(old('id_postulante', $pago->id_postulante) == $postulante->id_postulante)>
                {{ $postulante->ci }} - {{ $postulante->nombres }} {{ $postulante->apellidos }}
            </option>
        @endforeach
    </select>
</div>
<div class="row">
    <div class="col-md-4 mb-3"><label class="form-label">Monto</label><input type="number" step="0.01" min="0.01" name="monto" value="{{ old('monto', $pago->monto) }}" class="form-control" required></div>
    <div class="col-md-4 mb-3"><label class="form-label">Metodo de pago</label><input name="metodo_pago" value="{{ old('metodo_pago', $pago->metodo_pago) }}" class="form-control" required></div>
    <div class="col-md-4 mb-3"><label class="form-label">Codigo transaccion</label><input name="codigo_transaccion" value="{{ old('codigo_transaccion', $pago->codigo_transaccion) }}" class="form-control"></div>
</div>
<div class="mb-3">
    <label class="form-label">Estado</label>
    <select name="estado_pago" class="form-select" required>
        @foreach(['PENDIENTE','PAGADO','RECHAZADO','ANULADO'] as $estado)
            <option value="{{ $estado }}" @selected(old('estado_pago', $pago->estado_pago ?: 'PENDIENTE') === $estado)>{{ $estado }}</option>
        @endforeach
    </select>
</div>
