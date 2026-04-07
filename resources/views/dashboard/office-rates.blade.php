@extends('layouts.dashboard')
@section('title', 'نرخ‌های ' . $office->name . ' | آقای صرافی')
@section('dashboard_nav_title', 'نرخ‌ها — ' . $office->name)
@section('dashboard_nav_back', route('dashboard.rates'))
@section('dashboard_main_class', 'dashboard-rates')
@section('content')
@php
  $gbpRate = $office->exchangeRates->where('from_currency','GBP')->where('to_currency','IRR')->first();
  $props = [
    'officeId'                => $office->id,
    'initialRates'            => $office->exchangeRates->values()->toArray(),
    'initialSpecialBuy'       => $office->special_rate_buy,
    'initialSpecialSell'      => $office->special_rate_sell,
    'hasSpecialRateToday'     => $office->hasSpecialRateToday(),
    'paymentMethodOptions'    => \App\Models\ExchangeOffice::paymentMethodOptions(),
    'initialPaymentMethods'   => $office->getAcceptedPaymentMethods(),
    'initialTransferFeeUnder' => $office->transfer_fee_under_amount,
    'initialTransferFeeAmount'=> $office->transfer_fee_amount,
  ];
@endphp
  <div id="office-rates-app" data-props="{{ json_encode($props) }}"></div>
  @vite(['resources/js/pages/office-rates.js'])
@endsection
