@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-6">
  <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="bg-white shadow rounded-lg overflow-hidden border border-gray-200">
      <div class="px-6 py-4 flex justify-between items-center border-b border-gray-200">
        <h2 class="text-lg font-semibold text-gray-800">Task List</h2>
        <a href="{{ route('tasks.create') }}"
           class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm rounded hover:bg-indigo-700 transition">
          + Create Task
        </a>
      </div>

      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 text-sm text-left">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-6 py-3 font-medium text-gray-700">Title</th>
              <th class="px-6 py-3 font-medium text-gray-700">Description</th>
              <th class="px-6 py-3 font-medium text-gray-700">Due Date</th>
              <th class="px-6 py-3 font-medium text-gray-700">Actions</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-100">
            @forelse($tasks as $task)
              <tr class="hover:bg-gray-50">
                <td class="px-6 py-4 text-gray-900">{{ $task->title }}</td>
                <td class="px-6 py-4 text-gray-600">{{ Str::limit($task->description, 50) }}</td>
                <td class="px-6 py-4 text-gray-500">
                  {{ $task->due_date
                        ? \Carbon\Carbon::parse($task->due_date)->format('d M, Y')
                        : 'N/A' }}
                </td>
                <td class="px-6 py-4 space-x-2">
                  <a href="{{ route('tasks.edit', $task) }}"
                     class="px-2 py-1 bg-blue-600 text-white text-xs rounded hover:bg-blue-700 transition">
                    Edit
                  </a>
                  <form action="{{ route('tasks.destroy', $task) }}" method="POST" class="inline">
                    @csrf @method('DELETE')
                    <button type="submit"
                            class="px-2 py-1 bg-red-600 text-white text-xs rounded hover:bg-red-700 transition"
                            onclick="return confirm('Delete task?')">
                      Delete
                    </button>
                  </form>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                  No tasks found.
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
@endsection
