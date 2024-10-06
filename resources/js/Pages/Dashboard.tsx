import React, { Fragment } from 'react';
import AuthenticatedLayout from '@/layouts/authenticated-layout';
import { Head, Link } from '@inertiajs/react';
import PrimaryButton from "@/components/primary-button";
import { Menu, Transition } from '@headlessui/react';
import { ChevronDownIcon } from '@heroicons/react/20/solid';

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
}

const Pagination = ({ meta }: { meta: PaginationMeta }) => {
    const { current_page, last_page } = meta;

    return (
        <div className="flex justify-center mt-8 space-x-4">
            {current_page > 1 && (
                <Link href={`?page=${current_page - 1}`} className="px-4 py-2 bg-gray-700 rounded-md hover:bg-gray-600">
                    Previous
                </Link>
            )}
            <span className="px-4 py-2 bg-gray-800 rounded-md">
                Page {current_page} of {last_page}
            </span>
            {current_page < last_page && (
                <Link href={`?page=${current_page + 1}`} className="px-4 py-2 bg-gray-700 rounded-md hover:bg-gray-600">
                    Next
                </Link>
            )}
        </div>
    );
};

const Avatar = ({ name }: { name: string }) => {
    const initials = name.split(' ').map(n => n[0]).join('').toUpperCase();
    return (
        <div className="w-10 h-10 rounded-full bg-indigo-600 flex items-center justify-center text-white font-bold">
            {initials}
        </div>
    );
};

const ProfileMenu = ({ name }: { name: string }) => {
    return (
        <Menu as="div" className="relative inline-block text-left">
            <div>
                <Menu.Button className="inline-flex w-full justify-center items-center">
                    <Avatar name={name} />
                    <ChevronDownIcon className="-mr-1 ml-2 h-5 w-5" aria-hidden="true" />
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
                                        active ? 'bg-gray-700 text-gray-100' : 'text-gray-100'
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
                                        active ? 'bg-gray-700 text-gray-100' : 'text-gray-100'
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

export default function Dashboard({ auth, paginatedGenerations }: DashboardProps) {
    const generations = paginatedGenerations.data;
    const isNewUser = generations.length === 0;

    const handleDownload = (id: string) => {
        fetch(`/download/${id}`)
            .then(response => response.json())
            .then(data => {
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
            .catch(error => {
                console.error('Error downloading file:', error);
            });
    };

    return (
        <AuthenticatedLayout>
            <Head title="Dashboard" />

            <div className="min-h-screen bg-gray-900 text-white">
                <nav className="bg-gray-800 p-4 sticky top-0 z-10">
                    <div className="mx-auto flex max-w-7xl items-center justify-between">
                        <h1 className="text-2xl font-bold">Welcome, {auth.user.name}</h1>
                        <ProfileMenu name={auth.user.name} />
                    </div>
                </nav>

                <main className="mx-auto max-w-7xl p-8">
                    {isNewUser ? (
                        <div className="mb-8 bg-gray-800 rounded-lg p-6">
                            <h2 className="mb-4 text-3xl font-bold">Get Started with your first generation</h2>
                            <p className="mb-6 text-lg">Welcome to your dashboard! <br/>Start by creating your first generation to unlock the full potential of our platform.</p>
                            <Link href={route('generate.index')}>
                                <PrimaryButton className="px-6 py-3 text-lg">
                                    Create your generation →
                                </PrimaryButton>
                            </Link>
                        </div>
                    ) : (
                        <div className="mb-8 flex items-center justify-between">
                            <h2 className="text-4xl font-bold">Your Dashboard</h2>
                            <Link href={route('generate.index')}>
                                <PrimaryButton className="px-6 py-3">
                                    Generate new art
                                </PrimaryButton>
                            </Link>
                        </div>
                    )}

                    <div className="grid gap-8 md:grid-cols-2 lg:grid-cols-3">
                        {generations.map((generation: Generation) => (
                            <div key={generation.id} className="bg-gray-800 p-4 rounded-lg flex flex-col">
                                <img src={generation.thumbnail_url} alt={generation.art_type} className="w-full h-48 object-cover rounded-md mb-4"/>
                                <h4 className="font-bold text-xl mb-2">{generation.art_type}</h4>
                                <p className="text-gray-300 mb-4">{generation.art_style}</p>
                                <div className="mt-auto flex justify-between items-center">
                                    <PrimaryButton onClick={() => handleDownload(generation.id)} className="px-4 py-2">
                                        Download HD
                                    </PrimaryButton>
                                </div>
                            </div>
                        ))}
                    </div>

                    {generations.length === 0 && (
                        <div className="bg-gray-800 p-6 rounded-lg text-center">
                            <p className="text-lg">You haven't created any generations yet.</p>
                        </div>
                    )}

                    {generations.length > 0 && (
                        <Pagination meta={paginatedGenerations.meta} />
                    )}
                </main>
            </div>
        </AuthenticatedLayout>
    );
}
