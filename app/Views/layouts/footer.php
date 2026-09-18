    </main>
    <?php if (!empty($isLoggedIn)): ?>
            </section>
        </div>
    <?php endif; ?>

<!-- Modal de Confirmación Estilizado Personalizado -->
<div id="customConfirmModal" class="modal-overlay" onclick="closeConfirmModal(false)">
    <div class="modal-card" onclick="event.stopPropagation()">
        <div class="modal-body-content">
            <div class="modal-danger-icon">
                <svg width="26" height="26" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="3 6 5 6 21 6"></polyline>
                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                    <line x1="10" y1="11" x2="10" y2="17"></line>
                    <line x1="14" y1="11" x2="14" y2="17"></line>
                </svg>
            </div>
            <h3 class="modal-title">Confirmar Eliminación</h3>
            <p id="confirmModalText" class="modal-text">
                ¿Seguro que deseas eliminar este elemento? Esta acción no se puede deshacer.
            </p>
        </div>
        <div class="modal-footer-actions">
            <button type="button" class="btn-cancel-modal" onclick="closeConfirmModal(false)">
                Cancelar
            </button>
            <button type="button" id="confirmModalAcceptBtn" class="btn-confirm-delete">
                Sí, Eliminar
            </button>
        </div>
    </div>
</div>

<script>
let pendingConfirmForm = null;

// Restaurar posición del scroll al volver de una eliminación
(function() {
    const savedY = sessionStorage.getItem('_catalogScrollY');
    if (savedY !== null) {
        sessionStorage.removeItem('_catalogScrollY');
        // Esperar a que el DOM termine de renderizarse antes de hacer scroll
        requestAnimationFrame(function() {
            window.scrollTo({ top: parseInt(savedY, 10), behavior: 'instant' });
        });
    }
})();

function showCustomConfirm(message, formElement) {
    pendingConfirmForm = formElement;
    document.getElementById('confirmModalText').textContent = message;
    document.getElementById('customConfirmModal').classList.add('active');
    document.body.style.overflow = 'hidden';

    document.getElementById('confirmModalAcceptBtn').onclick = function() {
        if (pendingConfirmForm) {
            const form = pendingConfirmForm;
            pendingConfirmForm = null;
            closeConfirmModal(false);
            // Guardar posición actual antes de recargar
            sessionStorage.setItem('_catalogScrollY', window.scrollY);
            form.submit();
        }
    };
    return false;
}

function closeConfirmModal(accepted) {
    document.getElementById('customConfirmModal').classList.remove('active');
    document.body.style.overflow = '';
    if (!accepted) {
        pendingConfirmForm = null;
    }
}

// ── Toggle de visibilidad de contraseña (ojito) ──
function togglePasswordVisibility(btn) {
    const wrapper = btn.closest('.input-with-icon');
    if (!wrapper) return;
    const input = wrapper.querySelector('input');
    if (!input) return;

    const isPassword = input.type === 'password';
    input.type = isPassword ? 'text' : 'password';

    const eyeOpen = btn.querySelector('.eye-open');
    const eyeClosed = btn.querySelector('.eye-closed');
    if (eyeOpen && eyeClosed) {
        eyeOpen.style.display = isPassword ? 'none' : 'block';
        eyeClosed.style.display = isPassword ? 'block' : 'none';
    }
}
</script>
</body>
</html>
