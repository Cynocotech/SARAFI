<template>
  <div class="stats-row">
    <div class="stat-card" :class="subscriptionClass">
      <div class="stat-card-icon" aria-hidden="true">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="22" height="22"><rect x="1" y="4" width="22" height="16" rx="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
      </div>
      <div class="stat-card-body">
        <div class="stat-label">وضعیت اشتراک</div>
        <div class="stat-value">{{ subscriptionActive ? 'فعال' : 'غیرفعال' }}</div>
        <div v-if="planName" class="stat-sublabel">{{ planName }}</div>
        <div v-if="subscriptionActive && daysRemaining !== null" class="stat-sublabel">{{ farsiNum(daysRemaining) }} روز باقی‌مانده</div>
        <div v-else-if="subscriptionActive" class="stat-sublabel">تخصیص ادمین</div>
      </div>
    </div>

    <div class="stat-card stat-card--success">
      <div class="stat-card-icon stat-card-icon--buy" aria-hidden="true">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="22" height="22"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/></svg>
      </div>
      <div class="stat-card-body">
        <div class="stat-label">نرخ خرید پوند</div>
        <div class="stat-value">{{ buyRate }}</div>
        <div class="stat-sublabel">تومان</div>
      </div>
    </div>

    <div class="stat-card stat-card--danger">
      <div class="stat-card-icon stat-card-icon--sell" aria-hidden="true">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="22" height="22"><polyline points="23 18 13.5 8.5 8.5 13.5 1 6"/><polyline points="17 18 23 18 23 12"/></svg>
      </div>
      <div class="stat-card-body">
        <div class="stat-label">نرخ فروش پوند</div>
        <div class="stat-value">{{ sellRate }}</div>
        <div class="stat-sublabel">تومان</div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue';
import { farsiNum } from '@/utils/farsi.js';

const props = defineProps({
  subscriptionActive: { type: Boolean, default: false },
  planName:           { type: String, default: '' },
  daysRemaining:      { type: [Number, null], default: null },
  gbpBuyRate:         { type: [Number, null], default: null },
  gbpSellRate:        { type: [Number, null], default: null },
});

const subscriptionClass = computed(() =>
  props.subscriptionActive ? 'stat-card--success' : 'stat-card--danger'
);
const buyRate  = computed(() => props.gbpBuyRate  ? farsiNum(props.gbpBuyRate,  0) : '—');
const sellRate = computed(() => props.gbpSellRate ? farsiNum(props.gbpSellRate, 0) : '—');
</script>
