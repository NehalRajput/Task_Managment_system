@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-100 py-6">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

    <!-- Dashboard Header -->
    <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
      <div class="flex items-center justify-between">
        <div>
          <h1 class="text-3xl font-bold text-gray-900">
            Welcome Back, {{ auth()->user()->name }}
          </h1>
          <p class="mt-1 text-lg text-gray-600">
            Manage your tasks efficiently
          </p>
        </div>
        <form method="POST" action="{{ route('logout') }}">
          @csrf
          <button type="submit"
                  class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 transition">
            Logout
          </button>
        </form>
      </div>
    </div>

    <!-- Task Management Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
      <!-- View Tasks -->
      <a href="{{ route('tasks.index') }}"
         class="block bg-white rounded-lg border hover:shadow-md transition p-6">
        <div class="flex items-center">
          <div class="bg-blue-500 p-3 rounded shadow-inner">
            <!-- icon -->
          </div>
          <div class="ml-4">
            <h3 class="text-lg font-medium text-gray-900">View All Tasks</h3>
            <p class="mt-1 text-sm text-gray-600">Manage and monitor all tasks</p>
          </div>
        </div>
      </a>

      <!-- Create Task -->
      <a href="{{ route('tasks.create') }}"
         class="block bg-white rounded-lg border hover:shadow-md transition p-6">
        <div class="flex items-center">
          <div class="bg-green-500 p-3 rounded shadow-inner">
            <!-- icon -->
          </div>
          <div class="ml-4">
            <h3 class="text-lg font-medium text-gray-900">Create New Task</h3>
            <p class="mt-1 text-sm text-gray-600">Add a new task to the system</p>
          </div>
        </div>
      </a>
    </div>

  </div>
</div>
@endsection
