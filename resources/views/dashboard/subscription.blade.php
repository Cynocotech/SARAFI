@extends('layouts.dashboard')
@section('title', 'اشتراک ماهانه | آقای صرافی')
@section('content')
  <div class="sub">
    <header class="sub-header">
      <h1 class="sub-title">اشتراک ماهانه</h1>
      <a href="{{ route('dashboard.index') }}" class="sub-back" id="subBack">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
        بازگشت
      </a>
    </header>

    @if(session('success'))
      <div class="sub-alert sub-alert-success" role="alert">
        {{ session('success') }}
      </div>
    @endif
    @if($errors->has('stripe'))
      <div class="sub-alert sub-alert-error" role="alert">
        {{ $errors->first('stripe') }}
      </div>
    @endif

    <div class="sub-steps" id="subSteps">
      <div class="sub-step active" data-step="0"></div>
      <div class="sub-step" data-step="1"></div>
    </div>

    <div class="sub-panel active" id="panel0">
      <div class="sub-card">
        <h2>انتخاب پلن</h2>
        @if($plans->isEmpty())
          <p style="color:var(--text-muted);margin-bottom:1rem;">در حال حاضر پلنی موجود نیست.</p>
        @else
          @foreach($plans as $plan)
            <div class="sub-plan {{ $loop->first ? 'selected' : '' }}" data-plan="{{ $plan->id }}" data-price="{{ $plan->price }}" data-name="{{ $plan->name_fa ?? $plan->name }}">
              <div class="sub-plan-info">
                <strong>{{ $plan->name_fa ?? $plan->name }}</strong>
                <span>{{ $plan->description ?? '—' }}</span>
                @if(count($plan->getFeatureLabels()) > 0)
                  <p class="sub-plan-features">{{ implode(' • ', $plan->getFeatureLabels()) }}</p>
                @endif
              </div>
              <div class="sub-plan-price">{{ farsi_num(number_format($plan->price, 2)) }} <small>£ / {{ $plan->getIntervalLabel('fa') }}</small></div>
            </div>
          @endforeach
        @endif
      </div>
      @if($plans->isNotEmpty())
        <button type="button" class="sub-btn sub-btn-primary" id="btnNext0">ادامه</button>
      @endif
    </div>

    <div class="sub-panel" id="panel1">
      <div class="sub-card">
        <h2>بررسی و پرداخت</h2>
        <div class="sub-review">
          <div class="sub-review-row"><span>پلن</span><span id="reviewPlan">—</span></div>
          <div class="sub-review-row" id="reviewFeaturesRow" style="display:none;"><span>امکانات</span><span id="reviewFeatures">—</span></div>
          <div class="sub-review-row"><span>دوره</span><span id="reviewInterval">—</span></div>
          <div class="sub-review-row total"><span>مبلغ قابل پرداخت</span><span id="reviewPrice">—</span></div>
        </div>
        <p class="sub-stripe-desc">پرداخت به‌صورت امن از طریق Stripe انجام می‌شود.</p>
        <form action="{{ route('dashboard.subscription.checkout') }}" method="post" id="stripeCheckoutForm">
          @csrf
          <input type="hidden" name="plan_id" id="formPlanId" value="">
          <button type="submit" class="sub-btn sub-btn-primary sub-btn-stripe" id="btnPayStripe">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M13.976 9.15c-2.172-.806-3.356-1.426-3.356-2.409 0-.831.683-1.305 1.901-1.305 2.227 0 4.515.858 6.09 1.631l.89-5.494C18.252 1.125 15.697 0 12.165 0 9.667 0 7.589.654 6.104 1.872 4.56 3.147 3.757 4.85 3.757 7.218c0 4.039 2.467 5.76 6.476 7.219 2.585.92 3.445 1.574 3.445 2.583 0 .98-.84 1.549-2.354 1.549-1.875 0-4.965-.921-6.99-2.109l-.9 5.555C5.175 22.99 8.385 24 11.714 24c2.641 0 4.843-.624 6.328-1.813 1.664-1.305 2.525-3.236 2.525-5.732 0-4.128-2.524-5.851-6.571-7.305z"/></svg>
            پرداخت با Stripe
          </button>
        </form>
      </div>
      <button type="button" class="sub-btn sub-btn-secondary" id="btnPrev1">قبلی</button>
    </div>
  </div>

  <div class="alert-modal-overlay" id="alertModal">
    <div class="alert-modal">
      <div class="alert-modal-icon error" id="alertIcon">!</div>
      <h3 id="alertTitle">توجه</h3>
      <p id="alertMessage"></p>
      <button type="button" class="btn-ok" id="alertOk">متوجه شدم</button>
    </div>
  </div>
@endsection
@push('scripts')
<script>
  var plans = @json($plansJson);
  var step = 0, selectedPlan = null, selectedPrice = 0;
  if (Object.keys(plans).length > 0) {
    var firstId = Object.keys(plans)[0];
    selectedPlan = firstId;
    selectedPrice = plans[firstId].price;
  }
  function toP(n) { var s = (typeof n === 'number' ? n.toFixed(2) : n).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ','); return s.replace(/\d/g, function(d) { return '۰۱۲۳۴۵۶۷۸۹'[parseInt(d)]; }); }
  function setStep(s) {
    step = s;
    document.querySelectorAll('.sub-panel').forEach(function(p) { p.classList.remove('active'); });
    var panel = document.getElementById('panel' + step);
    if (panel) panel.classList.add('active');
    document.querySelectorAll('.sub-step').forEach(function(st, i) { st.classList.toggle('done', i < step); st.classList.toggle('active', i === step); });
  }
  function updateReview() {
    var reviewPlan = document.getElementById('reviewPlan');
    var reviewPrice = document.getElementById('reviewPrice');
    var reviewInterval = document.getElementById('reviewInterval');
    var reviewFeatures = document.getElementById('reviewFeatures');
    var reviewFeaturesRow = document.getElementById('reviewFeaturesRow');
    var formPlanId = document.getElementById('formPlanId');
    if (reviewPlan && selectedPlan && plans[selectedPlan]) reviewPlan.textContent = plans[selectedPlan].name;
    if (reviewPrice) reviewPrice.textContent = selectedPlan && plans[selectedPlan] ? toP(plans[selectedPlan].price) + ' £' : '—';
    if (reviewInterval && selectedPlan && plans[selectedPlan]) reviewInterval.textContent = plans[selectedPlan].intervalLabel || '—';
    if (reviewFeatures && reviewFeaturesRow && selectedPlan && plans[selectedPlan]) {
      var f = plans[selectedPlan].features;
      if (f && f.length > 0) {
        reviewFeatures.textContent = f.join(' • ');
        reviewFeaturesRow.style.display = '';
      } else {
        reviewFeaturesRow.style.display = 'none';
      }
    }
    if (formPlanId && selectedPlan) formPlanId.value = selectedPlan;
  }
  document.querySelectorAll('.sub-plan').forEach(function(el) {
    el.addEventListener('click', function() {
      document.querySelectorAll('.sub-plan').forEach(function(p) { p.classList.remove('selected'); });
      this.classList.add('selected');
      selectedPlan = this.dataset.plan;
      selectedPrice = parseFloat(this.dataset.price, 10);
    });
  });
  document.getElementById('btnNext0') && (document.getElementById('btnNext0').onclick = function() { updateReview(); setStep(1); });
  document.getElementById('btnPrev1') && (document.getElementById('btnPrev1').onclick = function() { setStep(0); });
  document.getElementById('stripeCheckoutForm') && document.getElementById('stripeCheckoutForm').addEventListener('submit', function(ev) {
    updateReview();
    if (!selectedPlan) {
      ev.preventDefault();
      document.getElementById('alertMessage').textContent = 'لطفاً یک پلن انتخاب کنید.';
      document.getElementById('alertModal').classList.add('show');
      return false;
    }
    document.getElementById('formPlanId').value = selectedPlan;
  });
  document.getElementById('alertOk') && (document.getElementById('alertOk').onclick = function() { document.getElementById('alertModal').classList.remove('show'); });
  document.getElementById('alertModal') && (document.getElementById('alertModal').onclick = function(e) { if (e.target.id === 'alertModal') this.classList.remove('show'); });
</script>
@endpush
