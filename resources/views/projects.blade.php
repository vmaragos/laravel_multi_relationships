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

    @if ($message = Session::get('success'))
    <div class="alert_message" id="success_message">
        <p>{{$message}}</p>
    </div>
    @endif

    <div id="project-container-header">
        <form method="POST" action="{{url('/projects/')}}">
            @csrf
            <input name="title" id="title" type="text" value="{{old('title')}}"></input>
            @if ($errors->has('title'))
                <p id="error-msg" style="color:red; font-size:1.5vh;">{{ $errors->first('title') }}</p>
            @endif
            <button type="submit" value="Add">Create Project</button>
        </form>
    </div>
    <div id="project-container-list">
        <ul id="events-list">
        @foreach ($projects as $project)
            <li>
                <a href="{{url('/projects/'.$project->id)}}">
                    <div id="project-tile">
                        <div id="project-title-box">
                            <span>{{$project->id}}: {{$project->title}}</span>
                            @if ( Auth::user()->is_admin == 1 )
                            <form class="delete_project_form" method="POST" action="{{url('/projects/'.$project->id)}}">
                            @csrf
                            @method('DELETE')
                                <button type="submit" value="Delete">Delete</button>
                            </form>
                            @endif
                        </div>                        
                    </div>
                </a>        
            </li>
            @endforeach
            
        </ul>
    </div>

    <script>
        $(document).ready(function(){
            $('.delete_project_form').on('submit', function(){
                if(confirm(`This action will permanently delete the Project. Do you wish to continue ?`))
                {
                    return true;
                }
                else
                {
                    return false;
                }
            });
        });
    </script>

    <script>    
        $(window).on("load",function(){
            $(".alert_message").delay(4000).fadeOut("slow");
        });
    </script>
</body>
</html>