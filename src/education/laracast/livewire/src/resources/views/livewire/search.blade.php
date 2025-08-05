<div class="w-100">
    <form>
        <div class="mt-2">
            <input type="text" class="p-4 w-full border rounded-md bg-gray-700 text-white"
                wire:model.live.debounce="searchText" placeholder="{{ $placeholder }}">
        </div>
    </form>
    <livewire:search-results :results="$results" :show="!empty($searchText)">

    </livewire:search-results>
</div>
