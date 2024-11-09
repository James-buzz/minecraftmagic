import PrimaryButton from '@/components/primary-button';
import AuthenticatedLayout from '@/layouts/authenticated-layout';
import { Head, router } from '@inertiajs/react';
import React, { useState } from 'react';

interface ArtStyle {
    id: string;
    name: string;
    description: string;
    resource_path: string;
}

interface ArtType {
    id: string;
    name: string;
    styles: ArtStyle[];
}

interface PageProps {
    art_types: ArtType[];
    flash?: {
        error?: string;
    };
}

const imageSizes = ['1024x1024', '1024x1792', '1792x1024'];
const imageQualities = ['standard', 'hd'];

export default function Generate({ art_types, flash }: PageProps) {
    const [selectedType, setSelectedType] = useState<string | null>();
    const [selectedStyle, setSelectedStyle] = useState<string | null>(null);
    const [selectedQuality, setSelectedQuality] = useState('standard');
    const [selectedRatio, setSelectedRatio] = useState('1024x1024');
    const [serverName, setServerName] = useState('');

    const isDisabled =
        !selectedType || !selectedStyle || !selectedQuality || !selectedRatio;

    const handleSubmit = (e: React.FormEvent<HTMLFormElement>) => {
        e.preventDefault();
        router.post(route('generate.store'), {
            art_type: selectedType,
            art_style: selectedStyle,
            metadata: {
                image_quality: selectedQuality,
                image_size: selectedRatio,
                fields: {
                    server_name: serverName,
                },
            },
        });
    };

    return (
        <AuthenticatedLayout>
            <Head title="Generate new" />

            <div className="min-h-screen bg-gray-900 p-8 text-white">
                <h1 className="mb-8 text-center text-4xl font-bold">
                    Generate Minecraft Art with AI
                </h1>

                <div className="mx-auto mb-8 max-w-2xl rounded-lg bg-gray-800 p-6 shadow-lg">
                    <h2 className="mb-4 text-2xl font-semibold">
                        How it works
                    </h2>
                    <ol className="list-inside list-decimal space-y-2">
                        <li>
                            Choose an art type and style from the options below.
                        </li>
                        <li>Select your preferred image quality and size.</li>
                        <li>
                            Enter your Minecraft server name for a personalised
                            touch.
                        </li>
                        <li>
                            Click "Generate Image" to create your custom
                            artwork!
                        </li>
                    </ol>
                </div>

                <form
                    onSubmit={handleSubmit}
                    className="mx-auto max-w-4xl space-y-8"
                >
                    <div>
                        <h3 className="mb-4 text-xl font-semibold">
                            Select Art Type
                        </h3>
                        <div className="grid grid-cols-2 gap-4 sm:grid-cols-3 md:grid-cols-4">
                            {art_types && art_types.map((type) => (
                                <div
                                    key={type.id}
                                    className={`relative cursor-pointer transition-all duration-300 ${
                                        selectedType === type.id
                                            ? 'ring-4 ring-primary'
                                            : 'hover:scale-105'
                                    }`}
                                    onClick={() => {
                                        setSelectedType(type.id);
                                        setSelectedStyle(null);
                                    }}
                                >
                                    <div className="flex h-40 items-center justify-center rounded-lg bg-gray-700">
                                        <span className="text-center">
                                            {type.name}
                                        </span>
                                    </div>
                                </div>
                            ))}
                        </div>
                    </div>

                    {selectedType && (
                        <div>
                            <h3 className="mb-4 text-xl font-semibold">
                                Select Style
                            </h3>
                            <div className="grid grid-cols-2 gap-4 sm:grid-cols-3 md:grid-cols-4">
                                {art_types
                                    .find((type) => type.id === selectedType)
                                    ?.styles.map((style) => (
                                        <div
                                            key={style.id}
                                            className={`relative cursor-pointer transition-all duration-300 ${
                                                selectedStyle === style.id
                                                    ? 'ring-4 ring-primary'
                                                    : 'hover:scale-105'
                                            }`}
                                            onClick={() =>
                                                setSelectedStyle(style.id)
                                            }
                                        >
                                            <img
                                                src={style.resource_path}
                                                alt={style.name}
                                                className="h-40 w-full rounded-lg object-cover"
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
                            <label
                                htmlFor="quality"
                                className="mb-2 block text-sm font-medium"
                            >
                                Image Quality*
                            </label>
                            <select
                                id="quality"
                                value={selectedQuality}
                                onChange={(e) =>
                                    setSelectedQuality(e.target.value)
                                }
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
                            <label
                                htmlFor="ratio"
                                className="mb-2 block text-sm font-medium"
                            >
                                Image Size*
                            </label>
                            <select
                                id="ratio"
                                value={selectedRatio}
                                onChange={(e) =>
                                    setSelectedRatio(e.target.value)
                                }
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
                                Minecraft Server Name*
                            </label>
                            <input
                                type="text"
                                id="serverName"
                                value={serverName}
                                onChange={(e) => setServerName(e.target.value)}
                                className="w-full rounded-md border border-gray-600 bg-gray-700 p-2 text-white"
                                placeholder="Enter your server name"
                                required={true}
                            />
                        </div>
                    </div>

                    {flash && flash.error && (
                        <div className="rounded-lg bg-red-500 p-4 text-white">
                            {flash.error}
                        </div>
                    )}

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
