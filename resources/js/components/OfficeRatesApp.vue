<template>
  <div class="dashboard-rates-content">
    <ToastContainer />
    <ConfirmDialog ref="confirmRef" />

    <!-- Flash alert -->
    <transition name="fade">
      <div v-if="flash" class="dashboard-rates-alert" style="background:var(--success);color:white;padding:0.75rem 1rem;border-radius:var(--radius);font-size:0.9rem;">
        {{ flash }}
      </div>
    </transition>

    <!-- Special rate card -->
    <div class="dash-card">
      <h2>نرخ ویژه امروز</h2>
      <p>نرخ ویژه به صورت بنر در لیست صرافی‌ها نمایش داده می‌شود.</p>
      <div class="form-group">
        <label>نرخ ویژه برای</label>
        <div style="display:flex;flex-wrap:wrap;gap:0.65rem;margin-top:0.25rem;">
          <label class="rate-radio-label" v-for="opt in specialRateOptions" :key="opt.value">
            <input type="radio" v-model="specialForm.option" :value="opt.value" class="special-rate-option-input">
            <span>{{ opt.label }}</span>
          </label>
        </div>
      </div>
      <div class="price-row special-rate-fields">
        <RateBox
          v-show="specialForm.option !== 'sell'"
          type="buy"
          label="نرخ خرید ویژه (تومان)"
          v-model="specialForm.buy"
          :error="specialErrors.buy"
          placeholder="خالی = غیرفعال"
        />
        <RateBox
          v-show="specialForm.option !== 'buy'"
          type="sell"
          label="نرخ فروش ویژه (تومان)"
          v-model="specialForm.sell"
          :error="specialErrors.sell"
          placeholder="خالی = غیرفعال"
        />
      </div>
      <button class="btn btn-primary btn-block" style="margin-top:0.5rem;" @click="saveSpecialRate" :disabled="specialLoading">
        <span v-if="specialLoading" class="inline-spinner"></span>
        <span v-else>ذخیره نرخ ویژه امروز</span>
      </button>
      <div v-if="props.hasSpecialRateToday" style="margin-top:0.75rem;">
        <button class="btn btn-secondary" @click="clearSpecialRate" :disabled="specialLoading">حذف نرخ ویژه</button>
      </div>
    </div>

    <!-- Payment methods card -->
    <div class="dash-card">
      <h2>پرداخت با</h2>
      <p>روش‌های پرداختی که در صرافی می‌پذیرید را انتخاب کنید.</p>
      <div style="display:flex;flex-wrap:wrap;gap:0.75rem 1rem;">
        <label v-for="(label, key) in props.paymentMethodOptions" :key="key" class="payment-method-check">
          <input type="checkbox" :value="key" v-model="paymentForm.methods">
          <span>{{ label }}</span>
        </label>
      </div>
      <button class="btn btn-primary" style="margin-top:1.25rem;" @click="savePaymentMethods" :disabled="paymentLoading">
        <span v-if="paymentLoading" class="inline-spinner"></span>
        <span v-else>ذخیره</span>
      </button>
    </div>

    <!-- Transfer fee card -->
    <div class="dash-card">
      <h2>کارمزد حواله</h2>
      <p>کارمزد حواله برای مبالغ زیر حد مشخص.</p>
      <div class="form-row" style="display:grid;grid-template-columns:repeat(auto-fit,minmax(140px,1fr));gap:1rem;">
        <div class="form-group">
          <label>برای حواله زیر (پوند)</label>
          <input type="number" v-model="feeForm.under" min="0" step="1" placeholder="مثال: 1000">
          <p v-if="feeErrors.under" class="text-danger" style="font-size:0.85rem;margin-top:0.25rem;">{{ feeErrors.under }}</p>
        </div>
        <div class="form-group">
          <label>مبلغ کارمزد (پوند)</label>
          <input type="number" v-model="feeForm.amount" min="0" step="0.01" placeholder="مثال: 5">
          <p v-if="feeErrors.amount" class="text-danger" style="font-size:0.85rem;margin-top:0.25rem;">{{ feeErrors.amount }}</p>
        </div>
      </div>
      <button class="btn btn-primary" style="margin-top:0.5rem;" @click="saveTransferFee" :disabled="feeLoading">
        <span v-if="feeLoading" class="inline-spinner"></span>
        <span v-else>ذخیره کارمزد حواله</span>
      </button>
    </div>

    <!-- Add rate card -->
    <div class="dash-card">
      <h2>ثبت نرخ جدید — پوند به تومان</h2>
      <div class="price-row">
        <RateBox type="buy" label="نرخ خرید (تومان)" v-model="addForm.buy" :error="addErrors.buy" placeholder="مثال: 85000" required />
        <RateBox type="sell" label="نرخ فروش (تومان)" v-model="addForm.sell" :error="addErrors.sell" placeholder="مثال: 86500" required />
      </div>
      <p v-if="addErrors.general" class="text-danger" style="font-size:0.85rem;">{{ addErrors.general }}</p>
      <button class="btn btn-primary btn-block" style="margin-top:0.75rem;" @click="addRate" :disabled="addLoading">
        <span v-if="addLoading" class="inline-spinner"></span>
        <span v-else>افزودن نرخ</span>
      </button>
    </div>

    <!-- Current rates table -->
    <div class="dash-card span-full">
      <h2>نرخ‌های فعلی</h2>
      <div v-if="rates.length === 0">
        <p>هنوز نرخی ثبت نشده است. با فرم بالا یک نرخ پوند به تومان اضافه کنید.</p>
      </div>
      <div v-else style="overflow-x:auto;">
        <table style="width:100%;border-collapse:collapse;font-size:0.9rem;">
          <thead>
            <tr style="border-bottom:1px solid var(--border);">
              <th style="text-align:right;padding:0.5rem;">جفت ارز</th>
              <th style="text-align:right;padding:0.5rem;">خرید</th>
              <th style="text-align:right;padding:0.5rem;">فروش</th>
              <th style="text-align:center;padding:0.5rem;">عملیات</th>
            </tr>
          </thead>
          <tbody>
            <template v-for="rate in rates" :key="rate.id">
              <tr style="border-bottom:1px solid var(--border);">
                <td style="padding:0.5rem;">{{ rate.from_currency }} → {{ rate.to_currency }}</td>
                <td style="padding:0.5rem;">{{ farsiNum(rate.buy_rate) }}</td>
                <td style="padding:0.5rem;">{{ farsiNum(rate.sell_rate) }}</td>
                <td style="padding:0.5rem;white-space:nowrap;text-align:center;">
                  <button class="btn btn-secondary" style="padding:0.35rem 0.6rem;font-size:0.8rem;" @click="startEdit(rate)">ویرایش</button>
                  <button class="btn" style="padding:0.35rem 0.6rem;font-size:0.8rem;background:var(--danger);color:white;border:none;border-radius:var(--radius);cursor:pointer;margin-right:0.25rem;" @click="deleteRate(rate)">حذف</button>
                </td>
              </tr>
              <!-- Inline edit row -->
              <tr v-if="editId === rate.id" style="background:var(--bg-elevated);">
                <td colspan="4" style="padding:1rem;">
                  <div class="price-row" style="margin-bottom:0.75rem;">
                    <RateBox type="buy" label="نرخ خرید (تومان)" v-model="editForm.buy" :error="editErrors.buy" />
                    <RateBox type="sell" label="نرخ فروش (تومان)" v-model="editForm.sell" :error="editErrors.sell" />
                  </div>
                  <div style="display:flex;gap:0.5rem;">
                    <button class="btn btn-primary" style="flex:1;" @click="saveEdit(rate)" :disabled="editLoading">
                      <span v-if="editLoading" class="inline-spinner"></span>
                      <span v-else>ذخیره تغییرات</span>
                    </button>
                    <button class="btn btn-secondary" @click="cancelEdit">انصراف</button>
                  </div>
                </td>
              </tr>
            </template>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue';
import { ratesApi, specialRateApi, paymentMethodsApi, transferFeeApi } from '@/api.js';
import { farsiNum } from '@/utils/farsi.js';
import { useToast } from '@/composables/useToast.js';
import ToastContainer from './ToastContainer.vue';
import ConfirmDialog from './ConfirmDialog.vue';
import RateBox from './RateBox.vue';

const props = defineProps({
  officeId:           { type: Number, required: true },
  initialRates:       { type: Array, default: () => [] },
  initialSpecialBuy:  { type: [Number, String, null], default: null },
  initialSpecialSell: { type: [Number, String, null], default: null },
  hasSpecialRateToday:{ type: Boolean, default: false },
  paymentMethodOptions: { type: Object, default: () => ({}) },
  initialPaymentMethods: { type: Array, default: () => [] },
  initialTransferFeeUnder:  { type: [Number, String, null], default: null },
  initialTransferFeeAmount: { type: [Number, String, null], default: null },
});

const { success: toastSuccess, error: toastError } = useToast();
const confirmRef = ref(null);
const flash = ref('');

// ── Rates ────────────────────────────────────────────────────────────────────
const rates = ref([...props.initialRates]);

// Add rate
const addForm   = reactive({ buy: '', sell: '' });
const addErrors = reactive({ buy: '', sell: '', general: '' });
const addLoading = ref(false);

async function addRate() {
  addErrors.buy = addErrors.sell = addErrors.general = '';
  if (!addForm.buy)  { addErrors.buy  = 'نرخ خرید الزامی است.'; return; }
  if (!addForm.sell) { addErrors.sell = 'نرخ فروش الزامی است.'; return; }
  addLoading.value = true;
  try {
    const { data } = await ratesApi.store(props.officeId, {
      from_currency: 'GBP', to_currency: 'IRR',
      buy_rate: addForm.buy, sell_rate: addForm.sell,
    });
    rates.value.push(data.rate);
    addForm.buy = addForm.sell = '';
    toastSuccess('نرخ با موفقیت اضافه شد.');
  } catch (e) {
    const errs = e.response?.data?.errors ?? {};
    addErrors.buy     = errs.buy_rate?.[0]     ?? '';
    addErrors.sell    = errs.sell_rate?.[0]    ?? '';
    addErrors.general = errs.from_currency?.[0] ?? (e.response?.data?.message ?? 'خطایی رخ داد.');
    toastError(addErrors.general || 'خطا در ثبت نرخ.');
  } finally { addLoading.value = false; }
}

// Edit rate
const editId     = ref(null);
const editForm   = reactive({ buy: '', sell: '' });
const editErrors = reactive({ buy: '', sell: '' });
const editLoading = ref(false);

function startEdit(rate) {
  editId.value   = rate.id;
  editForm.buy   = rate.buy_rate;
  editForm.sell  = rate.sell_rate;
  editErrors.buy = editErrors.sell = '';
}
function cancelEdit() { editId.value = null; }

async function saveEdit(rate) {
  editErrors.buy = editErrors.sell = '';
  if (!editForm.buy)  { editErrors.buy  = 'نرخ خرید الزامی است.'; return; }
  if (!editForm.sell) { editErrors.sell = 'نرخ فروش الزامی است.'; return; }
  editLoading.value = true;
  try {
    const { data } = await ratesApi.update(rate.id, { buy_rate: editForm.buy, sell_rate: editForm.sell });
    const idx = rates.value.findIndex((r) => r.id === rate.id);
    if (idx !== -1) rates.value[idx] = data.rate;
    editId.value = null;
    toastSuccess('نرخ با موفقیت ویرایش شد.');
  } catch (e) {
    const errs = e.response?.data?.errors ?? {};
    editErrors.buy  = errs.buy_rate?.[0]  ?? '';
    editErrors.sell = errs.sell_rate?.[0] ?? '';
    toastError('خطا در ویرایش نرخ.');
  } finally { editLoading.value = false; }
}

async function deleteRate(rate) {
  const ok = await confirmRef.value?.open({ title: 'حذف نرخ؟', message: `${rate.from_currency} → ${rate.to_currency}`, confirmLabel: 'حذف', variant: 'danger' });
  if (!ok) return;
  try {
    await ratesApi.remove(rate.id);
    rates.value = rates.value.filter((r) => r.id !== rate.id);
    toastSuccess('نرخ حذف شد.');
  } catch { toastError('خطا در حذف نرخ.'); }
}

// ── Special rate ─────────────────────────────────────────────────────────────
const specialRateOptions = [
  { value: 'buy', label: 'خرید' },
  { value: 'sell', label: 'فروش' },
  { value: 'both', label: 'خرید و فروش' },
];
const specialForm   = reactive({ option: 'buy', buy: props.initialSpecialBuy ?? '', sell: props.initialSpecialSell ?? '' });
const specialErrors = reactive({ buy: '', sell: '' });
const specialLoading = ref(false);

async function saveSpecialRate() {
  specialErrors.buy = specialErrors.sell = '';
  specialLoading.value = true;
  try {
    await specialRateApi.update(props.officeId, {
      special_rate_option: specialForm.option,
      special_rate_buy:  specialForm.option !== 'sell' ? specialForm.buy  : null,
      special_rate_sell: specialForm.option !== 'buy'  ? specialForm.sell : null,
    });
    toastSuccess('نرخ ویژه ذخیره شد.');
  } catch (e) {
    const errs = e.response?.data?.errors ?? {};
    specialErrors.buy  = errs.special_rate_buy?.[0]  ?? '';
    specialErrors.sell = errs.special_rate_sell?.[0] ?? '';
    toastError('خطا در ذخیره نرخ ویژه.');
  } finally { specialLoading.value = false; }
}

async function clearSpecialRate() {
  const ok = await confirmRef.value?.open({ title: 'حذف نرخ ویژه؟', confirmLabel: 'حذف', variant: 'danger' });
  if (!ok) return;
  specialLoading.value = true;
  try {
    await specialRateApi.clear(props.officeId);
    specialForm.buy = specialForm.sell = '';
    toastSuccess('نرخ ویژه حذف شد.');
  } catch { toastError('خطا در حذف نرخ ویژه.'); }
  finally { specialLoading.value = false; }
}

// ── Payment methods ───────────────────────────────────────────────────────────
const paymentForm    = reactive({ methods: [...props.initialPaymentMethods] });
const paymentLoading = ref(false);

async function savePaymentMethods() {
  paymentLoading.value = true;
  try {
    await paymentMethodsApi.update(props.officeId, { payment_methods: paymentForm.methods });
    toastSuccess('روش‌های پرداخت ذخیره شد.');
  } catch { toastError('خطا در ذخیره روش‌های پرداخت.'); }
  finally { paymentLoading.value = false; }
}

// ── Transfer fee ──────────────────────────────────────────────────────────────
const feeForm   = reactive({ under: props.initialTransferFeeUnder ?? '', amount: props.initialTransferFeeAmount ?? '' });
const feeErrors = reactive({ under: '', amount: '' });
const feeLoading = ref(false);

async function saveTransferFee() {
  feeErrors.under = feeErrors.amount = '';
  feeLoading.value = true;
  try {
    await transferFeeApi.update(props.officeId, {
      transfer_fee_under_amount: feeForm.under  || null,
      transfer_fee_amount:       feeForm.amount || null,
    });
    toastSuccess('کارمزد حواله ذخیره شد.');
  } catch (e) {
    const errs = e.response?.data?.errors ?? {};
    feeErrors.under  = errs.transfer_fee_under_amount?.[0] ?? '';
    feeErrors.amount = errs.transfer_fee_amount?.[0]       ?? '';
    toastError('خطا در ذخیره کارمزد.');
  } finally { feeLoading.value = false; }
}
</script>

<style scoped>
.inline-spinner {
  display: inline-block; width: 14px; height: 14px;
  border: 2px solid rgba(255,255,255,0.4); border-top-color: #fff;
  border-radius: 50%; animation: spin 0.6s linear infinite; vertical-align: -2px;
}
@keyframes spin { to { transform: rotate(360deg); } }
.text-danger { color: #ef4444; }
</style>
