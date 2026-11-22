<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index(Request $request)
    {
        $projects = Project::with('client')
            ->withCount('bids')
            ->when($request->q, fn ($query, $s) => $query->where('title', 'like', "%{$s}%"))
            ->latest()
            ->get();

        return view('projects.index', compact('projects'));
    }

    public function create()
    {
        return view('projects.form', ['project' => new Project]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'        => 'required|max:150',
            'description'  => 'required',
            'category'     => 'required|max:50',
            'budget'       => 'required|integer|min:0',
            'bid_deadline' => 'required|date|after:now',
        ]);

        // Pemilik diambil dari user yang login, BUKAN dari input form
        $project = Project::create($data + ['client_id' => auth()->id()]);

        return redirect()->route('projects.show', $project)->with('ok', 'Proyek dibuat.');
    }

    public function show(Project $project)
    {
        $project->load('client', 'bids.developer');

        return view('projects.show', compact('project'));
    }

    public function edit(Project $project)
    {
        $this->ensureOwner($project);

        return view('projects.form', compact('project'));
    }

    public function update(Request $request, Project $project)
    {
        $this->ensureOwner($project);

        $data = $request->validate([
            'title'        => 'required|max:150',
            'description'  => 'required',
            'category'     => 'required|max:50',
            'budget'       => 'required|integer|min:0',
            'bid_deadline' => 'required|date',
        ]);

        $project->update($data);

        return redirect()->route('projects.show', $project)->with('ok', 'Proyek diperbarui.');
    }

    public function destroy(Project $project)
    {
        $this->ensureOwner($project);

        $project->delete();

        return redirect()->route('projects.index')->with('ok', 'Proyek dihapus.');
    }

    // Hanya pemilik proyek yang boleh mengubah/menghapus
    private function ensureOwner(Project $project): void
    {
        abort_unless($project->client_id === auth()->id(), 403);
    }
}
