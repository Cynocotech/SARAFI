<?php

namespace App\Http\Controllers;

use App\Models\ExchangeClick;
use App\Models\ExchangeOffice;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ExchangeDirectoryController extends Controller
{
    public function index(): View
    {
        $offices = ExchangeOffice::query()
            ->where('status', ExchangeOffice::STATUS_ACTIVE)
            ->whereNull('blocked_at')
            ->with('exchangeRates')
            ->orderBy('name')
            ->get();

        // Featured (هایلایت) exchanges first, then by name
        $offices = $offices->sort(function ($a, $b) {
            $aHighlight = is_array($a->features) && in_array('highlight', $a->features);
            $bHighlight = is_array($b->features) && in_array('highlight', $b->features);
            if ($aHighlight !== $bHighlight) {
                return $aHighlight ? -1 : 1;
            }
            return strcmp($a->name, $b->name);
        })->values();

        return view('exchanges.index', compact('offices'));
    }

    public function show(ExchangeOffice $exchangeOffice): View
    {
        if ($exchangeOffice->status !== ExchangeOffice::STATUS_ACTIVE || $exchangeOffice->isBlocked()) {
            abort(404);
        }

        $this->recordClick($exchangeOffice, ExchangeClick::TYPE_VIEW);
        $exchangeOffice->load('exchangeRates');

        $effectiveLandingTheme = $exchangeOffice->landing_theme
            ?: \App\Models\Setting::get('exchange_landing_theme', 'default');

        if ($effectiveLandingTheme === 'theme2') {
            return view('exchanges.show-theme2', compact('exchangeOffice'));
        }

        return view('exchanges.show', compact('exchangeOffice'));
    }

    /**
     * Record a click (view/call/map) for an exchange. Called from frontend or show page.
     */
    public function recordClickAction(ExchangeOffice $exchangeOffice, Request $request)
    {
        if ($exchangeOffice->status !== ExchangeOffice::STATUS_ACTIVE || $exchangeOffice->isBlocked()) {
            return response()->json(['ok' => false], 404);
        }
        $type = $request->input('type', ExchangeClick::TYPE_VIEW);
        if (! in_array($type, [ExchangeClick::TYPE_VIEW, ExchangeClick::TYPE_CALL, ExchangeClick::TYPE_MAP], true)) {
            $type = ExchangeClick::TYPE_VIEW;
        }
        $this->recordClick($exchangeOffice, $type);

        return response()->json(['ok' => true]);
    }

    protected function recordClick(ExchangeOffice $office, string $type): void
    {
        $office->increment('clicks');
        $office->exchangeClicks()->create(['event_type' => $type]);
    }
}
