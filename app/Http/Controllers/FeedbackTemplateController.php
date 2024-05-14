<?php

namespace App\Http\Controllers;

use App\Models\FeedbackTemplate;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FeedbackTemplateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $feedback_templates = FeedbackTemplate::with('created_user')->orderBy('date', 'desc')->get();
        return view('admins.setting.feedback_template.feedback_template_list', compact('feedback_templates'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admins.setting.feedback_template.feedback_template_create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'feedback_template_question' => 'required|string'
        ]);
        $validated['created_user_id'] = Auth::user()->id;
        $validated['date'] = Carbon::now()->format('Y-m-d');
        FeedbackTemplate::create($validated);
        return redirect()->route('feedback_template.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $feedback_template = FeedbackTemplate::find($id);
        if (!$feedback_template) {
            return redirect()->back();
        }
        return view('admins.setting.feedback_template.feedback_template_edit', compact('feedback_template'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $feedback_template = FeedbackTemplate::find($id);
        if (!$feedback_template) {
            return redirect()->back();
        }
        $validated = $request->validate([
            'feedback_template_question' => 'required|string'
        ]);
        $validated['created_user_id'] = Auth::user()->id;
        $validated['date'] = Carbon::now()->format('Y-m-d');
        $feedback_template->update($validated);
        return redirect()->route('feedback_template.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
