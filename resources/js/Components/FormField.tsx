import { PropsWithChildren } from 'react';
import InputLabel from './InputLabel';
import InputError from './InputError';

export default function FormField({
    label,
    htmlFor,
    error,
    hint,
    children,
}: PropsWithChildren<{
    label?: string;
    htmlFor?: string;
    error?: string;
    hint?: string;
}>) {
    return (
        <div className="space-y-1.5">
            {label && <InputLabel htmlFor={htmlFor} value={label} />}
            {children}
            {hint && !error && <p className="text-xs text-muted-foreground">{hint}</p>}
            <InputError message={error} />
        </div>
    );
}
