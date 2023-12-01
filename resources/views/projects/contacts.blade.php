<table class="table w-full data">
    <thead>
    <tr>
        <th scope="col">#</th>
        <th scope="col">Name</th>
        <th scope="col">Address</th>
        <th scope="col">Email</th>
        <th scope="col">Phone</th>
        <th scope="col">Website</th>
        <th scope="col">Actions</th>
    </tr>
    </thead>
    <tbody>
    @foreach( $contacts as $idx => $contact )
    <tr>
        <td scope="col" valign="top">{{ $idx + 1 }}</td>
        <td scope="col" valign="top">{{ $contact->name }}</td>
        <td scope="col" valign="top">{{ $contact->contact_address }}</td>
        <td scope="col" valign="top">{{ $contact->email }}</td>
        <td scope="col" valign="top">{{ $contact->phone }}</td>
        <td scope="col" valign="top">{{ $contact->website }}</td>
        <td scope="col" valign="top">
            <a data-resourceid="{{ $contact->id }}" class="mr-2 text-blue-600 project-edit-resource" data-type="contacts" href="#!" title="Edit">
                <i class="fa-solid fa-pen"></i>
            </a>
            <a data-resourceid="{{ $contact->id }}" data-projectid="{{ $project_id }}" class="mr-2 text-red-600 project-delete-resource" data-type="contacts" href="#!" title="Delete">
                <i class="fa-solid fa-trash"></i>
            </a>
        </td>
    </tr>
    @endforeach
    </tbody>
</table>
<div class="text-center">
    <button id="project-add-resource" data-type="contacts" data-projectid="{{ $project_id }}" type="button" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Add contact</button>
    <div class="hidden contacts-form mt-2 mb-2 text-left">
        <div class="mb-3">
            {!! Form::label( 'name', 'Name*', [ 'class' => 'w-full' ] ) !!}
            {!! Form::text( 'name', '', [ 'class' => 'form-control w-full' ] ) !!}
        </div>
        <div class="mb-3">
            {!! Form::label( 'address', 'Address*', [ 'class' => 'w-full' ] ) !!}
            {!! Form::text( 'address', '', [ 'class' => 'form-control w-full' ] ) !!}
        </div>
        <div class="mb-3">
            {!! Form::label( 'postcode', 'Postcode', [ 'class' => 'w-full' ] ) !!}
            {!! Form::text( 'postcode', '', [ 'class' => 'form-control w-full' ] ) !!}
        </div>
        <div class="mb-3">
            {!! Form::label( 'city', 'City', [ 'class' => 'w-full' ] ) !!}
            {!! Form::text( 'city', '', [ 'class' => 'form-control w-full' ] ) !!}
        </div>
        <div class="mb-3">
            {!! Form::label( 'email', 'E-mail', [ 'class' => 'w-full' ] ) !!}
            {!! Form::email( 'email', '', [ 'class' => 'form-control w-full' ] ) !!}
        </div>
        <div class="mb-3">
            {!! Form::label( 'phone', 'Phone', [ 'class' => 'w-full' ] ) !!}
            {!! Form::text( 'phone', '', [ 'class' => 'form-control w-full' ] ) !!}
        </div>
        <div class="mb-3">
            {!! Form::label( 'website', 'Website', [ 'class' => 'w-full' ] ) !!}
            {!! Form::text( 'website', '', [ 'class' => 'form-control w-full' ] ) !!}
        </div>
        <div class="mb-3 text-center">
            {!! Form::hidden( 'contact_id', '', [ 'id' => 'project-contacts-id', 'class' => 'project-contacts-id' ] ) !!}
            {!! Form::hidden( 'action', 'add', [ 'id' => 'project-contacts-action', 'class' => 'project-contacts-action' ] ) !!}
            <div id="contacts-errors"></div>
            <button id="project-save-resource" data-type="contacts" data-projectid="{{ $project_id }}" type="button" class="text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">Save contact</button>
        </div>
    </div>
</div>