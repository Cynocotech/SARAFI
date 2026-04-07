import { createApp } from 'vue';
import DashboardStatsApp from '@/components/DashboardStatsApp.vue';

const el = document.getElementById('dashboard-stats-app');
if (el) {
    const props = JSON.parse(el.dataset.props || '{}');
    createApp(DashboardStatsApp, props).mount(el);
}
