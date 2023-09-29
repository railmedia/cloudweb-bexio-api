<x-app-layout>
    <div class="flex flex-col md:flex-row">
        @include('includes.sidebar-left')
        <section class="w-full">
            <div id="main" class="main-content p-3 h-full flex-1 bg-gray-100 mt-12 md:mt-2 pb-24 md:pb-5">
                <div class="flex align-items-center justify-between mb-4">
                    @if( $item->id )
                        <h1 class="h3 mb-0 text-gray-800">Edit user</h1>
                    @else
                        <h1 class="h3 mb-0 text-gray-800">Create user</h1>
                    @endif
                </div>
                @php //dd( $user_scopes ); @endphp
                <div class="row mb-3">
                    @include( 'common.errors' )
                    @include( 'common.session_messages' )
                    <div class="col-xl-12 col-lg-7">
                        <div class="bg-white border-transparent rounded-lg shadow-xl">
                            <div class="bg-gradient-to-b from-gray-300 to-gray-100 uppercase text-gray-800 border-b-2 border-gray-300 rounded-tl-lg rounded-tr-lg p-2">
                                <h2 class="font-bold uppercase text-gray-600 text-lg">
                                    User
                                </h2>
                            </div>
                            <div class="p-5">
                                @if( empty( $item->id ) )
                                    {!! Form::model( $item, [ 'url' => route( 'users.store' ), 'class' => 'create-edit-user', 'method' => 'post', 'files' => true ] ) !!}
                                @else
                                    {!! Form::model( $item, [ 'url' => route( 'users.update', ['user' => $item ] ), 'class' => 'create-edit-user', 'method' => 'put', 'files' => true ] ) !!}
                                @endif
                                <div class="mb-3">
                                    {!! Form::label( 'name', 'Name*', [ 'class' => 'w-full' ] ) !!}
                                    {!! Form::text( 'name', old( 'name', $item->name ?? '' ), [ 'class' => 'form-control w-full' ] ) !!}
                                </div>
                                <div class="mb-3">
                                    {!! Form::label( 'email', 'E-mail*', [ 'class' => 'w-full' ] ) !!}
                                    {!! Form::text( 'email', old( 'email', $item->email ?? '' ), [ 'class' => 'form-control w-full' ] ) !!}
                                </div>
                                @if( $item->id )
                                <a href="#!" class="text-blue-600" id="change-user-pass">Change user's password</a>
                                <div class="mb-3 password" style="display: none;">
                                    <p>If you do not wish to change the user's password, please leave both fields empty.</p>
                                    <button id="generate-password" type="button" class="bg-blue-500 hover:bg-blue-700 font-bold py-2 px-4 rounded text-center block text-white">Generate password</button>
                                    <p style="margin: 10px 0 0 0;" id="generated-password"></p>
                                </div>
                                @else
                                <button id="generate-password" type="button" class="bg-blue-500 hover:bg-blue-700 font-bold py-2 px-4 rounded text-center block text-white">Generate password</button>
                                @endif
                                <p style="margin: 10px 0 0 0;" id="generated-password"></p>
                                <div class="mb-3 password" style="@if( $item->id ) display: none; @endif">
                                    {!! Form::label( 'password', 'Password*', [ 'class' => 'w-full' ] ) !!}
                                    {!! Form::password( 'password', [ 'class' => 'form-control w-full' ] ) !!}
                                </div>
                                <div class="mb-3 confirm-password" style="@if( $item->id ) display: none; @endif">
                                    {!! Form::label( 'password_confirmation', 'Confirm password*', [ 'class' => 'w-full' ] ) !!}
                                    {!! Form::password( 'password_confirmation', [ 'class' => 'form-control w-full' ] ) !!}
                                </div>
                                <hr/>
                                <div class="mb-3 scopes">
                                    <h3 class="font-bold uppercase text-gray-600 text-lg mt-2 mb-2">
                                        Scopes
                                    </h3>
                                    <ul class="flex flex-wrap">
                                    @foreach( $scopes as $scope => $label )
                                        <li class="basis-2/4">
                                            @if( $scope === 'openid' || $scope === 'profile' || $scope === 'contact_show' || $scope === 'offline_access' )
                                            {{ Form::checkbox( 'scopes[]', $scope, true, ['id' => 'scope_' . $scope, 'onclick' => 'return false;'] ) }}
                                            @elseif( isset( $user_scopes[ $scope ] ) )
                                            {{ Form::checkbox( 'scopes[]', $scope, true, ['id' => 'scope_' . $scope] ) }}
                                            @else
                                            {{ Form::checkbox( 'scopes[]', $scope, false, ['id' => 'scope_' . $scope] ) }} 
                                            @endif
                                            {!! Form::label( 'scope_' . $scope, $label ) !!}
                                        </li>
                                    @endforeach
                                    </ul>
                                </div>
                                <!-- Add checkbox - notify user -->
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