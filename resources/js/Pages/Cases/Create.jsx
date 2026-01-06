import React, { useState } from 'react';
import AppLayout from '@/Layouts/AppLayout';
import { useForm, Head } from '@inertiajs/react';

export default function Create() {
    const { data, setData, post, processing, errors } = useForm({
        title: '',
        description: '',
        parties: [
            { name: '', party_type: 'plaintiff', phone: '' }
        ]
    });

    const handleSubmit = (e) => {
        e.preventDefault();
        post(route('cases.store'));
    };

    const addParty = () => {
        setData('parties', [
            ...data.parties,
            { name: '', party_type: 'defendant', phone: '' }
        ]);
    };

    const updateParty = (index, field, value) => {
        const newParties = [...data.parties];
        newParties[index][field] = value;
        setData('parties', newParties);
    };

    return (
        <AppLayout
            header={<h2 className="font-semibold text-xl text-gray-800 leading-tight">File New Case</h2>}
        >
            <Head title="File Case" />

            <div className="py-12">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div className="p-6 bg-white border-b border-gray-200">
                            <form onSubmit={handleSubmit}>
                                <div className="mb-4">
                                    <label className="block text-gray-700 text-sm font-bold mb-2">
                                        Case Title (Optional)
                                    </label>
                                    <input
                                        type="text"
                                        className="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                        value={data.title}
                                        onChange={e => setData('title', e.target.value)}
                                    />
                                    {errors.title && <div className="text-red-500 text-xs italic">{errors.title}</div>}
                                </div>

                                <div className="mb-4">
                                    <label className="block text-gray-700 text-sm font-bold mb-2">
                                        Case Description
                                    </label>
                                    <textarea
                                        className="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                        rows="4"
                                        value={data.description}
                                        onChange={e => setData('description', e.target.value)}
                                    ></textarea>
                                    {errors.description && <div className="text-red-500 text-xs italic">{errors.description}</div>}
                                </div>

                                <h3 className="text-lg font-medium text-gray-900 mt-6 mb-4">Parties</h3>

                                {data.parties.map((party, index) => (
                                    <div key={index} className="mb-4 p-4 border rounded bg-gray-50">
                                        <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
                                            <div>
                                                <label className="block text-sm font-medium text-gray-700">Name</label>
                                                <input
                                                    type="text"
                                                    className="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                                                    value={party.name}
                                                    onChange={e => updateParty(index, 'name', e.target.value)}
                                                />
                                            </div>
                                            <div>
                                                <label className="block text-sm font-medium text-gray-700">Type</label>
                                                <select
                                                    className="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                                                    value={party.party_type}
                                                    onChange={e => updateParty(index, 'party_type', e.target.value)}
                                                >
                                                    <option value="plaintiff">Plaintiff (বাদী)</option>
                                                    <option value="defendant">Defendant (বিবাদী)</option>
                                                </select>
                                            </div>
                                            <div>
                                                <label className="block text-sm font-medium text-gray-700">Phone</label>
                                                <input
                                                    type="text"
                                                    className="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                                                    value={party.phone}
                                                    onChange={e => updateParty(index, 'phone', e.target.value)}
                                                />
                                            </div>
                                        </div>
                                    </div>
                                ))}

                                <div className="mb-6">
                                    <button
                                        type="button"
                                        onClick={addParty}
                                        className="text-blue-600 hover:text-blue-900 text-sm"
                                    >
                                        + Add Another Party
                                    </button>
                                </div>

                                <div className="flex items-center justify-end">
                                    <button
                                        type="submit"
                                        disabled={processing}
                                        className="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
                                    >
                                        Save Draft
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </AppLayout>
    );
}
