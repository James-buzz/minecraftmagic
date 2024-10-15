import { InputHTMLAttributes } from 'react';

export default function Checkbox({
    className = '',
    ...props
}: InputHTMLAttributes<HTMLInputElement>) {
    return (
        <input
            {...props}
            type="checkbox"
            className={
                'rounded border-gray-700 bg-gray-900 text-purple-600 shadow-sm focus:ring-purple-600 focus:ring-offset-gray-800 ' +
                className
            }
        />
    );
}
