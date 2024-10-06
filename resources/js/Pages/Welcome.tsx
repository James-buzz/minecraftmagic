import { PageProps } from '@/types';
import { Head, Link } from '@inertiajs/react';
import Guest from "@/layouts/guest-layout";
import {MinecraftStyle} from "@/types/styles";
import PrimaryButton from "@/components/primary-button";
import ImageStyle from "@/components/image-style";

export default function Welcome() {
    return (
        <>
            <Head title="Welcome" />
            <Guest>
                <p className="mb-8 text-center text-xl text-white">
                    Unleash your creativity with AI-generated Minecraft art!
                </p>
                <div className="mx-auto grid w-full max-w-2xl grid-cols-3 gap-4">
                    {Object.values(MinecraftStyle).map((style) => (
                        <Link className={'flex'} href={route('dashboard')}>
                            <ImageStyle key={style} style={style}/>
                        </Link>
                    ))}
                </div>
                <div className="pt-12 text-center">
                    <Link href={route('dashboard')}>
                        <PrimaryButton className={'px-8 py-4 text-xl'}>Generate your own Magic!</PrimaryButton>
                    </Link>
                </div>
            </Guest>
        </>
    );
}
