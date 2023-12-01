<x-app-layout>
    <div class="flex flex-col md:flex-row">
        @include('includes.sidebar-left')
        <section class="w-full">
            <div id="main" class="main-content p-3 h-full flex-1 bg-gray-100 mt-12 md:mt-2 pb-24 md:pb-5">
                <div class="flex align-items-center justify-between mb-4">
                    <h1 class="h3 mb-0 text-gray-800">Users</h1>
                    <a href="{{ route('users.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-center block flex self-center">
                        Add user
                    </a>
                </div>
                
                <div class="row">
                    @include( 'common.session_messages' )
                    <div class="bg-white border-transparent rounded-lg shadow-xl">
                        <div class="bg-gradient-to-b from-gray-300 to-gray-100 uppercase text-gray-800 border-b-2 border-gray-300 rounded-tl-lg rounded-tr-lg p-2">
                            <h2 class="font-bold uppercase text-gray-600 text-lg">Users List</h2>
                        </div>
                        <div class="p-5">
                            @if( count( $items ) )
                                <table class="table w-full data">
                                    <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Name</th>
                                        <th scope="col">E-mail</th>
                                        <th scope="col">Registered</th>
                                        <th scope="col">Actions</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach( $items as $idx => $item )
                                    <tr>
                                        <td>{{ $idx + 1 }}</td>
                                        <td>{{ $item->name }}</td>
                                        <td>{{ $item->email }}</td>
                                        <td>{{ date( 'd.m.Y H:i:s', strtotime( $item->created_at ) ) }}</td>
                                        <td class="flex">
                                            <a class="mr-2 text-blue-600" href="{{ route( 'users.edit', [ 'user' => $item ] ) }}" title="Edit">
                                                <i class="fa-solid fa-pen"></i>
                                            </a>
                                            @if( $item->id != Auth::user()->id )
                                            {!! Form::model( $item, [ 'url' => route( 'users.delete', ['user' => $item->id] ), 'method' => 'post' ] ) !!}
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" onclick="return confirm('Are you sure?')" class="text-red-600" title="Delete"><i class="fa-solid fa-trash"></i></button>
                                            {!! Form::close() !!}
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                                @else
                                <p>No users added. <a href="{{ route( 'users.create') }}">Add a user</a></p>
                            @endif
                        </div>
                    </div>

                </div>
            </div>
        </section>
    </div>
</x-app-layout>
