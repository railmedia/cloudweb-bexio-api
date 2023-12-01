<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Task;
use App\Models\Contact;

class ProjectsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view( 'projects.list' )
                ->with( 'items', Project::all() );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $project = new Project;

        return view('projects.create_edit')
            ->with('item', $project);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate_fields = [
            'title'  => 'required|max:255'
        ];

        $validated = $request->validate( $validate_fields );

        $project = new Project;
        $project->title = $request->title;
        $project->total_hours = $request->total_hours;

        if( ! $project->save() || ! $validated ) {
            return redirect()->back()->withErrors(['msg' => 'There was an error saving the project. Please try again.']);
        }
        
        return redirect( route('projects.list') )->with( 'message-success', 'Project saved' );
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, Project $project)
    {
        return view('projects.create_edit')
            ->with('item', $project);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validate_fields = [
            'title'  => 'required|max:255'
        ];

        $validated = $request->validate( $validate_fields );

        $project = Project::where('id', $id)->first();
        $project->title = $request->title;
        $project->total_hours = $request->total_hours;

        if( ! $project->save() || ! $validated ) {
            return redirect()->back()->withErrors(['msg' => 'There was an error saving the project. Please try again.']);
        }
        
        return redirect( route('projects.list') )->with( 'message-success', 'Project saved!' );

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $tasks = Task::where('project_id', $id)->get();
        
        if( ! empty( $tasks ) ) {
            foreach( $tasks as $task ) {
                $task::destroy( $task->id );
            }
        }

        $contacts = Contact::where('project_id', $id)->get();

        if( ! empty( $contacts ) ) {
            foreach( $contacts as $contact ) {
                $contact::destroy( $contact->id );
            }
        }
        
        Project::destroy( $id );

        return redirect( route('projects.list') )->with( 'message-success', 'Project deleted' );

    }

    public function getProjectTasks( Request $request ) {
        
        $project = Project::where( 'id', $request->project_id )->first();
        
        return $project ? view( 'projects.tasks' )->with( 'tasks', $project->tasks )->with( 'project_id', $request->project_id ) : null;

    }

    public function getProjectContacts( Request $request ) {
        
        $project = Project::where( 'id', $request->project_id )->first();
        
        return $project ? view( 'projects.contacts' )->with( 'contacts', $project->contacts )->with( 'project_id', $request->project_id ) : null;

    }

    public function syncProject( Request $request ) {

        $syncProject = $request->project;

        $project = new Project;
        $project->bexio_id   = $syncProject['id'];
        $project->uuid       = $syncProject['uuid'];
        $project->number     = $syncProject['nr'];
        $project->title      = $syncProject['name'];
        $project->start_date = $syncProject['start_date'];
        $project->save();

        $projectId = $project->id;

        if( isset( $syncProject['timesheets'] ) && $syncProject['timesheets'] ) {
            foreach( $syncProject['timesheets'] as $idx => $timesheet ) {
                $task = new Task;
                $task->bexio_id      = $timesheet['id'];
                $task->bexio_user_id = $timesheet['user_id'];
                $task->project_id    = $projectId;
                $task->description   = $timesheet['text'];
                $task->time_spent    = $timesheet['duration'];
                $task->minutes       = isset( $syncProject['timesheetsDuration'] ) && isset( $syncProject['timesheetsDuration'][ $idx ] ) ? $syncProject['timesheetsDuration'][ $idx ] : null;
                $task->date          = $timesheet['date'];
                $task->save();
            }
        }

        if( isset( $syncProject['contacts'] ) && $syncProject['contacts'] ) {
            foreach( $syncProject['contacts'] as $projectContact ) {
                $contact = new Contact;
                $contact->bexio_id = $projectContact['id'];
                $contact->project_id = $projectId;
                $contact->name = $projectContact['name_1'];
                if( $projectContact['name_2'] ) {
                    $contact->name = $projectContact['name_1'] . ' ' . $projectContact['name_2'];
                }
                $address = $projectContact['address'];
                if( $projectContact['postcode'] ) {
                    $address .= ', ' . $projectContact['postcode'];
                }
                if( $projectContact['city'] ) {
                    $address .= ' ' . $projectContact['city'];
                }
                $contact->address = $address;
                $phone = $projectContact['phone_fixed'];
                if( ! $phone ) {
                    $phone = $projectContact['phone_fixed_second'];
                }
                if( ! $phone ) {
                    $phone = $projectContact['phone_mobile'];
                }
                $contact->phone = $phone;
                $contact->website = $projectContact['url'];
                $contact->save();
            }
        }

    }
}
