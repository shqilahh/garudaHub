<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    // Daftar proyek + pencarian
    public function index(Request $request)
    {
        $projects = Project::with('client')
            ->withCount('bids')
            ->when($request->q, fn ($query, $s) => $query->where('title', 'like', "%{$s}%"))
            ->latest()
            ->get();

        return view('projects.index', compact('projects'));
    }

    // Form buat proyek baru
    public function create()
    {
        return view('projects.form', [
            'project' => new Project,
            'clients' => User::where('role', 'client')->get(),
        ]);
    }

    // Simpan proyek baru
    public function store(Request $request)
    {
        $data = $request->validate([
            'client_id'    => 'required|exists:users,id',
            'title'        => 'required|max:150',
            'description'  => 'required',
            'category'     => 'required|max:50',
            'budget'       => 'required|integer|min:0',
            'bid_deadline' => 'required|date|after:now',
        ]);

        $project = Project::create($data);

        return redirect()->route('projects.show', $project)->with('ok', 'Proyek dibuat.');
    }

    // Detail proyek
    public function show(Project $project)
    {
        $project->load('client', 'bids.developer');

        return view('projects.show', [
            'project'    => $project,
            'developers' => User::where('role', 'developer')->get(),
        ]);
    }

    // Form edit
    public function edit(Project $project)
    {
        return view('projects.form', [
            'project' => $project,
            'clients' => User::where('role', 'client')->get(),
        ]);
    }

    // Simpan perubahan
    public function update(Request $request, Project $project)
    {
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

    // Hapus
    public function destroy(Project $project)
    {
        $project->delete();

        return redirect()->route('projects.index')->with('ok', 'Proyek dihapus.');
    }
}
