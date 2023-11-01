<x-app-layout>
    <div class="flex flex-col md:flex-row">
        @include('includes.sidebar-left')
        <section class="w-full">
            <div id="main" class="main-content p-3 h-full flex-1 bg-gray-100 mt-12 md:mt-2 pb-24 md:pb-5">
                <h1>Projects</h1>
                <div class="flex flex-row flex-wrap flex-grow mt-2">
                    <div class="bg-white border-transparent rounded-lg shadow-xl w-full">
                        <div class="bg-gradient-to-b from-gray-300 to-gray-100 uppercase text-gray-800 border-b-2 border-gray-300 rounded-tl-lg rounded-tr-lg p-2">
                            <h class="font-bold uppercase text-gray-600">Projects</h>
                        </div>
                        <div class="p-5">
                            @if ( count( $projects ) )

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