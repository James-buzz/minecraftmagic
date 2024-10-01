import React, { useState } from 'react';
import { Head } from '@inertiajs/react';
import AuthenticatedLayout from '@/layouts/authenticated-layout';
import PrimaryButton from "@/components/primary-button";
import { router } from '@inertiajs/react'

interface ArtStyle {
    identifier: string;
    name: string;
    description: string;
}

interface ArtType {
    identifier: string;
    displayName: string;
    styles: ArtStyle[];
}

interface PageProps {
    art_types: ArtType[];
}

const imageSizes = ['1024x1024', '1024x1792', '1792x1024'];
const imageQualities = ['standard', 'hd'];

export default function Dashboard({ art_types }: PageProps) {
    const [selectedType, setSelectedType] = useState<string | null>(null);
    const [selectedStyle, setSelectedStyle] = useState<string | null>(null);
    const [selectedQuality, setSelectedQuality] = useState('');
    const [selectedRatio, setSelectedRatio] = useState('');
    const [serverName, setServerName] = useState('');

    const isDisabled = !selectedType || !selectedStyle || !selectedQuality || !selectedRatio;

    const handleSubmit = (e: React.FormEvent<HTMLFormElement>) => {
        e.preventDefault();
        router.post(route('generate.store'), {
            art_type: selectedType,
            art_style: selectedStyle,
            metadata: {
                image_quality: selectedQuality,
                image_size: selectedRatio,
                serverName,
            }
        })
    };

    return (
        <AuthenticatedLayout>
            <Head title="Generate new" />

            <div className="min-h-screen bg-gray-900 p-8 text-white">
                <h1 className="mb-8 text-center text-4xl font-bold">
                    Generate Minecraft Art with AI
                </h1>

                <div className="mx-auto mb-8 max-w-2xl rounded-lg bg-gray-800 p-6 shadow-lg">
                    <h2 className="mb-4 text-2xl font-semibold">How it works</h2>
                    <ol className="list-inside list-decimal space-y-2">
                        <li>Choose an art type and style from the options below.</li>
                        <li>Select your preferred image quality and size.</li>
                        <li>Enter your Minecraft server name for a personalised touch.</li>
                        <li>Click "Generate Image" to create your custom artwork!</li>
                    </ol>
                </div>

                <form onSubmit={handleSubmit} className="mx-auto max-w-4xl space-y-8">
                    <div>
                        <h3 className="mb-4 text-xl font-semibold">Select Art Type</h3>
                        <div className="grid grid-cols-2 gap-4 sm:grid-cols-3 md:grid-cols-4">
                            {art_types.map((type) => (
                                <div
                                    key={type.identifier}
                                    className={`relative cursor-pointer transition-all duration-300 ${
                                        selectedType === type.identifier
                                            ? 'ring-4 ring-primary'
                                            : 'hover:scale-105'
                                    }`}
                                    onClick={() => {
                                        setSelectedType(type.identifier);
                                        setSelectedStyle(null);
                                    }}
                                >
                                    <div className="h-40 rounded-lg bg-gray-700 flex items-center justify-center">
                                        <span className="text-center">{type.displayName}</span>
                                    </div>
                                </div>
                            ))}
                        </div>
                    </div>

                    {selectedType && (
                        <div>
                            <h3 className="mb-4 text-xl font-semibold">Select Style</h3>
                            <div className="grid grid-cols-2 gap-4 sm:grid-cols-3 md:grid-cols-4">
                                {art_types.find(type => type.identifier === selectedType)?.styles.map((style) => (
                                    <div
                                        key={style.identifier}
                                        className={`relative cursor-pointer transition-all duration-300 ${
                                            selectedStyle === style.identifier
                                                ? 'ring-4 ring-primary'
                                                : 'hover:scale-105'
                                        }`}
                                        onClick={() => setSelectedStyle(style.identifier)}
                                    >
                                        <img
                                            src={`/assets/art/${selectedType}/${style.identifier}.png`}
                                            alt={style.name}
                                            className="rounded-lg w-full h-40 object-cover"
                                        />
                                        <div className="absolute bottom-0 left-0 right-0 rounded-b-lg bg-black bg-opacity-50 p-2 text-sm text-white">
                                            {style.name}
                                        </div>
                                    </div>
                                ))}
                            </div>
                        </div>
                    )}

                    <div className="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <div>
                            <label htmlFor="quality" className="mb-2 block text-sm font-medium">
                                Image Quality*
                            </label>
                            <select
                                id="quality"
                                value={selectedQuality}
                                onChange={(e) => setSelectedQuality(e.target.value)}
                                className="w-full rounded-md border border-gray-600 bg-gray-700 p-2 text-white"
                                required
                            >
                                <option value="">Select quality</option>
                                {imageQualities.map((quality) => (
                                    <option key={quality} value={quality}>
                                        {quality}
                                    </option>
                                ))}
                            </select>
                        </div>

                        <div>
                            <label htmlFor="ratio" className="mb-2 block text-sm font-medium">
                                Image Size*
                            </label>
                            <select
                                id="ratio"
                                value={selectedRatio}
                                onChange={(e) => setSelectedRatio(e.target.value)}
                                className="w-full rounded-md border border-gray-600 bg-gray-700 p-2 text-white"
                                required
                            >
                                <option value="">Choose size</option>
                                {imageSizes.map((size) => (
                                    <option key={size} value={size}>
                                        {size}
                                    </option>
                                ))}
                            </select>
                        </div>

                        <div className="sm:col-span-2">
                            <label
                                htmlFor="serverName"
                                className="mb-2 block text-sm font-medium"
                            >
                                Minecraft Server Name <span className={'text-xs text-gray-500'}>(Optional)</span>
                            </label>
                            <input
                                type="text"
                                id="serverName"
                                value={serverName}
                                onChange={(e) => setServerName(e.target.value)}
                                className="w-full rounded-md border border-gray-600 bg-gray-700 p-2 text-white"
                                placeholder="Enter your server name"
                            />
                        </div>
                    </div>

                    <PrimaryButton
                        type="submit"
                        className="w-full rounded-lg bg-primary px-4 py-3 text-lg font-bold text-white transition duration-300 hover:bg-primary"
                        disabled={isDisabled}
                    >
                        Generate Image
                    </PrimaryButton>
                </form>
            </div>
        </AuthenticatedLayout>
    );
}
