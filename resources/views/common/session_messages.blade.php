@if (session('message'))
<div class="row mb-3">
    <div class="bg-white border-transparent rounded-lg shadow-xl">
        <div class="bg-gradient-to-b from-gray-300 to-gray-100 uppercase text-gray-800 border-b-2 border-gray-300 rounded-tl-lg rounded-tr-lg p-2">
            <h2 class="font-bold uppercase text-gray-600">Warnings</h2>
        </div>
        <div class="p-5">
            <div class="alert alert-warning">
                <ul style="margin:0">
                    <li>{{ session('message') }}</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endif

@if (session('message-success'))
<div class="row mb-3">
    <div class="bg-white border-transparent rounded-lg shadow-xl">
        <!-- <div class="bg-gradient-to-b from-gray-300 to-gray-100 uppercase text-gray-800 border-b-2 border-gray-300 rounded-tl-lg rounded-tr-lg p-2">
            <h2 class="font-bold uppercase text-gray-600">Success</h2>
        </div> -->
        <div class="p-5">
            <div class="alert alert-success">
                <ul style="margin:0">
                    <li>{{ session('message-success') }}</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endif