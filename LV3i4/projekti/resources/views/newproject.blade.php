@extends('layouts.app')

@section('content')
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>New project</title>
    </head>
    <body>
        <div class="container"> 
            <div class="justify-content-center">
                <div class="card">
                    <div class="card-header">New project</div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('project') }}">
                        @csrf
                            <div class="form-group row">
                                <label for="name">Name: </label>
                                <input class="form-control" type="text" name="name" id="name" required>
                            </div>
                            <div class="form-group row">
                                <label for="description">Description: </label>
                                <textarea class="form-control" rows=5 name="description" id="description" required></textarea>
                            </div>
                            <div class="form-group row">
                                <label for="price">Price: </label>
                                <input class="form-control" type="text" name="price" id="price" required>
                            </div>
                            <div class="form-group row">
                                <label for="jobsdone">Jobs done: </label>
                                <textarea class="form-control" rows=5 name="jobsdone" id="jobsdone" required></textarea>
                            </div>
                            <div class="form-group row">
                                <label for="startdate">Start date: </label>
                                <input class="form-control" type="date" name="startdate" id="Start date" required>
                            </div>
                            <div class="form-group row">
                                <label for="enddate">End date: </label>
                                <input class="form-control" type="date" name="enddate" id="enddate" required>
                            </div>
                            <div class="form-group row mb-0">
                                <div class="col-md-8 offset-md-4">
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
@endsection
