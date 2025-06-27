<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">Configuración de Usuario</h5>
    </div>
    <div class="card-body">
        <form id="userSettingsForm">
            <div class="mb-3">
                <label class="form-label">Moneda Principal</label>
                <select class="form-select" name="currency" id="currencySetting">
                    <option value="NIO" selected>Córdobas (NIO)</option>
                    <option value="USD">Dólares (USD)</option>
                </select>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Formato de Fecha</label>
                <select class="form-select" name="date_format" id="dateFormatSetting">
                    <option value="d/m/Y">DD/MM/AAAA</option>
                    <option value="m/d/Y">MM/DD/AAAA</option>
                    <option value="Y-m-d">AAAA-MM-DD</option>
                </select>
            </div>
            
            <div class="form-check form-switch mb-3">
                <input class="form-check-input" type="checkbox" id="darkModeSetting" name="dark_mode">
                <label class="form-check-label" for="darkModeSetting">Modo Oscuro</label>
            </div>
            
            <div class="form-check form-switch mb-3">
                <input class="form-check-input" type="checkbox" id="notificationsSetting" name="notifications" checked>
                <label class="form-check-label" for="notificationsSetting">Notificaciones</label>
            </div>
            
            <button type="submit" class="btn btn-primary">Guardar Configuración</button>
        </form>
    </div>
</div>