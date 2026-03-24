<?php

namespace App\Http\Controllers;

use App\Models\ExchangeClick;
use App\Models\ExchangeOffice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class ExchangeDirectoryController extends Controller
{
    public function index(): View
    {
        $offices = Cache::remember('exchanges.index', 60, function () {
            return ExchangeOffice::query()
                ->where('status', ExchangeOffice::STATUS_ACTIVE)
                ->whereNull('blocked_at')
                ->with('exchangeRates')
                ->orderBy('name')
                ->get()
                ->sort(function (ExchangeOffice $a, ExchangeOffice $b): int {
                    $aHighlight = in_array('highlight', $a->features ?? [], true);
                    $bHighlight = in_array('highlight', $b->features ?? [], true);
                    if ($aHighlight !== $bHighlight) {
                        return $bHighlight <=> $aHighlight;
                    }

                    return strcasecmp((string) $a->name, (string) $b->name);
                })
                ->values();
        });

        return view('exchanges.index', compact('offices'));
    }

    public function show(ExchangeOffice $exchangeOffice): View
    {
        if ($exchangeOffice->status !== ExchangeOffice::STATUS_ACTIVE || $exchangeOffice->isBlocked()) {
            abort(404);
        }

        $this->recordClickAfterResponse($exchangeOffice, ExchangeClick::TYPE_VIEW);
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
        $this->recordClickAfterResponse($exchangeOffice, $type);

        return response()->json(['ok' => true]);
    }

    /** Defer click recording until after response is sent for faster page load. */
    protected function recordClickAfterResponse(ExchangeOffice $office, string $type): void
    {
        $officeId = $office->id;
        app()->terminating(function () use ($officeId, $type) {
            ExchangeOffice::whereKey($officeId)->increment('clicks');
            ExchangeClick::create(['exchange_office_id' => $officeId, 'event_type' => $type]);
        });
    }
}
