import PrimaryButton from '@/components/primary-button';
import AuthenticatedLayout from '@/layouts/authenticated-layout';
import { Menu, Transition } from '@headlessui/react';
import { ChevronDownIcon } from '@heroicons/react/20/solid';
import {Head, Link, router} from '@inertiajs/react';
import React, {Fragment, useState} from 'react';
import FeedbackDialog from "@/components/feedback-dialog";

interface Generation {
    id: string;
    art_type: string;
    art_style: string;
    thumbnail_url: string;
}

interface PaginationMeta {
    current_page: number;
    per_page: number;
    total: number;
    last_page: number;
}

interface DashboardProps {
    auth: {
        user: {
            name: string;
        };
    };
    paginatedGenerations: {
        data: Generation[];
        meta: PaginationMeta;
    };
    flash: {
        success?: string;
        error?: string;
    }
}

const emojis = [
    { emoji: '😍', rating: 5 },
    { emoji: '🙂', rating: 4 },
    { emoji: '😐', rating: 3 },
    { emoji: '😕', rating: 2 },
    { emoji: '😢', rating: 1 }
];

const Pagination = ({ meta }: { meta: PaginationMeta }) => {
    const { current_page, last_page } = meta;

    return (
        <div className="mt-8 flex justify-center space-x-4">
            {current_page > 1 && (
                <Link
                    href={`?page=${current_page - 1}`}
                    className="rounded-md bg-gray-700 px-4 py-2 hover:bg-gray-600"
                >
                    Previous
                </Link>
            )}
            <span className="rounded-md bg-gray-800 px-4 py-2">
                Page {current_page} of {last_page}
            </span>
            {current_page < last_page && (
                <Link
                    href={`?page=${current_page + 1}`}
                    className="rounded-md bg-gray-700 px-4 py-2 hover:bg-gray-600"
                >
                    Next
                </Link>
            )}
        </div>
    );
};

const Avatar = ({ name }: { name: string }) => {
    const initials = name
        .split(' ')
        .map((n) => n[0])
        .join('')
        .toUpperCase();
    return (
        <div className="flex h-10 w-10 items-center justify-center rounded-full bg-indigo-600 font-bold text-white">
            {initials}
        </div>
    );
};

const ProfileMenu = ({ name }: { name: string }) => {
    return (
        <Menu as="div" className="relative inline-block text-left">
            <div>
                <Menu.Button className="inline-flex w-full items-center justify-center">
                    <Avatar name={name} />
                    <ChevronDownIcon
                        className="-mr-1 ml-2 h-5 w-5"
                        aria-hidden="true"
                    />
                </Menu.Button>
            </div>

            <Transition
                as={Fragment}
                enter="transition ease-out duration-100"
                enterFrom="transform opacity-0 scale-95"
                enterTo="transform opacity-100 scale-100"
                leave="transition ease-in duration-75"
                leaveFrom="transform opacity-100 scale-100"
                leaveTo="transform opacity-0 scale-95"
            >
                <Menu.Items className="absolute right-0 z-10 mt-2 w-56 origin-top-right rounded-md bg-gray-700 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none">
                    <div className="py-1">
                        <Menu.Item>
                            {({ active }) => (
                                <Link
                                    href={route('profile.edit')}
                                    className={`${
                                        active
                                            ? 'bg-gray-700 text-gray-100'
                                            : 'text-gray-100'
                                    } block px-4 py-2 text-sm`}
                                >
                                    Edit Profile
                                </Link>
                            )}
                        </Menu.Item>
                        <Menu.Item>
                            {({ active }) => (
                                <Link
                                    href={route('logout')}
                                    method="post"
                                    as="button"
                                    className={`${
                                        active
                                            ? 'bg-gray-700 text-gray-100'
                                            : 'text-gray-100'
                                    } block w-full px-4 py-2 text-left text-sm`}
                                >
                                    Logout
                                </Link>
                            )}
                        </Menu.Item>
                    </div>
                </Menu.Items>
            </Transition>
        </Menu>
    );
};

export default function Dashboard({
    auth,
    paginatedGenerations,
    flash,
}: DashboardProps) {
    const generations = paginatedGenerations.data;
    const isNewUser = generations.length === 0;

    const handleDownload = (id: string) => {
        fetch(`/download/${id}`)
            .then((response) => response.json())
            .then((data) => {
                if (data.url) {
                    const link = document.createElement('a');
                    link.href = data.url;
                    link.setAttribute('download', '');
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                } else {
                    console.error('Download URL not found in the response');
                }
            })
            .catch((error) => {
                console.error('Error downloading file:', error);
            });
    };

    const [feedbackDialogOpen, setFeedbackDialogOpen] = useState(false);
    const [selectedGenerationId, setSelectedGenerationId] = useState<string>('');

    const handleFeedbackSubmit = ({ emoji, comment }: { emoji: string; comment: string }) => {
        router.post(route('feedback.store', selectedGenerationId), {
            comment,
            rating: emojis.find((e) => e.emoji === emoji)?.rating,
        });
    };

    return (
        <AuthenticatedLayout>
            <Head title="Dashboard" />

            <div className="min-h-screen bg-gray-900 text-white">
                <nav className="sticky top-0 z-10 bg-gray-800 p-4">
                    <div className="mx-auto flex max-w-7xl items-center justify-between">
                        <h1 className="text-2xl font-bold">
                            Welcome, {auth.user.name}
                        </h1>
                        <ProfileMenu name={auth.user.name} />
                    </div>
                </nav>

                <main className="mx-auto max-w-7xl p-8">
                    {isNewUser ? (
                        <div className="mb-8 rounded-lg bg-gray-800 p-6">
                            <h2 className="mb-4 text-3xl font-bold">
                                Get Started with your first generation
                            </h2>
                            <p className="mb-6 text-lg">
                                Welcome to your dashboard! <br />
                                Start by creating your first generation to
                                unlock the full potential of our platform.
                            </p>
                            <Link href={route('generate.index')}>
                                <PrimaryButton className="px-6 py-3 text-lg">
                                    Create your generation →
                                </PrimaryButton>
                            </Link>
                        </div>
                    ) : (
                        <div className="mb-8 flex items-center justify-between">
                            <h2 className="text-4xl font-bold">
                                Your Dashboard
                            </h2>
                            <Link href={route('generate.index')}>
                                <PrimaryButton className="px-6 py-3">
                                    Generate new art
                                </PrimaryButton>
                            </Link>
                        </div>
                    )}

                    {flash.success && (
                        <div className="rounded-lg bg-green-800 p-4 mb-6">
                            {flash.success}
                        </div>
                    )}

                    <div className="grid gap-8 md:grid-cols-2 lg:grid-cols-3">
                        {generations.map((generation: Generation) => (
                            <div
                                key={generation.id}
                                className="flex flex-col rounded-lg bg-gray-800 p-4"
                            >
                                <img
                                    src={generation.thumbnail_url}
                                    alt={generation.art_type}
                                    className="mb-4 h-48 w-full rounded-md object-cover"
                                />
                                <h4 className="mb-2 text-xl font-bold">
                                    {generation.art_type}
                                </h4>
                                <p className="mb-4 text-gray-300">
                                    {generation.art_style}
                                </p>
                                <div className="mt-auto flex items-center justify-between">
                                    <PrimaryButton
                                        onClick={() =>
                                            handleDownload(generation.id)
                                        }
                                        className="px-4 py-2"
                                    >
                                        Download HD
                                    </PrimaryButton>
                                    <button
                                        onClick={() => {
                                            setSelectedGenerationId(generation.id);
                                            setFeedbackDialogOpen(true);
                                        }}
                                        className="rounded-md bg-gray-700 px-3 py-2 text-xs text-gray-200 hover:bg-gray-600"
                                    >
                                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22"
                                             viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2"
                                             strokeLinecap="round" strokeLinejoin="round"
                                             className="lucide lucide-message-square-warning">
                                            <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
                                            <path d="M12 7v2"/>
                                            <path d="M12 13h.01"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        ))}
                    </div>

                    {generations.length === 0 && (
                        <div className="rounded-lg bg-gray-800 p-6 text-center">
                            <p className="text-lg">
                            You haven't created any generations yet.
                            </p>
                        </div>
                    )}

                    {generations.length > 0 && (
                        <Pagination meta={paginatedGenerations.meta}/>
                    )}
                </main>
            </div>
            <FeedbackDialog
                isOpen={feedbackDialogOpen}
                onClose={() => setFeedbackDialogOpen(false)}
                onSubmit={handleFeedbackSubmit}
            />
        </AuthenticatedLayout>
    );
}
