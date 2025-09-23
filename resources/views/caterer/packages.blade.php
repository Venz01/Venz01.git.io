<x-app-layout>
    <div class="p-6">
        <h2 class="text-xl font-bold mb-4">Create a Package</h2>

        <form action="{{ route('packages.store') }}" method="POST">
            @csrf
            <input type="text" name="name" placeholder="Package Name" class="border p-2 w-full mb-2">
            <textarea name="description" placeholder="Package Description" class="border p-2 w-full mb-2"></textarea>
            <input type="number" name="price" placeholder="Total Price" class="border p-2 w-full mb-2">

            <h3 class="font-semibold mt-3">Select Menu Items</h3>
            @foreach($categories as $category)
                <h4 class="mt-2">{{ $category->name }}</h4>
                @foreach($category->items as $item)
                    <label class="block">
                        <input type="checkbox" name="menu_items[]" value="{{ $item->id }}">
                        {{ $item->name }} - ₱{{ $item->price }}
                    </label>
                @endforeach
            @endforeach

            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded mt-3">Save Package</button>
        </form>
    </div>
</x-app-layout>
