<x-app-layout title="History & Logs">
    <div class="mb-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <nav class="flex items-center text-sm text-gray-500 mb-2">
                <a href="{{ route('dashboard') }}" class="hover:text-[#C9A050] transition-colors">Dashboard</a>
                <svg class="w-3.5 h-3.5 mx-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                <span class="text-gray-900 font-semibold">History & Logs</span>
            </nav>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 tracking-tight">History & Logs</h1>
        </div>
    </div>

    <!-- Tabs/Layout for Orders vs System -->
    <div class="grid grid-cols-1 xl:grid-cols-2 gap-8">
        
        <!-- Order History -->
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-6 border-b border-gray-100 flex flex-col sm:flex-row justify-between sm:items-center gap-4 bg-gray-50/50">
                <h2 class="text-lg font-bold text-gray-900">Order History</h2>
                <a href="{{ route('logs.export.orders') }}" class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded-lg shadow inline-flex items-center justify-center gap-2 transition duration-200">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                    <span class="text-white whitespace-nowrap">Export Order History</span>
                </a>
            </div>
            <div class="p-0 overflow-x-auto">
                <table class="w-full text-left text-sm whitespace-nowrap">
                    <thead class="text-xs text-gray-400 bg-gray-50 uppercase font-bold tracking-wider">
                        <tr>
                            <th class="px-6 py-4">Date</th>
                            <th class="px-6 py-4">Action</th>
                            <th class="px-6 py-4">User</th>
                            <th class="px-6 py-4">Changes</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($orderLogs as $log)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-6 py-4 text-gray-500">{{ $log->created_at->format('M d, Y H:i') }}</td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-1 rounded-md text-xs font-bold @if($log->event == 'created') bg-green-50 text-green-600 @elseif($log->event == 'updated') bg-blue-50 text-blue-600 @else bg-gray-100 text-gray-600 @endif">
                                    {{ ucfirst($log->event) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 font-medium text-gray-900">{{ $log->causer->name ?? 'System' }}</td>
                            <td class="px-6 py-4 text-xs text-gray-500 truncate max-w-[200px]" title="{{ json_encode($log->properties) }}">
                                {{ \Illuminate\Support\Str::limit(json_encode($log->properties), 30) }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-gray-400">No order logs found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($orderLogs->hasPages())
                <div class="p-4 border-t border-gray-100 bg-gray-50/50">
                    {{ $orderLogs->appends(['system_page' => request('system_page')])->links() }}
                </div>
            @endif
        </div>

        <!-- System Activity -->
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-6 border-b border-gray-100 flex flex-col sm:flex-row justify-between sm:items-center gap-4 bg-gray-50/50">
                <h2 class="text-lg font-bold text-gray-900">System Activity</h2>
                <a href="{{ route('logs.export.system') }}" class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded-lg shadow inline-flex items-center justify-center gap-2 transition duration-200">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                    <span class="text-white whitespace-nowrap">Export System Logs</span>
                </a>
            </div>
            <div class="p-0 overflow-x-auto">
                <table class="w-full text-left text-sm whitespace-nowrap">
                    <thead class="text-xs text-gray-400 bg-gray-50 uppercase font-bold tracking-wider">
                        <tr>
                            <th class="px-6 py-4">Date</th>
                            <th class="px-6 py-4">Module / Action</th>
                            <th class="px-6 py-4">User</th>
                            <th class="px-6 py-4">Changes</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($systemLogs as $log)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-6 py-4 text-gray-500">{{ $log->created_at->format('M d, Y H:i') }}</td>
                            <td class="px-6 py-4">
                                <span class="font-bold text-gray-700 block">{{ class_basename($log->subject_type) ?: 'System' }}</span>
                                <span class="text-xs text-gray-500 uppercase tracking-widest mt-0.5 block">{{ $log->event }}</span>
                            </td>
                            <td class="px-6 py-4 font-medium text-gray-900">{{ $log->causer->name ?? 'System' }}</td>
                            <td class="px-6 py-4 text-xs text-gray-500 truncate max-w-[200px]" title="{{ json_encode($log->properties) }}">
                                {{ \Illuminate\Support\Str::limit(json_encode($log->properties), 30) }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-gray-400">No system logs found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($systemLogs->hasPages())
                <div class="p-4 border-t border-gray-100 bg-gray-50/50">
                    {{ $systemLogs->appends(['order_page' => request('order_page')])->links() }}
                </div>
            @endif
        </div>
        
    </div>
</x-app-layout>
