<template>
  <div class="toast-container" aria-live="polite" aria-atomic="false">
    <transition-group name="toast">
      <div
        v-for="toast in toasts"
        :key="toast.id"
        class="toast"
        :class="`toast--${toast.type}`"
        role="alert"
      >
        <span class="toast-icon">
          <svg v-if="toast.type === 'success'" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" width="18" height="18"><polyline points="20 6 9 17 4 12"/></svg>
          <svg v-else-if="toast.type === 'error'" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" width="18" height="18"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
          <svg v-else viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" width="18" height="18"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
        </span>
        <span class="toast-message">{{ toast.message }}</span>
        <button class="toast-close" @click="remove(toast.id)" aria-label="بستن">✕</button>
      </div>
    </transition-group>
  </div>
</template>

<script setup>
import { useToast } from '@/composables/useToast.js';
const { toasts, remove } = useToast();
</script>

<style scoped>
.toast-container {
  position: fixed;
  top: 1.25rem;
  left: 50%;
  transform: translateX(-50%);
  z-index: 9999;
  display: flex;
  flex-direction: column;
  gap: 0.6rem;
  min-width: 280px;
  max-width: min(480px, 92vw);
  pointer-events: none;
}
.toast {
  display: flex;
  align-items: center;
  gap: 0.65rem;
  padding: 0.75rem 1rem;
  border-radius: 10px;
  font-size: 0.9rem;
  font-weight: 600;
  box-shadow: 0 4px 24px rgba(0,0,0,0.15);
  pointer-events: auto;
}
.toast--success { background: #10b981; color: #fff; }
.toast--error   { background: #ef4444; color: #fff; }
.toast--info    { background: #0891b2; color: #fff; }
.toast-icon { flex-shrink: 0; display: flex; }
.toast-message { flex: 1; }
.toast-close {
  background: none; border: none; color: inherit; cursor: pointer;
  opacity: 0.7; font-size: 0.85rem; padding: 0; flex-shrink: 0;
}
.toast-close:hover { opacity: 1; }

.toast-enter-active, .toast-leave-active { transition: all 0.25s ease; }
.toast-enter-from { opacity: 0; transform: translateY(-12px); }
.toast-leave-to   { opacity: 0; transform: translateY(-12px); }
</style>
