import AuthenticatedLayout from '@/layouts/authenticated-layout';
import { Head } from '@inertiajs/react';

export default function About() {
    return (
        <AuthenticatedLayout>
            <Head title="About MinecraftMagic" />

            <div className="bg-gray-900 pb-20 pt-12">
                <div className="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8">
                    <h1 className="mb-8 text-center text-4xl font-bold text-white">
                        About MinecraftMagic
                    </h1>

                    <div className="w-full rounded-lg bg-gray-800 p-6 text-white shadow-lg">
                        <p className="mb-4">
                            MinecraftMagic.com is a fun side-project that was
                            developed by a single developer and is meant to
                            showcase how you can build production-like projects
                            with Laravel and PHP and also how the power of AI
                            can be used such as DALL-E 3.
                        </p>
                        <p className="mb-4">
                            This project combines the developer's passion for
                            Minecraft and AI, creating a unique intersection of
                            these interests. It aims to demonstrate how AI can
                            enhance creativity within the Minecraft community.
                        </p>
                        <p>
                            Starting with server logos, MinecraftMagic explores
                            new ways to generate custom artwork for Minecraft
                            enthusiasts.
                        </p>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
