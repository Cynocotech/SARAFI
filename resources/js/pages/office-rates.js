import { createApp } from 'vue';
import OfficeRatesApp from '@/components/OfficeRatesApp.vue';

const el = document.getElementById('office-rates-app');
if (el) {
    const props = JSON.parse(el.dataset.props || '{}');
    createApp(OfficeRatesApp, props).mount(el);
}
