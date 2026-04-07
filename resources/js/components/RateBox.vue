<template>
  <div class="price-box" :class="type">
    <div class="label">
      <svg v-if="type === 'buy'" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" width="14" height="14"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/></svg>
      <svg v-else viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" width="14" height="14"><polyline points="23 18 13.5 8.5 8.5 13.5 1 6"/></svg>
      {{ label }}
    </div>
    <input
      type="number"
      :value="modelValue"
      @input="$emit('update:modelValue', $event.target.value)"
      min="0"
      step="1"
      :placeholder="placeholder"
      :required="required"
      :disabled="disabled"
      autocomplete="off"
    />
    <p v-if="error" class="rate-box-error">{{ error }}</p>
  </div>
</template>

<script setup>
defineProps({
  type:        { type: String, default: 'buy' },   // 'buy' | 'sell'
  label:       { type: String, required: true },
  modelValue:  { type: [String, Number], default: '' },
  placeholder: { type: String, default: 'مثال: 85000' },
  required:    { type: Boolean, default: false },
  disabled:    { type: Boolean, default: false },
  error:       { type: String, default: '' },
});
defineEmits(['update:modelValue']);
</script>

<style scoped>
.rate-box-error {
  font-size: 0.8rem;
  color: #ef4444;
  margin-top: 0.4rem;
  text-align: center;
}
</style>
