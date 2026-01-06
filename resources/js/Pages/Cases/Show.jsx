import React, { useState } from 'react';
import AppLayout from '@/Layouts/AppLayout';
import { Head, Link, useForm, usePage } from '@inertiajs/react';

export default function Show({ case: caseData }) {
    const { auth } = usePage().props;
    const { data: inviteData, setData: setInviteData, post: postInvite, processing: inviteProcessing, errors: inviteErrors } = useForm({
        email: '',
    });

    const isLitigant = auth.roles && auth.roles.includes('Litigant');
    const isLawyer = auth.roles && auth.roles.includes('Lawyer');
    const isSerestadar = auth.roles && auth.roles.includes('Serestadar');
    const isJudge = auth.roles && auth.roles.includes('Judge');

    // Check if current user is an invited lawyer
    const myInvitation = caseData.case_lawyers?.find(l => l.lawyer_id === auth.user.id);

    // Forms
    const { data: scheduleData, setData: setScheduleData, post: postSchedule, processing: scheduleProcessing, errors: scheduleErrors } = useForm({
        hearing_date: '',
        purpose: 'First Hearing',
    });

    const { post: postApprove, processing: approveProcessing } = useForm();
    const { data: rejectData, setData: setRejectData, post: postReject, processing: rejectProcessing } = useForm({
        note: ''
    });

    const handleInvite = (e) => {
        e.preventDefault();
        postInvite(route('cases.invite-lawyer', caseData.id));
    };

    const handleAccept = () => {
        if (confirm('Accept to represent this case?')) {
            useForm().post(route('cases.accept-lawyer', caseData.id));
        }
    };

    const handleSubmit = () => {
        if (confirm('Submit case for court approval? No further edits will be allowed until reviewed.')) {
            useForm().post(route('cases.submit', caseData.id));
        }
    };

    const handleSchedule = (e) => {
        e.preventDefault();
        postSchedule(route('cases.hearings.store', caseData.id));
    };

    const handleApprove = () => {
        if(confirm('Approve this case and assign a Case Number?')) {
            postApprove(route('cases.approve', caseData.id));
        }
    }

    const handleReject = (e) => {
        e.preventDefault();
        if(confirm('Return this case for correction?')) {
            postReject(route('cases.reject', caseData.id));
        }
    }

    return (
        <AppLayout
            header={<h2 className="font-semibold text-xl text-gray-800 leading-tight">Case Details: {caseData.title}</h2>}
        >
            <Head title={`Case #${caseData.id}`} />

            <div className="py-12">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    {/* Status Banner */}
                    <div className="bg-blue-50 border-l-4 border-blue-400 p-4 mb-4">
                        <div className="flex">
                            <div className="ml-3">
                                <p className="text-sm text-blue-700">
                                    Current Status: <span className="font-bold">{caseData.case_status?.name}</span>
                                    {caseData.case_number && <span> | Case No: {caseData.case_number}</span>}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
                        {/* Main Content */}
                        <div className="md:col-span-2 space-y-6">
                            <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                                <h3 className="text-lg font-medium mb-4">Description</h3>
                                <p className="text-gray-600 whitespace-pre-wrap">{caseData.description}</p>

                                <div className="mt-6 pt-6 border-t">
                                    <h4 className="font-medium mb-2">Parties</h4>
                                    <ul className="divide-y divide-gray-200">
                                        {caseData.parties?.map(party => (
                                            <li key={party.id} className="py-2 flex justify-between">
                                                <span>{party.name} <span className="text-xs text-gray-500">({party.party_type})</span></span>
                                                <span className="text-gray-500">{party.phone}</span>
                                            </li>
                                        ))}
                                    </ul>
                                </div>
                            </div>

                             {/* Actions */}
                            <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                                <h3 className="text-lg font-medium mb-4">Actions</h3>
                                <div className="flex space-x-4">
                                    <a
                                        href={route('cases.download-arji', caseData.id)}
                                        className="bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-2 px-4 rounded inline-flex items-center"
                                        target="_blank"
                                    >
                                        Download Arji (PDF)
                                    </a>

                                    {caseData.case_status.slug === 'draft' && (
                                        <button
                                            onClick={handleSubmit}
                                            className="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded"
                                        >
                                            Submit to Court
                                        </button>
                                    )}
                                </div>
                            </div>

                            {/* Hearing History (Visible to all if active) */}
                            {caseData.hearings?.length > 0 && (
                                <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                                    <h3 className="text-lg font-medium mb-4">Hearing History</h3>
                                    <table className="min-w-full leading-normal">
                                        <thead>
                                            <tr>
                                                <th className="px-2 py-2 border-b-2 text-left text-xs font-semibold uppercase">Date</th>
                                                <th className="px-2 py-2 border-b-2 text-left text-xs font-semibold uppercase">Purpose</th>
                                                <th className="px-2 py-2 border-b-2 text-left text-xs font-semibold uppercase">Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            {caseData.hearings.map(h => (
                                                <tr key={h.id}>
                                                    <td className="px-2 py-2 border-b text-sm">{h.hearing_date}</td>
                                                    <td className="px-2 py-2 border-b text-sm">{h.purpose}</td>
                                                    <td className="px-2 py-2 border-b text-sm">{h.status}</td>
                                                </tr>
                                            ))}
                                        </tbody>
                                    </table>
                                </div>
                            )}
                        </div>

                        {/* Sidebar */}
                        <div className="space-y-6">
                            {/* Lawyer Management */}
                            <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                                <h3 className="text-lg font-medium mb-4">Legal Representation</h3>

                                {/* List Lawyers */}
                                {caseData.case_lawyers?.length > 0 ? (
                                    <ul className="mb-4 space-y-2">
                                        {caseData.case_lawyers.map(cl => (
                                            <li key={cl.id} className="text-sm bg-gray-50 p-2 rounded">
                                                <div className="font-bold">{cl.lawyer.name}</div>
                                                <div className={`text-xs ${cl.status === 'accepted' ? 'text-green-600' : 'text-yellow-600'}`}>
                                                    Status: {cl.status}
                                                </div>
                                            </li>
                                        ))}
                                    </ul>
                                ) : (
                                    <p className="text-gray-500 text-sm mb-4">No lawyers assigned.</p>
                                )}

                                {/* Invite Form (Litigant Only) */}
                                {caseData.case_status.slug === 'draft' && (
                                    <form onSubmit={handleInvite} className="mt-4 pt-4 border-t">
                                        <label className="block text-sm font-medium text-gray-700">Invite Lawyer (Email)</label>
                                        <div className="flex mt-1">
                                            <input
                                                type="email"
                                                className="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-sm"
                                                value={inviteData.email}
                                                onChange={e => setInviteData('email', e.target.value)}
                                                placeholder="lawyer@example.com"
                                            />
                                            <button
                                                type="submit"
                                                disabled={inviteProcessing}
                                                className="ml-2 bg-indigo-600 text-white px-3 py-1 rounded text-sm hover:bg-indigo-700"
                                            >
                                                Invite
                                            </button>
                                        </div>
                                        {inviteErrors.email && <p className="text-red-500 text-xs mt-1">{inviteErrors.email}</p>}
                                    </form>
                                )}

                                {/* Accept Form (Lawyer Only) */}
                                {myInvitation && myInvitation.status === 'pending' && (
                                    <div className="mt-4 pt-4 border-t">
                                        <p className="text-sm text-gray-700 mb-2">You have been invited to represent this case.</p>
                                        <button
                                            onClick={handleAccept}
                                            className="w-full bg-green-600 text-white px-4 py-2 rounded text-sm hover:bg-green-700"
                                        >
                                            Accept Invitation
                                        </button>
                                    </div>
                                )}
                            </div>

                            {/* Staff Approval Actions */}
                            {isSerestadar && caseData.case_status.slug === 'pending-approval' && (
                                <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-yellow-400">
                                    <h3 className="text-lg font-medium mb-4">Staff Review</h3>
                                    <div className="space-y-4">
                                        <button
                                            onClick={handleApprove}
                                            disabled={approveProcessing}
                                            className="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded"
                                        >
                                            Approve & Assign Case No
                                        </button>

                                        <form onSubmit={handleReject} className="pt-4 border-t">
                                            <label className="block text-sm font-medium text-gray-700">Correction Note</label>
                                            <div className="flex mt-1">
                                                <input
                                                    type="text"
                                                    className="block w-full rounded-md border-gray-300 shadow-sm text-sm"
                                                    placeholder="Reason for return..."
                                                    value={rejectData.note}
                                                    onChange={e => setRejectData('note', e.target.value)}
                                                    required
                                                />
                                                <button
                                                    type="submit"
                                                    disabled={rejectProcessing}
                                                    className="ml-2 bg-red-600 text-white px-3 py-1 rounded text-sm hover:bg-red-700"
                                                >
                                                    Return
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            )}

                            {/* Court Actions (Judge/Staff) */}
                            {(isJudge || isSerestadar) && caseData.case_status.slug === 'active' && (
                                <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                                    <h3 className="text-lg font-medium mb-4">Court Actions</h3>

                                    <form onSubmit={handleSchedule} className="space-y-4">
                                        <div>
                                            <label className="block text-sm font-medium text-gray-700">Next Hearing Date</label>
                                            <input
                                                type="date"
                                                className="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                                                value={scheduleData.hearing_date}
                                                onChange={e => setScheduleData('hearing_date', e.target.value)}
                                            />
                                            {scheduleErrors.hearing_date && <p className="text-red-500 text-xs">{scheduleErrors.hearing_date}</p>}
                                        </div>
                                        <div>
                                            <label className="block text-sm font-medium text-gray-700">Purpose</label>
                                            <select
                                                className="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                                                value={scheduleData.purpose}
                                                onChange={e => setScheduleData('purpose', e.target.value)}
                                            >
                                                <option>First Hearing</option>
                                                <option>Witness Examination</option>
                                                <option>Arguments</option>
                                                <option>Verdict</option>
                                            </select>
                                        </div>
                                        <button
                                            type="submit"
                                            disabled={scheduleProcessing}
                                            className="w-full bg-blue-800 text-white px-4 py-2 rounded hover:bg-blue-900"
                                        >
                                            Schedule Hearing
                                        </button>
                                    </form>
                                </div>
                            )}
                        </div>
                    </div>
                </div>
            </div>
        </AppLayout>
    );
}
