import Checkbox from '@/components/checkbox';
import InputError from '@/components/input-error';
import InputLabel from '@/components/input-label';
import PrimaryButton from '@/components/primary-button';
import TextInput from '@/components/text-input';
import GuestLayout from '@/layouts/guest-layout';
import { Head, Link, router, useForm } from '@inertiajs/react';
import { FormEventHandler } from 'react';

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
        router.visit(route('socialite.redirect', { provider: 'github' }));
    };

    return (
        <GuestLayout>
            <Head title="Log in" />

            <div className="flex items-center justify-center bg-gray-900 px-4 pb-20 pt-12 sm:px-6 lg:px-8">
                <div className="w-full max-w-md space-y-8 rounded-lg bg-gray-800 p-8 shadow-lg">
                    <div>
                        <h2 className="mt-6 text-center text-3xl font-extrabold text-white">
                            Log in to your account
                        </h2>
                        <p className="mt-2 text-center text-sm text-gray-400">
                            Welcome back to MinecraftMagic
                        </p>
                    </div>
                    {status && (
                        <div className="mb-4 rounded-md border border-green-700 bg-green-900 p-4 text-sm font-medium text-green-400">
                            {status}
                        </div>
                    )}
                    <form className="mt-8 space-y-6" onSubmit={submit}>
                        <div className="space-y-4">
                            <div>
                                <InputLabel
                                    htmlFor="email"
                                    value="Email"
                                    className="text-white"
                                />
                                <TextInput
                                    id="email"
                                    type="email"
                                    name="email"
                                    value={data.email}
                                    className="relative mt-1 block w-full appearance-none rounded-md border border-gray-700 bg-gray-700 px-3 py-2 text-white placeholder-gray-500 focus:z-10 focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm"
                                    placeholder="Your email address"
                                    autoComplete="username"
                                    isFocused={true}
                                    onChange={(e) =>
                                        setData('email', e.target.value)
                                    }
                                />
                                <InputError
                                    message={errors.email}
                                    className="mt-2"
                                />
                            </div>
                            <div>
                                <InputLabel
                                    htmlFor="password"
                                    value="Password"
                                    className="text-white"
                                />
                                <TextInput
                                    id="password"
                                    type="password"
                                    name="password"
                                    value={data.password}
                                    className="relative mt-1 block w-full appearance-none rounded-md border border-gray-700 bg-gray-700 px-3 py-2 text-white placeholder-gray-500 focus:z-10 focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm"
                                    placeholder="Your password"
                                    autoComplete="current-password"
                                    onChange={(e) =>
                                        setData('password', e.target.value)
                                    }
                                />
                                <InputError
                                    message={errors.password}
                                    className="mt-2"
                                />
                            </div>
                        </div>

                        <div className="flex items-center justify-between">
                            <div className="flex items-center">
                                <Checkbox
                                    name="remember"
                                    checked={data.remember}
                                    onChange={(e) =>
                                        setData('remember', e.target.checked)
                                    }
                                    className="h-4 w-4 rounded border-gray-700 text-indigo-600 focus:ring-indigo-500"
                                />
                                <span className="ml-2 text-sm text-gray-300">
                                    Remember me
                                </span>
                            </div>
                            {canResetPassword && (
                                <div className="text-sm">
                                    <Link
                                        href={route('password.request')}
                                        className="font-medium text-indigo-400 transition duration-150 ease-in-out hover:text-indigo-300"
                                    >
                                        Forgot your password?
                                    </Link>
                                </div>
                            )}
                        </div>

                        <div>
                            <PrimaryButton
                                className="group relative flex w-full justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white transition duration-150 ease-in-out hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
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
                                <span className="bg-gray-800 px-2 text-gray-400">
                                    Or continue with
                                </span>
                            </div>
                        </div>
                        <div className="mt-6">
                            <button
                                onClick={() => redirectToGithub()}
                                className="flex w-full justify-center rounded-md border border-transparent bg-gray-700 px-4 py-2 text-sm font-medium text-white shadow-sm transition duration-150 ease-in-out hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2"
                            >
                                <span>Login with</span>
                                <img
                                    className={'ml-1 w-4'}
                                    src={'/assets/icons/github.svg'}
                                    alt={'Github icon'}
                                />
                            </button>
                        </div>
                    </div>
                    <div className="mt-6 text-center">
                        <Link
                            href={route('register')}
                            className="font-medium text-indigo-400 transition duration-150 ease-in-out hover:text-indigo-300"
                        >
                            Don't have an account? Register here
                        </Link>
                    </div>
                </div>
            </div>
        </GuestLayout>
    );
}
