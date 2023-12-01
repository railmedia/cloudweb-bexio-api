<x-app-layout>
    <div class="flex flex-col md:flex-row">
        @include('includes.sidebar-left')
        <section class="w-full">
            <div id="main" class="main-content p-3 h-full flex-1 bg-gray-100 mt-12 md:mt-2 pb-24 md:pb-5">
                <div class="flex align-items-center justify-between mb-4">
                    @if( $item->id )
                        <h1 class="h3 mb-0 text-gray-800">Edit project</h1>
                    @else
                        <h1 class="h3 mb-0 text-gray-800">Create project</h1>
                    @endif
                </div>
                @php //dd( $user_scopes ); @endphp
                <div class="row mb-3">
                    @include( 'common.errors' )
                    @include( 'common.session_messages' )
                    <div class="col-xl-12 col-lg-7">
                        <div class="bg-white border-transparent rounded-lg shadow-xl">
                            <!-- <div class="bg-gradient-to-b from-gray-300 to-gray-100 uppercase text-gray-800 border-b-2 border-gray-300 rounded-tl-lg rounded-tr-lg p-2">
                                <h2 class="font-bold uppercase text-gray-600 text-lg">
                                    Project
                                </h2>
                            </div> -->
                            <div class="p-5">
                                @if( empty( $item->id ) )
                                    {!! Form::model( $item, [ 'url' => route( 'projects.store' ), 'class' => 'create-edit-project', 'method' => 'post', 'files' => true ] ) !!}
                                @else
                                    {!! Form::model( $item, [ 'url' => route( 'projects.update', ['project' => $item ] ), 'class' => 'create-edit-user', 'method' => 'put', 'files' => true ] ) !!}
                                @endif
                                <div class="mb-3">
                                    {!! Form::label( 'title', 'Title*', [ 'class' => 'w-full' ] ) !!}
                                    {!! Form::text( 'title', old( 'title', $item->title ?? '' ), [ 'class' => 'form-control w-full' ] ) !!}
                                </div>
                                <div class="mb-3">
                                    {!! Form::label( 'total_hours', 'Total assigned hours', [ 'class' => 'w-full' ] ) !!}
                                    {!! Form::number( 'total_hours', old( 'total_hours', $item->total_hours ?? '' ), [ 'class' => 'form-control w-full' ] ) !!}
                                </div>
                                <div class="mb-3">
                                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 font-bold py-2 px-4 rounded text-center block text-white">
                                        Save
                                    </button>
                                </div>
                                {!! Form::close() !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</x-app-layout>