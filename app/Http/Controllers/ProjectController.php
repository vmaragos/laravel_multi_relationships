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

        $users = DB::table('users')->get();

        return view('projects.show', [
            'project' => $project,
            'users' => $users

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
            
            'title' => ['required', 'min:3', 'max:20', 'unique:projects'],
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
            $project->members()->detach(); //remove all relationships with this project, from the project_user (pivot) table
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
        // var_dump($username);
        // die;
        $exists = DB::table('users')->whereName($username)->count() > 0;
        
        if ($exists)
        {
            $user = User::where('name', $username) -> first();
            $user_id = $user->id;            
            $is_member = DB::table('project_user')->whereProjectId($project->id)->whereUserId($user_id)->count() > 0;

            if (!($is_member))
            {
                $project->members()->attach($user_id);
                return redirect('/projects/'.$project->id)->with('success', 'The user "'.$user->name.'" was successfully added to the "'.$project->title.'" project.');
            }

            else
            {
                return redirect('/projects/'.$project->id)->with('failure', 'The user "'.$user->name.'" is already a member of the "'.$project->title.'" project.');
                die;
            }

        }

        else
        {
            return redirect('/projects/'.$project->id)->with('failure', 'The user "'.$username['username'].'" does not exist.');
            die;
        }

        // return redirect('/projects/'.$project->id);
    }
    
    // public function remove_user(Project $project)
    // {


    //     // $username = request(['username']);        

    //     // $user = User::where('name', $username) -> first();
    //     // $user_id = $user->id;

    //     $user_id = $project->members->pluck('id');
    //     $project->members()->detach($user_id);
    //     return redirect('/projects/'.$project->id)->with('success', 'All members have been removed from the "'.$project->title.'" project.');
        
    //     // $user_id = $project->members()->id;
    //     // dd($user_id);
    //     // $project->members()->detach($user_id);
    //     // return redirect('/projects/'.$project->id);
    // }


    public function remove_user(Project $project, User $member)
    {
        $project->members()->detach($member->id);
        return redirect('/projects/'.$project->id);
    }
}
