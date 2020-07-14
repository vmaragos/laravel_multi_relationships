<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Project;


use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProjectController extends Controller
{
    public function show(Project $project)
    {
        // return view('events.show', [
        //     'project' => $project,
        //     'event_user' => $event_user
        // ]);
    }

    public function index()
    {
        $projects = Project::latest()->get();
        return view('projects', [
            // 'project' => $project,
            'projects' => $projects]);
    }

    public function store()
    {
        request()->validate([

            
            'title' => ['required', 'min:3', 'max:20'],
        ]);

        

        $project = new Project();

        $project->title = request('title');

        $project->save();

        return redirect('/projects');
    }

    public function destroy(Project $project)
    {
        // dd(Auth::user()->is_admin);
        if (Auth::user()->is_admin == 1)
        {
            $project->delete();
        }
        $project_title = $project->title;
        return redirect('/projects')->with('success', 'The Project with title "'. $project_title .'" has been deleted successfully.');
    }
}
