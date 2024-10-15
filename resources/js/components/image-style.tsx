import { MinecraftStyle } from '@/types/styles';
import React from 'react';

interface ImageStyleProps {
    style: MinecraftStyle;
}

const ImageStyle: React.FC<ImageStyleProps> = ({ style }) => {
    const imagePath = `/assets/styles/${style}.png`;
    const creator = style
        .replace(/-/g, ' ')
        .replace(/\b\w/g, (l) => l.toUpperCase());

    return (
        <div className="relative cursor-pointer overflow-hidden rounded-lg shadow-lg transition-all duration-300 hover:scale-105">
            <img
                src={imagePath}
                alt={`AI Generated Minecraft Image - ${creator}`}
                width={360}
                height={360}
            />
            <div className="absolute bottom-0 left-0 right-0 bg-black bg-opacity-50 p-2 text-sm text-white">
                <p>{creator}</p>
            </div>
        </div>
    );
};

export default ImageStyle;
