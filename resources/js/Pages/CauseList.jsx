import React, { useState } from 'react';
import AppLayout from '@/Layouts/AppLayout';
import { Head, router } from '@inertiajs/react';

export default function CauseList({ hearings, date }) {
    const [selectedDate, setSelectedDate] = useState(date);

    const handleDateChange = (e) => {
        setSelectedDate(e.target.value);
        router.get(route('cause-list'), { date: e.target.value }, { preserveState: true });
    };

    return (
        <AppLayout
            header={<h2 className="font-semibold text-xl text-gray-800 leading-tight">Daily Cause List</h2>}
        >
            <Head title="Cause List" />

            <div className="py-12">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        {/* Filter */}
                        <div className="mb-6 flex items-center space-x-4">
                            <label className="font-medium text-gray-700">Select Date:</label>
                            <input
                                type="date"
                                className="rounded border-gray-300 shadow-sm"
                                value={selectedDate}
                                onChange={handleDateChange}
                            />
                        </div>

                        {/* List */}
                        <table className="min-w-full leading-normal">
                            <thead>
                                <tr>
                                    <th className="px-5 py-3 border-b-2 bg-gray-100 text-left text-xs font-semibold uppercase">Sl.</th>
                                    <th className="px-5 py-3 border-b-2 bg-gray-100 text-left text-xs font-semibold uppercase">Case No</th>
                                    <th className="px-5 py-3 border-b-2 bg-gray-100 text-left text-xs font-semibold uppercase">Title</th>
                                    <th className="px-5 py-3 border-b-2 bg-gray-100 text-left text-xs font-semibold uppercase">Purpose</th>
                                </tr>
                            </thead>
                            <tbody>
                                {hearings.map((h, index) => (
                                    <tr key={h.id}>
                                        <td className="px-5 py-5 border-b text-sm">{index + 1}</td>
                                        <td className="px-5 py-5 border-b text-sm font-bold">{h.court_case.case_number}</td>
                                        <td className="px-5 py-5 border-b text-sm">{h.court_case.title}</td>
                                        <td className="px-5 py-5 border-b text-sm">
                                            <span className="bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-xs">
                                                {h.purpose}
                                            </span>
                                        </td>
                                    </tr>
                                ))}
                                {hearings.length === 0 && (
                                    <tr>
                                        <td colSpan="4" className="px-5 py-5 border-b text-center text-gray-500">
                                            No hearings scheduled for this date.
                                        </td>
                                    </tr>
                                )}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </AppLayout>
    );
}
