import React from 'react';
import { Head, Link, useForm } from '@inertiajs/react';
import { FormEventHandler } from 'react';
import GuestLayout from '@/layouts/guest-layout';
import InputError from '@/components/input-error';
import InputLabel from '@/components/input-label';
import PrimaryButton from '@/components/primary-button';
import TextInput from '@/components/text-input';
import Checkbox from '@/components/checkbox';
import { router } from '@inertiajs/react';

export default function Login({
                                  status,
                                  canResetPassword,
                              }: {
    status?: string;
    canResetPassword: boolean;
}) {
    const { data, setData, post, processing, errors, reset } = useForm({
        email: '',
        password: '',
        remember: false,
    });

    const submit: FormEventHandler = (e) => {
        e.preventDefault();
        post(route('login'), {
            onFinish: () => reset('password'),
        });
    };

    const redirectToGithub = () => {
        router.visit(route('socialite.redirect', {provider: 'github'}));
    }

    return (
        <GuestLayout>
            <Head title="Log in" />

            <div className="bg-gray-900 flex items-center justify-center pt-12 pb-20 px-4 sm:px-6 lg:px-8">
                <div className="max-w-md w-full space-y-8 bg-gray-800 p-8 rounded-lg shadow-lg">
                    <div>
                        <h2 className="mt-6 text-center text-3xl font-extrabold text-white">
                            Log in to your account
                        </h2>
                        <p className="mt-2 text-center text-sm text-gray-400">
                            Welcome back to MinecraftMagic
                        </p>
                    </div>
                    {status && (
                        <div className="mb-4 text-sm font-medium text-green-400 bg-green-900 border border-green-700 rounded-md p-4">
                            {status}
                        </div>
                    )}
                    <form className="mt-8 space-y-6" onSubmit={submit}>
                        <div className="space-y-4">
                            <div>
                                <InputLabel htmlFor="email" value="Email" className="text-white" />
                                <TextInput
                                    id="email"
                                    type="email"
                                    name="email"
                                    value={data.email}
                                    className="appearance-none relative block w-full px-3 py-2 border border-gray-700 placeholder-gray-500 text-white rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm bg-gray-700 mt-1"
                                    placeholder="Your email address"
                                    autoComplete="username"
                                    isFocused={true}
                                    onChange={(e) => setData('email', e.target.value)}
                                />
                                <InputError message={errors.email} className="mt-2" />
                            </div>
                            <div>
                                <InputLabel htmlFor="password" value="Password" className="text-white" />
                                <TextInput
                                    id="password"
                                    type="password"
                                    name="password"
                                    value={data.password}
                                    className="appearance-none relative block w-full px-3 py-2 border border-gray-700 placeholder-gray-500 text-white rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm bg-gray-700 mt-1"
                                    placeholder="Your password"
                                    autoComplete="current-password"
                                    onChange={(e) => setData('password', e.target.value)}
                                />
                                <InputError message={errors.password} className="mt-2" />
                            </div>
                        </div>

                        <div className="flex items-center justify-between">
                            <div className="flex items-center">
                                <Checkbox
                                    name="remember"
                                    checked={data.remember}
                                    onChange={(e) => setData('remember', e.target.checked)}
                                    className="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-700 rounded"
                                />
                                <span className="ml-2 text-sm text-gray-300">Remember me</span>
                            </div>
                            {canResetPassword && (
                                <div className="text-sm">
                                    <Link href={route('password.request')} className="font-medium text-indigo-400 hover:text-indigo-300 transition duration-150 ease-in-out">
                                        Forgot your password?
                                    </Link>
                                </div>
                            )}
                        </div>

                        <div>
                            <PrimaryButton
                                className="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150 ease-in-out"
                                disabled={processing}
                            >
                                Log in
                            </PrimaryButton>
                        </div>
                    </form>
                    <div className="mt-6">
                        <div className="relative">
                            <div className="absolute inset-0 flex items-center">
                                <div className="w-full border-t border-gray-700"></div>
                            </div>
                            <div className="relative flex justify-center text-sm">
                                <span className="px-2 bg-gray-800 text-gray-400">Or continue with</span>
                            </div>
                        </div>
                        <div className="mt-6">
                            <button
                                onClick={()=>redirectToGithub()}
                                className="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-gray-700 hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition duration-150 ease-in-out"
                            >
                                <span>Login with</span>
                                <img className={'w-4 ml-1'} src={'/assets/icons/github.svg'} alt={'Github icon'}/>
                            </button>
                        </div>
                    </div>
                    <div className="mt-6 text-center">
                    <Link
                            href={route('register')}
                            className="font-medium text-indigo-400 hover:text-indigo-300 transition duration-150 ease-in-out"
                        >
                            Don't have an account? Register here
                        </Link>
                    </div>
                </div>
            </div>
        </GuestLayout>
    );
}
