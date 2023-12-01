<x-app-layout>
    <div class="flex flex-col md:flex-row">
        @include('includes.sidebar-left')
        <section class="w-full">
            <div id="main" class="main-content p-3 h-full flex-1 bg-gray-100 mt-12 md:mt-2 pb-24 md:pb-5">
                <div class="flex align-items-center justify-between mb-4">
                    <h1 class="h3 mb-0 text-gray-800">Downloads basket</h1>
                    <a href="{{ route('download.basket.export.to.csv') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-center block flex self-center">
                        Export to CSV and empty basket
                    </a>
                </div>
                <div class="row">
                    @include( 'common.session_messages' )
                    <div class="bg-white border-transparent rounded-lg shadow-xl">
                        <div class="bg-gradient-to-b from-gray-300 to-gray-100 uppercase text-gray-800 border-b-2 border-gray-300 rounded-tl-lg rounded-tr-lg p-2">
                            <h2 class="font-bold uppercase text-gray-600 text-lg">Downloads basket</h2>
                        </div>
                        <div class="p-5">
                        @if( $downloads_basket )
                            <table class="w-full">
                                <thead>
                                <tr>
                                    <th class="p-2" style="width: 5%;" scope="col">ID</th>
                                    <th class="p-2" style="width: 15%;" scope="col">Name</th>
                                    <th class="p-2" style="width: 60%;" scope="col">Timesheets</th>
                                    <th class="p-2" style="width: 20%;" scope="col">Contacts</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach( $downloads_basket as $download_id => $data )
                                <tr style="border-bottom: 1px solid #d9d9d9">
                                    <td valign="top" class="p-2">{{ $download_id }}</td>
                                    <td valign="top" class="p-2">{{ $data->name }}</td>
                                    <td valign="top" class="p-2">
                                        
                                        @if( $data->timesheets )
                                            <table class="w-full">
                                                <thead>
                                                <tr>
                                                    <th style="width: 70%" valign="top" class="p-2" scope="col">Description</th>
                                                    <th style="width: 15%;" valign="top" class="p-2" scope="col">Date</th>
                                                    <th style="width: 15%;" valign="top" class="p-2" scope="col">Time</th>
                                                </tr>
                                                </thead>
                                                @foreach( $data->timesheets as $timesheet )
                                                <tr>
                                                    <td valign="top" class="p-2">{{ $timesheet->text }}</td>
                                                    <td valign="top" class="p-2">{{ $timesheet->date }}</td>
                                                    <td valign="top" class="p-2">{{ $timesheet->duration }}</td>
                                                </tr>
                                                @endforeach
                                                @if( $data->timesheetsTotalTime )
                                                <tr>
                                                    <td valign="top" class="p-2"></td>
                                                    <td valign="top" class="p-2"><strong>Total</strong></td>
                                                    <td valign="top" class="p-2">{{ $data->timesheetsTotalTime }}</td>
                                                </tr>
                                                @endif
                                            </table>
                                        @endif
                                    </td>
                                    <td valign="top" class="p-2">
                                        @if( $data->contacts )
                                            @foreach( $data->contacts as $contact )
                                                {{ $contact->name_1 }} {{ $contact->name_2 }}
                                            @endforeach
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                                </tbody>
                            </table>
                        @else
                        There are no entries in your downloads basket
                        @endif
                        </div>
                    </div>

                </div>
            </div>
        </section>
    </div>
</x-app-layout>
