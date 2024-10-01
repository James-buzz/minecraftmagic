import ApplicationLogo from '@/components/application-logo';
import { Link } from '@inertiajs/react';
import { PropsWithChildren } from 'react';

export default function Authenticated({ children }: PropsWithChildren) {
    return (
        <div
            className="mx-auto flex min-h-screen max-w-4xl flex-col bg-[#1A1A2E] p-8 font-[family-name:var(--font-geist-sans)] text-white">
            <header className="mb-8">
                <div className="mb-4 flex justify-center">
                    <ApplicationLogo />
                </div>
            </header>
            <main className="flex-grow">{children}</main>
            <footer className="mt-12 text-center text-sm text-[#c48cff]">
                <p className="text-white">
                    &copy; 2024 Minecraftmagic.com. All rights reserved.
                </p>
                <div className="mt-4 flex justify-center space-x-4">
                    <Link href="/about" className="hover:underline">
                        About
                    </Link>
                    <Link href="/contact" className="hover:underline">
                        Contact
                    </Link>
                    <Link href="/privacy" className="hover:underline">
                        Privacy Policy
                    </Link>
                </div>
            </footer>
        </div>
    );
}
