@extends('layouts.app')
@section('title', 'Pelanggan')
@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-6 sm:mb-8 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
            <div>
                <h1 class="text-xl sm:text-2xl font-bold text-gray-900">Pelanggan</h1>
                <p class="text-gray-600 text-sm sm:text-base">Kelola data pelanggan</p>
            </div>
            <a href="{{ route('customers.create') }}"
                class="inline-flex items-center px-3 sm:px-4 py-2 border border-indigo-300 text-indigo-700 bg-indigo-50 hover:bg-indigo-100 hover:border-indigo-400 rounded-md transition-colors duration-200 text-sm sm:text-base">
                <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6">
                    </path>
                </svg>
                Tambah Pelanggan
            </a>
        </div>

        <!-- Customers Table -->
        <div class="bg-white shadow rounded-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th
                                class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Nama
                            </th>
                            <th
                                class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Email
                            </th>
                            <th
                                class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden sm:table-cell">
                                Telepon
                            </th>
                            <th
                                class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden md:table-cell">
                                Member Code
                            </th>
                            <th
                                class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden sm:table-cell">
                                Poin
                            </th>
                            <th
                                class="px-4 sm:px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($customers as $c)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ $c->name }}
                                </td>
                                <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $c->email }}
                                </td>
                                <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-500 hidden sm:table-cell">
                                    {{ $c->phone }}
                                </td>
                                <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-500 hidden md:table-cell">
                                    {{ $c->member_code ?? '-' }}
                                </td>
                                <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-500 hidden sm:table-cell">
                                    {{ $c->total_points ?? $c->points()->sum('points') }}
                                </td>
                                <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex items-center justify-end gap-1 sm:gap-2">
                                        <a href="{{ route('customers.edit', $c) }}"
                                            class="inline-flex items-center px-2 sm:px-3 py-1.5 text-xs sm:text-sm border border-indigo-300 text-indigo-700 bg-indigo-50 hover:bg-indigo-100 hover:border-indigo-400 rounded-md transition-all duration-200 hover:shadow-sm">
                                            <svg class="w-3 h-3 sm:w-4 sm:h-4 sm:mr-1" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                </path>
                                            </svg>
                                            <span class="hidden sm:inline ml-1">Edit</span>
                                        </a>
                                        <button
                                            class="inline-flex items-center px-2 sm:px-3 py-1.5 text-xs sm:text-sm border border-green-300 text-green-700 bg-green-50 hover:bg-green-100 hover:border-green-400 rounded-md transition-all duration-200 hover:shadow-sm"
                                            @click="openPoint=true; pointId={{ $c->id }}">
                                            <svg class="w-3 h-3 sm:w-4 sm:h-4 sm:mr-1" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                            </svg>
                                            <span class="hidden sm:inline ml-1">+ Poin</span>
                                        </button>
                                        <form method="POST" action="{{ route('customers.destroy', $c) }}" class="inline"
                                            onsubmit="return confirm('Hapus pelanggan?')">@csrf @method('DELETE')
                                            <button
                                                class="inline-flex items-center px-2 sm:px-3 py-1.5 text-xs sm:text-sm border border-red-300 text-red-700 bg-red-50 hover:bg-red-100 hover:border-red-400 rounded-md transition-all duration-200 hover:shadow-sm">
                                                <svg class="w-3 h-3 sm:w-4 sm:h-4 sm:mr-1" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                    </path>
                                                </svg>
                                                <span class="hidden sm:inline ml-1">Hapus</span>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        @if ($customers->isEmpty())
                            <tr>
                                <td colspan="6" class="px-4 sm:px-6 py-4 text-center text-sm text-gray-500">
                                    Tidak ada pelanggan ditemukan
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if ($customers->hasPages())
                <div class="bg-white px-4 sm:px-6 py-3 flex items-center justify-between border-t border-gray-200">
                    {{ $customers->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
