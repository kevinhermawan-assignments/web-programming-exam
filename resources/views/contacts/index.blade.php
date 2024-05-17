<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
    <title>CREAM - Customer Relationship Management</title>
</head>

<body class="bg-gray-100" x-data="{ newContactModal: false, editContactModal: false, confirmDeleteModal: false, deleteUrl: '', editContact: null }">
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
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold">Contacts</h1>
                <button @click="newContactModal = true" class="rounded-md bg-green-600 px-3 py-2 text-sm font-semibold text-white border hover:bg-green-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-green-600">Add New Contact</button>
            </div>

            <div class="mt-4">
                @if (count($contacts) > 0)
                @foreach ($contacts as $contact)
                <div class="bg-white rounded-lg px-6 py-4 mb-4 flex justify-between items-center border border-gray-200">
                    <a class="cursor-pointer" href="{{ route('contacts.show', $contact) }}">
                        <h2 class="text-xl font-semibold hover:text-green-500">{{ $contact->name }}</h2>
                        <div class="text-sm text-gray-600 mt-1">
                            <span>{{ $contact->company }} / {{ $contact->email }} / {{ $contact->phone }}</span>
                        </div>
                    </a>
                    <div class="flex space-x-4">
                        <button @click="editContactModal = true; editContact = {{ $contact }};" class="rounded-md bg-blue-50 px-3 py-2 text-sm font-semibold text-blue-600 border hover:bg-blue-100">
                            Edit
                        </button>
                        <button @click="confirmDeleteModal = true; deleteUrl = '{{ route('contacts.destroy', $contact) }}';" class="rounded-md bg-red-50 px-3 py-2 text-sm font-semibold text-red-600 border hover:bg-red-100">
                            Delete
                        </button>
                    </div>
                </div>
                @endforeach
                @else
                <div class="bg-white rounded-lg px-6 py-4 mb-4 flex justify-center items-center border border-gray-200">
                    <p class="text-gray-600">There are no contacts available, you can add one by clicking the button above!</p>
                </div>
                @endif
            </div>

            <!-- Modal for adding new contact -->
            <div x-show="newContactModal" style="background-color: rgba(0,0,0,0.5);" class="fixed inset-0 flex items-center justify-center">
                <div class="bg-white p-4 rounded-lg shadow-lg w-full max-w-lg mx-4" @click.away="newContactModal = false">
                    <h3 class="text-lg font-bold mb-4">New Contact</h3>
                    <form action="{{ route('contacts.store') }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label for="name" class="block text-sm font-medium leading-6 text-gray-900">Name</label>
                            <div class="mt-2">
                                <input name="name" type="text" required class="block w-full rounded-md border-0 px-2 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-green-600 sm:text-sm sm:leading-6">
                            </div>
                        </div>
                        <div>
                            <label for="company" class="block text-sm font-medium leading-6 text-gray-900">Company</label>
                            <div class="mt-2">
                                <input name="company" type="text" required class="block w-full rounded-md border-0 px-2 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-green-600 sm:text-sm sm:leading-6">
                            </div>
                        </div>
                        <div>
                            <label for="email" class="block text-sm font-medium leading-6 text-gray-900">Email Address</label>
                            <div class="mt-2">
                                <input name="email" type="email" required class="block w-full rounded-md border-0 px-2 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-green-600 sm:text-sm sm:leading-6">
                            </div>
                        </div>
                        <div>
                            <label for="phone" class="block text-sm font-medium leading-6 text-gray-900">Phone Number</label>
                            <div class="mt-2">
                                <input name="phone" type="number" required class="block w-full rounded-md border-0 px-2 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-green-600 sm:text-sm sm:leading-6">
                            </div>
                        </div>
                        <div class="flex justify-end space-x-4">
                            <button type="button" @click="newContactModal = false" class="inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:w-auto">Cancel</button>
                            <button type="submit" class="inline-flex w-full justify-center rounded-md bg-green-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-green-500 sm:w-auto">Save Contact</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Modal for editing contact -->
            <div x-show="editContactModal" style="background-color: rgba(0,0,0,0.5);" class="fixed inset-0 flex items-center justify-center">
                <div class="bg-white p-4 rounded-lg shadow-lg w-full max-w-lg mx-4" @click.away="editContactModal = false">
                    <h3 class="text-lg font-bold mb-4">Edit Contact</h3>
                    <form :action="'/' + editContact.id" method="POST" class="space-y-4">
                        @csrf
                        @method('PUT')
                        <div>
                            <label for="name" class="block text-sm font-medium leading-6 text-gray-900">Name</label>
                            <div class="mt-2">
                                <input name="name" type="text" x-model="editContact.name" required class="block w-full rounded-md border-0 px-2 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-green-600 sm:text-sm sm:leading-6">
                            </div>
                        </div>
                        <div>
                            <label for="company" class="block text-sm font-medium leading-6 text-gray-900">Company</label>
                            <div class="mt-2">
                                <input name="company" type="text" x-model="editContact.company" required class="block w-full rounded-md border-0 px-2 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-green-600 sm:text-sm sm:leading-6">
                            </div>
                        </div>
                        <div>
                            <label for="email" class="block text-sm font-medium leading-6 text-gray-900">Email Address</label>
                            <div class="mt-2">
                                <input name="email" type="email" x-model="editContact.email" required class="block w-full rounded-md border-0 px-2 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-green-600 sm:text-sm sm:leading-6">
                            </div>
                        </div>
                        <div>
                            <label for="phone" class="block text-sm font-medium leading-6 text-gray-900">Phone Number</label>
                            <div class="mt-2">
                                <input name="phone" type="number" x-model="editContact.phone" required class="block w-full rounded-md border-0 px-2 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-green-600 sm:text-sm sm:leading-6">
                            </div>
                        </div>
                        <div class="flex justify-end space-x-4">
                            <button type="button" @click="editContactModal = false" class="inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:w-auto">Cancel</button>
                            <button type="submit" class="inline-flex w-full justify-center rounded-md bg-green-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-green-500 sm:w-auto">Update Contact</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Delete confirmation modal -->
            <div x-show="confirmDeleteModal" style="background-color: rgba(0,0,0,0.5);" class="fixed inset-0 flex items-center justify-center">
                <div class="bg-white p-4 rounded-lg shadow-lg w-full max-w-lg mx-4" @click.away="confirmDeleteModal = false">
                    <h3 class="text-lg font-bold mb-4">Confirm Deletion</h3>
                    <p class="text-gray-600 text-sm">Are you sure you want to delet this contact?</p>
                    <div class="flex justify-end space-x-4 mt-4">
                        <button type="button" @click="confirmDeleteModal = false" class="inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:w-auto">Cancel</button>
                        <form :action="deleteUrl" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex w-full justify-center rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 sm:w-auto">Delete Contact</button>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
</body>

</html>