<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Project;
use App\User;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProjectController extends Controller
{
    public function show(Project $project)
    {

        // $user = User::find(1);
        // $user->projects()->attach(1);
        // dd($user->projects);

        return view('projects.show', [
            'project' => $project,
            // 'project_user' => $project_user,
        ]);


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
        $project->user_id = Auth::user()->id;

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

    public function add_user(Project $project)
    {
        request()->validate([
            'username' => ['required', 'min:3', 'max:20'],
        ]);

        $username = request(['username']);        

        $user = User::where('name', $username) -> first();
        $user_id = $user->id;
        //find a way to prevent error when user is not found
        

        $project->members()->attach($user_id);
        return redirect('/projects/'.$project->id);
    }
    
    public function remove_user(Project $project)
    {

        
        $user_id = $project->members()->id;
        dd($user_id);
        $project->members()->detach($user_id);
        return redirect('/projects/'.$project->id);
    }
}
