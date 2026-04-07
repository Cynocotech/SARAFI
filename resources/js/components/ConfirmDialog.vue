<template>
  <teleport to="body">
    <transition name="fade">
      <div v-if="visible" class="confirm-overlay" @click.self="cancel">
        <div class="confirm-dialog" role="alertdialog" :aria-label="title">
          <p class="confirm-title">{{ title }}</p>
          <p v-if="message" class="confirm-message">{{ message }}</p>
          <div class="confirm-actions">
            <button class="btn-cancel" @click="cancel">انصراف</button>
            <button class="btn-confirm" :class="`btn-confirm--${variant}`" @click="confirm" :disabled="loading">
              <span v-if="loading" class="spinner"></span>
              <span v-else>{{ confirmLabel }}</span>
            </button>
          </div>
        </div>
      </div>
    </transition>
  </teleport>
</template>

<script setup>
import { ref } from 'vue';

const visible = ref(false);
const loading = ref(false);
const title   = ref('');
const message = ref('');
const variant = ref('danger');
const confirmLabel = ref('حذف');

let resolveFn = null;

function open(opts = {}) {
  title.value       = opts.title       ?? 'آیا مطمئن هستید؟';
  message.value     = opts.message     ?? '';
  variant.value     = opts.variant     ?? 'danger';
  confirmLabel.value = opts.confirmLabel ?? 'تأیید';
  visible.value = true;
  return new Promise((resolve) => { resolveFn = resolve; });
}
function confirm() { visible.value = false; resolveFn?.(true); }
function cancel()  { visible.value = false; resolveFn?.(false); }

defineExpose({ open, loading });
</script>

<style scoped>
.confirm-overlay {
  position: fixed; inset: 0; z-index: 9000;
  background: rgba(0,0,0,0.45); backdrop-filter: blur(3px);
  display: flex; align-items: center; justify-content: center; padding: 1rem;
}
.confirm-dialog {
  background: var(--bg-card, #fff); border-radius: 14px;
  padding: 1.75rem; max-width: 360px; width: 100%;
  box-shadow: 0 16px 48px rgba(0,0,0,0.2);
}
.confirm-title   { font-size: 1.05rem; font-weight: 700; color: var(--text, #0c4a6e); margin-bottom: 0.5rem; }
.confirm-message { font-size: 0.9rem; color: var(--text-muted, #64748b); margin-bottom: 1.25rem; line-height: 1.6; }
.confirm-actions { display: flex; gap: 0.75rem; justify-content: flex-end; }
.btn-cancel  { padding: 0.55rem 1.1rem; border-radius: 8px; border: 1px solid var(--border,#e2e8f0); background: transparent; color: var(--text-muted,#64748b); font-weight: 600; cursor: pointer; }
.btn-cancel:hover { background: var(--bg-elevated,#f1f5f9); }
.btn-confirm { padding: 0.55rem 1.1rem; border-radius: 8px; border: none; font-weight: 700; cursor: pointer; color: #fff; }
.btn-confirm--danger  { background: #ef4444; }
.btn-confirm--primary { background: var(--accent,#0891b2); }
.btn-confirm:disabled { opacity: 0.6; cursor: not-allowed; }
.spinner { display: inline-block; width: 14px; height: 14px; border: 2px solid rgba(255,255,255,0.4); border-top-color: #fff; border-radius: 50%; animation: spin 0.6s linear infinite; }
@keyframes spin { to { transform: rotate(360deg); } }
.fade-enter-active, .fade-leave-active { transition: opacity 0.2s; }
.fade-enter-from, .fade-leave-to { opacity: 0; }
</style>
