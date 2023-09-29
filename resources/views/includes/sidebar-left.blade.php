        <nav aria-label="alternative nav">
            <div class="bg-gray-800 shadow-xl h-20 fixed bottom-0 mt-12 md:relative md:h-screen z-10 w-full md:w-48 content-center">
                <div class="md:mt-12 md:w-48 md:fixed md:left-0 md:top-0 content-center md:content-start text-left justify-between">
                    <ul class="list-reset flex flex-row md:flex-col pt-3 md:py-3 px-1 md:px-2 text-center md:text-left">
                        @if( ! $access_token )
                        <li class="mr-3 flex-1">
                            <p class="text-white">You are currently logged out from Bexio</p>
                            <a href="{{ route('bexio.auth') }}" class="w-full bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-center block">
                                Connect to Bexio
                            </a>
                        </li>
                        @else
                        <li class="mr-3 flex-1">
                            <p class="text-white">You are currently logged in to Bexio</p>
                            <a href="{{ route('bexio.auth') }}" class="w-full bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-center block">
                                Refresh Bexio connection
                            </a>
                        </li>
                        @endif
                        <li class="flex">
                            <a href="{{ route('dashboard') }}" class="w-full py-1 md:py-3 pl-1 align-middle text-white no-underline hover:text-white border-b-2 border-gray-800 hover:border-pink-500">
                            <i class="fa-solid fa-house pr-0 md:pr-3"></i><span class="pb-1 md:pb-0 text-xs md:text-base text-gray-400 md:text-gray-200 block md:inline-block">Home</span>
                            </a>
                        </li>
                        <li class="flex">
                            <a href="{{ route('bexio.main') }}" class="w-full py-1 md:py-3 pl-1 align-middle text-white no-underline hover:text-white border-b-2 border-gray-800 hover:border-pink-500">
                                <i class="fas fa-tasks pr-0 md:pr-3"></i><span class="pb-1 md:pb-0 text-xs md:text-base text-gray-400 md:text-gray-200 block md:inline-block">Bexio</span>
                            </a>
                        </li>
                        <li class="flex">
                            <a href="{{ route('users.list') }}" class="w-full py-1 md:py-3 pl-1 align-middle text-white no-underline hover:text-white border-b-2 border-gray-800 hover:border-purple-500">
                                <i class="fa fa-user pr-0 md:pr-3"></i><span class="pb-1 md:pb-0 text-xs md:text-base text-gray-400 md:text-gray-200 block md:inline-block">Users</span>
                            </a>
                        </li>
                        <li class="flex">
                            <a href="{{ route('download.basket') }}" class="w-full py-1 md:py-3 pl-1 align-middle text-white no-underline hover:text-white border-b-2 border-gray-800 hover:border-purple-500">
                            <i class="fa-solid fa-download md:pr-3"></i><span class="pb-1 md:pb-0 text-xs md:text-base text-gray-400 md:text-gray-200 block md:inline-block">Downloads basket</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>