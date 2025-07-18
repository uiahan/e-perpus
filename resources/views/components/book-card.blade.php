<div class="card w-full">
    <figure>
        <img src="{{ asset('storage/' . $item->cover) }}" class="md:h-72 h-62 object-cover w-full" alt="{{ $item->title }}" />
    </figure>
    <div class="card-body bg-neutral-950">
        <h2 class="card-title text-white">
            {{ $item->title }}
            <div class="badge badge-secondary">{{ $item->year }}</div>
        </h2>
        <p class="text-gray-200 line-clamp-2">{{ $item->description }}</p>
        <p class="text-gray-200">Pengarang: {{ $item->author }}</p>
        <div class="card-actions justify-end flex-wrap">
            @foreach ($item->categories as $category)
                <div class="badge badge-outline badge-warning">{{ $category->category_name }}</div>
            @endforeach
        </div>
    </div>
</div>
