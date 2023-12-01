<x-app-layout>
    <div class="flex flex-col md:flex-row">
        @include('includes.sidebar-left')
        <section class="w-full">
            <div id="main" class="main-content p-3 h-full flex-1 bg-gray-100 mt-12 md:mt-2 pb-24 md:pb-5">
                <div class="flex align-items-center justify-between mb-4">
                    <h1 class="h3 mb-0 text-gray-800">Projects</h1>
                    <a href="{{ route('projects.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-center block flex self-center">
                        Add project
                    </a>
                </div>
                <div class="flex flex-row flex-wrap flex-grow mt-2">
                    @include( 'common.session_messages' )
                    <div class="bg-white border-transparent rounded-lg shadow-xl w-full">
                        <!-- <div class="bg-gradient-to-b from-gray-300 to-gray-100 uppercase text-gray-800 border-b-2 border-gray-300 rounded-tl-lg rounded-tr-lg p-2">
                            <h class="font-bold uppercase text-gray-600">Projects</h>
                        </div> -->
                        <div class="p-5">
                            @include( 'common.modal' )
                            @if ( count( $items ) )
                            <table class="table w-full data">
                                <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Title</th>
                                    <th scope="col">Total Assigned Hours</th>
                                    <th scope="col">Spent Hours</th>
                                    <th scope="col">Start Date</th>
                                    <th scope="col">Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach( $items as $idx => $item )
                                <tr>
                                    <td>{{ $idx + 1 }}</td>
                                    <td>{{ $item->title }}</td>
                                    <td>{{ $item->total_hours ?? 0 }}</td>
                                    <td>{{ $item->spent_hours ?? 0 }}</td>
                                    <td>{{ date( 'd.m.Y', strtotime( $item->start_date ) ) }}</td>
                                    <td class="flex">
                                        <ul class="flex">
                                            <li>
                                                <a class="mr-2 text-blue-600" href="{{ route( 'projects.edit', [ 'project' => $item ] ) }}" title="Edit">
                                                    <i class="fa-solid fa-pen"></i>
                                                </a>
                                            </li>
                                            <li>
                                                <a 
                                                    class="mr-2 cursor-pointer load-modal-resource" 
                                                    title="Project timesheets" 
                                                    data-modal-target="static-modal" 
                                                    data-modal-toggle="static-modal" 
                                                    data-type="tasks" 
                                                    data-title="Tasks" 
                                                    data-projectid="{{ $item->id }}"
                                                    data-projecttitle="{{ $item->title }}"
                                                >
                                                    <i class="fa-solid fa-list-check text-green-600"></i>
                                                </a>
                                                <div class="hidden" id="project-{{ $item->id }}-tasks">
                                                    Loading tasks
                                                </div>
                                            </li>
                                            <li>
                                                <a 
                                                    class="mr-2 cursor-pointer load-modal-resource" 
                                                    title="Project contacts" 
                                                    data-modal-target="static-modal" 
                                                    data-modal-toggle="static-modal" 
                                                    data-type="contacts" 
                                                    data-title="Contacts" 
                                                    data-projectid="{{ $item->id }}"
                                                    data-projecttitle="{{ $item->title }}"
                                                >
                                                    <i class="fa-solid fa-address-book text-green-600"></i>
                                                </a>
                                                <div class="hidden" id="project-{{ $item->id }}-contacts">
                                                    Loading contacts
                                                </div>
                                            </li>
                                            <li>
                                                {!! Form::model( $item, [ 'url' => route( 'projects.delete', ['project' => $item->id] ), 'method' => 'post' ] ) !!}
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" onclick="return confirm('Are you sure?')" class="text-red-600" title="Delete"><i class="fa-solid fa-trash"></i></button>
                                                {!! Form::close() !!}
                                            </li>
                                    </td>
                                </tr>
                                @endforeach
                                </tbody>
                            </table>
                            @else
                                No projects yet. Go to Bexio menu item and fetch some projects to begin with
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</x-app-layout>