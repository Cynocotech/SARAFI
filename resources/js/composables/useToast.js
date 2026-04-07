import { ref } from 'vue';

const toasts = ref([]);
let nextId = 0;

export function useToast() {
    function add(message, type = 'success', duration = 4000) {
        const id = ++nextId;
        toasts.value.push({ id, message, type });
        if (duration > 0) {
            setTimeout(() => remove(id), duration);
        }
        return id;
    }

    function remove(id) {
        const idx = toasts.value.findIndex((t) => t.id === id);
        if (idx !== -1) toasts.value.splice(idx, 1);
    }

    const success = (msg) => add(msg, 'success');
    const error   = (msg) => add(msg, 'error', 6000);
    const info    = (msg) => add(msg, 'info');

    return { toasts, add, remove, success, error, info };
}
