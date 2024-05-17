<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
    <title>CREAM</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body class="bg-gray-100" x-data="{ newInteractionModal: false, newSaleModal: false, confirmDeleteInteractionModal: false, confirmDeleteSaleModal: false, invoiceModal: false, deleteUrl: '' }">
    <div class="bg-white border-b border-gray-200 px-4 py-2">
        <div class="container mx-auto">
            <a href="{{ route('contacts.index') }}">
                <h1 class="text-xl font-bold flex items-center">CREAM</h1>
            </a>
            <span class="text-gray-600 text-sm">Customer Relationship Management</span>
        </div>
    </div>

    <div class="px-4 py-8">
        <div class="container mx-auto">
            <div class="mt-4 grid md:grid-cols-2 gap-0 sm:gap-4 sm:grid-cols-1">
                <!-- Most Common Interaction -->
                <div class="bg-white rounded-lg px-6 py-4 mb-4 flex justify-between items-center border border-gray-200">
                    <h1 class="text-xl font-semibold">🔄 Most Frequent Interaction</h1>
                    <div class="text-gray-600">
                        @php
                        $grouped = $interactions->groupBy('type');
                        $mostCommon = $grouped->sortByDesc(function($group) {
                        return $group->count();
                        })->first();
                        @endphp
                        <p class="capitalize">{{ str_replace('_', ' ', $mostCommon->first()->type) }}</p>
                    </div>
                </div>

                <!-- Highest Sale -->
                <div class="bg-white rounded-lg px-6 py-4 mb-4 flex justify-between items-center border border-gray-200">
                    <h1 class="text-xl font-semibold">🔝 Top Sale</h1>
                    <div class="text-gray-600">
                        @php
                        $highestSale = $sales->sortByDesc('amount')->first();
                        @endphp
                        <p class="capitalize">Rp. {{ number_format($highestSale->amount, 0, '', '.') }}</p>
                    </div>
                </div>

                <!-- Total Interactions -->
                <div class="bg-white rounded-lg px-6 py-4 mb-4 flex justify-between items-center border border-gray-200">
                    <h1 class="text-xl font-semibold">🔢 Total Interactions</h1>
                    <span class="text-gray-600">{{ count($interactions) }}</span>
                </div>

                <!-- Total Sales -->
                <div class="bg-white rounded-lg px-6 py-4 mb-4 flex justify-between items-center border border-gray-200">
                    <h1 class="text-xl font-semibold space-x-2">💰 Total Revenue</h1>
                    <span class="text-gray-600">Rp. {{ number_format($sales->sum('amount'), 0, '', '.') }}</span>
                </div>
            </div>


            <div class="flex justify-between items-center my-8">
                <h1 class="text-2xl font-bold">Interactions</h1>
                <button @click="newInteractionModal = true" class="rounded-md bg-green-600 px-3 py-2 text-sm font-semibold text-white border hover:bg-green-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-green-600">Add New Interaction</button>
            </div>

            <div class="mt-4">
                @if (count($interactions) > 0)
                @foreach ($interactions as $interaction)
                <div class="bg-white rounded-lg px-6 py-4 mb-4 flex justify-between items-center border border-gray-200">
                    <div>
                        <h2 class="text-xl font-semibold hover:text-green-500 capitalize">{{ str_replace('_', ' ', $interaction->type) }}</h2>
                        <div class="text-sm text-gray-600 mt-1">
                            <span>{{ $interaction->notes }}</span>
                        </div>
                    </div>
                    <div class="flex space-x-4">
                        <button @click="confirmDeleteInteractionModal = true; deleteUrl = '{{ route('interactions.destroy', [$contact->id, $interaction->id]) }}';" class="rounded-md bg-red-50 px-3 py-2 text-sm font-semibold text-red-600 border hover:bg-red-100">
                            Delete
                        </button>
                    </div>
                </div>
                @endforeach
                @else
                <div class="bg-white rounded-lg px-6 py-4 mb-4 flex justify-center items-center border border-gray-200">
                    <p class="text-gray-600">There are no interactions available, you can add one by clicking the button above!</p>
                </div>
                @endif
            </div>

            <div class="flex justify-between items-center my-8">
                <h1 class="text-2xl font-bold">Sales</h1>
                <div class="flex space-x-4">
                    <button @click="newSaleModal = true" class="rounded-md bg-green-600 px-3 py-2 text-sm font-semibold text-white border hover:bg-green-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-green-600">Add New Sale</button>
                    <button @click="invoiceModal = true" class="rounded-md bg-blue-600 px-3 py-2 text-sm font-semibold text-white border hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600">Show Invoice</button>
                </div>
            </div>

            <div class="mt-4">
                @if (count($sales) > 0)
                @foreach ($sales as $sale)
                <div class="bg-white rounded-lg px-6 py-4 mb-4 flex justify-between items-center border border-gray-200">
                    <div>
                        <h2 class="text-xl font-semibold hover:text-green-500 capitalize">{{ date_format($sale->created_at, 'M d, Y') }}</h2>
                        <div class="text-sm text-gray-600 mt-1">
                            <span>Rp. {{ number_format($sale->amount, 0, '', '.') }}</span>
                        </div>
                    </div>
                    <div class="flex space-x-4">
                        <button @click="confirmDeleteSaleModal = true; deleteUrl = '{{ route('sales.destroy', [$contact->id, $sale->id]) }}';" class="rounded-md bg-red-50 px-3 py-2 text-sm font-semibold text-red-600 border hover:bg-red-100">
                            Delete
                        </button>
                    </div>
                </div>
                @endforeach
                @else
                <div class="bg-white rounded-lg px-6 py-4 mb-4 flex justify-center items-center border border-gray-200">
                    <p class="text-gray-600">There are no sales available, you can add one by clicking the button above!</p>
                </div>
                @endif
            </div>

            <!-- Modal for adding new interaction -->
            <div x-show="newInteractionModal" style="background-color: rgba(0,0,0,0.5);" class="fixed inset-0 flex items-center justify-center">
                <div class="bg-white p-4 rounded-lg shadow-lg w-full max-w-lg mx-4" @click.away="newInteractionModal = false">
                    <h3 class="text-lg font-bold mb-4">New Interaction</h3>
                    <form action="{{ route('interactions.store', $contact) }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label for="type" class="block text-sm font-medium leading-6 text-gray-900">Type</label>
                            <div class="mt-2">
                                <select name="type" class="block w-full rounded-md border-0 px-2 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-green-600 sm:text-sm sm:leading-6">
                                    <option value="offline_meeting">Offline Meeting</option>
                                    <option value="online_meeting">Online Meeting</option>
                                    <option value="phone_call">Phone Call</option>
                                    <option value="email">Email</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                        </div>
                        <div>
                            <label for="notes" class="block text-sm font-medium leading-6 text-gray-900">Notes</label>
                            <div class="mt-2">
                                <textarea name="notes" rows="3" required class="block w-full rounded-md border-0 px-2 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-green-600 sm:text-sm sm:leading-6"></textarea>
                            </div>
                        </div>
                        <div class="flex justify-end space-x-4">
                            <button type="button" @click="newInteractionModal = false" class="inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:w-auto">Cancel</button>
                            <button type="submit" class="inline-flex w-full justify-center rounded-md bg-green-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-green-500 sm:w-auto">Save Interaction</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Modal for adding new sale -->
            <div x-show="newSaleModal" style="background-color: rgba(0,0,0,0.5);" class="fixed inset-0 flex items-center justify-center">
                <div class="bg-white p-4 rounded-lg shadow-lg w-full max-w-lg mx-4" @click.away="newSaleModal = false">
                    <h3 class="text-lg font-bold mb-4">New Sale</h3>
                    <form action="{{ route('sales.store', $contact) }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label for="amount" class="block text-sm font-medium leading-6 text-gray-900">Amount</label>
                            <div class="mt-2">
                                <input name="amount" type="number" required class="block w-full rounded-md border-0 px-2 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-green-600 sm:text-sm sm:leading-6">
                            </div>
                        </div>
                        <div class="flex justify-end space-x-4">
                            <button type="button" @click="newSaleModal = false" class="inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:w-auto">Cancel</button>
                            <button type="submit" class="inline-flex w-full justify-center rounded-md bg-green-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-green-500 sm:w-auto">Save Sale</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Modal for invoice -->
            <div x-show="invoiceModal" style="background-color: rgba(0,0,0,0.5);" class="fixed inset-0 flex items-center justify-center">
                <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-lg mx-4" @click.away="invoiceModal = false">
                    <h3 class="text-lg font-bold mb-4 text-center">Invoice</h3>
                    <div class="space-y-4">
                        <div>
                            <h4 class="text-md font-medium text-gray-900">Sales Details</h4>
                            <ul class="mt-2 space-y-2 text-sm text-gray-600">
                                @foreach ($sales as $sale)
                                <li class="flex justify-between border-b border-gray-200 py-4">
                                    <span class="font-semibold">{{ $sale->created_at->format('M d, Y') }}</span>
                                    <span class="text-green-600">Rp. {{ number_format($sale->amount, 0, '', '.') }}</span>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="mt-6 flex justify-between items-center">
                            <h4 class="text-md font-medium text-gray-900">Total Amount</h4>
                            <p class="text-lg font-bold text-gray-900">Rp. {{ number_format($sales->sum('amount'), 0, '', '.') }}</p>
                        </div>
                        <div class="flex justify-end space-x-4 mt-12">
                            <button type="button" @click="invoiceModal = false" class="inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:w-auto">Close</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Delete confirmation modal for interactions -->
            <div x-show="confirmDeleteInteractionModal" style="background-color: rgba(0,0,0,0.5);" class="fixed inset-0 flex items-center justify-center">
                <div class="bg-white p-4 rounded-lg shadow-lg w-full max-w-lg mx-4" @click.away="confirmDeleteInteractionModal = false">
                    <h3 class="text-lg font-bold mb-4">Confirm Deletion</h3>
                    <p class="text-gray-600 text-sm">Are you sure you want to delete this interaction?</p>
                    <div class="flex justify-end space-x-4 mt-4">
                        <button type="button" @click="confirmDeleteInteractionModal = false" class="inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:w-auto">Cancel</button>
                        <form :action="deleteUrl" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex w-full justify-center rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 sm:w-auto">Delete</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Delete confirmation modal for sales -->
            <div x-show="confirmDeleteSaleModal" style="background-color: rgba(0,0,0,0.5);" class="fixed inset-0 flex items-center justify-center">
                <div class="bg-white p-4 rounded-lg shadow-lg w-full max-w-lg mx-4" @click.away="confirmDeleteSaleModal = false">
                    <h3 class="text-lg font-bold mb-4">Confirm Deletion</h3>
                    <p class="text-gray-600 text-sm">Are you sure you want to delete this sale?</p>
                    <div class="flex justify-end space-x-4 mt-4">
                        <button type="button" @click="confirmDeleteSaleModal = false" class="inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:w-auto">Cancel</button>
                        <form :action="deleteUrl" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex w-full justify-center rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 sm:w-auto">Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>