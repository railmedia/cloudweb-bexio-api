<table class="table w-full data">
    <thead>
    <tr>
        <th scope="col">#</th>
        <th scope="col">Description</th>
        <th scope="col">Time Spent (hours)</th>
        <th scope="col">Date</th>
        <th scope="col">Actions</th>
    </tr>
    </thead>
    <tbody>
    @php $totalTime = 0; @endphp
    @foreach( $tasks as $idx => $task )
        @php $totalTime += $task->minutes @endphp
    <tr>
        <td scope="col" valign="top">{{ $idx + 1 }}</td>
        <td scope="col" valign="top">{{ $task->description }}</td>
        <td scope="col" valign="top">{{ $task->time_spent }}</td>
        <td scope="col" valign="top">{{ $task->date }}</td>
        <td scope="col" valign="top">
            <a data-resourceid="{{ $task->id }}" class="mr-2 text-blue-600 project-edit-resource" data-type="tasks" href="#!" title="Edit">
                <i class="fa-solid fa-pen"></i>
            </a>
            <a data-resourceid="{{ $task->id }}" data-projectid="{{ $project_id }}" class="mr-2 text-red-600 project-delete-resource" data-type="tasks" href="#!" title="Delete">
                <i class="fa-solid fa-trash"></i>
            </a>
        </td>
    </tr>
    @endforeach
    <tr>
        <td scope="col"></td>
        <td scope="col" class="text-right">Total: </td>
        <td scope="col">@php echo floor( $totalTime / 60 ) . ':' . ( sprintf( "%02d", $totalTime % 60 ) ); @endphp</td>
        <td scope="col"></td>
        <td scope="col"></td>
    </tr>
    </tbody>
</table>
<div class="text-center">
    <button id="project-add-resource" data-type="tasks" data-projectid="{{ $project_id }}" type="button" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Add task</button>
    <div class="hidden tasks-form mt-2 mb-2 text-left">
        <div class="mb-3">
            {!! Form::label( 'description', 'Description*', [ 'class' => 'w-full' ] ) !!}
            {!! Form::textarea( 'description', '', [ 'class' => 'form-control w-full' ] ) !!}
        </div>
        <div class="mb-3">
            {!! Form::label( 'time_spent', 'Time spent*', [ 'class' => 'w-full block' ] ) !!}
            {!! Form::number( 'time_spent_hours', '', [ 'id' => 'time_spent_hours', 'class' => 'form-control', 'style' => 'width: 49.5%', 'placeholder' => 'Hours' ] ) !!}
            {!! Form::number( 'time_spent_minutes', '', [ 'id' => 'time_spent_minutes', 'class' => 'form-control', 'style' => 'width: 49.5%', 'placeholder' => 'Minutes' ] ) !!}
        </div>
        <div class="mb-3">
            {!! Form::label( 'date', 'Date*', [ 'class' => 'w-full block' ] ) !!}
            {!! Form::text( 'date', '', [ 'id' => 'project-task-date', 'class' => 'project-task-date form-control w-full' ] ) !!}
        </div>
        <div class="mb-3 text-center">
            {!! Form::hidden( 'task_id', '', [ 'id' => 'project-tasks-id', 'class' => 'project-tasks-id' ] ) !!}
            {!! Form::hidden( 'action', 'add', [ 'id' => 'project-tasks-action', 'class' => 'project-tasks-action' ] ) !!}
            <div id="tasks-errors"></div>
            <button id="project-save-resource" data-type="tasks" data-projectid="{{ $project_id }}" type="button" class="text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">Save task</button>
        </div>
    </div>
</div>