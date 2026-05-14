<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LeadController extends Controller
{
    public function index(Request $request): View
    {
        $query = Lead::query();

        if ($status = $request->query('status')) {
            $query->where('status', $status);
        }
        if ($landing = $request->query('landing')) {
            $query->where('landing_page', 'like', "%{$landing}%");
        }
        if ($from = $request->query('from')) {
            $query->whereDate('created_at', '>=', $from);
        }
        if ($to = $request->query('to')) {
            $query->whereDate('created_at', '<=', $to);
        }

        $leads = $query->latest()->paginate(25)->withQueryString();

        $counts = [
            'all' => Lead::count(),
            'yeni' => Lead::byStatus('yeni')->count(),
            'aranildi' => Lead::byStatus('aranildi')->count(),
            'sonuclandi' => Lead::byStatus('sonuclandi')->count(),
            'iptal' => Lead::byStatus('iptal')->count(),
        ];

        return view('admin.leads.index', compact('leads', 'counts'));
    }

    public function show(Lead $lead): View
    {
        $lead->load('eventLog');

        return view('admin.leads.show', compact('lead'));
    }

    public function updateStatus(Request $request, Lead $lead): RedirectResponse
    {
        $request->validate([
            'status' => ['required', 'in:'.implode(',', Lead::STATUSES)],
        ]);
        $lead->update(['status' => $request->input('status')]);

        return back()->with('success', 'Durum güncellendi.');
    }

    public function updateNotes(Request $request, Lead $lead): RedirectResponse
    {
        $request->validate(['notes' => ['nullable', 'string', 'max:5000']]);
        $lead->update(['notes' => $request->input('notes')]);

        return back()->with('success', 'Not güncellendi.');
    }

    public function destroy(Lead $lead): RedirectResponse
    {
        $lead->delete();

        return redirect()->route('admin.leads.index')->with('success', 'Lead silindi.');
    }
}
