@extends('layouts.app')
@section('content')
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>All projects</title>
    </head>
    <body>
        <div class="container">
            @if(count((array)$projects) > 0)
                <table class="table table-striped table-hover">
                    <thead class="thead-dark">
                        <th>ID</th>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Price</th>
                        <th>Jobs done</th>
                        <th>Start date</th>
                        <th>End date</th>
                        <th></th>
                        <th></th>
                    </thead>
                    <tbody>
                        @foreach ($projects as $project)
                        <tr>
                            <td><div>{{$project->id}}</div></td>
                            <td><div>{{$project->name}}</div></td>
                            <td><div>{{$project->description}}</div></td>
                            <td><div>{{$project->price}}</div></td>
                            <td><div>{{$project->jobs_done}}</div></td>
                            <td><div>{{$project->start_date}}</div></td>
                            <td><div>{{$project->end_date}}</div></td>
                            <td><a class="link" href="{{ route('editproject', $project->id) }}">Edit</a></td>
                            @if($project->leader == Auth::user()->getId())
                            <td><a class="link" href="{{ route('users', $project->id) }}">Edit users</a></td>
                            @endif
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="card">
                    <a class="btn btn-primary" href="{{ route('newproject') }}">Create project.</a>                      
                </div>
            @else 
                No projects.
                <div class="card">
                    <a class="btn btn-primary" href="{{ route('newproject') }}">Create project.</a>                      
                </div>
            @endif
        </div>
    </body>
</html>
@endsection