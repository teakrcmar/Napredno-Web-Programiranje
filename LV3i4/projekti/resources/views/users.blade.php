@extends('layouts.app')
@section('content')
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Choose a user</title>
    </head>
    <body>
        <div class="container">    
            @if(count($users) > 0)
            <table class="table table-striped table-hover">
                <thead class="thead-dark">
                    <th>ID</th>
                    <th>Name</th>
                    <th>E-mail</th>
                    <th></th>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                    <tr>
                        <td><div>{{$user->id}}</div></td>
                        <td><div>{{$user->name}}</div></td>
                        <td><div>{{$user->email}}</div></td>
                        <td>
                            <form id="form" action="{{ route('adduser')}}" method="POST">
                                @csrf
                                @if($user->id != auth()->user()->id) 
                                @if(count(DB::select("select project_id, user_id from project_users where project_id='$project_id' and user_id='$user->id'"))==0)
                                <input type=number value="{{$project_id}}" name="project_id" id="project_id" style="display:none">
                                <input type=number value="{{$user->id}}" name="user_id" id="user_id" style="display:none">
                                <button class="btn btn-primary" type="submit">Choose</button>
                                @endif
                                @endif
                            </form>
                            @if($user->id != auth()->user()->id && !count(DB::select("select project_id, user_id from project_users where project_id='$project_id' and user_id='$user->id'"))==0)
                            <form action="{{{ route('removeuser')}}}" method="POST">
                                @csrf
                                <input type=number value="{{$project_id}}" name="project_id" id="project_id" style="display:none">
                                <input type=number value="{{$user->id}}" name="user_id" id="user_id" style="display:none">
                                <button class="btn btn-danger" type="submit">Remove</button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </body>
</html>
@endif

@endsection  