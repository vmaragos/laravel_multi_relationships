<!DOCTYPE html>

<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link href="https://fonts.googleapis.com/css?family=Montserrat&display=swap" rel="stylesheet"> 
    <link href="{{ asset('css/default.css') }}" rel="stylesheet" type="text/css" media="all" />
    <link href="/css/fonts.css" rel="stylesheet" type="text/css" media="all" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    
    
</head>
<body>
    <div id="project-container-header">

    </div>
    <div id="project-container-list">
        <span>Project ID: {{$project->id}}</span><br>
        <span>Project Title: {{$project->title}}</span><br>
        <span>Creation Date: {{ \Carbon\Carbon::parse($project->updated_at)->format('d  M Y - g:i a')}}</span><br>
        <span>Creator Name: {{$project->creator->name}}</span><br>
        <span>Project members:</span>
        <ul>
            @foreach($project->members as $member)
            <li>{{$member->name}} <span>(User ID:{{$member->id}})</span>
                <span>(Assigned on: {{ \Carbon\Carbon::parse($member->pivot->created_at)->format('d  M Y')}})</span>
                
                <!-- if connected user is admin, show "Remove user" button -->
                @if ( Auth::user()->is_admin == 1 )
                <form class="delete_project_form" method="POST" action="{{url('/projects/'.$project->id.'/remove_user/'.$member->id)}}">
                    @csrf
                    @method('DELETE')
                    <button type="submit" value="Delete">Remove</button>
                </form><br>
                @endif
            </li>
            @endforeach
        </ul>



        <!-- if connected user is admin, show "Add member" section -->
        @if ( Auth::user()->is_admin == 1 )
        <form method="POST" action="{{url('/projects/'.$project->id.'/add_user')}}">
            @csrf
            <select name="username" id="title" type="text" value="{{old('username')}}" autofocus>
                <option value="default" selected="selected" disabled hidden>Select User</option>
                @foreach($users as $user)
                    <option value="{{ $user->name }}" title="{{ $user->email }}">{{ $user->name }}</option>
                @endforeach
            </select>
            @if ($errors->has('username'))
                <p id="error-msg" style="color:red; font-size:1.5vh;">{{ $errors->first('username') }}</p>
            @endif
            <button type="submit" value="Add">Add Member</button>
        </form>
        @endif

        @if ($message = Session::get('failure'))
        <div class="alert_message" id="failure_message">
            <p style="color: red;">{{$message}}</p>
        </div>
        @endif

        @if ($message = Session::get('success'))
        <div class="alert_message" id="success_message">
            <p style="color: green;">{{$message}}</p>
        </div>
        @endif

        <script>    
        $(window).on("load",function(){
            $(".alert_message").delay(4000).fadeOut("slow");
        });
    </script>

    </div>
</body>
</html>