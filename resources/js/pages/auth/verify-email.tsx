import PrimaryButton from '@/components/primary-button';
import GuestLayout from '@/layouts/guest-layout';
import { Head, Link, useForm } from '@inertiajs/react';
import { FormEventHandler } from 'react';

export default function VerifyEmail({ status }: { status?: string }) {
    const { post, processing } = useForm({});

    const submit: FormEventHandler = (e) => {
        e.preventDefault();

        post(route('verification.send'));
    };

    return (
        <GuestLayout>
            <Head title="Email Verification"/>

            <div className="bg-gray-900 py-24 text-white">
                <div className="mx-auto w-full max-w-xl">

                    <h1 className={"text-2xl mb-6"}>Verify email address</h1>

                    <div className="mb-6 text-sm text-gray-200">
                        Thanks for signing up! Before getting started, could you verify
                        your email address by clicking on the link we just emailed to
                        you? If you didn't receive the email, we will gladly send you
                        another.
                    </div>

                    {status === 'verification-link-sent' && (
                        <div className="mb-4 text-sm font-medium text-green-600">
                            A new verification link has been sent to the email address
                            you provided during registration.
                        </div>
                    )}

                    <form onSubmit={submit}>
                        <div className="mt-4 flex items-center justify-between">
                            <PrimaryButton disabled={processing}>
                                Resend Verification Email
                            </PrimaryButton>

                            <Link
                                href={route('logout')}
                                method="post"
                                as="button"
                                className="rounded-md text-sm text-gray-300 underline hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                            >
                                Log Out
                            </Link>
                        </div>
                    </form>
                </div>
            </div>
        </GuestLayout>
);
}
