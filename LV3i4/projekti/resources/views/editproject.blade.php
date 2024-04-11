@extends('layouts.app')

@section('content')
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Edit project</title>
        <style>

        </style>
    </head>
    <body>
        <div class="container"> 
            <div class="justify-content-center">
                <div class="card">
                    <div class="card-header">Edit project {{$project->name}}</div>
                    <div class="card-body">
                    <table><tr>
                        <form method="POST" action="{{ route('saveproject', $project->id) }}">
                        @csrf
                        <input type="hidden" name="_method" value="PUT">
                        @if($project->leader == Auth::user()->getId())
                            <div class="form-group row">
                                <label for="name">Name: </label>
                                <input class="form-control" type="text" name="name" id="name" value="{{$project->name}}" required>
                            </div>
                            <div class="form-group row">
                                <label for="description">Description: </label>
                                <textarea  class="form-control" rows=5 name="description" id="description" required>{{$project->description}}</textarea>
                            </div>
                            <div class="form-group row">
                                <label for="price">Price: </label>
                                <input  class="form-control" type="text" name="price" id="price" value="{{$project->price}}"  required>
                            </div>
                            <div class="form-group row">
                        @endif
                                <label for="jobsdone">Jobs done: </label>
                                <textarea  class="form-control" rows=5 name="jobsdone" id="jobsdone" required> {{$project->jobs_done}}</textarea>
                            </div>
                        @if($project->leader == Auth::user()->getId())
                            <div class="form-group row">
                                <label for="startdate">Start date: </label>
                                <input  class="form-control" type="text" name="startdate" id="Start date" value="{{$project->start_date}}"  required>
                            </div>
                            <div class="form-group row">
                                <label for="enddate">End date: </label>
                                <input  class="form-control" type="text" name="enddate" id="enddate" value="{{$project->end_date}}"  required>
                            </div>
                        @endif
                            <button type="submit" class="btn btn-primary" style="margin-right:50px">Save</button> 
                        </form>
                        <tr>
                        @if($project->leader == Auth::user()->getId())
                        <form action="{{{ route('deleteproject', $project->id)}}}" method="POST">
                        @csrf
                        @method('put')
                            <button class="btn btn-danger" type="submit" style="margin-right:50px">Remove</button>
                        </form>
                        
                        <form action="{{{ route('projects')}}}" method="GET">
                        @csrf
                            <button class="btn btn-danger" type="submit" style="margin-right:50px">Cancel</button>
                        </form>
</tr>
</tr>
                        @endif
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
@endsection