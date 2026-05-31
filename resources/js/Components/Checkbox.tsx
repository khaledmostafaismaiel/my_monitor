import { InputHTMLAttributes } from 'react';
import clsx from 'clsx';

export default function Checkbox({
    className = '',
    ...props
}: InputHTMLAttributes<HTMLInputElement>) {
    return (
        <input
            {...props}
            type="checkbox"
            className={clsx(
                'rounded border-input bg-card text-primary shadow-sm focus:ring-ring',
                className,
            )}
        />
    );
}
