@if ($errors->any())
<div class="row mb-3">
    <div class="bg-white border-transparent rounded-lg shadow-xl">
        <div class="bg-gradient-to-b from-gray-300 to-gray-100 uppercase text-gray-800 border-b-2 border-gray-300 rounded-tl-lg rounded-tr-lg p-2">
            <h2 class="font-bold uppercase text-gray-600">Errors</h2>
        </div>
        <div class="p-5">
            <div class="alert alert-danger">
                <ul style="margin:0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>
@endif