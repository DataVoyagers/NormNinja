@extends('layouts.app')

@section('title', 'Quiz Statistics - ' . $quiz->title)

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between mb-4">
            <a href="{{ route('quizzes.index') }}" class="text-indigo-600 hover:text-indigo-800">
                <i class="fas fa-arrow-left mr-2"></i>Back to Quizzes
            </a>
            <div class="flex gap-2">
                <button onclick="exportData()" 
                        class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-semibold transition duration-200">
                    <i class="fas fa-download mr-2"></i>Export Data
                </button>
            </div>
        </div>
        <h1 class="text-3xl font-bold text-gray-800">{{ $quiz->title }} - Student Results</h1>
        <p class="text-gray-600 mt-2">Analyze student performance</p>
    </div>

    <!-- Student Attempts Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="p-6 border-b">
            <h2 class="text-xl font-bold text-gray-800">
                <i class="fas fa-users mr-2 text-indigo-600"></i>Student Attempts
            </h2>
        </div>

        @if($attempts->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            Student
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            Date & Time
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            Score
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            Status
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            Performance
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($attempts as $attempt)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold mr-3">
                                    {{ strtoupper(substr($attempt->student->name, 0, 2)) }}
                                </div>
                                <div>
                                    <div class="text-sm font-medium text-gray-900">{{ $attempt->student->name }}</div>
                                    <div class="text-xs text-gray-500">{{ $attempt->student->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($attempt->completed_at)
                                <div class="text-sm text-gray-900">{{ $attempt->completed_at->format('M d, Y') }}</div>
                                <div class="text-xs text-gray-500">{{ $attempt->completed_at->format('h:i A') }}</div>
                            @else
                                <span class="text-sm text-gray-400">Not Attempted</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($attempt->percentage !== null)
                                <span class="text-lg font-bold text-gray-900">{{ $attempt->percentage }}</span>
                            @else
                                <span class="text-sm text-gray-500">---</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if(!$attempt)
                            <span class="px-3 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-700">
                            <i class="fas fa-minus-circle mr-1"></i> Not Attempted
                            </span>
                            @elseif($attempt->percentage !== null)
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                <i class="fas fa-check mr-1"></i> Completed
                            </span>
                            @else
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                <i class="fas fa-xmark mr-1"></i> Incomplete
                            </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($attempt->percentage !== null)
                                @if($attempt->passed)
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    <i class="fas fa-star mr-1"></i> PASSED
                                </span>
                                @else
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-orange-100 text-orange-800">
                                    <i class="fas fa-redo mr-1"></i> NEEDS REVIEW
                                </span>
                                @endif
                            @else
                            <span class="text-gray-500 text-center">---</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($attempts->hasPages())
        <div class="px-6 py-4 bg-gray-50 border-t">
            {{ $attempts->links() }}
        </div>
        @endif
        @else
        <div class="p-12 text-center">
            <i class="fas fa-chart-bar text-gray-300 text-6xl mb-4"></i>
            <p class="text-gray-500 text-lg">No attempts yet</p>
            <p class="text-gray-400 mt-2">Students haven't attempted this quiz yet</p>
        </div>
        @endif
    </div>

</div>

<!-- Export Script -->
<script>
function exportData() {
    // Create CSV content
    let csv = 'Student Name,Email,Date,Time,Score,Time Spent,Status,Performance\n';
    
    const rows = document.querySelectorAll('tbody tr');
    rows.forEach(row => {
        const cells = row.querySelectorAll('td');
        if (cells.length > 0) {
            const name = cells[0].querySelector('.text-sm.font-medium').textContent;
            const email = cells[0].querySelector('.text-xs.text-gray-500').textContent;
            const dateCell = cells[1].querySelector('.text-sm');
            const timeCell = cells[1].querySelector('.text-xs');
            const date = dateCell ? dateCell.textContent.trim() : '---';
            const time = timeCell ? timeCell.textContent.trim() : '---';
            const score = cells[2].textContent.trim();
            const status = cells[3].textContent.trim();
            const performance = cells[4].textContent.trim();
            
            csv += `"${name}","${email}","${date}","${time}","${score}","${status}","${performance}"\n`;
        }
    });
    
    // Download CSV
    const blob = new Blob([csv], { type: 'text/csv' });
    const url = window.URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.setAttribute('hidden', '');
    a.setAttribute('href', url);
    a.setAttribute('download', 'quiz_statistics_{{ $quiz->id }}_{{ date("Y-m-d") }}.csv');
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
}
</script>
@endsection
