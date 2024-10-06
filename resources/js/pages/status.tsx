import React, {useEffect, useState} from 'react';
import { Head } from '@inertiajs/react';
import AuthenticatedLayout from '@/layouts/authenticated-layout';
import { PageProps } from "@/types";
import { router } from '@inertiajs/react'
import PrimaryButton from "@/components/primary-button";

interface StatusProps {
    status: {
        art_type: string;
        art_style: string;
        status: string;
        metadata: {
            image_size?: string;
            image_quality?: string
        }
    }
}

export default function Status({ auth, status }: PageProps&StatusProps) {
    const [isTabVisible, setIsTabVisible] = useState(true);

    const getStatusEffect = (status: string) => {
        switch (status) {
            case 'pending':
                return 'bg-purple-500';
            case 'processing':
                return 'bg-blue-500 animate-pulse';
            case 'completed':
                return 'bg-green-500';
            case 'failed':
                return 'bg-red-500';
            default:
                return 'bg-gray-500';
        }
    };

    const getStatusText = (status: string) => {
        switch (status) {
            case 'pending':
                return 'Pending';
            case 'processing':
                return 'Processing...';
            case 'completed':
                return 'Completed';
            case 'failed':
                return 'Failed';
            default:
                return 'Unknown';
        }
    };

    useEffect(() => {
        const pollInterval = 5000;
        let timeoutId: NodeJS.Timeout;

        const checkStatus = () => {
            if (isTabVisible && ['pending', 'processing'].includes(status.status)) {
                router.reload({ only: ['status'] });
            }
        };

        const initialDelay = setTimeout(() => {
            checkStatus();
            timeoutId = setInterval(checkStatus, pollInterval);
        }, 5000);

        const handleVisibilityChange = () => {
            setIsTabVisible(!document.hidden);
        };
        document.addEventListener('visibilitychange', handleVisibilityChange);

        return () => {
            clearTimeout(initialDelay);
            clearInterval(timeoutId);
            document.removeEventListener('visibilitychange', handleVisibilityChange);
        };
    }, [status.status, isTabVisible]);

    return (
        <AuthenticatedLayout>
            <Head title="Status" />

            <div className="bg-gray-900 pt-12 pb-20">
                <div className="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
                    <h1 className="mb-8 text-center text-4xl font-bold text-white">
                        Status
                    </h1>

                    <div className="mb-8 w-full rounded-lg bg-gray-800 p-6 shadow-lg text-white">
                        <h2 className="mb-4 text-2xl font-semibold">Your Input</h2>
                        <div className="grid grid-cols-2 gap-4">
                            <div>
                                <p className="font-medium">Type:</p>
                                <p>{status.art_type}</p>
                            </div>
                            <div>
                                <p className="font-medium">Style:</p>
                                <p>{status.art_style}</p>
                            </div>
                            <div>
                                <p className="font-medium">Image Size:</p>
                                <p>{status.metadata.image_size || 'N/A'}</p>
                            </div>
                            <div>
                                <p className="font-medium">Image Quality:</p>
                                <p>{status.metadata.image_quality || 'N/A'}</p>
                            </div>
                        </div>
                    </div>

                    <div className="relative mb-6 w-full">
                        <div className="flex flex-col items-center">
                            <div
                                className={`mb-4 flex h-20 w-20 items-center justify-center rounded-full ${getStatusEffect(status.status)}`}
                            >
                            </div>
                            <span className="text-center text-xl font-semibold text-white">{getStatusText(status.status)}</span>
                        </div>
                    </div>

                    <div className="text-center text-white">
                        {status.status === 'pending' && (
                            <p className="mb-6 text-lg">
                                Your position in queue: 1
                            </p>
                        )}
                        {status.status === 'completed' ? (
                            <>
                                <p className="text-lg text-green-500 font-semibold mb-6">
                                    Your image is ready!
                                </p>
                                <PrimaryButton className={'bg-green-600 hover:bg-green-700'} onClick={()=> {
                                    router.visit('/dashboard')
                                }}>View your art</PrimaryButton>
                            </>
                        ) : status.status === 'failed' ? (
                            <>
                                <p className="text-lg text-red-500 font-semibold">
                                    We encountered an error while creating your image. Please try again.
                                </p>
                                <PrimaryButton className={'bg-red-600 hover:bg-red-700 mt-4'} onClick={()=> {
                                    router.visit('/dashboard')
                                }}>Go Back</PrimaryButton>
                            </>
                        ) : (
                            <>
                                <p className="text-lg font-semibold">
                                    Please wait while we create your unique Minecraft art.
                                </p>
                                <p className="mt-2 text-sm text-gray-400">
                                    This process usually takes a few minutes.
                                </p>
                            </>
                        )}
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
