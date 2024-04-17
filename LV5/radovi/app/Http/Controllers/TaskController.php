<?php

namespace App\Http\Controllers;

use App\Models\TaskApplication;
use App;
use App\Models\Task;
use Illuminate\Http\Request;
use View;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = Task::all();
        return view('tasks.index', compact('tasks'));
    }

    public function create()
    {
        $locale = App::getLocale();
        $data = [
            'name' => __('tasks.name'),
            'name_en' => __('tasks.name_en'),
            'description' => __('tasks.description'),
            'study_type' => __('tasks.study_type'),
        ];
        return View::make('tasks.create', compact('locale', 'data'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'name_en' => 'required|max:255',
            'description' => 'required',
            'study_type' => 'required|in:stručni,preddiplomski,diplomski',
        ]);

        if (auth()->user()->hasRole('nastavnik')) {
            $task = new Task;
            $task->name = $validatedData['name'];
            $task->name_en = $validatedData['name_en'];
            $task->description = $validatedData['description'];
            $task->study_type = $validatedData['study_type'];
            $task->user_id = auth()->id();
            $task->save();

            return redirect()->route('tasks.index')->with('success', 'Task created successfully.');
        } else {
            abort(403, 'Unauthorized action.');
        }
    }

    public function edit(Task $task)
    {
        return view('tasks.edit', compact('task'));
    }

    public function update(Request $request, Task $task)
    {
        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'name_en' => 'required|max:255',
            'description' => 'required',
            'study_type' => 'required|in:stručni,preddiplomski,diplomski',
        ]);

        $task->name = $validatedData['name'];
        $task->name_en = $validatedData['name_en'];
        $task->description = $validatedData['description'];
        $task->study_type = $validatedData['study_type'];
        $task->save();

        return redirect()->route('tasks.index')->with('success', 'Task updated successfully.');
    }

    public function destroy(Task $task)
    {
        $task->delete();
        return redirect()->route('tasks.index')->with('success', 'Task deleted successfully.');
    }

    public function showSignupPage()
    {
        $tasks = Task::where('status', 'open')->get();
        return view('tasks.signup', ['tasks' => $tasks]);
    }

    public function showApplications(Task $task)
    {
        $applications = $task->applications;

        return view('tasks.applications', compact('applications', 'task'));
    }

    public function approveApplication(Task $task, TaskApplication $application)
    {
        $application->update(['status' => 'approved']);

        $task->assigned_to = $application->user_id;
        $task->save();

        return redirect()->back();
    }

}